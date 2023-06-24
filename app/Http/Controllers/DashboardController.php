<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\PersonRepository;
use App\Repositories\SettingRepository;
use App\Services\DatabaseService;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class DashboardController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // $this->middleware('can:Log Aktivitas');
    }

    /**
     * Menampilkan halaman dashboard
     *
     * @return Response
     */
    public function index()
    {
        $widgets = [];
        $user = auth()->user();

        if ($user->can('Pengguna'))
            $widgets[] = (object)[
                'title' => 'Pengguna',
                'count' => User::count(),
                'bg'    => 'primary',
                'icon'  => 'users',
                'route' => route('user-management.users.index'),
            ];
        if ($user->can('Role'))
            $widgets[] = (object)[
                'title' => 'Role',
                'count' => Role::count(),
                'bg'    => 'danger',
                'icon'  => 'lock',
                'route' => route('user-management.roles.index')
            ];
        if ($user->can('Log Aktivitas'))
            $widgets[] = (object)[
                'title' => 'Log Aktivitas',
                'count' => ActivityLog::count(),
                'bg'    => 'success',
                'icon'  => 'clock-rotate-left',
                'route' => route('activity-logs.index')
            ];

        if ($user->can('Notifikasi')) {
            $widgets[] = (object)[
                'title' => 'Notifikasi',
                'count' => Notification::where('user_id', $user->id)->count(),
                'bg'    => 'info',
                'icon'  => 'bell',
                'route' => route('notifications.index'),
            ];
        }

        if ($user->can('Backup Database')) {
            $widgets[] = (object)[
                'title' => 'Backup Database',
                'count' => count((new DatabaseService)->getAllBackupMysql()),
                'bg'    => 'primary',
                'icon'  => 'database',
                'route' => route('backup-databases.index')
            ];
        }

        if ($user->can('Penduduk')) {
            $widgets[] = (object)[
                'title' => 'Penduduk',
                'count' => (new PersonRepository)->count(),
                'bg'    => 'primary',
                'icon'  => 'user-plus',
                'route' => route('backup-databases.index')
            ];
        }

        $logs = $this->activityLogRepository->getMineLatest();

        return view('stisla.dashboard.index', [
            'widgets' => $widgets,
            'logs'    => $logs,
            'user'    => $user,
        ]);
    }

    /**
     * home page
     *
     * @return Response
     */
    public function home()
    {
        return view('stisla.homes.index', [
            'title' => __('Selamat datang di ') . SettingRepository::applicationName(),
        ]);
    }
}
