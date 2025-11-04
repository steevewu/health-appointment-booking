<?php

namespace App\Filament\Doctor\Widgets;

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



    public function onEventClick(array $event): void
    {

        if (!isset($event['id'])) {
            return;
        }


        
        if ($this->getModel()) {
            $this->record = $this->resolveRecord($event['id']);
        }
        


        $this->mountAction(
            'view',
            [
                'type' => 'click',
                'event' => $event,
            ]
        );



    }


    public function fetchEvents(array $fetchInfo): array
    {

        return Event::query()
            ->join('workshifts', 'workshifts.event_id', '=', 'events.id')
            ->where('workshifts.doctor_id', '=', auth()->user()->doctor->id)
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->select('events.*')
            ->get()
            ->map(
                fn(Event $event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_at,
                    'end' => $event->end_at,
                    'description' => $event->description
                ]

            )
            ->all();
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
            )
            ->modalWidth('xl');
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


        // dd($key);
        // prevent N+1 issue in query
        return $this->getModel()::with('workshifts')->findOrFail($key);
    }



    protected function headerActions(): array{
        return [];
    }




}
