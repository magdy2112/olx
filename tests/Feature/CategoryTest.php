<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;
use App\Jobs\Deletecategory;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public User $admin;
    public User $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->normalUser = User::factory()->create([
            'role' => 'user'
        ]);
    }


public function it_returns_all_categories_without_auth()
{
    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/category/');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'status',
                 'status_msg',
                 'message',
                 'data' => [
                     'allcategory',
                     'allcategorywithsubcategory'
                 ]
             ]);

    // نتأكد إن فعلاً 3 categories راجعين
    $this->assertCount(3, $response->json('data.allcategory'));
}



    public function non_admin_cannot_add_category()
    {
        Sanctum::actingAs($this->normalUser);

        $response = $this->postJson('/api/category/addcategory', [
            'name' => 'Test'
        ]);

        $response->assertStatus(403);
    }


    public function admin_can_add_category()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/category/addcategory', [
            'name' => 'Electronics'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Electronics']);

        // IMPORTANT: DB stores lowercase (because of mutator)
        $this->assertDatabaseHas('categories', [
            'name' => 'electronics'
        ]);
    }


    public function admin_cannot_create_category_without_name()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/category/addcategory', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }


    public function admin_can_update_category()
    {
        Sanctum::actingAs($this->admin);

        $category = Category::factory()->create([
            'name' => 'Old'
        ]);

        $response = $this->patchJson('/api/category/updatecategory/'.$category->id, [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(200);

        // DB stores lowercase
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'updated name'
        ]);
    }

   
    public function update_fails_for_non_existing_category()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->patchJson('/api/category/updatecategory/99999', [
            'name' => 'Anything'
        ]);

        $response->assertStatus(404);
    }

 
    public function non_admin_cannot_update_category()
    {
        Sanctum::actingAs($this->normalUser);

        $category = Category::factory()->create();

        $response = $this->patchJson('/api/category/updatecategory/'.$category->id, [
            'name' => 'Blocked'
        ]);

        $response->assertStatus(403);
    }

   
public function test_admin_can_dispatch_delete_job()
{
    $this->withoutMiddleware();

    Sanctum::actingAs($this->admin);

    Mockery::mock('alias:App\Helper\Systemupdate')
        ->shouldReceive('ensureSystemIsFree')
        ->andReturn(null);

    Queue::fake();

    $category = Category::factory()->create();

    $response = $this->deleteJson('/api/category/deletecategory/' . $category->id);

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'message' => 'Delete job dispatched successfully. It will be processed in background.'
             ]);

    Queue::assertPushed(Deletecategory::class);
}




public function delete_category_returns_404_if_not_found()
{
    Sanctum::actingAs($this->admin);

    // FIX: mock Systemupdate static method properly
    Mockery::mock('alias:App\Helper\Systemupdate')
        ->shouldReceive('systemUpdatingResponse')
        ->andReturn(null);

    $response = $this->deleteJson('/api/category/deletecategory/999999');

    $response->assertStatus(404);
}

 
    public function non_admin_cannot_delete_category()
    {
        Sanctum::actingAs($this->normalUser);

        $category = Category::factory()->create();

        $response = $this->deleteJson('/api/category/deletecategory/'.$category->id);

        $response->assertStatus(403);
    }
}

