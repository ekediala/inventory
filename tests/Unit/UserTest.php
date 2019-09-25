<?php

namespace Tests\Unit;

use App\User;
use Faker\Factory;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test registering new user
     *
     * @return void
     */

    public function testRegister()
    {
        $faker = Factory::create();
        $response = $this->json('post', 'api/user', [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
            'password' => 'password',
        ]);

        $response->assertSuccessful();
    }

    /**
     * Test retrieve individual user information
     *
     * @return void
     */

    public function testRetrieveUser()
    {
        $user = User::find(2);
        $response = $this->actingAs($user, 'api')->json('get', 'api/user');
        $response->assertSuccessful();
    }

    /**
     * Test admin can manage user
    */

    public function testDeleteUser()
    {
        $admin = User::find(1);
        $response = $this->actingAs($admin, 'api')->json('delete', '/api/user/4');
        $response->assertSuccessful();
    }

    /**
     * Test user updates
     */

    public function testUpdateUser()
    {
        $user = User::find(3);
        $faker = Factory::create();
        $response = $this->actingAs($user, 'api')->json('patch', 'api/user/3', [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
        ]);

        $response->assertSuccessful();
    }

    /**
     * Test user cannot manage other users
     */

    public function testCantUpdateUser()
    {
        $user = User::find(2);
        $faker = Factory::create();
        $response = $this->actingAs($user, 'api')->json('patch', 'api/user/3', [
            'name' => $faker->name,
            'email' => $faker->safeEmail,
        ]);

        $response->assertStatus(403);
    }

}
