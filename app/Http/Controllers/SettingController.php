<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Repositories\SettingRepository;
use App\Services\FileService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\Console\Output\BufferedOutput;

class SettingController extends Controller
{

    /**
     * setting repository var
     *
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * fileservice
     *
     * @var FileService
     */
    private FileService $fileService;

    /**
     * construct function
     *
     * @return void
     */
    public function __construct()
    {
        $this->settingRepository = new SettingRepository;
        $this->fileService       = new FileService;

        $this->middleware('can:Pengaturan');
        $this->middleware('can:Reset Sistem')->only(['reset', 'reset2']);
    }

    /**
     * showing setting page
     *
     * @param mixed $type
     * @return Response
     */
    public function index($type)
    {
        if (config('app.template') === 'stisla') {
            $skins = $this->settingRepository->getStislaSkins();
            $fullTitle = 'Pengaturan Umum';
            if ($type === 'meta') {
                $fullTitle = 'Pengaturan Meta';
            } else if ($type === 'view') {
                $fullTitle = 'Pengaturan Tampilan';
            } else if ($type === 'other') {
                $fullTitle = 'Pengaturan Lainnya';
            } else if ($type === 'sso') {
                $fullTitle = __('SSO Login dan Register');
            }
            return view('stisla.settings.index', [
                'skins'      => $skins,
                'type'       => $type,
                'routeIndex' => route('settings.all'),
                'fullTitle'  => $fullTitle,
                'title'      => __('Pengaturan'),
            ]);
        } else {
            $skins = collect($this->settingRepository->getSkins())->map(function ($item) {
                $item2['name'] = $item;
                return $item2;
            })->pluck('name', 'name')->toArray();
            return view('sbadmin.settings.index', [
                'skins' => $skins,
            ]);
        }
    }

    /**
     * showing all setting page
     *
     * @return View
     */
    public function allSetting()
    {
        if (config('app.template') === 'stisla') {
            $skins = $this->settingRepository->getStislaSkins();
            $options = [
                [
                    'title' => __('Umum'),
                    'desc'  => __('Pengaturan seperti nama aplikasi, nama perusahaan, tahun berdiri, dll.'),
                    'route' => route('settings.index', ['type' => 'general']),
                    'icon'  => 'cog',
                ],
                [
                    'title' => __('Meta'),
                    'desc'  => __('Pengaturan seperti meta author, description, dan keyword.'),
                    'route' => route('settings.index', ['type' => 'meta']),
                    'fullIcon'  => 'fab fa-chrome',
                ],
                [
                    'title' => __('Tampilan'),
                    'desc'  => __('Pengaturan seperti nama aplikasi, nama perusahaan, tahun berdiri, dll.'),
                    'route' => route('settings.index', ['type' => 'view']),
                    'icon'  => 'eye'
                ],
                [
                    'title' => __('Email'),
                    'desc'  => __('Pengaturan seperti provider email, pengirim, nama pengirim, dll.'),
                    'route' => route('settings.index', ['type' => 'email']),
                    'icon'  => 'envelope'
                ],
                [
                    'title' => __('SSO Login dan Register'),
                    'desc'  => __('Pengaturan untuk SSO menggunakan media sosial seperti google, facebook, twitter dan github.'),
                    'route' => route('settings.index', ['type' => 'sso']),
                    'icon'  => 'key'
                ],
                [
                    'title' => __('Lainnya'),
                    'desc'  => __('Pengaturan email verifikasi, lupa password, halaman daftar.'),
                    'route' => route('settings.index', ['type' => 'other']),
                    'icon'  => 'cogs'
                ],
            ];
            return view('stisla.settings.all', [
                'title'      => __('Pengaturan'),
                'options'    => $options
            ]);
        } else {
            $skins = collect($this->settingRepository->getSkins())->map(function ($item) {
                $item2['name'] = $item;
                return $item2;
            })->pluck('name', 'name')->toArray();
            return view('sbadmin.settings.index', [
                'skins' => $skins,
            ]);
        }
    }

    /**
     * update setting data
     *
     * @param SettingRequest $request
     * @return Response
     */
    public function update(SettingRequest $request)
    {
        $before = $this->settingRepository->all();
        $encrypts = SettingRepository::getEncryptedKeys();
        foreach ($request->all() as $key => $input) {
            $value = $input;
            if ($key === 'favicon') {
                $value = $this->fileService->uploadFavicon($request->file('favicon'));
            } else if ($key === 'logo') {
                $value = $this->fileService->uploadLogo($request->file('logo'));
            } else if ($key === 'stisla_bg_login') {
                $value = $this->fileService->uploadStislaBgLogin($request->file('stisla_bg_login'));
            } else if ($key === 'stisla_bg_home') {
                $value = $this->fileService->uploadStislaBgHome($request->file('stisla_bg_home'));
            }

            if (in_array($key, $encrypts)) {
                $this->settingRepository->updateByKey(['value' => encrypt($value)], $key);
            } else {
                $this->settingRepository->updateByKey(['value' => $value], $key);
            }
        }
        $settings = SettingRepository::settings();
        foreach ($settings as $key => $setting) {
            Session::forget('_' . $key);
        }
        Session::forget('_logo_url');
        Session::forget('_logo');
        $after = $this->settingRepository->all();
        logUpdate('Pengaturan', $before, $after);
        return back()->with(config('app.template') === 'stisla' ? 'successMessage' : 'success_msg', __('Application Setting') . ' ' . __('success updated'));
    }

    /**
     * reset setting data
     *
     * @return Response
     */
    public function reset()
    {
        // return back();
        config(['stisla.use_setting' => '1']);

        // shell_exec('php artisan db:seed');
        // //get output from shell
        // $commandOutput = shell_exec('php artisan db:seed');
        // dd($commandOutput);
        $output = new BufferedOutput();
        $a = Artisan::call(
            'migrate:refresh',
            [
                '--force' => true,
                '--path' => 'database/migrations',
                '--realpath' => true,
                // '--pretend' => false,
                '--seed' => true,
            ],
            $output
        );
        $commandOutput = $output->fetch();
        return backSuccess('Aplikasi berhasil direset');
    }

    /**
     * reset setting data
     *
     * @return Response
     */
    public function reset2()
    {
        config(['stisla.use_setting' => '2']);
        Artisan::call('db:seed');
        return backSuccess(__('Aplikasi berhasil direset'));
    }
}
