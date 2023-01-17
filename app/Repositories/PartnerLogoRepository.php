<?php

namespace App\Repositories;

use App\Models\PartnerLogo;

class PartnerLogoRepository
{
    public function store($partnerLogo)
    {
        return PartnerLogo::create($partnerLogo);
    }
    public function update($partnerLogoInput)
    {
        $partnerLogo = PartnerLogo::find($partnerLogoInput["id"]);
        $oldImage = $partnerLogo->getImageName();
        $partnerLogo->image = isset($partnerLogoInput["image"]) ? $partnerLogoInput["image"] : $oldImage;
        $partnerLogo->save();
        return ["old_image" => $oldImage, "updated_logo" => $partnerLogo];
    }
    public function delete($id)
    {
        $partnerLogo = PartnerLogo::find($id);
        $oldImage = null;
        if ($partnerLogo) {
            $oldImage = $partnerLogo->getImageName();
            $partnerLogo->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize)
    {
        return PartnerLogo::paginate($pageSize);
    }
    public function getAllLogos()
    {
        return PartnerLogo::get();
    }
}
