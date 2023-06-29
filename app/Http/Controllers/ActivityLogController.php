<?php

namespace App\Http\Controllers;

use App\Exports\ActivityLogExport;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ActivityLogController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:Log Aktivitas');
    }

    /**
     * showing page data
     *
     * @return Response
     */
    public function index()
    {
        $user            = auth()->user();
        $user            = $this->userRepository->find($user->id);
        $queryString     = request()->query();
        $data            = $this->activityLogRepository->getFilter();
        $roles           = $this->userRepository->getRoleOptions();
        $users           = $this->userRepository->getUserOptions();
        $kinds           = $this->activityLogRepository->getActivityTypeOptions();
        $browserOptions  = $this->activityLogRepository->getBrowserOptions();
        $platformOptions = $this->activityLogRepository->getPlatformOptions();
        $deviceOptions   = $this->activityLogRepository->getDeviceOptions();

        return view('stisla.activity-logs.index', [
            'data'             => $data,
            'users'            => $users,
            'roles'            => $roles,
            'kinds'            => $kinds,
            'browserOptions'   => $browserOptions,
            'platformOptions'  => $platformOptions,
            'deviceOptions'    => $deviceOptions,
            'canCreate'        => false,
            // 'canCreate'        => $user->can('Log Aktivitas Tambah'),
            // 'canUpdate'        => $user->can('Log Aktivitas Ubah'),
            // 'canDelete'        => $user->can('Log Aktivitas Hapus'),
            // 'canImportExcel'   => $user->can('Log Aktivitas Impor Excel'),
            'canExport'        => $user->can('Log Aktivitas Ekspor'),
            'title'            => __('Log Aktivitas'),
            'routeCreate'      => null,
            // 'routeCreate'      => route('activity-logs.create'),
            'routePdf'         => route('activity-logs.pdf', $queryString),
            'routePrint'       => route('activity-logs.print', $queryString),
            'routeExcel'       => route('activity-logs.excel', $queryString),
            'routeCsv'         => route('activity-logs.csv', $queryString),
            'routeJson'        => route('activity-logs.json', $queryString),
            'isSuperAdmin'     => $user->hasRole('superadmin'),
            // 'routeImportExcel' => route('activity-logs.import-excel'),
            // 'excelExampleLink' => route('activity-logs.import-excel-example'),
        ]);
    }

    /**
     * download export data as json
     *
     * @return BinaryFileResponse
     */
    public function json(): BinaryFileResponse
    {
        $data = $this->activityLogRepository->getFilter();
        return $this->fileService->downloadJson($data, 'activity-logs.json');
    }

    /**
     * download export data as xlsx
     *
     * @return BinaryFileResponse
     */
    public function excel(): BinaryFileResponse
    {
        $data = $this->activityLogRepository->getFilter();
        return (new ActivityLogExport($data))->download('activity-logs.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return BinaryFileResponse
     */
    public function csv(): BinaryFileResponse
    {
        $data = $this->activityLogRepository->getFilter();
        return (new ActivityLogExport($data))->download('activity-logs.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf(): Response
    {
        $data = $this->activityLogRepository->getFilter();
        $html = view('stisla.activity-logs.export-pdf', [
            'data'    => $data,
            'isPrint' => false,
            'isExport' => true,
        ])->render();
        return PDF::setPaper('Letter', 'landscape')
            ->loadHTML($html)
            ->download('activity-logs.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->activityLogRepository->getFilter();
        return view('stisla.activity-logs.export-pdf', [
            'data'    => $data,
            'isPrint' => true,
            'isExport' => true,
        ]);
    }
}
