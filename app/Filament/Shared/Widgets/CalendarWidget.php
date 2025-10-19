<?php

namespace App\Filament\Shared\Widgets;

use App\Models\Doctor;
use App\Models\Event;
use App\Models\Workshift;
use DB;
use Exception;
use Filament\Notifications\Notification;
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

        $this->mountAction(
            'edit', // default: 'view'
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


                        DB::transaction(function () use ($data, $model, $action) {
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


                        Notification::make(
                            'success'
                        )
                            ->title(__('filament::resources.success'))
                            ->body(__('filament::resources.succ_messages', ['action' => $action->getName()]), )
                            ->success()
                            ->seconds(5)
                            ->send();



                    } catch (Exception $e) {
                        Notification::make(
                            'error'
                        )
                            ->title(__('filament::resources.error'))
                            ->body(__('filament::resources.err_messages') . "\n" . $e->getMessage())
                            ->danger()
                            ->seconds(10)
                            ->send();
                    }
                })
                ->successNotificationMessage(null)
        ];
    }



    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
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
                ),
            Actions\DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return Actions\ViewAction::make();
    }

    public function getFormSchema(): array
    {
        return [

            Forms\Components\Grid::make()
                ->schema(
                    [
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label(__('filament::resources.hehe')),
                        Forms\Components\Select::make('doctors')
                            ->required()
                            ->multiple()
                            ->options(fn() => Doctor::query()->whereNotNull('fullname')->pluck('fullname', 'id')->toArray())
                            ->searchable()
                            ->label(__('filament::resources.hehe'))
                    ]
                ),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('start_at')
                        ->displayFormat('d M Y H:i')
                        ->seconds(false),

                    Forms\Components\DateTimePicker::make('end_at')
                        ->displayFormat('d M Y H:i')
                        ->seconds(false),
                ]),
            Forms\Components\MarkdownEditor::make('description')
        ];
    }




    protected function resolveRecord(string|int $key): Model
    {

        // prevent N+1 issue in query
        return $this->getModel()::with('workshifts')->findOrFail($key);
    }


}
