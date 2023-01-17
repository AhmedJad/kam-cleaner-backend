<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\User;
use App\Repositories\SliderRepository;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    private $sliderRepository;
    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }
    public function store(StoreSliderRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $slider = $this->sliderRepository->store($request->input());
        return $slider;
    }
    public function update(UpdateSliderRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->sliderRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_slider"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->sliderRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $text = isset(request()->text) ? request()->text : '';
        return $this->sliderRepository->getPage(request()->page_size, $text);
    }
    //Web endpoints
    public function getAllSliders()
    {
        return $this->sliderRepository->getAllSliders();
    }
}
