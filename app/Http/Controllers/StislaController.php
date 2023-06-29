<?php

namespace App\Http\Controllers;

use App\Repositories\ActivityLogRepository;
use App\Repositories\SettingRepository;
use App\Repositories\UserRepository;
use App\Services\DropBoxService;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StislaController extends Controller
{
    /**
     * file service
     *
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * email service
     *
     * @var EmailService
     */
    protected EmailService $emailService;

    /**
     * user repository
     *
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * activity log repository
     *
     * @var ActivityLogRepository
     */
    protected ActivityLogRepository $activityLogRepository;

    /**
     * icon of module
     *
     * @var String
     */
    protected String $icon;

    /**
     * prefix route of module
     *
     * @var String
     */
    protected String $prefixRoute;

    /**
     * name orientation pdf
     *
     * @var String
     */
    protected String $orientationPdf = 'landscape';

    /**
     * name paper size pdf
     *
     * @var String
     */
    protected String $paperSize = 'Letter';

    /**
     * name view folder
     *
     * @var String
     */
    protected String $viewFolder;

    /**
     * import excel example path
     *
     * @var String
     */
    protected String $importExcelExamplePath;

    /**
     * dropbox service
     *
     * @var DropBoxService
     */
    protected DropBoxService $dropBoxService;

    /**
     * setting repository
     *
     * @var SettingRepository
     */
    protected SettingRepository $settingRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->fileService           = new FileService;
        $this->emailService          = new EmailService;
        $this->userRepository        = new UserRepository;
        $this->dropBoxService        = new DropBoxService;
        $this->activityLogRepository = new ActivityLogRepository;
        $this->dropBoxService        = new DropBoxService;
        $this->settingRepository     = new SettingRepository;
    }

    /**
     * Default middleware
     *
     * @param string $moduleName
     * @return void
     */
    public function defaultMiddleware(string $moduleName)
    {
        $this->middleware('can:' . $moduleName . '');
        $this->middleware('can:' . $moduleName . ' Tambah')->only(['create', 'store']);
        $this->middleware('can:' . $moduleName . ' Ubah')->only(['edit', 'update']);
        $this->middleware('can:' . $moduleName . ' Detail')->only(['show']);
        $this->middleware('can:' . $moduleName . ' Hapus')->only(['destroy']);
        $this->middleware('can:' . $moduleName . ' Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:' . $moduleName . ' Impor Excel')->only(['importExcel', 'importExcelExample']);
        $this->middleware('can:' . $moduleName . ' Force Login')->only(['forceLogin']);
    }

    /**
     * Default data index
     *
     * @param string $title
     * @param string $permissionPrefix
     * @param string $routePrefix
     * @return array
     */
    public function getDefaultDataIndex(string $title, string $permissionPrefix, string $routePrefix)
    {
        $user = auth()->user();

        $canCreate      = $user->can($permissionPrefix . ' Tambah');
        $canUpdate      = $user->can($permissionPrefix . ' Ubah');
        $canDetail      = $user->can($permissionPrefix . ' Detail');
        $canDelete      = $user->can($permissionPrefix . ' Hapus');
        $canImportExcel = $user->can($permissionPrefix . ' Impor Excel');
        $canExport      = $user->can($permissionPrefix . ' Ekspor');
        $canForceLogin  = $user->can($permissionPrefix . ' Force Login');

        // dd($canImportExcel);

        return [
            'canCreate'         => $canCreate,
            'canUpdate'         => $canUpdate,
            'canDetail'         => $canDetail,
            'canDelete'         => $canDelete,
            'canImportExcel'    => $canImportExcel,
            'canExport'         => $canExport,
            'canForceLogin'     => $canForceLogin,
            'title'             => $title,
            'moduleIcon'        => $this->icon,
            'route_create'      => $canCreate ? route($routePrefix . '.create') : null,
            'routeImportExcel'  => $canImportExcel && Route::has($routePrefix . '.import-excel') ? route($routePrefix . '.import-excel') : null,
            'routeExampleExcel' => $canImportExcel && Route::has($routePrefix . '.import-excel') ? route($routePrefix . '.import-excel-example') : null,
            'routePdf'          => $canExport && Route::has($routePrefix . '.pdf') ? route($routePrefix . '.pdf') : null,
            'routeExcel'        => $canExport && Route::has($routePrefix . '.excel') ? route($routePrefix . '.excel') : null,
            'routeCsv'          => $canExport && Route::has($routePrefix . '.csv') ? route($routePrefix . '.csv') : null,
            'routeJson'         => $canExport && Route::has($routePrefix . '.json') ? route($routePrefix . '.json') : null,
            'routeYajra'        => Route::has($routePrefix . '.ajax-yajra') ? route($routePrefix . '.ajax-yajra') : null,
            'routeStore'        => Route::has($routePrefix . '.store') ? route($routePrefix . '.store') : null,
            'routePrefix'       => $routePrefix,
            'isExport'          => false,
            'folder'            => $routePrefix,
            'viewFolder'        => $this->viewFolder,
        ];
    }

    /**
     * Default data create
     *
     * @param string $title
     * @param string $prefixRoute
     * @return array
     */
    public function getDefaultDataCreate(string $title, string $prefixRoute)
    {
        if (request()->ajax()) {
            $isAjax = true;
        }
        $routeIndex = route($prefixRoute . '.index');
        return [
            'title'           => $title,
            'routeIndex'      => $routeIndex,
            'action'          => route($prefixRoute . '.store'),
            'moduleIcon'      => $this->icon,
            'isDetail'        => false,
            'isAjax'          => $isAjax ?? false,
            'breadcrumbs'     => [
                [
                    'label' => __('Dashboard'),
                    'link'  => route('dashboard.index')
                ],
                [
                    'label' => $title,
                    'link'  => $routeIndex
                ],
                [
                    'label' => 'Tambah'
                ]
            ],
            'viewFolder' => $this->viewFolder,
        ];
    }

    /**
     * Default data detail
     *
     * @param string $title
     * @param string $prefixRoute
     * @param Model $row
     * @param boolean $isDetail
     * @return array
     */
    public function getDefaultDataDetail(string $title, string $prefixRoute, Model $row, bool $isDetail)
    {
        $routeIndex  = route($prefixRoute . '.index');
        $breadcrumbs = [
            [
                'label' => __('Dashboard'),
                'link'  => route('dashboard.index')
            ],
            [
                'label' => $title,
                'link'  => $routeIndex
            ],
            [
                'label' => $isDetail ? 'Detail' : 'Ubah'
            ]
        ];
        return [
            'd'           => $row,
            'title'       => $title,
            'routeIndex'  => $routeIndex,
            'action'      => route($prefixRoute . '.update', [$row->id]),
            'moduleIcon'  => $this->icon,
            'isDetail'    => $isDetail,
            'breadcrumbs' => $breadcrumbs,
            'viewFolder'  => $this->viewFolder,
        ];
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        return response()->download($this->importExcelExamplePath);
    }

    /**
     * get export data
     *
     * @return array
     */
    protected function getExportData(): array
    {
        $times      = date('Y-m-d_H-i-s');
        $moduleName = str_replace('-', '_', $this->prefixRoute);
        $data       = [
            'isExport'   => true,
            'pdf_name'   => $times . '_' . $moduleName . '.pdf',
            'excel_name' => $times . '_' . $moduleName . '.xlsx',
            'csv_name'   => $times . '_' . $moduleName . '.csv',
            'json_name'  => $times . '_' . $moduleName . '.json',
        ];
        return array_merge($this->getIndexData(), $data);
    }

    /**
     * download export data as json
     *
     * @return BinaryFileResponse
     */
    public function json(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadJson($data['data'], $data['json_name']);
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadExcelGeneral('stisla.' . $this->viewFolder . '.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.' . $this->viewFolder . '.table', $data, $data['csv_name']);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf(): Response
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadPdf('stisla.includes.others.export-pdf', $data, $data['pdf_name'], $this->paperSize, $this->orientationPdf);
    }
}
