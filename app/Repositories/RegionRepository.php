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
     * getVillages
     *
     * @param  mixed $districtId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getVillages($districtId)
    {
        return $this->model->query()->where('code', 'like', $districtId . '%')->whereRaw('LENGTH(code) = 13')->get();
    }
}
