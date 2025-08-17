<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class ActivityLogRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new ActivityLog();
    }

    /**
     * getFilter
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFilter()
    {
        return $this->model->query()
            ->when(request('filter_date', date('Y-m-d')), function ($query) {
                $query->whereDate('created_at', request('filter_date', date('Y-m-d')));
            })
            // ->when(request('filter_role'), function ($query) {
            //     $query->whereRoleId(request('filter_role'));
            // })
            ->when(request('filter_user'), function ($query) {
                $query->whereUserId(request('filter_user'));
            })
            ->when(request('filter_kind'), function ($query) {
                $query->whereActivityType(request('filter_kind'));
            })
            ->when(request('filter_browser'), function ($query) {
                $query->whereBrowser(request('filter_browser'));
            })
            ->when(!auth_user()->hasRole('superadmin'), function ($query) {
                $query->where('user_id', auth_user()->id);
            })
            ->with([
                'user',
                // 'role'
            ])
            ->latest()
            ->get();
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
            ->where('user_id', auth_id())
            ->limit($limit)
            ->with([
                'user',
                // 'role'
            ])
            ->latest()
            ->get();
    }
}
