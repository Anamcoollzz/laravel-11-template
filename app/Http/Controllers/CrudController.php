<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CrudController extends Controller
{

    /**
     * showing page crud generator
     *
     * @return Response
     */
    public function index()
    {
        if (auth_user()->hasRole('superadmin'))
            return view('stisla.crud-generators.index');
        abort(404);
    }

    public function generateJson(Request $request)
    {
        if (!auth_user()->hasRole('superadmin')) {
            abort(404);
        }
        $columns = [];
        foreach ($request->columns as $column) {
            $storeValidations = $column['form']['validations']['store'];
            if ($custom = $column['form']['validations']['store_custom']) {
                $storeValidations = array_merge($storeValidations, explode('|', $custom));
            }

            $updateValidations = $column['form']['validations']['update'];
            if ($custom = $column['form']['validations']['update_custom']) {
                $updateValidations = array_merge($updateValidations, explode('|', $custom));
            }

            if ($column['type'] === 'bigIncrements')
                $columns[] = [
                    'type' => 'ai'
                ];
            else if (
                // $column['type'] === 'date' ||
                // $column['type'] === 'time' ||
                // $column['type'] === 'datetime' ||
                // $column['type'] === 'datetime'
                $column['type'] !== 'varchar'
            )
                $columns[] = [
                    'name' => $column['name'],
                    'type' => $column['type'],
                    'label' => $column['form']['label'],
                    'form' => [
                        'type' => $column['form']['type'],
                        'required' => 2
                    ],
                    'validations' => [
                        'store' => $storeValidations,
                        'update' => $updateValidations,
                    ],
                    'nullable' => $column['nullable'] ?? false,
                    'unique' => $column['unique'] ?? false,
                    'foreign' => ($column['foreign']['on'] ?? false) ? $column['foreign'] : null
                ];
            else if ($column['type'] === 'varchar')
                $columns[] = [
                    'name' => $column['name'],
                    'type' => $column['type'],
                    'length' => $column['column_length'] ?? 191,
                    'label' => $column['form']['label'],
                    'form' => [
                        'type' => $column['form']['type'],
                        'required' => 2
                    ],
                    'validations' => [
                        'store' => $storeValidations,
                        'update' => $updateValidations,
                    ],
                    'nullable' => $column['nullable'] ?? false,
                    'unique' => $column['unique'] ?? false,
                    'foreign' => ($column['foreign']['on'] ?? false) ? $column['foreign'] : null
                ];
        }
        if ($request->timestamps) {
            $columns[] = [
                'type' => 'timestamps'
            ];
        }
        $jsonData = [
            'title' => $request->title,
            'icon' => $request->icon,
            'model' => $request->modelName,
            'columns' => $columns
        ];
        $json = json_encode($jsonData);
        $filename = Str::slug($request->modelName);
        $fullpath = app_path('Console/Commands/data/crud/files/' . $filename . '.json');
        file_put_contents($fullpath, $json);

        $artisan = Artisan::call('make:crud', [
            'filename' => $filename,
        ]);

        $path = app_path('Console/Commands/data/crud/logs/' . $filename . '.json');
        $response = json_decode(file_get_contents($path));

        return response200($response, 'Crud Berhasil');
    }
}
