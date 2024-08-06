<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CrudExample;
use App\Models\LogRequest;
use App\Models\Menu;
use App\Models\MenuGroup;
use App\Models\Notification;
use App\Models\PermissionGroup;
use App\Models\User;
use App\Repositories\SettingRepository;
use App\Services\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
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

        if ($user->can('Contoh CRUD'))
            $widgets[] = (object)[
                'title' => 'Contoh CRUD',
                'count' => CrudExample::count(),
                'bg'    => 'primary',
                'icon'  => 'atom',
                'route' => route('crud-examples.index'),
            ];
        if ($user->can('Pengguna'))
            $widgets[] = (object)[
                'title' => 'Pengguna',
                'count' => User::count(),
                'bg'    => 'danger',
                'icon'  => 'users',
                'route' => route('user-management.users.index'),
            ];
        if ($user->can('Role'))
            $widgets[] = (object)[
                'title' => 'Role',
                'count' => Role::count(),
                'bg'    => 'success',
                'icon'  => 'lock',
                'route' => route('user-management.roles.index')
            ];
        if ($user->can('Permission'))
            $widgets[] = (object)[
                'title' => 'Permission',
                'count' => Permission::count(),
                'bg'    => 'info',
                'icon'  => 'key',
                'route' => route('user-management.permissions.index')
            ];
        if ($user->can('Group Permission'))
            $widgets[] = (object)[
                'title' => 'Group Permission',
                'count' => PermissionGroup::count(),
                'bg'    => 'info',
                'icon'  => 'key',
                'route' => route('user-management.permission-groups.index'),
                'bg_color' => 'brown'
            ];
        if ($user->can('Menu'))
            $widgets[] = (object)[
                'title' => 'Menu',
                'count' => Menu::count(),
                'bg'    => 'primary',
                'icon'  => 'bars',
                'route' => route('menu-managements.index'),
                'bg_color' => 'pink'
            ];
        if ($user->can('Grup Menu'))
            $widgets[] = (object)[
                'title' => 'Grup Menu',
                'count' => MenuGroup::count(),
                'bg'    => 'danger',
                'icon'  => 'bars',
                'route' => route('menu-managements.index'),
                'bg_color' => 'orange'
            ];
        if ($user->can('Notifikasi')) {
            $widgets[] = (object)[
                'title' => 'Notifikasi',
                'count' => Notification::where('user_id', $user->id)->count(),
                'bg'    => 'info',
                'icon'  => 'bell',
                'route' => route('notifications.index'),
                'bg_color' => 'navy'
            ];
        }
        if ($user->can('Log Aktivitas'))
            $widgets[] = (object)[
                'title' => 'Log Aktivitas',
                'count' => ActivityLog::count(),
                'bg'    => 'success',
                'icon'  => 'clock-rotate-left',
                'route' => route('activity-logs.index'),
                'bg_color' => 'black'
            ];
        if ($user->can('Log Request'))
            $widgets[] = (object)[
                'title' => 'Log Request',
                'count' => LogRequest::count(),
                'bg'    => 'success',
                'icon'  => 'clock-rotate-left',
                'route' => route('request-logs.index'),
                'bg_color' => '#C88A65',
            ];
        if ($user->can('Pengaturan'))
            $widgets[] = (object)[
                'title' => 'Pengaturan',
                'count' => '6',
                'bg'    => 'success',
                'icon'  => 'clock-rotate-left',
                'route' => route('settings.all'),
                'bg_color' => '#E9D66B',
            ];
        if ($user->can('Backup Database')) {
            $widgets[] = (object)[
                'title' => 'Backup Database',
                'count' => count((new DatabaseService)->getAllBackupMysql()),
                'bg'    => 'primary',
                'icon'  => 'database',
                'route' => route('backup-databases.index'),
                'bg_color' => 'purple',
            ];
        }

        $logs = $this->activityLogRepository->getMineLatest();

        return view('stisla.dashboard.index', [
            'widgets' => $widgets,
            'logs'    => $logs,
            'user'    => $user,
        ]);
    }

    public function post(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|max:102048',
        ]);
        $link = $this->fileService->uploadFile($request->file('file_upload'), 'file_upload');
        auth()->user()->update(['file_upload' => $link]);
        return redirect()->back()->with('successMessage', 'File berhasil diupload');
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
