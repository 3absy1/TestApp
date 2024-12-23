<?php

namespace App\Repositery;

use App\Models\Test;


class TestRepository
{
    public function getAll($perPage)
    {
        $query = Test::query();

        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return Test::find($id);
    }

    public function create(array $data)
    {
        return Test::create($data);
    }

    public function update(Test $test, array $data)
    {
        $test->update($data);
        return $test;
    }

    public function delete(Test $test)
    {
        return $test->delete();
    }
}
