<?php

namespace App\Repositories;

use App\Models\About;

class AboutRepository
{
    public function store($about)
    {
        return About::create($about);
    }
    public function update($aboutInput)
    {
        $about = About::find($aboutInput["id"]);
        $oldImage = $about->getImageName();
        $about->title_ar = $aboutInput["title_ar"];
        $about->title_en = $aboutInput["title_en"];
        $about->description_ar = $aboutInput["description_ar"];
        $about->description_en = $aboutInput["description_en"];
        $about->features = $aboutInput["features"];
        $about->phone = $aboutInput["phone"];
        $about->image = isset($aboutInput["image"]) ? $aboutInput["image"] : $oldImage;
        $about->save();
        return ["old_image" => $oldImage, "updated_about" => $about];
    }
    public function getFirst()
    {
        return About::first();
    }
}
