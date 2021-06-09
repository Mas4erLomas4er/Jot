<?php

namespace App\Http\Controllers;

use App\Http\Resources\Contact as ContactResource;
use App\Models\Contact;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactsController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index ()
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = request()->user()->contacts->sortBy('name');

        return ContactResource::collection($contacts);
    }

    /**
     * @throws AuthorizationException
     */
    public function store ()
    {
        $this->authorize('create', Contact::class);

        $contact = request()->user()->contacts()->create($this->validateData());

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function show (Contact $contact)
    {
        $this->authorize('view', $contact);

        return new ContactResource($contact);
    }

    /**
     * @throws AuthorizationException
     */
    public function update (Contact $contact)
    {
        $this->authorize('update', $contact);

        $contact->update($this->validateData());

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }


    /**
     * @throws Exception
     */
    public function destroy (Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    private function validateData () : array
    {
        return request()->validate([
            'name' => ['required', 'max:50', 'min:3'],
            'email' => ['required', 'email', 'max:35'],
            'company' => ['required', 'max:50', 'min:3'],
            'birthday' => ['required', 'date_format:d.m.Y', 'before:now'],
        ]);
    }
}
