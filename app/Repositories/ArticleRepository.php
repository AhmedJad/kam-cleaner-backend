<?php

namespace App\Repositories;

use App\Models\About;
use App\Models\Article;

class ArticleRepository
{
    public function store($article)
    {
        return Article::create($article);
    }
    public function update($articleInput)
    {
        $article = Article::find($articleInput["id"]);
        $oldImage = $article->getImageName();
        $article->title_ar = $articleInput["title_ar"];
        $article->title_en = $articleInput["title_en"];
        $article->subject_ar = $articleInput["subject_ar"];
        $article->subject_en = $articleInput["subject_en"];
        $article->description_ar = $articleInput["description_ar"];
        $article->description_en = $articleInput["description_en"];
        $article->image = isset($articleInput["image"]) ? $articleInput["image"] : $oldImage;
        $article->save();
        return ["old_image" => $oldImage, "updated_article" => $article];
    }
    public function delete($id)
    {
        $article = Article::find($id);
        $oldImage = null;
        if ($article) {
            $oldImage = $article->getImageName();
            $article->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Article::whereRaw('LOWER(`title_ar`) LIKE ? or LOWER(`title_en`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
        ])->paginate($pageSize);
    }
    public function getLatestArticles($limit)
    {
        return Article::orderBy('id', 'desc')->take($limit)->get();
    }
}
