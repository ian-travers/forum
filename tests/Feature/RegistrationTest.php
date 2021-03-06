<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function register_a_new_users_not_verifies_their_email()
    {
        /** @var User $user */
        $user = factory(User::class)->states('unverified')->create();

        $this->assertFalse($user->hasVerifiedEmail());
    }
}
