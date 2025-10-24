<?php

namespace App\Filament\Patient\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Event;
use App\Notifications\SteeveNotification;
use DB;
use Exception;
use Filament\Forms;
use Saade\FilamentFullCalendar\Actions;
use Filament\Actions\Action;

use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;


class CalendarWidget extends FullCalendarWidget
{


    protected static bool $isDiscovered = false;



    public Model|string|null $model = Event::class;

    public int $doctor_id;


    // public function __construct(int $doctor_id)
    // {
    //     $this->doctor_id = $doctor_id;
    // }


    public function fetchEvents(array $fetchInfo): array
    {

        // dd(Event::query()
        //     ->join('workshifts', 'workshifts.event_id', '=', 'events.id')
        //     ->where('workshifts.doctor_id', '=', $this->doctor_id)
        //     ->where('start_at', '>=', $fetchInfo['start'])
        //     ->where('end_at', '<=', $fetchInfo['end'])
        //     ->select(['events.*', 'workshifts.id as workshift_id'])
        //     ->get());
        return Event::query()
            ->join('workshifts', 'workshifts.event_id', '=', 'events.id')
            ->where('workshifts.doctor_id', '=', $this->doctor_id)
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->select(['events.*', 'workshifts.id as workshift_id'])
            ->get()
            ->map(
                fn(Event $event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_at,
                    'end' => $event->end_at,
                    'description' => $event->description,
                    'workshift_id' => $event->workshift_id
                ]

            )
            ->all();
    }



    // public function headerActions(): array{
    //     return [
    //         Forms\Components\Select::make('hehe')
    //         ->options(
    //             [
    //                 1 => '1',
    //                 2 => '2',
    //                 3 => '3'
    //             ]
    //         )
    //     ];
    // }


    public function headerActions(): array
    {
        return [];
    }


    public function onEventClick(array $event): void
    {
        // override default behaviour of Event-click
        // force to 'editable' form instead of 'view' only
        if (!isset($event['id'])) {
            return;
        }

        if ($this->getModel()) {
            $this->record = $this->resolveRecord($event['id']);
        }
        $this->mountAction(
            'edit', // default: 'view'
            [
                'type' => 'click',
                'event' => $event,
            ]
        );
    }




    protected function modalActions(): array
    {

        return
            [
                Actions\EditAction::make()
                    ->modalWidth('xl')
                    ->modalHeading(
                        __('filament::resources.appointments.heading')
                    )
                    ->modalAlignment('center')
                    ->mountUsing(
                        function (Event $record, Forms\Form $form, array $arguments) {

                            // listen on Event's movement and fill in the form
                            $form->fill([
                                'title' => $record->title,
                                'start_at' => $arguments['event']['start'] ?? $record->start_at,
                                'end_at' => $arguments['event']['end'] ?? $record->end_at,
                                'description' => $record->description,
                                'workshift_id' => $arguments['event']['extendedProps']['workshift_id']
                            ]);
                        }
                    )
                    ->using(
                        function (array $data, Event $record, Action $action) {

                            try {

                                DB::transaction(
                                    function () use ($data, $record, $action) {


                                        if (Appointment::isConflict(auth()->user()->patient->id, $data['workshift_id']))
                                            throw new Exception(__('filament::resources.appointments.conflict'));


                                        $appointment = new Appointment(
                                            [
                                                'workshift_id' => $data['workshift_id'],
                                                'patient_id' => auth()->user()->patient->id,
                                                'message' => $data['message'],
                                            ]
                                        );

                                        $appointment->forceFill(
                                            [
                                                'status' => 'pending'
                                            ]
                                        );
                                        $appointment->save();


                                    }
                                );

                                SteeveNotification::sendSuccessNotification();
                            } catch (Exception $e) {
                                SteeveNotification::sendFailedNotification(message: $e->getMessage());
                            }


                        }

                    )
                    ->successNotificationMessage(null)
                    ->modalFooterActions(
                        [

                        ]
                    )
                    ->form(
                        [
                            Forms\Components\Hidden::make('workshift_id'),
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\DateTimePicker::make('start_at')
                                        ->disabled()
                                        ->native(false)
                                        ->format('Y-m-d H:i:00')
                                        ->displayFormat('d/m/Y H:i')
                                        ->seconds(condition: false),
                                    Forms\Components\DateTimePicker::make('end_at')
                                        ->disabled()
                                        ->native(false)
                                        ->format('Y-m-d H:i:00')
                                        ->displayFormat('d/m/Y H:i')
                                        ->seconds(false),
                                ]),
                            Forms\Components\MarkdownEditor::make('message')
                                ->nullable()
                        ]
                    )
                    ->modalSubmitActionLabel(
                        __('filament::resources.appointments.submit')
                    )
            ];
    }




    protected function resolveRecord(string|int $key): Model
    {

        // prevent N+1 issue in query
        return $this->getModel()::with('workshifts')->findOrFail($key);
    }




}
