<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\User;
use App\Repositories\AboutRepository;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    private $aboutRepository;
    public function __construct(AboutRepository $aboutRepository)
    {
        $this->aboutRepository = $aboutRepository;
    }
    public function save(AboutRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $about = null;
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        if (isset($request->id)) {
            $updatedResult = $this->aboutRepository->update($request->input());
            if ($request->file("image")) {
                Storage::delete($updatedResult["old_image"]);
            }
            $about = $updatedResult["updated_about"];
        } else {
            $about = $this->aboutRepository->store($request->input());
        }
        return $about;
    }
    //This end point for Dashboard and Web
    public function getFirst()
    {
        return $this->aboutRepository->getFirst();
    }
}
