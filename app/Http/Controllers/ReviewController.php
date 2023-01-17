<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\User;
use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    private $reviewRepository;
    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }
    public function store(StoreReviewRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $review = $this->reviewRepository->store($request->input());
        return $review;
    }
    public function update(UpdateReviewRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->reviewRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_review"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->reviewRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $text = isset(request()->text) ? request()->text : '';
        return $this->reviewRepository->getPage(request()->page_size, $text);
    }
    //Web endpoints
    public function getAllReviews()
    {
        return $this->reviewRepository->getAllReviews();
    }
}
