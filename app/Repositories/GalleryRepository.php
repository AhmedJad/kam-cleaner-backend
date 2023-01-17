<?php

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository
{
    public function store($gallery)
    {
        return Gallery::create($gallery);
    }
    public function update($galleryInput)
    {
        $gallery = Gallery::find($galleryInput["id"]);
        $oldImage = $gallery->getImageName();
        $gallery->image = isset($galleryInput["image"]) ? $galleryInput["image"] : $oldImage;
        $gallery->save();
        return ["old_image" => $oldImage, "updated_gallery" => $gallery];
    }
    public function delete($id)
    {
        $gallery = Gallery::find($id);
        $oldImage = null;
        if ($gallery) {
            $oldImage = $gallery->getImageName();
            $gallery->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize)
    {
        return Gallery::paginate($pageSize);
    }
}
