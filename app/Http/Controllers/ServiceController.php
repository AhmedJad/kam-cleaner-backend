<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\User;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    private $serviceRepository;
    
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }
    //Dashboard end points
    public function store(StoreServiceRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $service = $this->serviceRepository->store($request->input());
        return $service;
    }
    public function update(UpdateServiceRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->serviceRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_service"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->serviceRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        return $this->serviceRepository->getServices();
    }
    //Web end points
    public function getLatestServices($limit)
    {
        return $this->serviceRepository->getLatestServices($limit);
    }
}
