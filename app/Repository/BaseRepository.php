<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;


class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function paginated(int $perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function findOneById(string $id)
    {
        return $this->model->find($id);
    }

    public function findOne(array $filter)
    {
        return $this->model->where($filter)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data)
    {
        $record = $this->model->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}