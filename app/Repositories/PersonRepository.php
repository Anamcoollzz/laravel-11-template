<?php

namespace App\Repositories;

use App\Models\Person;
use Illuminate\Support\Facades\DB;

class PersonRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Person();
    }

    /**
     * getFilter
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFilter()
    {
        return $this->model->query()
            ->when(auth()->user()->hasRole('tim sukses'), function ($query) {
                $query->where('pic_id', auth()->user()->id);
            })
            ->with([
                'pic', 'village', 'district', 'city', 'province'
            ])
            ->latest()
            ->get();
    }

    /**
     * count
     *
     * @return mixed
     */
    public function count()
    {
        return $this->model->query()
            ->when(auth()->user()->hasRole('tim sukses'), function ($query) {
                $query->where('pic_id', auth()->user()->id);
            })
            ->count();
    }

    /**
     * getActivityTypeOptions
     *
     * @return array
     */
    public function getActivityTypeOptions()
    {
        $query = "SELECT DISTINCT activity_type FROM `activity_logs`;";
        $results = DB::select($query);
        return collect($results)->pluck('activity_type', 'activity_type')->toArray();
    }

    /**
     * getBrowserOptions
     *
     * @return array
     */
    public function getBrowserOptions()
    {
        $query = "SELECT DISTINCT browser FROM `activity_logs`;";
        $results = DB::select($query);
        return collect($results)->pluck('browser', 'browser')->toArray();
    }

    /**
     * getDeviceOptions
     *
     * @return array
     */
    public function getDeviceOptions()
    {
        $query = "SELECT DISTINCT device FROM `activity_logs`;";
        $results = DB::select($query);
        return collect($results)->pluck('device', 'device')->toArray();
    }

    /**
     * getPlatformOptions
     *
     * @return array
     */
    public function getPlatformOptions()
    {
        $query = "SELECT DISTINCT platform FROM `activity_logs`;";
        $results = DB::select($query);
        return collect($results)->pluck('platform', 'platform')->toArray();
    }

    /**
     * getMineLatest
     *
     * @param integer $limit
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getMineLatest($limit = 10)
    {
        return $this->model->query()
            ->where('user_id', auth()->id())
            ->limit($limit)
            ->with([
                'user',
                // 'role'
            ])
            ->latest()
            ->get();
    }
}
