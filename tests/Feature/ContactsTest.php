<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp () : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function an_authenticated_user_should_redirected_to_login ()
    {
        $response = $this->post(
            '/api/contacts',
            Arr::except($this->data(), 'api_token')
        );

        $response->assertRedirect('/login');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function an_authenticated_user_can_fetch_only_own_contacts ()
    {
        $anotherUser = User::factory()->create();

        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $anotherContact = Contact::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->get('/api/contacts?api_token=' . $this->user->api_token);

        $response->assertJsonCount(1)
            ->assertJson([
                'data' => [
                    [
                        'data' => ['contact_id' => $contact->id],
                    ],
                ],
            ]);
    }

    /** @test */
    public function an_authenticated_user_can_add_a_contact ()
    {
        $response = $this->post('/api/contacts', $this->data());

        $contact = Contact::first();

//        dd(json_decode($response->getContent()));

        $this->assertEquals($this->data()['name'], $contact->name);
        $this->assertEquals($this->data()['email'], $contact->email);
        $this->assertEquals($this->data()['birthday'], $contact->birthday->format('m/d/Y'));
        $this->assertEquals($this->data()['company'], $contact->company);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'data' => ['contact_id' => $contact->id],
            'links' => ['self' => $contact->path()],
        ]);
    }

    /** @test */
    public function an_authenticated_user_can_retrieve_a_contact ()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(
            '/api/contacts/' . $contact->id . '?api_token=' . $this->user->api_token
        );

        $response->assertJson([
            'data' => [
                'name' => $contact->name,
                'email' => $contact->email,
                'birthday' => $contact->birthday->format('d/m/Y'),
                'company' => $contact->company,
                'last_updated' => $contact->updated_at->diffForHumans(),
            ],
        ]);
    }

    /** @test */
    public function only_the_users_contacts_can_be_retrieved ()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $anotherUser = User::factory()->create();

        $response = $this->get(
            '/api/contacts/' . $contact->id . '?api_token=' . $anotherUser->api_token
        );

        $response->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_patch_a_contact ()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patch(
            '/api/contacts/' . $contact->id,
            $this->data()
        );

        $contact = $contact->fresh();

        $this->assertEquals($this->data()['name'], $contact->name);
        $this->assertEquals($this->data()['email'], $contact->email);
        $this->assertEquals($this->data()['birthday'], $contact->birthday->format('m/d/Y'));

        $this->assertEquals($this->data()['company'], $contact->company);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => ['contact_id' => $contact->id],
            'links' => ['self' => $contact->path()],
        ]);
    }

    /** @test */
    public function only_the_owner_of_contact_can_patch_it ()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $anotherUser = User::factory()->create();

        $response = $this->patch(
            '/api/contacts/' . $contact->id,
            array_merge($this->data(), ['api_token' => $anotherUser->api_token])
        );

        $response->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_delete_a_contact ()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(
            '/api/contacts/' . $contact->id,
            ['api_token' => $this->user->api_token]
        );

        $this->assertCount(0, Contact::all());
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function only_the_owner_of_contact_can_delete_it ()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $anotherUser = User::factory()->create();

        $response = $this->delete(
            '/api/contacts/' . $contact->id,
            ['api_token' => $anotherUser->api_token]
        );

        $response->assertStatus(403);
    }

    /** @test */
    public function fields_are_required ()
    {
        collect(['name', 'email', 'birthday', 'company', 'user_id'])->each(function ($field)
        {
            $response = $this->post(
                '/api/contacts',
                Arr::except($this->data(), $field)
            );

            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Contact::all());
        });
    }

    /** @test */
    public function email_must_be_a_valid_email ()
    {
        $response = $this->post(
            '/api/contacts',
            array_merge($this->data(), ['email' => 'NOT AN EMAIL'])
        );

        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function birthdays_are_properly_stored ()
    {
        $this->post(
            '/api/contacts',
            array_merge($this->data(), ['birthday' => '14.05.1988'])
        );

        $this->assertCount(1, Contact::all());
        $this->assertInstanceOf(Carbon::class, Contact::first()->birthday);
        $this->assertEquals('05-14-1988', Contact::first()->birthday->format('m-d-Y'));
    }

    private function data () : array
    {
        return [
            'name' => 'Test name',
            'email' => 'test@email.com',
            'birthday' => '05/14/1988',
            'company' => 'ABC String',
            'api_token' => $this->user->api_token,
            'user_id' => $this->user->id,
        ];
    }
}
