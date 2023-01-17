<?php

namespace App\Repositories;

use App\Models\Counter;

class CounterRepository
{
    public function store($counter)
    {
        return Counter::create($counter);
    }
    public function update($counterInput)
    {
        $counter = Counter::find($counterInput["id"]);
        $counter->update($counterInput);
        return $counter;
    }
    public function delete($id)
    {
        $counter = Counter::find($id);
        if ($counter) {
            $counter->delete();
        }
    }
    public function getCounters()
    {
        return Counter::get();
    }
    public function getLatestCounters($limit)
    {
        return Counter::orderBy('id', 'desc')->take($limit)->get();
    }
}
