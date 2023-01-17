<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactUsRepository
{
    public function store($contact)
    {
        return Contact::create($contact);
    }
    public function update($contactInput)
    {
        $contact = Contact::find($contactInput["id"]);
        $contact->update($contactInput);
        return $contact;
    }
    public function getFirst()
    {
        return Contact::first();
    }
}
