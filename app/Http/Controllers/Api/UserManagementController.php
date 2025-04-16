<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserManagementController extends Controller
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
        $this->middleware('can:Pengguna');
        $this->middleware('can:Pengguna Tambah')->only(['create', 'store']);
        $this->middleware('can:Pengguna Ubah')->only(['edit', 'update', 'updatePassword']);
        $this->middleware('can:Pengguna Hapus')->only(['destroy']);
    }

    /**
     * get all user as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->userRepository->getPaginateUsers(request('perPage'));
        $successMessage = successMessageLoadData("Pengguna");
        return response200($data, $successMessage);
    }

    /**
     * get detail user
     *
     * @param mixed $userId
     * @return JsonResponse
     */
    public function show(mixed $userId)
    {
        $data = $this->userRepository->findWithOrFail($userId, ['roles.permissions']);
        $successMessage = successMessageLoadData("Pengguna");
        return response200($data, $successMessage);
    }

    /**
     * save new user to db
     *
     * @param UserRequest $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $user = $this->userRepository->create(
            array_merge(
                [
                    'password' => bcrypt($request->password)
                ],
                $request->only([
                    'name',
                    'email',
                    'phone_number',
                    'birth_date',
                    'address',
                ])
            )
        );
        $user->assignRole($request->role);
        logCreate('Pengguna', $user);
        $successMessage = successMessageCreate("Pengguna");
        return response200($user, $successMessage);
    }

    /**
     * update user to db
     *
     * @param UserRequest $request
     * @param mixed $userId
     * @return Response
     */
    public function update(UserRequest $request, mixed $userId)
    {
        $user = $this->userRepository->findOrFail($userId);
        $data = $request->only([
            'name',
            'email',
            'phone_number',
            'birth_date',
            'address',
        ]);
        $userNew = $this->userRepository->update($data, $user->id);
        $this->userRepository->syncRoles($userNew, $request->role);
        logUpdate('Pengguna', $user, $userNew);
        $successMessage = successMessageUpdate("Pengguna");
        return response200($userNew, $successMessage);
    }

    /**
     * delete user from db
     *
     * @param mixed $userId
     * @return Response
     */
    public function destroy(mixed $userId)
    {
        $user = $this->userRepository->findOrFail($userId);
        $deleted = $this->userRepository->delete($userId);
        logDelete('Pengguna', $user);
        $successMessage = successMessageDelete("Pengguna");
        return response200($deleted, $successMessage);
    }

    /**
     * update password user to db
     *
     * @param UserRequest $request
     * @param mixed $userId
     * @return Response
     */
    public function updatePassword(UserRequest $request, $userId)
    {
        $user = $this->userRepository->findOrFail($userId);
        $data = ['password' => bcrypt($request->new_password)];
        $userNew = $this->userRepository->update($data, $userId);
        logUpdate(logTitleUpdate('Kata Sandi Pengguna'), $user->password, $userNew->password);
        return response200(true, __('Berhasil memperbarui kata sandi pengguna'));
    }
}
