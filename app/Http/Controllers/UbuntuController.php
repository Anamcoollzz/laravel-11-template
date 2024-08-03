<?php

namespace App\Http\Controllers;

use App\Jobs\EditFileJob;
use App\Jobs\ShellJob;
use App\Services\CommandService;
use App\Services\DatabaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UbuntuController extends StislaController
{

    /**
     * CommandService
     *
     * @var CommandService
     */
    private CommandService $commandService;

    /**
     * DatabaseService
     *
     * @var DatabaseService
     */
    private DatabaseService $dbService;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:Ubuntu');

        $this->commandService = new CommandService();
        $this->dbService      = new DatabaseService();
    }

    /**
     * index method
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->query('redirect_folder')) {
            $path = $request->query('redirect_folder');
            return redirect()->route('ubuntu.index', ['folder' => encrypt($path)]);
        }

        if ($request->query('download')) {
            $path = decrypt($request->query('download'));
            return response()->download($path);
        }

        $files = collect([]);
        if (File::exists('/etc/nginx/sites-available')) {
            $files      = File::files('/etc/nginx/sites-available');
        }

        // $path       = '/Users/anamkun/Documents/PROJEK/ME';
        // $path = '/Users/anamkun/Documents/PROJEK/ME/laravel-8-template';
        $path = '/var/www';
        if ($request->query('folder')) {
            $path = decrypt($request->query('folder'));
        }
        $parentPath = dirname($path);

        $phps = $this->fileService->getAllPhp();

        $supervisors = $this->fileService->getSupervisor();

        $isEnvExists = false;
        $foldersWww = [];
        $filesWww = [];
        $isLaravel = false;
        if (File::exists($path)) {
            $filesWww   = File::files($path, true);
            foreach ($filesWww as $f) {
                if ($f->getFilename() == '.env') {
                    $isEnvExists = true;
                }
            }
            $foldersWww = File::directories($path);
        }

        foreach ($foldersWww as $folder) {
            if (basename($folder) === 'routes') {
                $isLaravel = true;
            }
        }

        $seeders = [];
        if ($isLaravel && File::exists($path . '/database/seeders')) {
            $seederFiles   = File::files($path . '/database/seeders', true);
            foreach ($seederFiles as $seed) {
                $seeders[] = str_replace('.php', '', $seed->getFilename());
            }
        }

        $isGit = File::exists($path . '/.git');

        $i = 0;
        foreach ($files as $file) {
            File::exists('/etc/nginx/sites-enabled/' . $file->getFilename()) ? $files[$i]->enabled = true : $files[$i]->enabled = false;
            $content = $files[$i]->content = file_get_contents($file->getPathname());
            $domain =  explode('server_name', $content)[1];
            $domain = trim(explode(';', $domain)[0]);
            $files[$i]->domain = $domain ?? null;
            $files[$i]->is_ssl = str_contains($content, 'ssl_certificate');
            $i++;
        }

        $databases = [];
        $tables    = [];
        $structure = [];
        $rows      = [];
        $database  = request('database');
        $table     = request('table');
        $action    = request('action');

        $primary = 'id';
        if ($database && $table && $action == 'json') {
            return $this->dbService->getAllRowMySqlAsJson($database, $table);
        } else if ($database && $table && $action == 'json-download') {
            $data = $this->dbService->getAllRowMySqlAsJson($database, $table);
            $data = json_encode($data, JSON_PRETTY_PRINT);
            Storage::put('public/' . $database . '-' . $table . '.json', $data);
            return response()->download(storage_path('app/public/' . $database . '-' . $table . '.json'))->deleteFileAfterSend();
        } else if ($database && $table && $action == 'json-paginate') {
            $data = $this->dbService->getPaginateMySql($database, $table, request('perPage', 20));
            return response()->json($data);
        } else if ($database && $table) {
            $result    = $this->dbService->getAllRowMySql($database, $table);
            $rows      = $result['rows'];
            $structure = $result['structure'];
            $primary   = $this->dbService->getPrimaryColumn($database, $table);
        } else if ($database) {
            $tables = $this->dbService->getAllTableMySql($database);
        } else {
            $databases = $this->dbService->getAllDbMySql();
        }


        $nginxStatus      = shell_exec('service nginx status');
        $supervisorStatus = shell_exec('service supervisor status');
        $mysqlStatus      = shell_exec('service mysql status');

        return view('stisla.ubuntu.index', [
            'files'            => $files,
            'filesWww'         => $filesWww,
            'foldersWww'       => $foldersWww,
            'path'             => $path,
            'isGit'            => $isGit,
            'isLaravel'        => $isLaravel,
            'databases'        => $databases,
            'tables'           => $tables,
            'rows'             => $rows,
            'structure'        => $structure,
            'parentPath'       => $parentPath,
            'nginxStatus'      => $nginxStatus,
            'phps'             => $phps,
            'supervisors'      => $supervisors,
            'supervisorStatus' => $supervisorStatus,
            'isEnvExists'      => $isEnvExists,
            'primary'          => $primary,
            'mysqlStatus'      => $mysqlStatus,
            'seeders'          => $seeders,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $pathname
     * @return Response
     */
    public function edit($pathname)
    {
        $pathnameD = decrypt($pathname);
        $file = file_get_contents($pathnameD);

        return view('stisla.ubuntu.form', [
            'file'       => $file,
            'title'      => __('Ubuntu'),
            'fullTitle'  => __('Ubah File'),
            'routeIndex' => route('ubuntu.index'),
            'action'     => route('ubuntu.update', [$pathname]),
            'pathname'   => $pathnameD,
        ]);
    }

    /**
     * edit row
     *
     * @param string $database
     * @param string $table
     * @param string|int $id
     * @return void
     */
    public function editRow($database, $table, $id)
    {
        $primary = request("primary");
        $d = DB::table($database . '.' . $table)->where($primary, $id)->first();

        if (!$d) abort(404);

        if (request('json') === 'true') {
            return response()->json($d);
        }

        $d = json_decode(json_encode($d), true);

        return view('stisla.ubuntu.form-row', [
            'title'      => __('MySQL Database'),
            'fullTitle'  => __('MySQL Database'),
            'routeIndex' => route('ubuntu.index'),
            'action'     => route('ubuntu.update-row', [$database, $table, $id, 'primary' => $primary]),
            'd'          => $d,
            'keys'       => array_keys($d),
        ]);
    }

    /**
     * update row
     *
     * @param string $database
     * @param string $table
     * @param string|int $id
     * @param Request $request
     * @return Response
     */
    public function updateRow($database, $table, $id, Request $request)
    {
        $primary = request("primary");
        $data = $request->except('_token', '_method', 'primary');
        DB::table($database . '.' . $table)->where($primary, $id)->update($data);
        return redirect()->back()->with('successMessage', 'Berhasil memperbarui data');
    }

    /**
     * update file
     *
     * @param string $pathname
     * @param Request $request
     * @return Response
     */
    public function update($pathname, Request $request)
    {
        $pathnameD = decrypt($pathname);
        EditFileJob::dispatch($pathnameD, $request->filename);

        if ($pathnameD !== request('pathname')) {
            $command = 'mv ' . $pathnameD . ' ' . request('pathname');
            ShellJob::dispatch($command);
            return redirect('/ubuntu')->with('successMessage', 'Berhasil memperbarui file');
        }

        return redirect()->back()->with('successMessage', 'Berhasil memperbarui file');
    }

    /**
     * duplicate file
     *
     * @param string $pathname
     * @return Response
     */
    public function duplicate($pathname)
    {
        $pathnameD = decrypt($pathname);
        $content = file_get_contents($pathnameD);
        if (request('as')) {
            $folder = str_replace(basename($pathnameD), '', $pathnameD);
            EditFileJob::dispatch($folder . request('as'), $content);
            return redirect()->back()->with('successMessage', 'Berhasil menduplikasi file ke .env');
        } else {
            EditFileJob::dispatch($pathnameD . '_copy', $content);
            return redirect()->back()->with('successMessage', 'Berhasil menduplikasi file');
        }
    }

    /**
     * delete file
     *
     * @param string $pathname
     * @return Response
     */
    public function destroy($pathname)
    {
        $pathnameD = decrypt($pathname);
        $command = $this->commandService->deleteFile($pathnameD);
        ShellJob::dispatch($command);

        return redirect()->back()->with('successMessage', 'Berhasil menghapus file');
    }

    /**
     * toggle enabled
     *
     * @param string $pathname
     * @param string $nextStatus
     * @return Response
     */
    public function toggleEnabled($pathname, $nextStatus)
    {
        $pathnameD = decrypt($pathname);
        if ($nextStatus === 'true') {
            $command = $this->commandService->enableNginxConf($pathnameD);
            ShellJob::dispatch($command);
            return redirect()->back()->with('successMessage', 'Berhasil menjalankan command ' . $command);
        } else if ($nextStatus === 'false') {
            $command = $this->commandService->disableNginxConf($pathnameD);
            ShellJob::dispatch($command);
            return redirect()->back()->with('successMessage', 'Berhasil menjalankan command ' . $command);
        }
        abort(404);
    }

    /**
     * toggle ssl
     *
     * @param string $pathname
     * @param string $nextStatus
     * @return Response
     */
    public function toggleSSL($pathname, $nextStatus)
    {
        $pathnameD = decrypt($pathname);
        $content   = file_get_contents($pathnameD);
        $domain    = explode('server_name', $content)[1];
        $domain    = trim(explode(';', $domain)[0]);
        if ($nextStatus === 'true') {
            $command = $this->commandService->sslNginx($domain);
            ShellJob::dispatch($command);
            return redirect()->back()->with('successMessage', 'Berhasil menjalankan command ' . $command);
        } else if ($nextStatus === 'false') {
            $command = $this->commandService->deleteSSL($domain);
            ShellJob::dispatch($command);
            return redirect()->back()->with('successMessage', 'Berhasil menjalankan command ' . $command);
        }
        abort(404);
    }

    /**
     * git pull
     *
     * @param string $pathname
     * @return Response
     */
    public function gitPull($pathname)
    {

        $pathnameD = decrypt($pathname);

        $command = 'git config --global --add safe.directory ' . $pathnameD . ' 2>&1';
        $output = shell_exec($command);
        return $output;
        // $command = 'git config --global --add safe.directory ' . $pathnameD . ' && /usr/bin/git pull origin 2>&1';
        ShellJob::dispatch($command, $pathnameD);

        return redirect()->back()->with('successMessage', 'Berhasil run command ' . $command);
    }

    /**
     * set laravel permission
     *
     * @param string $pathname
     * @return Response
     */
    public function setLaravelPermission($pathname)
    {
        $pathnameD = decrypt($pathname);

        ShellJob::dispatch($command = $this->commandService->setLaravelPermission($pathnameD));

        return redirect()->back()->with('successMessage', 'Berhasil run command ' . $command);
    }

    /**
     * create new db
     *
     * @return Response
     */
    public function createDb()
    {
        $schemaName = request('database_name');
        $this->dbService->createMySqlDb($schemaName);
        return redirect()->back()->with('successMessage', 'Berhasil membuat database ' . $schemaName);
    }

    /**
     * delete row in table
     *
     * @return Response
     */
    public function deleteRow($database, $table, $id)
    {
        $this->dbService->deleteRow($database, $table, $id);
        return redirect()->back()->with('successMessage', 'Berhasil menghapus data');
    }

    /**
     * command php-fpm
     *
     * @return Response
     */
    public function phpFpm($version, $action)
    {
        $command = $this->commandService->phpFpm($version, $action);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan command  ' . $command);
    }

    /**
     * command mysql
     *
     * @return Response
     */
    public function mysql($version, $action)
    {
        $command = $this->commandService->mysql($version, $action);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan command  ' . $command);
    }

    /**
     * command supervisor
     *
     * @return Response
     */
    public function supervisor($action)
    {
        $command = $this->commandService->supervisor($action);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan command  ' . $command);
    }

    /**
     * command laravel seeder
     *
     * @return Response
     */
    public function laravelSeeder($class)
    {
        $pathnameD = decrypt(request('folder'));
        $command = $this->commandService->laravelDbSeed($pathnameD, $class);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan command  ' . $command);
    }

    /**
     * command laravel migrate
     *
     * @return Response
     */
    public function laravelMigrate()
    {
        $pathnameD = decrypt(request('folder'));
        $command = $this->commandService->laravelMigrate($pathnameD);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan artisan command  ' . $command);
    }

    /**
     * command laravel migrate refresh
     *
     * @return Response
     */
    public function laravelMigrateRefresh()
    {
        $pathnameD = decrypt(request('folder'));
        $command = $this->commandService->laravelMigrateRefresh($pathnameD);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan artisan command  ' . $command);
    }

    /**
     * nginx
     *
     * @param Request $request
     * @return Response
     */
    public function nginx(Request $request)
    {
        $command = $this->commandService->nginx($request->nginx);
        ShellJob::dispatch($command);
        return redirect()->back()->with('successMessage', 'Berhasil menjalankan command  ' . $command);
    }
}
