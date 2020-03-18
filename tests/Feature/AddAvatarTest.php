<?php /** @noinspection PhpUndefinedClassInspection */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function only_members_can_add_avatars()
    {
//        $this->withoutExceptionHandling();

        $this->json('post', '/api/users/1/avatar')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function valid_avatar_must_be_provided()
    {
        $this->signIn();

        $this->json('post', '/api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'not-an-image'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    function user_can_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('post', '/api/users/' . auth()->id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $fileHash = $file->hashName();

        $this->assertEquals('avatars/' . $fileHash, auth()->user()->avatar_path);

        Storage::disk('public')
            ->assertExists('avatars/' . $fileHash);
    }
}
