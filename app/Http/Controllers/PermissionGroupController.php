<?php

namespace App\Http\Controllers;

use App\Exports\PermissionGroupExport;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\PermissionGroupRequest;
use App\Imports\PermissionGroupImport;
use App\Models\PermissionGroup;
use App\Repositories\UserRepository;
use App\Services\FileService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PermissionGroupController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->defaultMiddleware('Group Permission');

        $this->icon = 'fa fa-user-lock';
        $this->viewFolder = 'user-management.permission-groups';
    }
    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData(): array
    {
        $data        = $this->userRepository->getLatestPermissionGroups();
        $defaultData = $this->getDefaultDataIndex(__('Group Permission'), 'Group Permission', 'user-management.permission-groups');
        $data        = array_merge(['data' => $data], $defaultData);
        return $data;
    }

    /**
     * get store data
     *
     * @param PermissionGroupRequest $request
     * @return array
     */
    private function getStoreData(PermissionGroupRequest $request): array
    {
        $data = $request->only(['group_name']);
        return $data;
    }

    /**
     * get detail data
     *
     * @param PermissionGroup $permissionGroup
     * @param boolean $isDetail
     * @return array
     */
    private function getDetailDataOld(PermissionGroup $permissionGroup, bool $isDetail): array
    {
        $defaultData = $this->getDefaultDataDetail(__('Group Permission'), 'user-management.permission-groups', $permissionGroup, $isDetail);
        $data        = ['fullTitle' => $isDetail ? __('Detail Group Permission') : __('Ubah Group Permission')];
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
            'pdf_name'   => $times . '_permission_groups.pdf',
            'excel_name' => $times . '_permission_groups.xlsx',
            'csv_name'   => $times . '_permission_groups.csv',
            'json_name'  => $times . '_permission_groups.json',
        ];
        return array_merge($this->getIndexData(), $data);
    }

    /**
     * showing permission group page
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->getIndexData();
        return view('stisla.user-management.permission-groups.index', $data);
    }

    /**
     * showing create permission group page
     *
     * @return View
     */
    public function create(): View
    {
        $defaultData = $this->getDefaultDataCreate(__('Group Permission'), 'user-management.permission-groups');
        $data        = ['fullTitle' => __('Tambah Group Permission')];
        $data        = array_merge($data, $defaultData);

        return view('stisla.user-management.permission-groups.form', $data);
    }

    /**
     * store permission group data
     *
     * @param PermissionGroupRequest $request
     * @return Response
     */
    public function store(PermissionGroupRequest $request)
    {
        $data   = $this->getStoreData($request);
        $result = $this->userRepository->createPermissionGroup($data);

        logCreate('Group Permission', $result);

        $successMessage = successMessageCreate('Group Permission');
        return backSuccess($successMessage);
    }

    /**
     * showing edit permission group page
     *
     * @param PermissionGroup $permissionGroup
     * @return Response
     */
    public function edit(PermissionGroup $permissionGroup)
    {
        $data = $this->getDetailDataOld($permissionGroup, false);
        return view('stisla.user-management.permission-groups.form', $data);
    }

    /**
     * update permission group data
     *
     * @param PermissionGroupRequest $request
     * @param PermissionGroup $permissionGroup
     * @return RedirectResponse
     */
    public function update(PermissionGroupRequest $request, PermissionGroup $permissionGroup): RedirectResponse
    {
        $before = $this->userRepository->findPermissionGroup($permissionGroup->id);
        $data   = $this->getStoreData($request);
        $after  = $this->userRepository->updatePermissionGroup($permissionGroup->id, $data);

        logUpdate('Group Permission', $before, $after);

        $successMessage = successMessageUpdate('Group Permission');
        return backSuccess($successMessage);
    }

    /**
     * showing detail permission group page
     *
     * @param PermissionGroup $permissionGroup
     * @return Response
     */
    public function show(PermissionGroup $permissionGroup)
    {
        $data = $this->getDetailDataOld($permissionGroup, true);
        return view('stisla.user-management.permission-groups.form', $data);
    }

    /**
     * delete permission data
     *
     * @param Permission $permissionGroup
     * @return RedirectResponse
     */
    public function destroy(Permission $permissionGroup): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $before = $this->userRepository->findPermissionGroup($permissionGroup->id);
            $this->userRepository->deletePermissionGroup($permissionGroup->id);

            logDelete('Group Permission', $before);
            DB::commit();

            $successMessage = successMessageDelete('Group Permission');
            return backSuccess($successMessage);
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
        $filepath = public_path('excel_examples/sample_permission_groups.xlsx');
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
            $this->fileService->importExcel(new PermissionGroupImport, $request->file('import_file'));

            DB::commit();

            $successMessage = successMessageImportExcel("Group Permission");
            return backSuccess($successMessage);
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
        return $this->fileService->downloadExcelGeneral('stisla.user-management.permission-groups.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.user-management.permission-groups.table', $data, $data['csv_name']);
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
