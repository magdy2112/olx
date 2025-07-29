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




        DB::table('attributes')->truncate();
        DB::table('categories')->truncate();
        DB::table('sub_categories')->truncate();


        // 1) Categories
        $categories = [
            ['id' => 1, 'name' => 'vehicles'],
            ['id' => 2, 'name' => 'Real Estate'],

            ['id' => 3, 'name' => 'Electronics',],
        ];

        DB::table('categories')->insert($categories);

        // 2) Attributes for each category
$attributes = [
    // Cars
    ['id' => 1, 'name' => 'Price', 'sub_category_id' => 1],
    ['id' => 2, 'name' => 'Year', 'sub_category_id' => 1],
    ['id' => 3, 'name' => 'Mileage', 'sub_category_id' => 1],
    ['id' => 4, 'name' => 'Color', 'sub_category_id' => 1],
    ['id' => 5, 'name' => 'Fuel Type', 'sub_category_id' => 1],
    ['id' => 6, 'name' => 'Transmission', 'sub_category_id' => 1],
    ['id' => 7, 'name' => 'Body Type', 'sub_category_id' => 1],
    ['id' => 8, 'name' => 'Engine Capacity (CC)', 'sub_category_id' => 1],
    ['id' => 9, 'name' => 'Horsepower (HP)', 'sub_category_id' => 1],
    ['id' => 10, 'name' => 'Drive Type', 'sub_category_id' => 1],
    ['id' => 11, 'name' => 'Doors', 'sub_category_id' => 1],
    ['id' => 12, 'name' => 'Seats', 'sub_category_id' => 1],
    ['id' => 13, 'name' => 'Condition', 'sub_category_id' => 1],
    ['id' => 14, 'name' => 'Warranty', 'sub_category_id' => 1],
    ['id' => 15, 'name' => 'Insurance', 'sub_category_id' => 1],

    // Motorcycles
    ['id' => 16, 'name' => 'Price', 'sub_category_id' => 2],
    ['id' => 17, 'name' => 'Year', 'sub_category_id' => 2],
    ['id' => 18, 'name' => 'Engine Capacity (CC)', 'sub_category_id' => 2],
    ['id' => 19, 'name' => 'Mileage', 'sub_category_id' => 2],
    ['id' => 20, 'name' => 'Color', 'sub_category_id' => 2],
    ['id' => 21, 'name' => 'Condition', 'sub_category_id' => 2],
    ['id' => 22, 'name' => 'Fuel Type', 'sub_category_id' => 2],
    ['id' => 23, 'name' => 'Transmission', 'sub_category_id' => 2],
    ['id' => 24, 'name' => 'Type', 'sub_category_id' => 2],

    // Mobiles
    ['id' => 25, 'name' => 'Price', 'sub_category_id' => 3],
    ['id' => 26, 'name' => 'Storage Capacity', 'sub_category_id' => 3],
    ['id' => 27, 'name' => 'RAM', 'sub_category_id' => 3],
    ['id' => 28, 'name' => 'Screen Size', 'sub_category_id' => 3],
    ['id' => 29, 'name' => 'Camera Resolution', 'sub_category_id' => 3],
    ['id' => 30, 'name' => 'Battery Capacity', 'sub_category_id' => 3],
    ['id' => 31, 'name' => 'OS', 'sub_category_id' => 3],
    ['id' => 32, 'name' => 'Condition', 'sub_category_id' => 3],
    ['id' => 33, 'name' => 'Warranty', 'sub_category_id' => 3],

    // Laptops
    ['id' => 34, 'name' => 'Price', 'sub_category_id' => 4],
    ['id' => 35, 'name' => 'CPU', 'sub_category_id' => 4],
    ['id' => 36, 'name' => 'RAM', 'sub_category_id' => 4],
    ['id' => 37, 'name' => 'Storage Capacity', 'sub_category_id' => 4],
    ['id' => 38, 'name' => 'GPU', 'sub_category_id' => 4],
    ['id' => 39, 'name' => 'Screen Size', 'sub_category_id' => 4],
    ['id' => 40, 'name' => 'Battery Condition', 'sub_category_id' => 4],
    ['id' => 41, 'name' => 'OS', 'sub_category_id' => 4],
    ['id' => 42, 'name' => 'Condition', 'sub_category_id' => 4],

    // Computers
    ['id' => 43, 'name' => 'Price', 'sub_category_id' => 5],
    ['id' => 44, 'name' => 'CPU', 'sub_category_id' => 5],
    ['id' => 45, 'name' => 'RAM', 'sub_category_id' => 5],
    ['id' => 46, 'name' => 'Storage Capacity', 'sub_category_id' => 5],
    ['id' => 47, 'name' => 'GPU', 'sub_category_id' => 5],
    ['id' => 48, 'name' => 'Condition', 'sub_category_id' => 5],
    ['id' => 49, 'name' => 'OS', 'sub_category_id' => 5],

    // TVs
    ['id' => 50, 'name' => 'Price', 'sub_category_id' => 6],
    ['id' => 51, 'name' => 'Screen Size', 'sub_category_id' => 6],
    ['id' => 52, 'name' => 'Resolution', 'sub_category_id' => 6],
    ['id' => 53, 'name' => 'Smart TV', 'sub_category_id' => 6],
    ['id' => 54, 'name' => 'Panel Type', 'sub_category_id' => 6],
    ['id' => 55, 'name' => 'Condition', 'sub_category_id' => 6],
    ['id' => 56, 'name' => 'Warranty', 'sub_category_id' => 6],

    // Commercial Properties
    ['id' => 57, 'name' => 'Price', 'sub_category_id' => 7],
    ['id' => 58, 'name' => 'Property Type', 'sub_category_id' => 7],
    ['id' => 59, 'name' => 'Area (sqm)', 'sub_category_id' => 7],
    ['id' => 60, 'name' => 'Location', 'sub_category_id' => 7],
    ['id' => 61, 'name' => 'Floors', 'sub_category_id' => 7],
    ['id' => 62, 'name' => 'Condition', 'sub_category_id' => 7],
    ['id' => 63, 'name' => 'Furnished', 'sub_category_id' => 7],
    ['id' => 64, 'name' => 'Finishing Type', 'sub_category_id' => 7],
    ['id' => 65, 'name' => 'Purpose', 'sub_category_id' => 7],

    // Residential Properties
    ['id' => 66, 'name' => 'Price', 'sub_category_id' => 8],
    ['id' => 67, 'name' => 'Property Type', 'sub_category_id' => 8],
    ['id' => 68, 'name' => 'Area (sqm)', 'sub_category_id' => 8],
    ['id' => 69, 'name' => 'Bedrooms', 'sub_category_id' => 8],
    ['id' => 70, 'name' => 'Bathrooms', 'sub_category_id' => 8],
    ['id' => 71, 'name' => 'Balconies', 'sub_category_id' => 8],
    ['id' => 72, 'name' => 'Furnished', 'sub_category_id' => 8],
    ['id' => 73, 'name' => 'Finishing Type', 'sub_category_id' => 8],
    ['id' => 74, 'name' => 'Floor Number', 'sub_category_id' => 8],
    ['id' => 75, 'name' => 'Building Age', 'sub_category_id' => 8],
    ['id' => 76, 'name' => 'Purpose', 'sub_category_id' => 8],

    // Lands
    ['id' => 77, 'name' => 'Price', 'sub_category_id' => 9],
    ['id' => 78, 'name' => 'Land Area (sqm)', 'sub_category_id' => 9],
    ['id' => 79, 'name' => 'Land Type', 'sub_category_id' => 9],
    ['id' => 80, 'name' => 'Number of Streets', 'sub_category_id' => 9],
    ['id' => 81, 'name' => 'Land Nature', 'sub_category_id' => 9],
    ['id' => 82, 'name' => 'Licensing Status', 'sub_category_id' => 9],
    ['id' => 83, 'name' => 'Purpose', 'sub_category_id' => 9],
];



        DB::table('attributes')->insert($attributes);


        $subcategories = [
            ['id' => 1, 'name' => 'cars', 'category_id' => 1,  'created_at' => '2025-07-29 12:01:13', 'updated_at' => '2025-07-29 15:10:47'],
            ['id' => 2, 'name' => 'motorcycle', 'category_id' => 1,  'created_at' => '2025-07-29 12:02:25', 'updated_at' => '2025-07-29 12:02:25'],
            ['id' => 3, 'name' => 'mobiles', 'category_id' => 3,  'created_at' => '2025-07-29 12:03:06', 'updated_at' => '2025-07-29 12:03:06'],
            ['id' => 4, 'name' => 'laptops', 'category_id' => 3, 'created_at' => '2025-07-29 12:03:25', 'updated_at' => '2025-07-29 12:03:25'],
            ['id' => 5, 'name' => 'computers', 'category_id' => 3, 'created_at' => '2025-07-29 12:07:14', 'updated_at' => '2025-07-29 12:07:14'],
            ['id' => 6, 'name' => 'tv', 'category_id' => 3,  'created_at' => '2025-07-29 14:57:12', 'updated_at' => '2025-07-29 14:57:12'],
            ['id' => 7, 'name' => 'commercial property', 'category_id' => 2,  'created_at' => '2025-07-29 15:00:45', 'updated_at' => '2025-07-29 15:00:45'],
            ['id' => 8, 'name' => 'residential property', 'category_id' => 2, 'created_at' => '2025-07-29 15:01:08', 'updated_at' => '2025-07-29 15:01:08'],
            ['id' => 9, 'name' => 'lands', 'category_id' => 2, 'created_at' => '2025-07-29 15:01:36', 'updated_at' => '2025-07-29 15:01:36'],
        ];

        DB::table('sub_categories')->insert($subcategories);

        $modals = [
            [ 'name' => 'Toyota', 'sub_category_id' => '1'],
            [ 'name' => 'Nissan', 'sub_category_id' => '1'],
            [ 'name' => 'Mitsubishi', 'sub_category_id' => '1'],
            [ 'name' => 'Volkswagen', 'sub_category_id' => '1'],
            [ 'name' => 'Kia', 'sub_category_id' => '1'],
            [ 'name' => 'Fiat', 'sub_category_id' => '1'],
            [ 'name' => 'Mercedes-Benz', 'sub_category_id' => '1'],
            [ 'name' => 'BMW', 'sub_category_id' => '1'],
            [ 'name' => 'Honda ', 'sub_category_id' => '2'],
            [ 'name' => 'Yamaha ', 'sub_category_id' => '2'],
            [ 'name' => 'Kawasaki ', 'sub_category_id' => '2'],
            [ 'name' => 'Suzuki ', 'sub_category_id' => '2'],
            [ 'name' => 'Harley-Davidson ', 'sub_category_id' => '2'],
            [ 'name' => 'Apple ', 'sub_category_id' => '3'],
            [ 'name' => 'Samsung ', 'sub_category_id' => '3'],
            [ 'name' => 'Xiaomi ', 'sub_category_id' => '3'],
            [ 'name' => 'Oppo ', 'sub_category_id' => '3'],
            [ 'name' => 'Apple ', 'sub_category_id' => '4'],
            [ 'name' => 'Dell ', 'sub_category_id' => '4'],
            [ 'name' => 'HP ', 'sub_category_id' => '4'],
            [ 'name' => 'Lenovo ', 'sub_category_id' => '4'],
            [ 'name' => 'Acer ', 'sub_category_id' => '4'],
            [ 'name' => 'Apple', 'sub_category_id' => '5'],
            [ 'name' => 'Dell', 'sub_category_id' => '5'],
            [ 'name' => 'HP', 'sub_category_id' => '5'],
            [ 'name' => 'Lenovo', 'sub_category_id' => '5'],
            [ 'name' => 'Samsung ', 'sub_category_id' => '6'],
            [ 'name' => 'LG ', 'sub_category_id' => '6'],
            [ 'name' => 'Sony ', 'sub_category_id' => '6'],
            [ 'name' => 'TCL ', 'sub_category_id' => '6'],
            [ 'name' => 'Hisense ', 'sub_category_id' => '6'],
            [ 'name' => 'apartment', 'sub_category_id' => '7'],
            [ 'name' => 'factory', 'sub_category_id' => '7'],
            [ 'name' => 'apartment', 'sub_category_id' => '8'],
            [ 'name' => 'villa', 'sub_category_id' => '8'],
            [ 'name' => 'studio', 'sub_category_id' => '8'],
            [ 'name' => 'dublex', 'sub_category_id' => '8'],
            
        ];

        // 1	c
        // 2	motorcy
        // 3	mobi
        // 4	laptops
        // 5	computers
        // 6	tv
        // 7	commercial property
        // 8	residential property
        // 9	lands
    }
}
