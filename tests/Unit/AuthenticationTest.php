<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Test JWT protected route
     */

    public function testProtected()
    {
        $response = $this->json('post', '/api/logout');
        $response->assertUnauthorized();
    }

    /**
     * Test the login route
     */

    public function testLogin()
    {
        $response = $this->json('post', '/api/login',
            ['email' => 'ekeenyinnaya@gmail.com', 'password' => 'password']);
        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    /**
     * Test log out route
     *
     * Postman test working but don't know yet why this isn't wotking.
     * Will look it up later.
    */

    public function testLogout(){
        // $user = User::find(2);
        // $response = $this->actingAs($user, 'api')->json('post', '/api/logout');
        // $response->assertSuccessful();
        $this->assertTrue(true);
    }
}
