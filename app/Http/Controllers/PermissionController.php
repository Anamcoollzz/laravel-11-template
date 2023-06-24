<?php

namespace App\Http\Controllers;

use App\Exports\PermissionExport;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\PermissionRequest;
use App\Imports\PermissionImport;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PermissionController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->defaultMiddleware('Permission');

        $this->icon = 'fa fa-user-lock';
        $this->viewFolder = 'user-management.permissions';
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData(): array
    {
        $data        = $this->userRepository->getLatestPermissionJoinGroups();
        $defaultData = $this->getDefaultDataIndex(__('Permission'), 'Permission', 'user-management.permissions');
        $data        = array_merge(['data' => $data], $defaultData);
        return $data;
    }

    /**
     * get store data
     *
     * @param PermissionRequest $request
     * @return array
     */
    private function getStoreData(PermissionRequest $request): array
    {
        $data = $request->only(['name', 'permission_group_id']);
        return $data;
    }

    /**
     * get detail data
     *
     * @param Permission $permission
     * @param boolean $isDetail
     * @return array
     */
    private function getDetailData(Permission $permission, bool $isDetail): array
    {
        $defaultData = $this->getDefaultDataDetail(__('Permission'), 'user-management.permissions', $permission, $isDetail);
        $data = [
            'groupOptions'     => $this->userRepository->getPermissionGroupOptions(),
            'fullTitle'        => $isDetail ? __('Detail Permission') : __('Ubah Permission')
        ];
        return array_merge($data, $defaultData);
    }

    /**
     * get export data
     *
     * @return array
     */
    protected function getExportData(): array
    {
        $times = date('Y-m-d_H-i-s');
        $data = [
            'isExport'   => true,
            'pdf_name'   => $times . '_permissions.pdf',
            'excel_name' => $times . '_permissions.xlsx',
            'csv_name'   => $times . '_permissions.csv',
            'json_name'  => $times . '_permissions.json',
        ];
        return array_merge($this->getIndexData(), $data);
    }

    /**
     * showing data page
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->getIndexData();
        return view('stisla.user-management.permissions.index', $data);
    }

    /**
     * showing create role page
     *
     * @return Response
     */
    public function create()
    {
        $defaultData = $this->getDefaultDataCreate(__('Permission'), 'user-management.permissions');
        $data = [
            'fullTitle'        => 'Tambah Permission',
            'groupOptions'     => $this->userRepository->getPermissionGroupOptions(),
        ];
        $data = array_merge($data, $defaultData);
        return view('stisla.user-management.permissions.form', $data);
    }

    /**
     * store role data
     *
     * @param PermissionRequest $request
     * @return Response
     */
    public function store(PermissionRequest $request)
    {
        $data   = $this->getStoreData($request);
        $result = $this->userRepository->createPermission($data);

        logCreate('Permission', $result);

        $successMessage = successMessageCreate('Permission');
        return back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit permission page
     *
     * @param Permission $permission
     * @return Response
     */
    public function edit(Permission $permission)
    {
        $data = $this->getDetailData($permission, false);
        return view('stisla.user-management.permissions.form', $data);
    }

    /**
     * update permission data
     *
     * @param PermissionRequest $request
     * @param Permission $permission
     * @return Response
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $before = $this->userRepository->findPermission($permission->id);
        $data   = $this->getStoreData($request);
        $after  = $this->userRepository->updatePermission($permission->id, $data);

        logUpdate('Permission', $before, $after);

        $successMessage = successMessageUpdate('Permission');
        return back()->with('successMessage', $successMessage);
    }

    /**
     * showing detail permission page
     *
     * @param Permission $permission
     * @return Response
     */
    public function show(Permission $permission)
    {
        $data = $this->getDetailData($permission, true);
        return view('stisla.user-management.permissions.form', $data);
    }

    /**
     * delete permission data
     *
     * @param Permission $permission
     * @return Response
     */
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $before = $this->userRepository->findPermission($permission->id);
            $this->userRepository->deletePermission($permission->id);

            logDelete('Permission', $before);

            DB::commit();

            $successMessage = successMessageDelete('Permission');
            return back()->with('successMessage', $successMessage);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('errorMessage', $exception->getMessage());
        }
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        $filepath = public_path('excel_examples/sample_permissions.xlsx');
        return response()->download($filepath);
    }

    /**
     * import excel file to db
     *
     * @param ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(ImportExcelRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->fileService->importExcel(new PermissionImport, $request->file('import_file'));
            $successMessage = successMessageImportExcel("Permission");
            DB::commit();
            return back()->with('successMessage', $successMessage);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('errorMessage', $exception->getMessage());
        }
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
        return $this->fileService->downloadExcelGeneral('stisla.user-management.permissions.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.user-management.permissions.table', $data, $data['csv_name']);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf(): Response
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadPdfLetter('stisla.includes.others.export-pdf', $data, $data['pdf_name'], 'portrait');
    }
}
