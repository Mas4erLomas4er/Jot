<?php

namespace App\Http\Controllers;

use App\Http\Resources\Contact as ContactsResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index ()
    {
        $data = request()->validate([
            'searchTerm' => 'required',
        ]);

        $contacts = Contact::search($data['searchTerm'])
            ->where('user_id', request()->user()->id)
            ->take(8)
            ->get();

        return ContactsResource::collection($contacts);
    }

}
