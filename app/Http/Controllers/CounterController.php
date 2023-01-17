<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCounterRequest;
use App\Http\Requests\UpdateCounterRequest;
use App\Models\User;
use App\Repositories\CounterRepository;

class CounterController extends Controller
{
    private $counterRepository;
    public function __construct(CounterRepository $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }
    //Endpoints for dashboard
    public function store(StoreCounterRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $client = $this->counterRepository->store($request->input());
        return $client;
    }
    public function update(UpdateCounterRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $client = $this->counterRepository->update($request->input());
        return $client;
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $this->counterRepository->delete($id);
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        return $this->counterRepository->getCounters();
    }
    //Endpoints For Web
    public function getLatestCounters($limit)
    {
        return $this->counterRepository->getLatestCounters($limit);
    }
}
