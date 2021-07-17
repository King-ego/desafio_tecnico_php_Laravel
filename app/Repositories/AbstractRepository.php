<?php

    namespace App\Repositories;

    abstract class AbstractRepository
    {
        protected $model;

        public function __construct()
        {
            $this->model = $this->resolveModel();
        }

        protected function resolveModel()
        {
            return app($this->model);
        }

        public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function whereEquals($column, $value)
    {
        return $this->model->where($column, $value);
    }

    public function first()
    {
        $this->model->first();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update(array $data)
    {
        return $this->model->update($data);
    }

    public function paginate(int $integer)
    {
        return $this->model->paginate($integer);
    }

    public function paginateById(int $id, int $integer)
    {
        return $this->model->where('wallet_id', $id)->paginate($integer);
    }

    public function orderBy($column, $clause = 'DESC')
    {
        return $this->model->orderBy($column, $clause);
    }

    public function delete()
    {
        return $this->model->delete();
    }

    }
