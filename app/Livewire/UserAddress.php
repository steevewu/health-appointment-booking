<?php

namespace App\Livewire;

use App\Notifications\SteeveNotification;
use App\Services\AddressResolver;
use DB;
use Exception;
use Http;
use Livewire\Component;
use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;

class UserAddress extends PersonalInfo
{
    public array $only = ['province_id', 'district_id', 'ward_id'];


    public int|null $province_id = null;
    public int|null $district_id = null;
    public int|null $ward_id = null;




    public function mount(): void
    {
        parent::mount();



        $role = $this->user->getRoleNames()->first(); // return: ['admin', 'officer', 'scheduler', 'doctor', 'patient']

        // access user's correspoding information tables, i.e., `officers`, `schedulers`, `doctors`, `patients` and fetch address data
        // address format: province_id|district_id|ward_id, e.g., `1|2|3`, see env("ADDRESS_API") for more details
        $address = $this->user->{$role}?->address ?? null;


        [$this->province_id, $this->district_id, $this->ward_id] = AddressResolver::resolveCode($address);


        $this->form->fill(
            [
                'province_id' => $this->province_id,
                'district_id' => $this->district_id,
                'ward_id' => $this->ward_id
            ]
        );
    }

    // You can override the default components by returning an array of components.
    protected function getProfileFormComponents(): array
    {
        return [
            $this->getProvinceComponent(),
            $this->getDistrictComponent(),
            $this->getWardComponent(),
        ];
    }

    protected function getProvinceComponent(): Forms\Components\Select
    {
        return Forms\Components\Select::make('province_id')
        ->label(__('filament::resources.province'))
            ->options(
                function () {
                    $provinces = Http::withHeaders(
                        [
                            'Accept' => 'application/json',
                        ]
                    )
                        ->withQueryParameters(
                            [
                                'depth' => 1
                            ]
                        )
                        ->withoutVerifying()
                        ->get(config('app.address_api'));

                    return collect($provinces->json())->pluck('name', 'code')->toArray();
                }
            )
            ->placeholder(__('filament::resources.province'))
            ->searchable()
            ->live()
            ->afterStateUpdated(
                function ($state, callable $set) {
                    $set('district_id', null);
                    $set('ward_id', null);
                }
            );
    }

    protected function getDistrictComponent(): Forms\Components\Select
    {
        return Forms\Components\Select::make('district_id')
            ->label(__('filament::resources.district'))
            ->options(
                function (callable $get) {

                    // fetch user's selected province
                    $province = $get('province_id');


                    if (!$province)
                        return [];
                    $districts = Http::withHeaders(
                        [
                            'Accept' => 'application/json',
                        ]
                    )
                        ->withQueryParameters(
                            [
                                'depth' => 2
                            ]
                        )
                        ->withoutVerifying()
                        ->get(config('app.address_api') . "/p/{$province}");

                    return collect($districts->json()['districts'])->pluck('name', 'code')->toArray();
                }
            )
            ->placeholder(__('filament::resources.district'))
            ->searchable()
            ->live()
            ->afterStateUpdated(
                function ($state, callable $set) {
                    $set('ward_id', null);
                }
            );
    }


    protected function getWardComponent(): Forms\Components\Select
    {
        return Forms\Components\Select::make('ward_id')
            ->label(__('filament::resources.ward'))
            ->options(
                function (callable $get) {
                    $district = $get('district_id');


                    if (!$district)
                        return [];
                    $wards = Http::withHeaders(
                        [
                            'Accept' => 'application/json',
                        ]
                    )
                        ->withQueryParameters(
                            [
                                'depth' => 2
                            ]
                        )
                        ->withoutVerifying()
                        ->get(config('app.address_api') . "/d/{$district}");


                    return collect($wards->json()['wards'])->pluck('name', 'code')->toArray();

                }
            )
            ->placeholder(__('filament::resources.ward'))
            ->live()
            ->searchable();
    }


    public function submit(): void
    {
        $this->validate();

        try {
            $data = collect($this->form->getState())->only($this->only)->all();



            DB::transaction(function () use ($data) {

                $role = $this->user->getRoleNames()->first();

                $address = join('|', $data);

                $this->user->{$role}?->update(
                    [
                        'address' => $address
                    ]
                );


            });


            SteeveNotification::sendSuccessNotification(action: 'update');



        } catch (Exception $e) {
            SteeveNotification::sendFailedNotification(message: $e->getMessage());
        }


    }





}

