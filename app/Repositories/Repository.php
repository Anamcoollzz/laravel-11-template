<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class Repository extends RepositoryAbstract
{

    protected Model $model;

    /**
     * get all data
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * get all data order by created at desc
     *
     * @return Collection
     */
    public function getLatest()
    {
        return $this->model->latest()->get();
    }

    /**
     * get all data order by created at desc and with
     *
     * @return Collection
     */
    public function getLatestWith(array $with = [])
    {
        return $this->model->with($with)->latest()->get();
    }

    /**
     * get all data order by created at desc
     *
     * @param string $column
     * @param string $method
     * @return Collection
     */
    public function getOrderBy(string $column, string $method = 'asc')
    {
        return $this->model->orderBy($column, $method)->get();
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->query()->create($data);
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function createWithUser(array $data)
    {
        $data['created_by_id'] = Auth::id();
        // $data['last_updated_by_id'] = Auth::id();
        return $this->create($data);
    }

    /**
     * find or store data to db
     *
     * @param array $data
     * @param array $data2
     * @return Model
     */
    public function firstOrCreate(array $data, array $data2 = [])
    {
        return $this->model->firstOrCreate($data, $data2);
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data)
    {
        return $this->create($data);
    }

    /**
     * find data by id
     *
     * @param mixed $id
     * @param array $columns
     * @return Model
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->query()
            ->where('id', $id)
            ->select($columns)
            ->first();
    }

    /**
     * find or fail data by id
     *
     * @param mixed $id
     * @return Model
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * find with data by id
     *
     * @param mixed $id
     * @param array $with
     * @return Model
     */
    public function findWith($id, $with = [])
    {
        return $this->model->where('id', $id)->with($with)->first();
    }

    /**
     * find with or fail data by id
     *
     * @param mixed $id
     * @param array $with
     * @return Model
     */
    public function findWithOrFail($id, $with = [])
    {
        return $this->model->where('id', $id)->with($with)->firstOrFail();
    }

    /**
     * update data by id
     *
     * @param array $data
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function update(array $data, int $id, array $columns = ['*'])
    {
        $model = $this->find($id);
        if ($model) {
            $model->update($data);
            return $this->find($id, $columns);
        }
        return 0;
    }

    /**
     * update data by id
     *
     * @param array $data
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function updateWithUser(array $data, int $id, array $columns = ['*'])
    {
        // $data['created_by_id'] = Auth::id();
        $data['last_updated_by_id'] = Auth::id();
        return $this->update($data, $id, $columns);
    }

    /**
     * update data by key
     *
     * @param array $data
     * @param string $key
     * @return Model
     */
    public function updateByKey(array $data, string $key)
    {
        $model = $this->model->where('key', $key);
        if ($model) {
            $model->update($data);
            return $model;
        }
        return 0;
    }

    /**
     * delete data by id
     *
     * @param int $id
     * @return Model
     */
    public function delete(int $id)
    {
        $model = $this->find($id);
        if ($model) {
            return $model->delete();
        }
        return 0;
    }

    /**
     * delete data by id
     *
     * @param int $id
     * @return Model
     */
    public function destroy(int $id)
    {
        return $this->delete($id);
    }

    /**
     * get data as pagination
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginate()
    {
        $perPage = request('perPage', 20);
        return $this->model->query()
            ->when(request('sort') === 'oldest', function ($query) {
                $query->sortBy('id', 'asc');
            })
            ->when(request('sort') === 'latest' || request('sort') === null, function ($query) {
                $query->latest();
            })
            ->paginate($perPage);
    }

    /**
     * getFilter
     *
     * @return Collection
     */
    public function getFilter()
    {
        return $this->model->get();
    }

    /**
     * getWhereIn
     *
     * @param string $column
     * @param array $data
     * @param array $columns
     * @return Collection
     */
    public function getWhereIn(string $column, array $data, array $columns = ['*'])
    {
        return $this->model->query()
            ->select($columns)
            ->whereIn($column, $data)
            ->get();
    }

    /**
     * get query
     *
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * get data as datatable
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     * @param array $params
     * @return Response
     */
    protected function generateDataTables($query, array $params)
    {
        $dataTables = DataTables::of($query)->addIndexColumn();
        if (isset($params['addColumns']) && is_array($params['addColumns'])) {
            foreach ($params['addColumns'] as $column => $value) {
                $dataTables->addColumn($column, $value);
            }
        }
        if (isset($params['editColumns']) && is_array($params['editColumns'])) {
            foreach ($params['editColumns'] as $column => $value) {
                $dataTables->editColumn($column, $value);
            }
        }
        if (isset($params['rawColumns']) && is_array($params['rawColumns'])) {
            $dataTables->rawColumns($params['rawColumns']);
        }
        return $dataTables->make(true);
    }

    /**
     * get data as datatable
     *
     * @return Response
     */
    public function getYajraDataTables()
    {
        return $this->generateDataTables($this->query(), []);
    }

    /**
     * get data as select options
     *
     * @param string $label
     * @param string $value
     * @return array
     */
    public function getSelectOptions($label = 'name', $value = 'id')
    {
        return $this->query()->select($label, $value)->get()->pluck($label, $value)->toArray();
    }

    /**
     * get yajra columns
     *
     * @return string
     */
    public function getYajraColumns()
    {
        return json_encode([
            [
                'data'       => 'DT_RowIndex',
                'name'       => 'DT_RowIndex',
                'searchable' => false,
                'orderable'  => false
            ],
            [
                'data' => 'action',
                'name' => 'action',
                'orderable' => false,
                'searchable' => false
            ],
        ]);
    }

    /**
     * get query full data
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryFullData()
    {
        return $this->model
            ->when(request('filter_created_by_id'), function (Builder $query) {
                $query->where('created_by_id', request('filter_created_by_id'));
            })
            ->when(request('filter_last_updated_by_id'), function (Builder $query) {
                $query->where('last_updated_by_id', request('filter_last_updated_by_id'));
            })
            ->when(request('filter_start_created_at'), function (Builder $query) {
                $query->whereDate('created_at', '>=', request('filter_start_created_at'));
            })
            ->when(request('filter_end_created_at'), function (Builder $query) {
                $query->whereDate('created_at', '<=', request('filter_end_created_at'));
            })
            ->when(request('filter_start_updated_at'), function (Builder $query) {
                $query->whereDate('updated_at', '>=', request('filter_start_updated_at'));
            })
            ->when(request('filter_end_updated_at'), function (Builder $query) {
                $query->whereDate('updated_at', '<=', request('filter_end_updated_at'));
            })
            ->when(request('filter_limit', 50), function (Builder $query) {
                $query->limit(request('filter_limit', 50));
            })
            ->when(request('filter_sort_by_created_at', 'latest'), function (Builder $query) {
                if (request('filter_sort_by_created_at') === 'oldest') {
                    $query->oldest();
                } else {
                    $query->latest();
                }
            });
    }

    /**
     * get full data with relations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFullData()
    {
        return $this->queryFullData()->with(['createdBy', 'lastUpdatedBy'])->latest()->get();
    }

    /**
     * get full data with relations
     *
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFullDataWith(array $relations = [])
    {
        return $this->queryFullData()->with(array_merge(['createdBy', 'lastUpdatedBy'], $relations))->latest()->get();
    }
}
