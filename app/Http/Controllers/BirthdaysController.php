<?php

namespace App\Http\Controllers;

use App\Http\Resources\Contact;
use Illuminate\Http\Request;

class BirthdaysController extends Controller
{
    public function index ()
    {
        $contacts = request()->user()->contacts()
            ->birthdays()
            ->orderByRaw('Extract(DAY from birthday )')
            ->get();

        return Contact::collection($contacts);
    }
}
