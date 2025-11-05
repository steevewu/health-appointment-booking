<?php

namespace App\Filament\Shared\Widgets;

use App\Models\Doctor;
use App\Models\Event;
use App\Models\Workshift;
use App\Notifications\SteeveNotification;
use Carbon\Carbon;
use DB;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions;
use Filament\Forms;
use Filament\Actions\Action;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{


    protected static bool $isDiscovered = false;



    public Model|string|null $model = Event::class;


    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Event $event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_at,
                    'end' => $event->end_at,
                    'description' => $event->description,
                    'backgroundColor' => '#FFF1CB',
                    'textColor' => '#1f1f1f'
                ]
            )
            ->all();
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



        $viewType = Filament::getCurrentPanel()->getId() === 'scheduler' ? 'edit' : 'view';
        $this->mountAction(
            $viewType, // default: 'view'
            [
                'type' => 'click',
                'event' => $event,
            ]
        );
    }




    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mountUsing(
                    function (Forms\Form $form, array $arguments) {
                        $form->fill([
                            'start_at' => $arguments['start'] ?? null,
                            'end_at' => $arguments['end'] ?? null
                        ]);

                    }
                )
                ->using(function (array $data, string $model, Action $action) {


                    try {


                        DB::transaction(function () use ($data) {
                            $start = Carbon::parse($data['start_at']);
                            $end = Carbon::parse($data['end_at']);


                            // check for conflict or overlap event
                            if (Event::isConflict($start, $end))
                                throw new Exception(__('filament::resources.events.time_conflict'));


                            $doctors = $data['doctors'];
                            $event = new Event(
                                [
                                    'title' => $data['title'],
                                    'start_at' => $data['start_at'],
                                    'end_at' => $data['end_at'],
                                    'description' => $data['description']
                                ]
                            );


                            $event->save();

                            foreach ($doctors as $doctor_id) {

                                // check if doctor already has a workshift on this period
                                if (Workshift::isConflict($start, $end, $doctor_id))
                                    throw new Exception(__('filament::resources.events.doctor_conflict'));


                                $workshift = new Workshift();

                                $workshift->forceFill(
                                    [
                                        'event_id' => $event->id,
                                        'doctor_id' => $doctor_id
                                    ]
                                );

                                $workshift->save();
                            }

                        });


                        SteeveNotification::sendSuccessNotification(action: $action);

                    } catch (Exception $e) {
                        SteeveNotification::sendFailedNotification(message: $e->getMessage());
                    }
                })
                ->successNotificationMessage(null)
                ->visible(
                    fn() => Filament::getCurrentPanel()->getId() === 'scheduler'
                )
        ];
    }



    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->mountUsing(
                    function (Event $record, Forms\Form $form, array $arguments) {

                        // listen on Event's movement and fill in the form
                        $form->fill([
                            'title' => $record->title,
                            'start_at' => $arguments['event']['start'] ?? $record->start_at,
                            'end_at' => $arguments['event']['end'] ?? $record->end_at,
                            'description' => $record->description,
                            'doctors' => $record->workshifts()->pluck('doctor_id')->toArray()
                        ]);
                    }
                )
                ->mutateFormDataUsing(
                    function (array $data): array {

                        $data['start_at'] = Carbon::parse($data['start_at'])->seconds(0)->format('Y-m-d H:i:s');
                        $data['end_at'] = Carbon::parse($data['end_at'])->seconds(0)->format('Y-m-d H:i:s');

                        return $data;
                    }
                )
                ->using(
                    function (array $data, Event $record, Action $action) {


                        try {
                            DB::transaction(

                                function () use ($data, $record) {
                                    $record->fill(
                                        [
                                            'title' => $data['title'],
                                            'start_at' => $data['start_at'],
                                            'end_at' => $data['end_at'],
                                            'description' => $data['description']
                                        ]
                                    );

                                    if ($record->isDirty(['start_at', 'end_at']) && Event::isConflict($data['start_at'], $data['end_at']))
                                        throw new Exception(__('filament::resources.events.time_conflict'));



                                    // check if dropped doctors are already have appoitments
                
                                    $dropped_doctors = array_diff($record->workshifts()->pluck('doctor_id')->toArray(), $data['doctors']);

                                    foreach ($dropped_doctors as $doctor) {

                                        $workshift = Workshift::where('event_id', $record->id)
                                            ->where('doctor_id', $doctor)
                                            ->first();

                                        if ($workshift->exists && $workshift->isBooked())
                                            throw new Exception(__('filament::resources.appointments.already_booked'));

                                        $workshift->delete();

                                    }


                                    // re-create workshifts
                                    foreach ($data['doctors'] as $doctor_id) {

                                        $workshift = Workshift::firstOrNew(
                                            [
                                                'event_id' => $record->id,
                                                'doctor_id' => $doctor_id
                                            ],
                                            []
                                        );



                                        if (!$workshift->exists) {


                                            // check if doctor already has a workshift on this period
                                            if (Workshift::isConflict($data['start_at'], $data['end_at'], $doctor_id))
                                                throw new Exception(__('filament::resources.events.doctor_conflict' . "{$doctor_id}"));


                                            $workshift->forceFill(
                                                [
                                                    'event_id' => $record->id,
                                                    'doctor_id' => $doctor_id

                                                ]
                                            );
                                        }

                                        $workshift->save();
                                        $record->save();

                                    }


                                }
                            );

                            SteeveNotification::sendSuccessNotification(action: $action);
                        } catch (Exception $e) {
                            SteeveNotification::sendFailedNotification(message: $e->getMessage());
                        }

                    }
                )
                ->successNotificationMessage(null)
                ->extraModalActions(
                    [
                        Actions\DeleteAction::make()
                    ]
                ),
        ];
    }




    protected function viewAction(): Action
    {
        return Actions\ViewAction::make()
            ->mountUsing(
                function (Event $record, Forms\Form $form, array $arguments) {

                    $form->fill([
                        'title' => $record->title,
                        'start_at' => $arguments['event']['start'] ?? $record->start_at,
                        'end_at' => $arguments['event']['end'] ?? $record->end_at,
                        'description' => $record->description,
                        'doctors' => $record->workshifts()->pluck('doctor_id')->toArray()
                    ]);
                }
            )
            ->modalFooterActions(
                []
            );
    }

    public function getFormSchema(): array
    {
        return [

            Forms\Components\Grid::make()
                ->schema(
                    [
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label(__('filament::resources.events.title')),
                        Forms\Components\Select::make('doctors')
                            ->required()
                            ->multiple()
                            ->options(fn() => Doctor::query()->whereNotNull('fullname')->pluck('fullname', 'id')->toArray())
                            ->searchable()
                            ->label(__('filament::resources.events.doctors'))
                    ]
                ),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('start_at')
                        ->native(false)
                        ->required()
                        ->label(__('filament::resources.events.start'))
                        ->format('Y-m-d H:i:00')
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(condition: false),


                    Forms\Components\DateTimePicker::make('end_at')
                        ->label(__('filament::resources.events.end'))
                        ->required()
                        ->native(false)
                        ->format('Y-m-d H:i:00')
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(false),
                ]),
            Forms\Components\MarkdownEditor::make('description')
            ->nullable()
            ->label(__('filament::resources.events.description'))
        ];
    }




    protected function resolveRecord(string|int $key): Model
    {

        // prevent N+1 issue in query
        return $this->getModel()::with('workshifts')->findOrFail($key);
    }








}
