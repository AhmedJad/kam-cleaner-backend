<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use App\Models\User;
use App\Repositories\ContactUsRepository;

class ContactUsController extends Controller
{
    private $contactUsRepository;
    public function __construct(ContactUsRepository $contactUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
    }
    public function save(ContactUsRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $contactUs = null;
        if (isset($request->id)) {
            $contactUs = $this->contactUsRepository->update($request->input());
        } else {
            $contactUs = $this->contactUsRepository->store($request->input());
        }
        return $contactUs;
    }
    //Used in both dashboard and web
    public function getFirst()
    {
        return $this->contactUsRepository->getFirst();
    }
}
