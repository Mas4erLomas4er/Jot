<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BirthdaysTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contacts_with_birthdays_in_the_current_month_can_be_fetched ()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $birthdayContact = Contact::factory()->create([
            'user_id' => $user->id,
            'birthday' => now()->subYear(),
        ]);

        $noBirthdayContact = Contact::factory()->create([
            'user_id' => $user->id,
            'birthday' => now()->subMonth(),
        ]);

        $response = $this->get('/api/birthdays?api_token=' . $user->api_token);

        $response->assertJsonCount(1)
            ->assertJson([
                'data' => [
                    [
                        'data' => ['contact_id' => $birthdayContact->id],
                    ],
                ],
            ]);
    }
}
