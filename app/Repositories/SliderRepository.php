<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository
{
    public function store($slider)
    {
        return Slider::create($slider);
    }
    public function update($sliderInput)
    {
        $slider = Slider::find($sliderInput["id"]);
        $oldImage = $slider->getImageName();
        $slider->title_ar = $sliderInput["title_ar"];
        $slider->title_en = $sliderInput["title_en"];
        $slider->url = $sliderInput["url"];
        $slider->image = isset($sliderInput["image"]) ? $sliderInput["image"] : $oldImage;
        $slider->save();
        return ["old_image" => $oldImage, "updated_slider" => $slider];
    }
    public function delete($id)
    {
        $slider = Slider::find($id);
        $oldImage = null;
        if ($slider) {
            $oldImage = $slider->getImageName();
            $slider->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Slider::whereRaw('LOWER(`title_ar`) LIKE ? or LOWER(`title_en`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%'
        ])->paginate($pageSize);
    }
    public function getAllSliders()
    {
        return Slider::get();
    }
}
