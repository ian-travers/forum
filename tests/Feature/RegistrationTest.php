<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function register_a_new_users_not_verifies_their_email()
    {
        /** @var User $user */
        $user = create(User::class);

        $this->assertFalse($user->hasVerifiedEmail());
    }
}
