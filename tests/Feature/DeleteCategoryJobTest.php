<?php

namespace Tests\Feature;

use App\Jobs\Deletecategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Modal;
use App\Models\Submodal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteCategoryJobTest extends TestCase
{
    use RefreshDatabase;

    
    public function it_deletes_category_and_relations_successfully()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $category = Category::factory()->create();
        $subcategory = SubCategory::factory()->create(['category_id' => $category->id]);
        $modal = Modal::factory()->create(['sub_category_id' => $subcategory->id]);
        $submodal = Submodal::factory()->create(['modal_id' => $modal->id]);

        $job = new Deletecategory($category->id, $user->id);
        $job->handle();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('sub_categories', ['id' => $subcategory->id]);
        $this->assertDatabaseMissing('modals', ['id' => $modal->id]);
        $this->assertDatabaseMissing('submodals', ['id' => $submodal->id]);
    }
}
