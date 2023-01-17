<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository
{
    public function store($review)
    {
        return Review::create($review);
    }
    public function update($reviewInput)
    {
        $review = Review::find($reviewInput["id"]);
        $oldImage = $review->getImageName();
        $review->name = $reviewInput["name"];
        $review->job_ar = $reviewInput["job_ar"];
        $review->job_en = $reviewInput["job_en"];
        $review->image = isset($reviewInput["image"]) ? $reviewInput["image"] : $oldImage;
        $review->review_ar = $reviewInput["review_ar"];
        $review->review_en = $reviewInput["review_en"];
        $review->save();
        return ["old_image" => $oldImage, "updated_review" => $review];
    }
    public function delete($id)
    {
        $review = Review::find($id);
        $oldImage = null;
        if ($review) {
            $oldImage = $review->getImageName();
            $review->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Review::whereRaw('LOWER(`job_ar`) LIKE ? or LOWER(`job_en`) LIKE ? or 
        LOWER(`name`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%'
        ])->paginate($pageSize);
    }
    public function getAllReviews()
    {
        return Review::get();
    }
}
