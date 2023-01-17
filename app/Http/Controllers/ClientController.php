<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\User;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    private $clientRepository;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    //Dashboard end points
    public function store(StoreClientRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $client = $this->clientRepository->store($request->input());
        return $client;
    }
    public function update(UpdateClientRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $client = $this->clientRepository->update($request->input());
        return $client;
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $this->clientRepository->delete($id);
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        return $this->clientRepository->getClients();
    }
    //Web end points
    public function getLatestClients($limit)
    {
        return $this->clientRepository->getLatestClients($limit);
    }
}
