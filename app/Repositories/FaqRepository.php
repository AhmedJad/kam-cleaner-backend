<?php

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository
{
    public function store($faq)
    {
        return Faq::create($faq);
    }
    public function update($faqInput)
    {
        $faq = Faq::find($faqInput["id"]);
        $faq->update($faqInput);
        return $faq;
    }
    public function delete($id)
    {
        $faq = Faq::find($id);
        if ($faq) {
            $faq->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return Faq::whereRaw('LOWER(`question_ar`) LIKE ? or LOWER(`question_en`) LIKE ? or 
        LOWER(`answer_ar`) LIKE ? or LOWER(`answer_en`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%'
        ])->paginate($pageSize);
    }
}
