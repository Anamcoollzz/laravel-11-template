<?php

namespace App\Repositories;

use App\Models\RequestLog;
use Illuminate\Support\Facades\DB;

class RequestLogRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new RequestLog();
    }

    /**
     * getFilter
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFilter()
    {
        return $this->model->query()
            ->when(request('filter_date'), function ($query) {
                $query->whereDate('created_at', request('filter_date'));
            })
            ->when(request('filter_user'), function ($query) {
                $query->whereUserId(request('filter_user'));
            })
            ->when(request('filter_method'), function ($query) {
                $query->whereMethod(request('filter_method'));
            })
            ->when(request('filter_device'), function ($query) {
                $query->whereDevice(request('filter_device'));
            })
            ->when(request('filter_platform'), function ($query) {
                $query->wherePlatform(request('filter_platform'));
            })
            ->when(request('filter_browser'), function ($query) {
                $query->whereBrowser(request('filter_browser'));
            })
            ->when(!auth()->user()->hasRole('superadmin'), function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->with([
                'user',
            ])
            ->when(request('filter_limit', 50), function ($query) {
                $query->limit(request('filter_limit', 50));
            })
            ->latest()
            ->get();
    }

    /**
     * getBrowserOptions
     *
     * @return array
     */
    public function getBrowserOptions()
    {
        $query = "SELECT DISTINCT browser FROM `log_requests`;";
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
        $query = "SELECT DISTINCT device FROM `log_requests`;";
        $results = DB::select($query);
        return collect($results)->pluck('device', 'device')->toArray();
    }

    /**
     * getMethodOptions
     *
     * @return array
     */
    public function getMethodOptions()
    {
        $query = "SELECT DISTINCT method FROM `log_requests`;";
        $results = DB::select($query);
        return collect($results)->pluck('method', 'method')->toArray();
    }

    /**
     * getPlatformOptions
     *
     * @return array
     */
    public function getPlatformOptions()
    {
        $query = "SELECT DISTINCT platform FROM `log_requests`;";
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
            ])
            ->latest()
            ->get();
    }
}
