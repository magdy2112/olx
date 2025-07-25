<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Modal;
use App\Models\Submodal;
use App\Models\Attribute;
use App\Models\Chain;

class DatabaseSeeder extends Seeder
{
    public function run()
    {


        User::factory()->count(100)->create();



        //     // 1. Categories
        //     $categoriesData = ['Cars', 'Real Estate', 'Electronics'];

        //     foreach ($categoriesData as $name) {
        //         Category::firstOrCreate(
        //             ['slug' => Str::slug($name)],
        //             ['name' => $name, 'slug' => Str::slug($name)]
        //         );
        //     }

        //     $categories = Category::all()->keyBy('slug');

        //     // 2. SubCategories
        //     $subCategoriesData = [
        //         ['name' => 'Mercedes', 'category_slug' => 'cars'],
        //         ['name' => 'BMW', 'category_slug' => 'cars'],
        //         ['name' => 'Toyota', 'category_slug' => 'cars'],
        //         ['name' => 'Apartments', 'category_slug' => 'real-estate'],
        //         ['name' => 'Laptops', 'category_slug' => 'electronics'],
        //         ['name' => 'Smartphones', 'category_slug' => 'electronics'],
        //         ['name' => 'Watches', 'category_slug' => 'electronics'],
        //         ['name' => 'Villa', 'category_slug' => 'real-estate'],
        //         ['name' => 'Cameras', 'category_slug' => 'electronics'],
        //         ['name' => 'Appliances', 'category_slug' => 'electronics'],
        //     ];

        //     foreach ($subCategoriesData as $data) {
        //         $slug = Str::slug($data['name']);
        //         if (isset($categories[$data['category_slug']])) {
        //             SubCategory::firstOrCreate(
        //                 ['slug' => $slug],
        //                 [
        //                     'name' => $data['name'],
        //                     'slug' => $slug,
        //                     'category_id' => $categories[$data['category_slug']]->id,
        //                 ]
        //             );
        //         }
        //     }

        //     $subCategories = SubCategory::all()->keyBy('slug');

        //     // 3. Modals
        //     $modalsData = [
        //         ['name' => 'Toyota Camry', 'sub_category_slug' => 'toyota'],
        //         ['name' => 'S500', 'sub_category_slug' => 'mercedes'],
        //         ['name' => 'Modern Apartment', 'sub_category_slug' => 'apartments'],
        //         ['name' => 'Luxury Villa', 'sub_category_slug' => 'villa'],
        //         ['name' => 'Dell', 'sub_category_slug' => 'laptops'],
        //         ['name' => 'Apple', 'sub_category_slug' => 'laptops'],
        //         ['name' => 'HP', 'sub_category_slug' => 'laptops'],
        //         ['name' => 'Samsung', 'sub_category_slug' => 'smartphones'],
        //         ['name' => 'iPhone 14', 'sub_category_slug' => 'smartphones'],
        //         ['name' => 'LG', 'sub_category_slug' => 'appliances'],
        //         ['name' => 'Apple Watch', 'sub_category_slug' => 'watches'],
        //         ['name' => 'Canon', 'sub_category_slug' => 'cameras'],
        //     ];

        //     foreach ($modalsData as $data) {
        //         $slug = Str::slug($data['name']);
        //         if (isset($subCategories[$data['sub_category_slug']])) {
        //             Modal::firstOrCreate(
        //                 ['slug' => $slug],
        //                 [
        //                     'name' => $data['name'],
        //                     'slug' => $slug,
        //                     'sub_category_id' => $subCategories[$data['sub_category_slug']]->id,
        //                 ]
        //             );
        //         }
        //     }

        //     $modals = Modal::all()->keyBy('slug');

        //     // 4. Submodals
        //     $submodalsData = [
        //         ['name' => 'S24 Ultra', 'modal_slug' => 'samsung'],
        //         ['name' => 'Lenovo C50', 'modal_slug' => 'dell'],
        //         ['name' => 'Dell XPS 13', 'modal_slug' => 'dell'],
        //         ['name' => 'iPhone 14 Pro', 'modal_slug' => 'iphone-14'],
        //         ['name' => 'Samsung Galaxy Tab', 'modal_slug' => 'samsung'],
        //         ['name' => 'Apple Watch Series 8', 'modal_slug' => 'apple-watch'],
        //         ['name' => 'Canon EOS R5', 'modal_slug' => 'canon'],
        //         ['name' => 'LG Refrigerator', 'modal_slug' => 'lg'],
        //         ['name' => 'Sony Bravia', 'modal_slug' => 'hp'],
        //     ];

        //     foreach ($submodalsData as $data) {
        //         $slug = Str::slug($data['name']);
        //         if (isset($modals[$data['modal_slug']])) {
        //             Submodal::firstOrCreate(
        //                 ['slug' => $slug],
        //                 [
        //                     'name' => $data['name'],
        //                     'slug' => $slug,
        //                     'modal_id' => $modals[$data['modal_slug']]->id,
        //                 ]
        //             );
        //         }
        //     }

        //     // 5. Attributes
        //     $attributesList = [
        //         'Price',
        //         'Color',
        //         'Year',
        //         'Description',
        //         'Model',
        //         'Size',
        //         'Condition',
        //         'Location',
        //         'Brand',
        //         'Warranty',
        //         'Features',
        //         'Fuel Type',
        //         'Transmission',
        //         'Mileage',
        //         'Engine Size',
        //         'Bedrooms',
        //         'Bathrooms',
        //         'Area',
        //         'Screen Size',
        //         'Storage',
        //         'RAM',
        //         'Processor',
        //         'Graphics Card',
        //         'Battery Life',
        //         'Network',
        //         'Camera',
        //         'Audio',
        //         'Accessories',
        //         'Availability',
        //         'Seller Type',
        //         'Payment Method',
        //         'Delivery Options',
        //         'Return Policy',
        //         'Shipping Cost',
        //         'Warranty Period',
        //         'Installation Service',
        //         'User Manual',
        //         'Support',
        //         'Energy Efficiency',
        //         'Safety Features',
        //         'Performance',
        //         'Compatibility',
        //         'Reviews',
        //         'Ratings',
        //         'Discount',
        //         'Special Offers',
        //         'Loyalty Points',
        //         'Gift Options',
        //         'Customization',
        //         'Subscription Plans',
        //         'Trial Period',
        //         'Referral Program',
        //     ];

        //     foreach ($attributesList as $name) {
        //         Attribute::firstOrCreate(
        //             ['slug' => Str::slug($name)],
        //             ['name' => $name, 'slug' => Str::slug($name)]
        //         );
        //     }

        //     $attributes = Attribute::all()->keyBy('slug');

        //     // 6. attribute_category
        //     $attributeCategoryData = [
        //         // Cars
        //         ['category_slug' => 'cars', 'attribute_slug' => 'price'],
        //         ['category_slug' => 'cars', 'attribute_slug' => 'color'],
        //         ['category_slug' => 'cars', 'attribute_slug' => 'year'],
        //         ['category_slug' => 'cars', 'attribute_slug' => 'description'],

        //         // Real Estate
        //         ['category_slug' => 'real-estate', 'attribute_slug' => 'price'],
        //         ['category_slug' => 'real-estate', 'attribute_slug' => 'description'],

        //         // Electronics
        //         ['category_slug' => 'electronics', 'attribute_slug' => 'price'],
        //         ['category_slug' => 'electronics', 'attribute_slug' => 'color'],
        //         ['category_slug' => 'electronics', 'attribute_slug' => 'description'],
        //     ];

        //     foreach ($attributeCategoryData as $data) {
        //         if (isset($categories[$data['category_slug']]) && isset($attributes[$data['attribute_slug']])) {
        //             DB::table('attribute_category')->updateOrInsert(
        //                 [
        //                     'category_id' => $categories[$data['category_slug']]->id,
        //                     'attribute_id' => $attributes[$data['attribute_slug']]->id,
        //                 ],
        //                 [

        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ]
        //             );
        //         }
        //     }

        //     // 7. chains
        //     Chain::insert([
        //         [
        //             'category' => 'Cars',
        //             'subcategory' => 1,
        //             'modal' => 1,
        //             'submodal' => 0,
        //         ],
        //         [
        //             'category' => 'Real Estate',
        //             'subcategory' => 1,
        //             'modal' => 1,
        //             'submodal' => 0,
        //         ],
        //         [
        //             'category' => 'Electronics',
        //             'subcategory' => 1,
        //             'modal' => 1,
        //             'submodal' => 1,
        //         ]
        //     ]);
        // }

        // جدول رئيسي
        // \App\Models\User::factory(100)->create();
        // \App\Models\Category::factory(50)->create();
        // \App\Models\Attribute::factory(200)->create();

        // \App\Models\SubCategory::factory(200)->create();
        // \App\Models\Modal::factory(500)->create();
        // \App\Models\Submodal::factory(800)->create();

        // \App\Models\Advertising::factory(300)->create()->each(function ($ad) {
        //     $attributes = \App\Models\Attribute::inRandomOrder()->take(rand(3, 10))->get();
        //     foreach ($attributes as $att) {
        //         $ad->attributes()->attach($att->id, [
        //             'value' => fake()->word()
        //         ]);
        //     }
        // });
        // }
    }
}