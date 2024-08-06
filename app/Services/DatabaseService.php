<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseService
{

    private $folder;
    private $folderName;

    /**
     * DatabaseService constructor.
     */
    public function __construct()
    {
        $this->folderName = 'database-backups';
        $this->folder = storage_path('app/public/' . $this->folderName);
    }

    /**
     * backup mysql database
     *
     * @return void
     */
    public function backupMysql()
    {
        $this->checkFolder();

        $filename = 'backup-db-' . date('Y-m-d-H-i-s') . '.sql';
        $path = $this->folder . '/' . $filename;
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $command = "mysqldump --user={$username} --password={$password} {$database} > {$path}";
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        $commandZip = "cd {$this->folder} && zip -r {$filename}.zip {$filename}";
        $returnVarZip = NULL;
        $outputZip = NULL;
        exec($commandZip, $outputZip, $returnVarZip);

        $commandDelete = "cd {$this->folder} && rm {$filename}";
        $returnVarDelete = NULL;
        $outputDelete = NULL;
        exec($commandDelete, $outputDelete, $returnVarDelete);

        return $returnVar;
    }

    /**
     * backup daily mysql
     *
     * @return void
     */
    public function backupDailyMysql()
    {
        $today = date('Y-m-d');
        $data = $this->getAllBackupMysql();
        $data = collect($data)->filter(function ($item) use ($today) {
            return Str::contains($item, $today);
        })->sort()->reverse();
        if ($data->count() === 0) {
            $this->backupMysql();
        }
    }

    /**
     * get all backup mysql database
     *
     * @return Collection
     */
    public function getAllBackupMysql()
    {
        $files = Storage::files('public/' . $this->folderName);
        $data = collect($files)->sort()->reverse()
            ->map(function ($item) {
                return [
                    'path'       => $item,
                    'name'       => basename($item),
                    'url'        => asset(Storage::url($item)),
                    'size'       => $size = Storage::size($item),
                    'size_on_mb' => round($size / 1024 / 1024, 2),
                    'size_on_kb' => round($size / 1024, 2),
                    'size_on_gb' => round($size / 1024 / 1024 / 1024, 2),
                ];
                return $item;
            })->values();
        return $data;
    }

    /**
     * check folder is exist
     *
     * @return void
     */
    private function checkFolder()
    {
        if (!file_exists($this->folder)) {
            Storage::makeDirectory('public/' . $this->folderName);
        }
    }

    /**
     * restore mysql database
     *
     * @param $filename
     * @return void
     */
    public function restoreMysql($filename)
    {

        $path = $this->folder . '/' . $filename;
        $sqlPath = str_replace('.zip', '', $path);

        // unzip first for temp
        $commandUnZip = "cd {$this->folder} && unzip {$filename}";
        $returnVarUnZip = NULL;
        $outputUnZip = NULL;
        exec($commandUnZip, $outputUnZip, $returnVarUnZip);

        // restore database
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $command = "mysql --user={$username} --password={$password} {$database} < {$sqlPath}";
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        // delete sql temp file
        $commandDeleteZip = "rm {$sqlPath}";
        $returnVarDeleteZip = NULL;
        $outputDeleteZip = NULL;
        exec($commandDeleteZip, $outputDeleteZip, $returnVarDeleteZip);

        return $returnVar;
    }

    /**
     * delete mysql database
     *
     * @param $filename
     * @return void
     */
    public function deleteMysql($filename)
    {
        return Storage::delete('public/' . $this->folderName . '/' . $filename);
    }

    /**
     * delete row
     *
     * @param string $db
     * @param string $table
     * @param string $id
     * @return int
     */
    public function deleteRow($db, $table, $id)
    {
        return DB::table($db . '.' . $table)->where('id', $id)->delete();
    }

    /**
     * create database
     *
     * @param string $db
     * @return void
     */
    public function createMySqlDb($db)
    {
        $charset    = config("database.connections.mysql.charset", 'utf8mb4');
        $collation  = config("database.connections.mysql.collation", 'utf8mb4_unicode_ci');
        $query      = "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET $charset COLLATE $collation;";
        DB::statement($query);
    }

    /**
     * drop database
     *
     * @param string $db
     * @return void
     */
    public function dropMySqlDb($db)
    {
        $query      = "DROP DATABASE $db;";
        DB::statement($query);
    }

    public function getAllDbMySql()
    {
        $query = 'SHOW DATABASES';
        $databases = collect(DB::select($query));
        $databases = $databases->transform(function ($item) {
            $item->total_table = DB::select('SELECT COUNT(*) as total_table FROM information_schema.TABLES WHERE table_schema = ?', [$item->Database])[0]->total_table;
            $query = 'SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS "size_mb" FROM information_schema.TABLES WHERE table_schema = ?';
            $item->size_mb = DB::select($query, [$item->Database])[0]->size_mb ?? 0;
            $item->database = $item->Database;
            return $item;
        })->sortBy('database')->values();
        return $databases;
    }

    public function getAllTableMySql($database)
    {
        $query = 'SELECT TABLE_NAME AS `table`, ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) AS `size_mb` FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC;';
        $tables = collect(DB::select($query, [$database]));
        $tables = $tables->transform(function ($item) use ($database) {
            $item->total_row = DB::select('SELECT COUNT(*) as total_row FROM ' . $database . '.' . $item->table)[0]->total_row;
            return $item;
        })->sortByDesc('total_row')->values();
        return $tables;
    }

    public function getPrimaryColumn($database, $table)
    {
        $query = "SELECT k.column_name
                    FROM information_schema.table_constraints t
                    JOIN information_schema.key_column_usage k
                    USING(constraint_name,table_schema,table_name)
                    WHERE t.constraint_type='PRIMARY KEY'
                    AND t.table_schema= ?
                    AND t.table_name=?;";
        $primary = collect(DB::select($query, [$database, $table]));
        $primary = $primary->pluck('column_name')->toArray()[0] ?? 'id';
        return $primary;
    }

    public function getAllRowMySql($database, $table)
    {
        $primary = $this->getPrimaryColumn($database, $table);
        $query = 'SELECT COLUMN_NAME AS `column`, DATA_TYPE AS `type`, CHARACTER_MAXIMUM_LENGTH AS `length`, IS_NULLABLE AS `nullable`, COLUMN_DEFAULT AS `default`, COLUMN_COMMENT AS `comment` FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?;';
        $structure = collect(DB::select($query, [$database, $table]));
        $query = 'SELECT * FROM ' . $database . '.' . $table . ' ORDER BY `' . $primary . '` desc;';
        $rows = collect(DB::select($query));
        return [
            'structure' => $structure,
            'rows'      => $rows,
        ];
    }

    public function getAllRowMySqlAsJson($database, $table)
    {
        $query = 'SELECT * FROM ' . $database . '.' . $table . ';';
        $rows = collect(DB::select($query));
        return ['count' => count($rows), 'rows' => $rows];
    }

    public function getPaginateMySql($database, $table, $perPage = 20)
    {
        return DB::table($database . '.' . $table)->paginate($perPage);
    }
}
