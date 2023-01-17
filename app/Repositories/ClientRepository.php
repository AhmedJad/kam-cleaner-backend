<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function store($client)
    {
        return Client::create($client);
    }
    public function update($clientInput)
    {
        $client = Client::find($clientInput["id"]);
        $client->update($clientInput);
        return $client;
    }
    public function delete($id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->delete();
        }
    }
    public function getClients()
    {
        return Client::get();
    }
    public function getLatestClients($limit)
    {
        return Client::orderBy('id', 'desc')->take($limit)->get();
    }
}
