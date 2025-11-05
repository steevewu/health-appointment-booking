<?php

namespace App\Filament\Patient\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Event;
use App\Models\Workshift;
use App\Notifications\SteeveNotification;
use DB;
use Exception;
use Filament\Forms;
use Filament\Support\Colors\Color;
use Saade\FilamentFullCalendar\Actions;
use Filament\Actions\Action;

use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;


class CalendarWidget extends FullCalendarWidget
{


    protected static bool $isDiscovered = false;



    public Model|string|null $model = Event::class;

    public int $doctor_id;

    public function fetchEvents(array $fetchInfo): array
    {

        return Event::query()
            ->join('workshifts', 'workshifts.event_id', '=', 'events.id')
            ->where('workshifts.doctor_id', '=', $this->doctor_id)
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->select(['events.*', 'workshifts.id as workshift_id'])
            ->get()
            ->map(

                function (Event $event) {
                    $workshift = Workshift::where('id', $event->workshift_id)->first();
                    $color = $workshift->isBooked() ? '#FB4141' : '#78C841';
                    return
                        [
                            'id' => $event->id,
                            'title' => $event->title,
                            'start' => $event->start_at,
                            'end' => $event->end_at,
                            'description' => $event->description,
                            'workshift_id' => $event->workshift_id,
                            'backgroundColor' => $color,
                            'isBooked' => $workshift->isBooked()
                        ];
                }

            )
            ->all();
    }





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

        $isBooked = $event['extendedProps']['isBooked'];

        if($isBooked) return;
        $this->mountAction(
            'edit',
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
                        function (array $arguments) {
                            try {
                                $workshift = Workshift::where('id', $arguments['event']['extendedProps']['workshift_id'])->firstOrFail();

                                return __('filament::resources.appointments.heading', ['name' => $workshift->doctor->fullname]);
                            } catch (Exception $e) {
                                SteeveNotification::sendFailedNotification(message: $e->getMessage());
                            }
                        }
                    )
                    ->modalAlignment('center')
                    ->mountUsing(
                        function (Event $record, Forms\Form $form, array $arguments) {

                            $form->fill([
                                'title' => $record->title,
                                'start_at' => $arguments['event']['start'] ?? $record->start_at,
                                'end_at' => $arguments['event']['end'] ?? $record->end_at,
                                'workshift_id' => $arguments['event']['extendedProps']['workshift_id']
                            ]);
                        }
                    )
                    ->using(
                        function (array $data, Event $record, Action $action) {

                            try {

                                DB::transaction(
                                    function () use ($data) {

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
                    ->form(
                        [
                            Forms\Components\Hidden::make('workshift_id'),
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\DateTimePicker::make('start_at')
                                        ->disabled()
                                        ->label(__('filament::resources.events.start'))
                                        ->native(false)
                                        ->format('Y-m-d H:i:00')
                                        ->displayFormat('H:i d/m/Y')
                                        ->seconds(condition: false),
                                    Forms\Components\DateTimePicker::make('end_at')
                                        ->label(__('filament::resources.events.end'))
                                        ->disabled()
                                        ->native(false)
                                        ->format('Y-m-d H:i:00')
                                        ->displayFormat('H:i d/m/Y')
                                        ->seconds(false),
                                ]),
                            Forms\Components\MarkdownEditor::make('message')
                                ->nullable()
                        ]
                    )
                    ->modalSubmitActionLabel(
                        __('filament::resources.appointments.submit')
                    )
                    ->failureNotificationMessage(null)
                    ->successNotificationMessage(null)
                    ->modalFooterActions([])
            ];
    }




    protected function resolveRecord(string|int $key): Model
    {

        // prevent N+1 issue in query
        return $this->getModel()::with('workshifts')->findOrFail($key);
    }



}
