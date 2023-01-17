<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\User;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    //Dashboard end points
    public function store(StoreArticleRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $article = $this->articleRepository->store($request->input());
        return $article;
    }
    public function update(UpdateArticleRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->articleRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_article"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->articleRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    //For both dashboard and web
    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->articleRepository->getPage(request()->page_size, $text);
    }
    //For web Endpoint
    public function show(Article $article)
    {
        return $article;

    }
    public function getLatestArticles($limit)
    {
        return $this->articleRepository->getLatestArticles($limit);
    }
}
