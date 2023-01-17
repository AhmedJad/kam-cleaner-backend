<?php

namespace App\Repositories;

use App\Models\ContactForm;

class ContactFormRepository
{
    public function store($employee)
    {
        return ContactForm::create($employee);
    }
    public function getPage($pageSize, $text)
    {
        return ContactForm::whereRaw('LOWER(`name`) LIKE ? or LOWER(`email`) LIKE ? or 
        phone LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%'
        ])->paginate($pageSize);
    }
}
