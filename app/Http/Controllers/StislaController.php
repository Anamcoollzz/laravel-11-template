<?php

namespace App\Http\Controllers;

use App\Exports\GeneralExport;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\GeneralImport;
use App\Repositories\ActivityLogRepository;
use App\Repositories\Repository;
use App\Repositories\RequestLogRepository;
use App\Repositories\SettingRepository;
use App\Repositories\UserRepository;
use App\Services\DropBoxService;
use App\Services\EmailService;
use App\Services\FileService;
use App\Services\PDFService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;

class StislaController extends Controller
{
    /**
     * file service
     *
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * pdf service
     *
     * @var PDFService
     */
    protected PDFService $pdfService;

    /**
     * email service
     *
     * @var EmailService
     */
    protected EmailService $emailService;

    /**
     * repository
     *
     * @var Repository
     */
    protected Repository $repository;

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
     * request log repository
     *
     * @var RequestLogRepository
     */
    protected RequestLogRepository $requestLogRepository;

    /**
     * icon of module
     *
     * @var String
     */
    protected String $icon;

    /**
     * can export
     *
     * @var String
     */
    protected String $canExport;

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
     * title
     *
     * @var String
     */
    protected String $title;

    /**
     * prefix
     *
     * @var String
     */
    protected String $prefix;

    /**
     * import excel example path
     *
     * @var String
     */
    protected String $importExcelExamplePath;

    /**
     * pdf paper size
     *
     * @var String
     */
    protected String $pdfPaperSize = 'A4';

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
     * import
     *
     * @var GeneralImport
     */
    protected GeneralImport $import;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->fileService           = new FileService;
        $this->pdfService            = new PDFService;
        $this->emailService          = new EmailService;
        $this->userRepository        = new UserRepository;
        $this->dropBoxService        = new DropBoxService;
        $this->activityLogRepository = new ActivityLogRepository;
        $this->requestLogRepository  = new RequestLogRepository;
        $this->dropBoxService        = new DropBoxService;
        $this->settingRepository     = new SettingRepository;
        $this->import                = new GeneralImport;
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
        $this->middleware('can:' . $moduleName . ' Ekspor')->only(['json', 'excel', 'csv', 'pdf', 'exportJson', 'exportExcel', 'exportCsv', 'exportPdf']);
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
        $user = auth_user();

        $canCreate      = $user->can($permissionPrefix . ' Tambah');
        $canUpdate      = $user->can($permissionPrefix . ' Ubah');
        $canDetail      = $user->can($permissionPrefix . ' Detail');
        $canDelete      = $user->can($permissionPrefix . ' Hapus');
        $canImportExcel = $user->can($permissionPrefix . ' Impor Excel');
        $canExport      = $user->can($permissionPrefix . ' Ekspor');
        $canForceLogin  = $user->can($permissionPrefix . ' Force Login');

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
        $moduleName = str_replace('-', '_', $this->prefixRoute ?? $this->prefix);
        $data       = [
            'isExport'   => true,
            'pdf_name'   => $times . '_' . $moduleName . '.pdf',
            'excel_name' => $times . '_' . $moduleName . '.xlsx',
            'csv_name'   => $times . '_' . $moduleName . '.csv',
            'json_name'  => $times . '_' . $moduleName . '.json',
        ];

        return array_merge($data, $this->getIndexDataFromParent());
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

    /**
     * get index data
     *
     * @param array $data
     * @return array
     */
    protected function getIndexDataFromParent(array $data = [])
    {
        $prefix = $this->prefix;
        $title  = $this->title;

        $isYajra     = Route::is($prefix . '.index-yajra');
        $isAjax      = Route::is($prefix . '.index-ajax');
        $isAjaxYajra = Route::is($prefix . '.index-ajax-yajra');

        if ($isYajra || $isAjaxYajra)
            $data = collect([]);
        else {
            if (isset($data['data']))
                $data = $data['data'];
            else
                $data = $this->repository->getFullData();
        }

        $defaultData = $this->getDefaultDataIndex($title, $title, $prefix . '');
        $users = [];

        if (Route::is($prefix . '.index') || Route::is($prefix . '.index-ajax'))
            $users = $this->userRepository->getLatest();

        $times    = date('Y-m-d_H-i-s');
        $filename = $times . '_' . Str::snake($title);

        return array_merge($defaultData, [
            'data'         => $data,
            'isYajra'      => $isYajra,
            'isAjax'       => $isAjax,
            'isAjaxYajra'  => $isAjaxYajra,
            'yajraColumns' => $this->repository->getYajraColumns(),
            'users'        => $users,
            'isExport'     => false,
            'pdf_name'     => $filename . '.pdf',
            'excel_name'   => $filename . '.xlsx',
            'csv_name'     => $filename . '.csv',
            'json_name'    => $filename . '.json',
            'moduleIcon'   => $this->icon,
            'canExport'    => $this->canExport ?? $defaultData['canExport'],
        ]);
    }

    /**
     * import excel file to db
     *
     * @param ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(ImportExcelRequest $request)
    {
        $this->fileService->importExcel($this->import, $request->file('import_file'));
        $successMessage = successMessageImportExcel($this->title);

        return backSuccess($successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function exportJson()
    {
        $filename = date('YmdHis') . '_' . Str::snake($this->title) . '.json';
        $data     = $this->repository->getLatest();

        return $this->fileService->downloadJson($data, $filename);
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function exportExcel()
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadExcelGeneral('stisla.' . $this->prefix . '.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function exportCsv()
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.' . $this->prefix . '.table', $data, $data['csv_name']);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function exportPdf()
    {
        $filename = date('YmdHis') . '_' . Str::snake($this->title) . '.pdf';
        $html     = view('stisla.' . $this->prefix . '.export-pdf', [
            'title'    => $this->title,
            'data'     => $this->repository->getFullData(),
            'isExport' => true,
        ])->render();
        // return $html;

        if ($this->pdfPaperSize === 'A2') {
            return $this->pdfService->downloadPdfA2($html, $filename);
        } else if ($this->pdfPaperSize === 'A4') {
            return $this->pdfService->downloadPdfA4($html, $filename);
        } else if ($this->pdfPaperSize === 'A3') {
            return $this->pdfService->downloadPdfA3($html, $filename);
        }
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    protected function executeImportExcelExample(): BinaryFileResponse
    {
        $excelInstance = new GeneralExport('stisla.' . $this->prefix . '.table', [
            'data' => $this->repository->getFullData(),
            'isExport' => true,
        ]);
        return $this->fileService->downloadExcel($excelInstance, Str::snake($this->prefix) . '_import.xlsx');
    }

    /**
     * execute destroy
     *
     * @param Model $model
     * @return Response
     */
    protected function executeDestroy(Model $model)
    {
        $this->repository->delete($model->id);
        logDelete($this->title, $model);
        $successMessage = successMessageDelete($this->title);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return backSuccess($successMessage);
    }

    /**
     * execute update
     *
     * @param Request $request
     * @param Model $model
     * @return Response
     */
    protected function executeUpdate(Request $request, Model $model)
    {
        $data    = $this->getStoreData($request);
        $newData = $this->repository->updateWithUser($data, $model->id);
        logUpdate($this->title, $model, $newData);
        $successMessage = successMessageUpdate($this->title);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return backSuccess($successMessage);
    }

    /**
     * save data to db
     *
     * @param Request $request
     * @return Response
     */
    protected function executeStore(Request $request)
    {
        $data   = $this->getStoreData($request);
        $result = $this->repository->create($data);
        logCreate($this->title, $result);
        $successMessage = successMessageCreate($this->title);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return backSuccess($successMessage);
    }

    /**
     * prepare create form
     *
     * @param Request $request
     * @param array $data
     * @return Response
     */
    protected function prepareCreateForm(Request $request, array $data = [])
    {
        $fullTitle  = __('Tambah ' . $this->title);
        $data       = array_merge($this->getDefaultDataCreate($this->title, $this->prefix), $data);
        $data       = array_merge($data, [
            'selectOptions'   => get_options(10, true),
            'select2Options'  => get_options(10),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $fullTitle,
        ]);

        if ($request->ajax()) {
            return view('stisla.' . $this->prefix . '.only-form', $data);
        }

        return view('stisla.' . $this->prefix . '.form', $data);
    }

    /**
     * get detail data
     *
     * @param Model|Illuminate\Foundation\Auth\User $model
     * @param bool $isDetail
     * @param array $data
     * @return array
     */
    protected function getDetailData(Model $model, bool $isDetail = false, array $data = []): array
    {
        $defaultData = $this->getDefaultDataDetail($this->title, $this->prefix, $model, $isDetail);
        return array_merge($defaultData, [
            'selectOptions'   => get_options(10, true),
            'select2Options'  => get_options(10, true),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $isDetail ? __('Detail ' . $this->title) : __('Ubah ' . $this->title),
        ], $data);
    }

    /**
     * prepare index
     *
     * @param Request $request
     * @param array $data
     * @return Response
     */
    protected function prepareIndex(Request $request, array $data2 = [])
    {
        $data = array_merge($this->getIndexDataFromParent($data2), $data2);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => view('stisla.' . $this->prefix . '.table', $data)->render(),
            ]);
        }

        return view('stisla.' . $this->prefix . '.index', $data);
    }

    /**
     * prepare detail form
     *
     * @param Request $request
     * @param Model $model
     * @param bool $isDetail
     * @param array $data
     * @return Response
     */
    protected function prepareDetailForm(Request $request, Model $model, bool $isDetail = false, array $data = [])
    {
        $data = array_merge($this->getDetailData($model, $isDetail), $data);

        if ($request->ajax()) {
            return view('stisla.' . $this->prefix . '.only-form', $data);
        }

        return view('stisla.' . $this->prefix . '.form', $data);
    }

    /**
     * datatable yajra index
     *
     * @return Response
     */
    public function yajraAjax()
    {
        $defaultData = $this->getDefaultDataIndex(__($this->title), $this->title, $this->prefix);
        return $this->repository->getYajraDataTables($defaultData);
    }
}
