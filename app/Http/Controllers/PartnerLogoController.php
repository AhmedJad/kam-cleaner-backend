<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerLogoRequest;
use App\Http\Requests\UpdatePartnerLogoRequest;
use App\Models\User;
use App\Repositories\PartnerLogoRepository;
use Illuminate\Support\Facades\Storage;

class PartnerLogoController extends Controller
{
    private $partnerLogoRepository;
    public function __construct(PartnerLogoRepository $partnerLogoRepository)
    {
        $this->partnerLogoRepository = $partnerLogoRepository;
    }
    //Dashboard endpoints
    public function store(StorePartnerLogoRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $slider = $this->partnerLogoRepository->store($request->input());
        return $slider;
    }
    public function update(UpdatePartnerLogoRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->partnerLogoRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_logo"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->partnerLogoRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        return $this->partnerLogoRepository->getPage(request()->page_size);
    }
    //Web endpoint
    public function getAllLogos()
    {
        return $this->partnerLogoRepository->getAllLogos();
    }
}
