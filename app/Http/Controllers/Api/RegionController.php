<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * @var RegionRepository
     */
    private RegionRepository $regionRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->regionRepository = new RegionRepository();
    }

    /**
     * getProvinces
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces()
    {
        $data = $this->regionRepository->getProvinces();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * getCities
     *
     * @param  mixed $provinceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities($provinceId)
    {
        $data = $this->regionRepository->getCities($provinceId);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * getDistricts
     *
     * @param  mixed $cityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistricts($cityId)
    {
        $data = $this->regionRepository->getDistricts($cityId);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * getVillages
     *
     * @param  mixed $districtId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVillages($districtId)
    {
        $data = $this->regionRepository->getVillages($districtId);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
