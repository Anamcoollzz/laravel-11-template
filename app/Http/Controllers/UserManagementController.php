<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\UserRequest;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserManagementController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->defaultMiddleware('Pengguna');

        $this->icon           = 'fa fa-users';
        $this->viewFolder     = 'user-management';
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData()
    {
        $roleOptions = $this->userRepository->getRoleOptions();
        $defaultData = $this->getDefaultDataIndex(__('Pengguna'), 'Pengguna', 'user-management.users');
        return array_merge($defaultData, [
            'data'      => $this->userRepository->getUsers(),
            'roleCount' => count($roleOptions),
        ]);
    }

    /**
     * get store data
     *
     * @param UserRequest $request
     * @return array
     */
    private function getStoreData(UserRequest $request): array
    {
        $data = $request->only([
            'name',
            'email',
            'phone_number',
            'birth_date',
            'address',
        ]);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        return $data;
    }

    /**
     * get detail data
     *
     * @param User $user
     * @param boolean $isDetail
     * @return array
     */
    protected function getDetailDataOld(User $user, bool $isDetail)
    {
        $roleOptions = $this->userRepository->getRoleOptions();
        if ($user->roles->count() > 1)
            $user->role = $user->roles->pluck('id')->toArray();
        else
            $user->role = $user->roles->first()->id ?? null;
        $defaultData = $this->getDefaultDataDetail(__('Pengguna'), 'user-management.users', $user, $isDetail);
        return array_merge($defaultData, [
            'roleOptions' => $roleOptions,
            'fullTitle'   => $isDetail ? __('Detail Pengguna') : __('Ubah Pengguna'),
        ]);
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
            'pdf_name'   => $times . '_users.pdf',
            'excel_name' => $times . '_users.xlsx',
            'csv_name'   => $times . '_users.csv',
            'json_name'  => $times . '_users.json',
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
        return view('stisla.user-management.users.index', $data);
    }

    /**
     * showing add new user page
     *
     * @return Response
     */
    public function create()
    {
        $roleOptions = $this->userRepository->getRoleOptions();
        $defaultData = $this->getDefaultDataCreate(__('Pengguna'), 'user-management.users');
        return view('stisla.user-management.users.form', array_merge($defaultData, [
            'roleOptions' => $roleOptions,
            'fullTitle'   => __('Tambah Pengguna'),
        ]));
    }

    /**
     * save new user to db
     *
     * @param UserRequest $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $data = $this->getStoreData($request);
        $data['created_by_id'] = auth_id();
        // $data['last_updated_by_id'] = auth_id();
        $user = $this->userRepository->create($data);
        $this->userRepository->syncRolesByID($user, $request->role);
        logCreate('Pengguna', $user);
        $successMessage = successMessageCreate('Pengguna');
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit user page
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $data = $this->getDetailDataOld($user, false);
        return view('stisla.user-management.users.form', $data);
    }

    /**
     * update user to db
     *
     * @param UserRequest $request
     * @param User $user
     * @return Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $this->getStoreData($request);
        $data['last_updated_by_id'] = auth_id();

        $userNew = $this->userRepository->update($data, $user->id);
        $this->userRepository->syncRolesByID($userNew, $request->role);
        logUpdate('Pengguna', $user, $userNew);
        $successMessage = successMessageUpdate('Pengguna');
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing detail user page
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        $data = $this->getDetailDataOld($user, true);
        return view('stisla.user-management.users.form', $data);
    }

    /**
     * delete user from db
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $this->userRepository->softDelete($user->id);
        logDelete('Pengguna', $user);
        $successMessage = successMessageDelete('Pengguna');
        return backSuccess($successMessage);
    }

    /**
     * force login with specific user
     *
     * @param User $user
     * @return Response
     */
    public function forceLogin(User $user)
    {
        $this->userRepository->login($user);
        return Helper::redirectSuccess(route('dashboard.index'), __('Berhasil masuk ke dalam sistem'));
    }

    /**
     * block specific user
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function block(Request $request, User $user)
    {
        $request->validate([
            'blocked_reason' => 'required|string|max:255',
        ]);
        $after = $this->userRepository->update([
            'is_active'          => false,
            'blocked_reason'     => $request->blocked_reason,
            'last_updated_by_id' => auth_id()
        ], $user->id);
        logExecute('Blokir Pengguna', UPDATE, $user, $after);
        return backSuccess('Pengguna ' . $user->email . ' berhasil diblokir');
    }

    /**
     * unblock specific user
     *
     * @param User $user
     * @return Response
     */
    public function unblock(User $user)
    {
        $after = $this->userRepository->update([
            'is_active'          => true,
            'blocked_reason'     => null,
            'last_updated_by_id' => auth_id()
        ], $user->id);
        logExecute('Buka Blokir Pengguna', UPDATE, $user, $after);
        return backSuccess('Pengguna ' . $user->email . ' berhasil diaktifkan kembali');
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        $filepath = public_path('excel_examples/sample_users.xlsx');
        return response()->download($filepath);
    }

    /**
     * import excel file to db
     *
     * @param ImportExcelRequest $request
     * @return RedirectResponse
     */
    public function importExcel(ImportExcelRequest $request): RedirectResponse
    {
        $this->fileService->importExcel(new UserImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Pengguna");
        return backSuccess($successMessage);
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
        return $this->fileService->downloadExcelGeneral('stisla.user-management.users.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.user-management.users.table', $data, $data['csv_name']);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf(): Response
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadPdfA3('stisla.includes.others.export-pdf', $data, $data['pdf_name']);
    }
}
