<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;


class InventoryTest extends TestCase
{

    /**
     * Test inventory creation
     *
     * @return void
     */
    public function testCreate()
    {
        $user = User::find(2);
        $response = $this->actingAs($user, 'api')->json('post', '/api/inventory', [
            'title' => 'Lorem',
            'category' => 'Ipsum',
            'description' => 'Lorem Ipsum dolar sit amet',
            'price' => 50,
            'units' => 20,
            'status' => 'unavailable',
        ]);
        $response->assertSuccessful();
    }

    /**
     * Test get all user's inventories
     *
     * @return void
     */

    public function testIndex()
    {
        $user = User::find(2);
        $response = $this->actingAs($user, 'api')->json('get', 'api/inventory');
        $response->assertSuccessful();
    }

    /**
     * Test user can update created model
     *
     * @return void
     */

    public function testUpdate()
    {
        $user = User::find(2);
        $response = $this->actingAs($user, 'api')->json('patch', '/api/inventory/1', [
            'title' => 'Lorem',
            'category' => 'Ipsum',
            'description' => 'Lorem Ipsum dolar sit amet',
            'price' => 50,
            'units' => 20,
            'status' => 'available',
        ]);
        $response->assertSuccessful();
    }

    /**
     * Test user can not change inventory not created by them
     *
     * @return void
     */

    public function testRestrictedAccess()
    {
        $user = User::find(4);
        $response = $this->actingAs($user, 'api')->json('patch', '/api/inventory/2', [
            'title' => 'Lorem',
            'category' => 'Ipsum',
            'description' => 'Lorem Ipsum dolar sit amet',
            'price' => 50,
            'units' => 20,
            'status' => 'available',
        ]);
        $response->assertStatus(403);

    }

    /**
     * Test admin can manage any inventory
     *
     * @return void
     */

     public function adminDelete(){
        $admin = User::find(1);
        $response = $this->actingAs($admin, 'api')->json('delete', '/api/inventory/3');
        $response->assertSuccessful();
     }
}
