<?php

namespace App\Http\Controllers;

use App\Exports\RoleExampleExport;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\RoleRequest;
use App\Imports\RoleImport;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoleController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->defaultMiddleware('Role');

        $this->icon = 'fas fa-user-tag';
        $this->viewFolder = 'user-management.roles';
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData()
    {
        $data        = $this->userRepository->getRoles();
        $defaultData = $this->getDefaultDataIndex(__('Role'), 'Role', 'user-management.roles');
        $data        = array_merge(['data' => $data], $defaultData);
        return $data;
    }

    /**
     * get store data
     *
     * @param RoleRequest $request
     * @return array
     */
    private function getStoreData(RoleRequest $request): array
    {
        $data = $request->only([
            'permissions',
            'name',
        ]);
        return $data;
    }

    /**
     * get detail data
     *
     * @param Role $role
     * @param boolean $isDetail
     * @return array
     */
    private function getDetailData(Role $role, bool $isDetail): array
    {
        $role->load(['permissions']);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $defaultData = $this->getDefaultDataDetail(__('Role'), 'user-management.roles', $role, $isDetail);
        $data = [
            'rolePermissions'  => $rolePermissions,
            'permissionGroups' => $this->userRepository->getPermissionGroupWithChild(),
            'fullTitle'        => $isDetail ? __('Detail Role') : __('Ubah Role')
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
            'pdf_name'   => $times . '_roles.pdf',
            'excel_name' => $times . '_roles.xlsx',
            'csv_name'   => $times . '_roles.csv',
            'json_name'  => $times . '_roles.json',
        ];
        return array_merge($this->getIndexData(), $data);
    }

    /**
     * showing user management page
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->getIndexData();

        return view('stisla.user-management.roles.index', $data);
    }

    /**
     * showing create role page
     *
     * @return Response
     */
    public function create()
    {
        $data = [
            'permissionGroups' => $this->userRepository->getPermissionGroupWithChild(),
            'fullTitle'        => __('Tambah Role')
        ];
        $defaultData = $this->getDefaultDataCreate(__('Role'), 'user-management.roles');
        $data = array_merge($data, $defaultData);
        return view('stisla.user-management.roles.form', $data);
    }

    /**
     * store role data
     *
     * @param RoleRequest $request
     * @return Response
     */
    public function store(RoleRequest $request)
    {
        $data   = $this->getStoreData($request);
        $result = $this->userRepository->createRole($request->name, $data);

        logCreate('Role', $result);

        $successMessage = successMessageCreate('Role Dan Permission');
        return back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit role page
     *
     * @param Role $role
     * @return View
     */
    public function edit(Role $role): View
    {
        $data = $this->getDetailData($role, false);
        return view('stisla.user-management.roles.form', $data);
    }

    /**
     * update role data
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->is_locked) abort(404);

        $before = $this->userRepository->findRole($role->id);
        $data   = $this->getStoreData($request);
        $after  = $this->userRepository->updateRole($role->id, $data);

        logUpdate('Role', $before, $after);

        $successMessage = successMessageUpdate('Role Dan Permission');
        return back()->with('successMessage', $successMessage);
    }

    /**
     * showing detail role page
     *
     * @param Role $role
     * @return View
     */
    public function show(Role $role): View
    {
        $data = $this->getDetailData($role, true);
        return view('stisla.user-management.roles.form', $data);
    }

    /**
     * delete role data
     *
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($role->is_locked) abort(404);

            $before = $this->userRepository->findRole($role->id);
            $this->userRepository->deleteRole($role->id);

            logDelete('Role', $before);
            DB::commit();

            $successMessage = successMessageDelete('Role Dan Permission');
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
        $filepath = public_path('excel_examples/sample_roles.xlsx');
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
            $this->fileService->importExcel(new RoleImport, $request->file('import_file'));
            DB::commit();
            return back()->with('successMessage', __('Impor berhasil dilakukan'));
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
        return $this->fileService->downloadExcelGeneral('stisla.user-management.roles.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.user-management.roles.table', $data, $data['csv_name']);
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
