<?php

namespace App\Http\Controllers;

use App\Exports\RequestLogExport;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RequestLogController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:Log Request');
    }

    /**
     * showing page data
     *
     * @return Response
     */
    public function index()
    {
        $user            = auth_user();
        $user            = $this->userRepository->find($user->id);
        $queryString     = request()->query();
        $data            = $this->requestLogRepository->getFilter();
        $roles           = $this->userRepository->getRoleOptions();
        $users           = $this->userRepository->getUserOptions();
        $browserOptions  = $this->requestLogRepository->getBrowserOptions();
        $platformOptions = $this->requestLogRepository->getPlatformOptions();
        $deviceOptions   = $this->requestLogRepository->getDeviceOptions();
        $methodOptions   = $this->requestLogRepository->getMethodOptions();

        return view('stisla.request-logs.index', [
            'data'             => $data,
            'users'            => $users,
            'roles'            => $roles,
            'browserOptions'   => $browserOptions,
            'platformOptions'  => $platformOptions,
            'deviceOptions'    => $deviceOptions,
            'methodOptions'    => $methodOptions,
            'canCreate'        => false,
            // 'canCreate'        => $user->can('Log Request Tambah'),
            // 'canUpdate'        => $user->can('Log Request Ubah'),
            // 'canDelete'        => $user->can('Log Request Hapus'),
            // 'canImportExcel'   => $user->can('Log Request Impor Excel'),
            // 'canExport'        => $user->can('Log Request Ekspor'),
            'canExport'        => false,
            'title'            => __('Log Request'),
            'routeCreate'      => null,
            // 'routeCreate'      => route('request-logs.create'),
            'routePdf'         => route('request-logs.pdf', $queryString),
            'routePrint'       => route('request-logs.print', $queryString),
            'routeExcel'       => route('request-logs.excel', $queryString),
            'routeCsv'         => route('request-logs.csv', $queryString),
            'routeJson'        => route('request-logs.json', $queryString),
            'isSuperAdmin'     => $user->hasRole('superadmin'),
            // 'routeImportExcel' => route('request-logs.import-excel'),
            // 'excelExampleLink' => route('request-logs.import-excel-example'),
        ]);
    }

    /**
     * download export data as json
     *
     * @return BinaryFileResponse
     */
    public function json(): BinaryFileResponse
    {
        $data = $this->requestLogRepository->getFilter();
        return $this->fileService->downloadJson($data, 'request-logs.json');
    }

    /**
     * download export data as xlsx
     *
     * @return BinaryFileResponse
     */
    public function excel(): BinaryFileResponse
    {
        $data = $this->requestLogRepository->getFilter();
        return (new RequestLogExport($data))->download('request-logs.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return BinaryFileResponse
     */
    public function csv(): BinaryFileResponse
    {
        $data = $this->requestLogRepository->getFilter();
        return (new RequestLogExport($data))->download('request-logs.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf(): Response
    {
        $data = $this->requestLogRepository->getFilter();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.request-logs.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('request-logs.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->requestLogRepository->getFilter();
        return view('stisla.request-logs.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
