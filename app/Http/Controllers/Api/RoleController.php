<?php

namespace App\Http\Controllers\Api;

use App\Exports\RoleExampleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\RoleRequest;
use App\Imports\RoleImport;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoleController extends Controller
{
    /**
     * user repository
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository;

        $this->middleware('can:Role');
        $this->middleware('can:Role Tambah')->only(['store']);
        $this->middleware('can:Role Ubah')->only(['update']);
        $this->middleware('can:Role Hapus')->only(['destroy']);
    }

    /**
     * get roles data
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->userRepository->getRoles();
        $successMessage = successMessageLoadData('Role Dan Permission');
        return response200($data, $successMessage);
    }

    /**
     * store role data
     *
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request)
    {
        $result = $this->userRepository->createRole($request->name, $request->only(['permissions']));
        logCreate('Role', $result);
        $successMessage = successMessageCreate('Role Dan Set Permission');
        return response200($result, $successMessage);
    }

    /**
     * get detail role
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        $role->load(['permissions']);
        $successMessage = successMessageLoadData('Role Dan Permission');
        return response200($role, $successMessage);
    }

    /**
     * update role data
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        if ($role->is_locked) return response404();
        $before = $this->userRepository->findRole($role->id);
        $after = $this->userRepository->updateRole($role->id, $request->only(['permissions']));
        logUpdate('Role', $before, $after);
        $successMessage = successMessageUpdate('Role Dan Permission');
        return response200($after, $successMessage);
    }

    /**
     * delete role data
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            if ($role->is_locked) return response404();
            $before = $this->userRepository->findRole($role->id);
            $this->userRepository->deleteRole($role->id);
            logDelete('Role', $before);
            DB::commit();
            $successMessage = successMessageDelete('Role Dan Permission');
            return response200(true, $successMessage);
        } catch (Exception $exception) {
            DB::rollBack();
            return response500(null, $exception->getMessage());
        }
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        return Excel::download(new RoleExampleExport($this->userRepository->getRoles()), 'role_import_examples.xlsx');
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
            Excel::import(new RoleImport, $request->file('import_file'));
            return back()->with('successMessage', __('Impor berhasil dilakukan'));
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('errorMessage', $exception->getMessage());
        }
    }

    /**
     * get all permissions
     *
     * @return JsonResponse
     */
    public function permissions()
    {
        $data = $this->userRepository->getPermissions();
        $successMessage = successMessageLoadData('Permission');
        return response200($data, $successMessage);
    }
}
