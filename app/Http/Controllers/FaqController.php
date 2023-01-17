<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\User;
use App\Repositories\FaqRepository;

class FaqController extends Controller
{
    private $faqRepository;
    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }
    //Dashboard endpoints
    public function store(FaqRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $faq = $this->faqRepository->store($request->input());
        return $faq;
    }
    public function update(FaqRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $faq = $this->faqRepository->update($request->input());
        return $faq;
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $this->faqRepository->delete($id);
    }
    //For both dash and web
    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->faqRepository->getPage(request()->page_size, $text);
    }
}
