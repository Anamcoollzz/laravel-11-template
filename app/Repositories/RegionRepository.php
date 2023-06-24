<?php

namespace App\Repositories;

use App\Models\Region;
use Illuminate\Support\Facades\DB;

class RegionRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Region();
    }

    /**
     * getProvinces
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getProvinces()
    {
        return $this->model->query()->whereRaw('LENGTH(code) = 2')->get();
    }

    /**
     * getProvincesOptions
     *
     * @return array
     */
    public function getProvincesOptions()
    {
        return $this->getProvinces()->pluck('name', 'code')->toArray();
    }

    /**
     * getCities
     *
     * @param  mixed $provinceId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCities($provinceId)
    {
        return $this->model->query()->where('code', 'like', $provinceId . '%')->whereRaw('LENGTH(code) = 5')->get();
    }

    /**
     * getCitiesOptions
     *
     * @param  mixed $provinceId
     * @return array
     */
    public function getCitiesOptions($provinceId)
    {
        return $this->getCities($provinceId)->pluck('name', 'code')->toArray();
    }

    /**
     * getDistricts
     *
     * @param  mixed $cityId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDistricts($cityId)
    {
        return $this->model->query()->where('code', 'like', $cityId . '%')->whereRaw('LENGTH(code) = 8')->get();
    }

    /**
     * getDistrictsOptions
     *
     * @param  mixed $cityId
     * @return array
     */
    public function getDistrictsOptions($cityId)
    {
        return $this->getDistricts($cityId)->pluck('name', 'code')->toArray();
    }

    /**
     * getVillages
     *
     * @param  mixed $districtId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getVillages($districtId)
    {
        return $this->model->query()->where('code', 'like', $districtId . '%')->whereRaw('LENGTH(code) = 13')->get();
    }

    /**
     * getVillagesOptions
     *
     * @param  mixed $districtId
     * @return array
     */
    public function getVillagesOptions($districtId)
    {
        return $this->getVillages($districtId)->pluck('name', 'code')->toArray();
    }
}
