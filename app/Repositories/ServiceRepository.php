<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function store($service)
    {
        return Service::create($service);
    }
    public function update($serviceInput)
    {
        $service = Service::find($serviceInput["id"]);
        $oldImage = $service->getImageName();
        $service->title_ar = $serviceInput["title_ar"];
        $service->title_en = $serviceInput["title_en"];
        $service->description_ar = $serviceInput["description_ar"];
        $service->description_en = $serviceInput["description_en"];
        $service->url = $serviceInput["url"];
        $service->image = isset($serviceInput["image"]) ? $serviceInput["image"] : $oldImage;
        $service->save();
        return ["old_image" => $oldImage, "updated_service" => $service];
    }
    public function delete($id)
    {
        $service = Service::find($id);
        $oldImage = null;
        if ($service) {
            $oldImage = $service->getImageName();
            $service->delete();
        }
        return $oldImage;
    }
    public function getServices()
    {
        return Service::get();
    }
    public function getLatestServices($limit)
    {
        return Service::orderBy('id', 'desc')->take($limit)->get();
    }
}
