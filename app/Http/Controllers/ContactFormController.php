<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\User;
use App\Repositories\ContactFormRepository;

class ContactFormController extends Controller
{
    private $contactFormRepository;
    public function __construct(ContactFormRepository $contactFormRepository)
    {
        $this->contactFormRepository = $contactFormRepository;
    }
    public function index()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $text = isset(request()->text) ? request()->text : '';
        return $this->contactFormRepository->getPage(request()->page_size, $text);
    }
    //Web end points
    public function store(ContactFormRequest $request)
    {
        $request->merge(["message" => isset($request->message) ? $request->message : '']);
        return $this->contactFormRepository->store($request->input());
    }
}
