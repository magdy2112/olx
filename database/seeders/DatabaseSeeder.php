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




        $governorates = [
            ['name' => 'Cairo',        'lat' => 30.0444, 'lng' => 31.2357],
            ['name' => 'Giza',         'lat' => 30.0131, 'lng' => 31.2089],
            ['name' => 'Alexandria',   'lat' => 31.2001, 'lng' => 29.9187],
            ['name' => 'Dakahlia',     'lat' => 31.0364, 'lng' => 31.3807],
            ['name' => 'Red Sea',      'lat' => 25.2934, 'lng' => 33.7633],
            ['name' => 'Beheira',      'lat' => 30.8480, 'lng' => 30.3436],
            ['name' => 'Fayoum',       'lat' => 29.3084, 'lng' => 30.8428],
            ['name' => 'Gharbia',      'lat' => 30.8754, 'lng' => 31.0335],
            ['name' => 'Ismailia',     'lat' => 30.5965, 'lng' => 32.2715],
            ['name' => 'Monufia',      'lat' => 30.5972, 'lng' => 30.9876],
            ['name' => 'Minya',        'lat' => 28.1099, 'lng' => 30.7503],
            ['name' => 'Qalyubia',     'lat' => 30.4650, 'lng' => 31.1848],
            ['name' => 'New Valley',   'lat' => 25.5460, 'lng' => 28.9618],
            ['name' => 'Suez',         'lat' => 29.9668, 'lng' => 32.5498],
            ['name' => 'Aswan',        'lat' => 24.0889, 'lng' => 32.8998],
            ['name' => 'Assiut',       'lat' => 27.1801, 'lng' => 31.1837],
            ['name' => 'Beni Suef',    'lat' => 29.0661, 'lng' => 31.0994],
            ['name' => 'Port Said',    'lat' => 31.2565, 'lng' => 32.2841],
            ['name' => 'Damietta',     'lat' => 31.4165, 'lng' => 31.8133],
            ['name' => 'Sharqia',      'lat' => 30.7323, 'lng' => 31.7195],
            ['name' => 'South Sinai',  'lat' => 28.2410, 'lng' => 33.6220],
            ['name' => 'Kafr El Sheikh','lat' => 31.1095, 'lng' => 30.9397],
            ['name' => 'Matrouh',      'lat' => 31.3543, 'lng' => 27.2373],
            ['name' => 'Luxor',        'lat' => 25.6872, 'lng' => 32.6396],
            ['name' => 'Qena',         'lat' => 26.1642, 'lng' => 32.7267],
            ['name' => 'North Sinai',  'lat' => 30.6200, 'lng' => 33.8000],
            ['name' => 'Sohag',        'lat' => 26.5590, 'lng' => 31.6957],
        ];

        DB::table('governorates')->insert($governorates);


        // 1) Categories
        $categories = [
            ['id' => 1, 'name' => 'vehicles'],
            ['id' => 2, 'name' => 'Real Estate'],

            ['id' => 3, 'name' => 'Electronics',],
        ];

        DB::table('categories')->insert($categories);

        // 2) Attributes for each category
       $categoryattributes = [
    // Cars (sub_category_id = 1)
    
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

    // Motorcycles (sub_category_id = 2)
   
    ['id' => 17, 'name' => 'Year', 'sub_category_id' => 2],
    ['id' => 18, 'name' => 'Engine Capacity (CC)', 'sub_category_id' => 2],
    ['id' => 19, 'name' => 'Mileage', 'sub_category_id' => 2],
    ['id' => 20, 'name' => 'Color', 'sub_category_id' => 2],
    ['id' => 21, 'name' => 'Condition', 'sub_category_id' => 2],
    ['id' => 22, 'name' => 'Fuel Type', 'sub_category_id' => 2],
    ['id' => 23, 'name' => 'Transmission', 'sub_category_id' => 2],
    ['id' => 24, 'name' => 'Type', 'sub_category_id' => 2],

    // Mobiles (sub_category_id = 3)
  
    ['id' => 26, 'name' => 'Storage Capacity', 'sub_category_id' => 3],
    ['id' => 27, 'name' => 'RAM', 'sub_category_id' => 3],
    ['id' => 28, 'name' => 'Screen Size', 'sub_category_id' => 3],
    ['id' => 29, 'name' => 'Camera Resolution', 'sub_category_id' => 3],
    ['id' => 30, 'name' => 'Battery Capacity', 'sub_category_id' => 3],
    ['id' => 31, 'name' => 'OS', 'sub_category_id' => 3],
    ['id' => 32, 'name' => 'Condition', 'sub_category_id' => 3],
    ['id' => 33, 'name' => 'Warranty', 'sub_category_id' => 3],

    // Laptops (sub_category_id = 4)

    ['id' => 35, 'name' => 'CPU', 'sub_category_id' => 4],
    ['id' => 36, 'name' => 'RAM', 'sub_category_id' => 4],
    ['id' => 37, 'name' => 'Storage Capacity', 'sub_category_id' => 4],
    ['id' => 38, 'name' => 'GPU', 'sub_category_id' => 4],
    ['id' => 39, 'name' => 'Screen Size', 'sub_category_id' => 4],
    ['id' => 40, 'name' => 'Battery Condition', 'sub_category_id' => 4],
    ['id' => 41, 'name' => 'OS', 'sub_category_id' => 4],
    ['id' => 42, 'name' => 'Condition', 'sub_category_id' => 4],
    ['id' => 43, 'name' => 'Model', 'sub_category_id' => 4],

    // Computers (sub_category_id = 5)
  
    ['id' => 44, 'name' => 'CPU', 'sub_category_id' => 5],
    ['id' => 45, 'name' => 'RAM', 'sub_category_id' => 5],
    ['id' => 46, 'name' => 'Storage Capacity', 'sub_category_id' => 5],
    ['id' => 47, 'name' => 'GPU', 'sub_category_id' => 5],
    ['id' => 48, 'name' => 'Condition', 'sub_category_id' => 5],
    ['id' => 49, 'name' => 'OS', 'sub_category_id' => 5],
    ['id' => 50, 'name' => 'Model', 'sub_category_id' => 5],

    // TVs (sub_category_id = 6)

    ['id' => 51, 'name' => 'Screen Size', 'sub_category_id' => 6],
    ['id' => 52, 'name' => 'Resolution', 'sub_category_id' => 6],
    ['id' => 53, 'name' => 'Smart TV', 'sub_category_id' => 6],
    ['id' => 54, 'name' => 'Panel Type', 'sub_category_id' => 6],
    ['id' => 55, 'name' => 'Condition', 'sub_category_id' => 6],
    ['id' => 56, 'name' => 'Warranty', 'sub_category_id' => 6],

    // Commercial Properties (sub_category_id = 7)

    ['id' => 58, 'name' => 'Property Type', 'sub_category_id' => 7],
    ['id' => 59, 'name' => 'Area (sqm)', 'sub_category_id' => 7],
    ['id' => 60, 'name' => 'Location', 'sub_category_id' => 7],
    ['id' => 61, 'name' => 'Floors', 'sub_category_id' => 7],
    ['id' => 62, 'name' => 'Condition', 'sub_category_id' => 7],
    ['id' => 63, 'name' => 'Furnished', 'sub_category_id' => 7],
    ['id' => 64, 'name' => 'Finishing Type', 'sub_category_id' => 7],
  

    // Residential Properties (sub_category_id = 8)

    ['id' => 67, 'name' => 'Property Type', 'sub_category_id' => 8],
    ['id' => 68, 'name' => 'Area (sqm)', 'sub_category_id' => 8],
    ['id' => 69, 'name' => 'Bedrooms', 'sub_category_id' => 8],
    ['id' => 70, 'name' => 'Bathrooms', 'sub_category_id' => 8],
    ['id' => 71, 'name' => 'Balconies', 'sub_category_id' => 8],
    ['id' => 72, 'name' => 'Furnished', 'sub_category_id' => 8],
    ['id' => 73, 'name' => 'Finishing Type', 'sub_category_id' => 8],
    ['id' => 74, 'name' => 'Floor Number', 'sub_category_id' => 8],
    ['id' => 75, 'name' => 'Building Age', 'sub_category_id' => 8],
 

    // Lands (sub_category_id = 9)
  
    ['id' => 78, 'name' => 'Land Area (sqm)', 'sub_category_id' => 9],
    ['id' => 79, 'name' => 'Land Type', 'sub_category_id' => 9],
    ['id' => 80, 'name' => 'Number of Streets', 'sub_category_id' => 9],
    ['id' => 81, 'name' => 'Land Nature', 'sub_category_id' => 9],
    ['id' => 82, 'name' => 'Licensing Status', 'sub_category_id' => 9],
 
];

        DB::table('categoryattributes')->insert($categoryattributes);





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
    ['id' => 1,  'name' => 'Toyota', 'sub_category_id' => '1'],
    ['id' => 2,  'name' => 'Nissan', 'sub_category_id' => '1'],
    ['id' => 3,  'name' => 'Mitsubishi', 'sub_category_id' => '1'],
    ['id' => 4,  'name' => 'Volkswagen', 'sub_category_id' => '1'],
    ['id' => 5,  'name' => 'Kia', 'sub_category_id' => '1'],
    ['id' => 6,  'name' => 'Fiat', 'sub_category_id' => '1'],
    ['id' => 7,  'name' => 'Mercedes-Benz', 'sub_category_id' => '1'],
    ['id' => 8,  'name' => 'BMW', 'sub_category_id' => '1'],

    ['id' => 9,  'name' => 'Honda', 'sub_category_id' => '2'],
    ['id' => 10, 'name' => 'Yamaha', 'sub_category_id' => '2'],
    ['id' => 11, 'name' => 'Kawasaki', 'sub_category_id' => '2'],
    ['id' => 12, 'name' => 'Suzuki', 'sub_category_id' => '2'],
    ['id' => 13, 'name' => 'Harley-Davidson', 'sub_category_id' => '2'],

    ['id' => 14, 'name' => 'Apple', 'sub_category_id' => '3'],
    ['id' => 15, 'name' => 'Samsung', 'sub_category_id' => '3'],
    ['id' => 16, 'name' => 'Xiaomi', 'sub_category_id' => '3'],
    ['id' => 17, 'name' => 'Oppo', 'sub_category_id' => '3'],

    ['id' => 18, 'name' => 'Apple', 'sub_category_id' => '4'],
    ['id' => 19, 'name' => 'Dell', 'sub_category_id' => '4'],
    ['id' => 20, 'name' => 'HP', 'sub_category_id' => '4'],
    ['id' => 21, 'name' => 'Lenovo', 'sub_category_id' => '4'],
    ['id' => 22, 'name' => 'Acer', 'sub_category_id' => '4'],

    ['id' => 23, 'name' => 'Apple', 'sub_category_id' => '5'],
    ['id' => 24, 'name' => 'Dell', 'sub_category_id' => '5'],
    ['id' => 25, 'name' => 'HP', 'sub_category_id' => '5'],
    ['id' => 26, 'name' => 'Lenovo', 'sub_category_id' => '5'],

    ['id' => 27, 'name' => 'Samsung', 'sub_category_id' => '6'],
    ['id' => 28, 'name' => 'LG', 'sub_category_id' => '6'],
    ['id' => 29, 'name' => 'Sony', 'sub_category_id' => '6'],
    ['id' => 30, 'name' => 'TCL', 'sub_category_id' => '6'],
    ['id' => 31, 'name' => 'Hisense', 'sub_category_id' => '6'],

    ['id' => 32, 'name' => 'apartment', 'sub_category_id' => '7'],
    ['id' => 33, 'name' => 'factory', 'sub_category_id' => '7'],

    ['id' => 34, 'name' => 'apartment', 'sub_category_id' => '8'],
    ['id' => 35, 'name' => 'villa', 'sub_category_id' => '8'],
    ['id' => 36, 'name' => 'studio', 'sub_category_id' => '8'],
    ['id' => 37, 'name' => 'dublex', 'sub_category_id' => '8'],
];
        DB::table('modals')->insert($modals);

       $submodals = [
     // Toyota
    [ 'name' => 'toyota_corolla', 'modal_id' => 1],
    [ 'name' => 'toyota_camry', 'modal_id' => 1],
    [ 'name' => 'toyota_prius', 'modal_id' => 1],
    [ 'name' => 'toyota_land_cruiser', 'modal_id' => 1],
    [ 'name' => 'toyota_rav4', 'modal_id' => 1],
    [ 'name' => 'toyota_yaris', 'modal_id' => 1],
    [ 'name' => 'toyota_hilux', 'modal_id' => 1],
    [ 'name' => 'toyota_highlander', 'modal_id' => 1],

            // Nissan
    ['name' => 'nissan_sunny', 'modal_id' => 2],
    [ 'name' => 'nissan_altima', 'modal_id' => 2],
    [ 'name' => 'nissan_maxima', 'modal_id' => 2],
    [ 'name' => 'nissan_patrol', 'modal_id' => 2],
    [ 'name' => 'nissan_navara', 'modal_id' => 2],
    [ 'name' => 'nissan_micra', 'modal_id' => 2],
    [ 'name' => 'nissan_juke', 'modal_id' => 2],
    [ 'name' => 'nissan_qashqai', 'modal_id' => 2],

    // Mitsubishi
    [ 'name' => 'mitsubishi_lancer', 'modal_id' => 3],
    ['name' => 'mitsubishi_pajero', 'modal_id' => 3],
    ['name' => 'mitsubishi_outlander', 'modal_id' => 3],
    ['name' => 'mitsubishi_asx', 'modal_id' => 3],
    ['name' => 'mitsubishi_eclipse', 'modal_id' => 3],
    ['name' => 'mitsubishi_mirage', 'modal_id' => 3],
    ['name' => 'mitsubishi_triton', 'modal_id' => 3],
   // volkes
     ['name' => 'volkswagen_golf', 'modal_id' => 4],
    ['name' => 'volkswagen_jetta', 'modal_id' => 4],
    ['name' => 'volkswagen_passat', 'modal_id' =>4],
    ['name' => 'volkswagen_tiguan', 'modal_id' => 4],
    ['name' => 'volkswagen_touareg', 'modal_id' => 4],
    ['name' => 'volkswagen_polo', 'modal_id' => 4],
    ['name' => 'volkswagen_arteon', 'modal_id' => 4],

    // Fiat
    [ 'name' => 'fiat_tipo', 'modal_id' => 6],
    [ 'name' => 'fiat_500', 'modal_id' => 6],
    [ 'name' => 'fiat_panda', 'modal_id' => 6],
    [ 'name' => 'fiat_punto', 'modal_id' => 6],
    [ 'name' => 'fiat_doblo', 'modal_id' => 6],
    [ 'name' => 'fiat_fiorino', 'modal_id' => 6],
    [ 'name' => 'fiat_linea', 'modal_id' => 6],

    // Kia
    [ 'name' => 'kia_picanto', 'modal_id' => 5],
    [ 'name' => 'kia_sportage', 'modal_id' => 5],
    [ 'name' => 'kia_sorento', 'modal_id' => 5],
    [ 'name' => 'kia_ceed', 'modal_id' => 5],
    [ 'name' => 'kia_optima', 'modal_id' => 5],
    [ 'name' => 'kia_rio', 'modal_id' => 5],
    [ 'name' => 'kia_stinger', 'modal_id' => 5],
    [ 'name' => 'kia_seltos', 'modal_id' => 5],
       

// Kawasaki

    ['name' => 'kawasaki_ninja', 'modal_id' => 11],
    ['name' => 'kawasaki_z1000', 'modal_id' => 11],
    ['name' => 'kawasaki_z900', 'modal_id' => 11],
    ['name' => 'kawasaki_versys', 'modal_id' => 11],
    ['name' => 'kawasaki_vulcan', 'modal_id' => 11],
    ['name' => 'kawasaki_klx', 'modal_id' => 11],
    ['name' => 'kawasaki_kx', 'modal_id' => 11],


   //acerlaptop
    ['name' => 'acer_aspire', 'modal_id' => 22],
    ['name' => 'acer_predator', 'modal_id' => 22],
    ['name' => 'acer_nitro', 'modal_id' => 22],
    ['name' => 'acer_spin', 'modal_id' => 22],
    ['name' => 'acer_travelmate', 'modal_id' => 22],
    ['name' => 'acer_swift', 'modal_id' => 22],
    ['name' => 'acer_chromebook', 'modal_id' => 22],
//applecomputer
       ['name' => 'apple_macbook_air', 'modal_id' => 23],
    ['name' => 'apple_macbook_pro', 'modal_id' => 23],
    ['name' => 'apple_imac', 'modal_id' => 23],
    ['name' => 'apple_mac_mini', 'modal_id' => 23],
    ['name' => 'apple_mac_studio', 'modal_id' => 23],
    ['name' => 'apple_mac_pro', 'modal_id' => 23],
     // hisense tv
        ['name' => 'hisense_led_tv', 'modal_id' => 31],
    ['name' => 'hisense_smart_tv', 'modal_id' => 31],
    ['name' => 'hisense_uhd_tv', 'modal_id' => 31],
    ['name' => 'hisense_qled_tv', 'modal_id' => 31],
    ['name' => 'hisense_4k_tv', 'modal_id' => 31],
    ['name' => 'hisense_8k_tv', 'modal_id' => 31],
    ['name' => 'hisense_android_tv', 'modal_id' => 31],
    ['name' => 'hisense_oled_tv', 'modal_id' => 31],
    // Mercedes
    [ 'name' => 'mercedes_a200', 'modal_id' => 7],
    [ 'name' => 'mercedes_c200', 'modal_id' => 7],
    [ 'name' => 'mercedes_c300', 'modal_id' => 7],
    [ 'name' => 'mercedes_e200', 'modal_id' => 7],
    [ 'name' => 'mercedes_e300', 'modal_id' => 7],
    [ 'name' => 'mercedes_gla200', 'modal_id' => 7],
    [ 'name' => 'mercedes_gle450', 'modal_id' => 7],
    [ 'name' => 'mercedes_s500', 'modal_id' => 7],

    // BMW
    [ 'name' => 'bmw_116i', 'modal_id' => 8],
    [ 'name' => 'bmw_118i', 'modal_id' => 8],
    [ 'name' => 'bmw_320i', 'modal_id' => 8],
    [ 'name' => 'bmw_330i', 'modal_id' => 8],
    [ 'name' => 'bmw_520i', 'modal_id' => 8],
    [ 'name' => 'bmw_530i', 'modal_id' => 8],
    [ 'name' => 'bmw_x1', 'modal_id' => 8],
    [ 'name' => 'bmw_x3', 'modal_id' => 8],
    [ 'name' => 'bmw_x5', 'modal_id' => 8],
    [ 'name' => 'bmw_x6', 'modal_id' => 8],
    [ 'name' => 'bmw_m3', 'modal_id' => 8],
    [ 'name' => 'bmw_m5', 'modal_id' => 8],
    [ 'name' => 'bmw_m8', 'modal_id' => 8],

    ['name' => 'honda_cbr500r', 'modal_id' => 9],
    ['name' => 'honda_cbr600rr', 'modal_id' => 9],
    ['name' => 'honda_cbr1000rr', 'modal_id' => 9],
    ['name' => 'honda_goldwing', 'modal_id' => 9],
    ['name' => 'honda_rebel500', 'modal_id' => 9],
    ['name' => 'honda_cb500x', 'modal_id' => 9],
    ['name' => 'honda_crf250l', 'modal_id' => 9],
    ['name' => 'honda_crf1100l', 'modal_id' => 9],
    ['name' => 'honda_monkey', 'modal_id' => 9],
    ['name' => 'honda_grom', 'modal_id' => 9],

    // Yamaha
    [ 'name' => 'yamaha_r1', 'modal_id' => 10],
    [ 'name' => 'yamaha_r6', 'modal_id' => 10],
    [ 'name' => 'yamaha_r3', 'modal_id' => 10],
    [ 'name' => 'yamaha_mt07', 'modal_id' => 10],
    [ 'name' => 'yamaha_mt09', 'modal_id' => 10],
    [ 'name' => 'yamaha_mt10', 'modal_id' => 10],
    [ 'name' => 'yamaha_xsr700', 'modal_id' => 10],
    [ 'name' => 'yamaha_xsr900', 'modal_id' => 10],
    [ 'name' => 'yamaha_tenere700', 'modal_id' => 10],
    [ 'name' => 'yamaha_tracer900', 'modal_id' => 10],
    [ 'name' => 'yamaha_bolt', 'modal_id' => 10],
    [ 'name' => 'yamaha_vmax', 'modal_id' => 10],

    // Suzuki
    [ 'name' => 'suzuki_gsxr600', 'modal_id' => 12],
    [ 'name' => 'suzuki_gsxr750', 'modal_id' => 12],
    [ 'name' => 'suzuki_gsxr1000', 'modal_id' => 12],
    [ 'name' => 'suzuki_hayabusa', 'modal_id' => 12],
    [ 'name' => 'suzuki_sv650', 'modal_id' => 12],
    [ 'name' => 'suzuki_vstrom650', 'modal_id' => 12],
    [ 'name' => 'suzuki_vstrom1000', 'modal_id' => 12],
    [ 'name' => 'suzuki_boulevard_m109r', 'modal_id' => 12],
    [ 'name' => 'suzuki_burgman400', 'modal_id' => 12],
    [ 'name' => 'suzuki_burgman650', 'modal_id' => 12],

    // Harley-Davidson
    [ 'name' => 'harley_sportster_iron883', 'modal_id' => 13],
    [ 'name' => 'harley_sportster_fortyeight', 'modal_id' => 13],
    [ 'name' => 'harley_street750', 'modal_id' => 13],
    [ 'name' => 'harley_streetrod', 'modal_id' => 13],
    [ 'name' => 'harley_fatboy', 'modal_id' => 13],
    [ 'name' => 'harley_fatbob', 'modal_id' => 13],
    [ 'name' => 'harley_breakout', 'modal_id' => 13],
    [ 'name' => 'harley_heritage_classic', 'modal_id' => 13],
    [ 'name' => 'harley_street_glide', 'modal_id' => 13],
    [ 'name' => 'harley_road_king', 'modal_id' => 13],
    [ 'name' => 'harley_ultra_limited', 'modal_id' => 13],

    // زيادة شوية عشان نوصل حوالي 50
    [ 'name' => 'honda_navi', 'modal_id' => 9],
    [ 'name' => 'yamaha_fjr1300', 'modal_id' => 10],
    [ 'name' => 'suzuki_gixxer250', 'modal_id' => 12],
    [ 'name' => 'harley_pan_america', 'modal_id' => 13],
    [ 'name' => 'honda_cb650r', 'modal_id' => 9],
    [ 'name' => 'yamaha_fz25', 'modal_id' => 10],

     // Apple
    [ 'name' => 'apple_iphone_11', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_11_pro', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_12', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_12_pro', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_13', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_13_pro', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_14', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_14_pro', 'modal_id' => 14],
    [ 'name' => 'apple_iphone_15', 'modal_id' => 14],
    ['name' => 'apple_iphone_15_pro', 'modal_id' => 14],
    ['name' => 'apple_iphone_se_2020', 'modal_id' => 14],
    ['name' => 'apple_iphone_se_2022', 'modal_id' => 14],

    // Samsung
    ['name' => 'samsung_galaxy_s20', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s20_ultra', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s21', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s21_ultra', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s22', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s22_ultra', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s23', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_s23_ultra', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_note20', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_note20_ultra', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_z_fold3', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_z_fold4', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_a52', 'modal_id' => 15],
    ['name' => 'samsung_galaxy_a72', 'modal_id' => 15],
    // Xiaomi
    ['name' => 'xiaomi_redmi_note_9', 'modal_id' => 16],
    ['name' => 'xiaomi_redmi_note_10', 'modal_id' => 16],
    ['name' => 'xiaomi_redmi_note_11', 'modal_id' => 16],
    ['name' => 'xiaomi_redmi_note_12', 'modal_id' => 16],
    ['name' => 'xiaomi_poco_x3', 'modal_id' => 16],
    ['name' => 'xiaomi_poco_x4', 'modal_id' => 16],
    ['name' => 'xiaomi_poco_f3', 'modal_id' => 16],
    ['name' => 'xiaomi_poco_f4', 'modal_id' => 16],
    ['name' => 'xiaomi_mi_11', 'modal_id' => 16],
    ['name' => 'xiaomi_mi_11_ultra', 'modal_id' => 16],
    ['name' => 'xiaomi_12', 'modal_id' => 16],
    ['name' => 'xiaomi_12_pro', 'modal_id' => 16],

    // Oppo
    [ 'name' => 'oppo_find_x2', 'modal_id' => 17],
    [ 'name' => 'oppo_find_x3', 'modal_id' => 17],
    [ 'name' => 'oppo_find_x5', 'modal_id' => 17],
    [ 'name' => 'oppo_find_x5_pro', 'modal_id' => 17],
    [ 'name' => 'oppo_reno5', 'modal_id' => 17],
    [ 'name' => 'oppo_reno6', 'modal_id' => 17],
    [ 'name' => 'oppo_reno7', 'modal_id' => 17],
    [ 'name' => 'oppo_reno8', 'modal_id' => 17],
    [ 'name' => 'oppo_a74', 'modal_id' => 17],
    [ 'name' => 'oppo_a76', 'modal_id' => 17],
    [ 'name' => 'oppo_a95', 'modal_id' => 17],
    [ 'name' => 'oppo_f19', 'modal_id' => 17],

     // Apple
    [  'name' => 'apple_macbook_air_m1', 'modal_id' => 18],
    [  'name' => 'apple_macbook_air_m2', 'modal_id' => 18],
    [  'name' => 'apple_macbook_pro_13_m1', 'modal_id' => 18],
    [  'name' => 'apple_macbook_pro_13_m2', 'modal_id' => 18],
    [  'name' => 'apple_macbook_pro_14_m1pro', 'modal_id' => 18],
    [  'name' => 'apple_macbook_pro_14_m2pro', 'modal_id' => 18],
    [  'name' => 'apple_macbook_pro_16_m1max', 'modal_id' => 18],
    [  'name' => 'apple_macbook_pro_16_m2max', 'modal_id' => 18],
    [  'name' => 'dell_xps_13', 'modal_id' => 19],
    [ 'name' => 'dell_xps_15', 'modal_id' => 19],
    [ 'name' => 'dell_xps_17', 'modal_id' => 19],
    [ 'name' => 'dell_inspiron_14', 'modal_id' => 19],
    [ 'name' => 'dell_inspiron_15', 'modal_id' => 19],
    [ 'name' => 'dell_latitude_5420', 'modal_id' => 19],
    [ 'name' => 'dell_latitude_7430', 'modal_id' => 19],
    [ 'name' => 'dell_g15_gaming', 'modal_id' => 19],
    [ 'name' => 'dell_alienware_m15', 'modal_id' => 19],
 
    ['name' => 'hp_pavilion_14', 'modal_id' => 20],
    ['name' => 'hp_pavilion_15', 'modal_id' => 20],
    ['name' => 'hp_envy_13', 'modal_id' => 20],
    ['name' => 'hp_envy_15', 'modal_id' => 20],
    ['name' => 'hp_spectre_x360_13', 'modal_id' => 20],
    ['name' => 'hp_spectre_x360_14', 'modal_id' => 20],
    ['name' => 'hp_omen_15', 'modal_id' => 20],
    ['name' => 'hp_omen_16', 'modal_id' => 20],
    ['name' => 'hp_victus_15', 'modal_id' => 20],
    ['name' => 'lenovo_thinkpad_x1_carbon', 'modal_id' => 21],
    ['name' => 'lenovo_thinkpad_x1_extreme', 'modal_id' => 21],
    ['name' => 'lenovo_thinkpad_t14', 'modal_id' => 21],
    ['name' => 'lenovo_thinkpad_t16', 'modal_id' => 21],
    ['name' => 'lenovo_yoga_7i', 'modal_id' => 21],
    ['name' => 'lenovo_yoga_9i', 'modal_id' => 21],
    ['name' => 'lenovo_legion_5', 'modal_id' => 21],
    ['name' => 'lenovo_legion_7', 'modal_id' => 21],
    ['name' => 'lenovo_ideapad_3', 'modal_id' => 21],
    ['name' => 'lenovo_ideapad_5', 'modal_id' => 21],
    ['name' => 'lenovo_ideapad_gaming_3', 'modal_id' => 21],
  
   
    ['name' => 'apple_macbook_air_2024', 'modal_id' => 18],
    ['name' => 'dell_precision_5570', 'modal_id' => 19],
    ['name' => 'hp_zbook_firefly_14', 'modal_id' => 20],
    ['name' => 'lenovo_thinkbook_14', 'modal_id' => 21],
    ['name' => 'apple_macbook_pro_m3', 'modal_id' => 18],
    ['name' => 'dell_inspiron_16', 'modal_id' => 19],
    ['name' => 'hp_spectre_x360_16', 'modal_id' => 20],
    ['name' => 'lenovo_legion_slim_5', 'modal_id' => 21],
    ['name' => 'dell_xps_14', 'modal_id' => 19],

   
    ['name' => 'dell_optiplex_3090', 'modal_id' => 24],
    ['name' => 'dell_optiplex_5090', 'modal_id' => 24],
    ['name' => 'dell_optiplex_7090', 'modal_id' => 24],
    ['name' => 'dell_precision_3450', 'modal_id' => 24],
    ['name' => 'dell_precision_3650', 'modal_id' => 24],
    ['name' => 'dell_vostro_3681', 'modal_id' => 24],
    ['name' => 'dell_vostro_3910', 'modal_id' => 24],
    ['name' => 'dell_inspiron_3891', 'modal_id' => 24],
    ['name' => 'dell_inspiron_3910', 'modal_id' => 24],
    ['name' => 'dell_alienware_aurora_r13', 'modal_id' => 24],
    ['name' => 'dell_alienware_aurora_r15', 'modal_id' => 24],
    ['name' => 'dell_xps_desktop_8950', 'modal_id' => 24],
    ['name' => 'dell_xps_desktop_8960', 'modal_id' => 24],
    ['name' => 'dell_optiplex_micro_3090', 'modal_id' => 24],
    ['name' => 'dell_precision_5820', 'modal_id' => 24],
   

    [ 'name' => 'hp_elitedesk_800_g6', 'modal_id' => 25],
    [ 'name' => 'hp_elitedesk_800_g8', 'modal_id' => 25],
    [ 'name' => 'hp_eliteone_800_g6_aio', 'modal_id' => 25],
    [ 'name' => 'hp_eliteone_800_g8_aio', 'modal_id' => 25],
    [ 'name' => 'hp_prodesk_600_g6', 'modal_id' => 25],
    [ 'name' => 'hp_prodesk_600_g7', 'modal_id' => 25],
    [ 'name' => 'hp_proone_600_g6_aio', 'modal_id' => 25],
    [ 'name' => 'hp_proone_600_g7_aio', 'modal_id' => 25],
    [ 'name' => 'hp_pavilion_gaming_desktop', 'modal_id' => 25],
    [ 'name' => 'hp_envy_desktop', 'modal_id' => 25],
    [ 'name' => 'hp_omen_25l', 'modal_id' => 25],
    [ 'name' => 'hp_omen_30l', 'modal_id' => 25],
    [ 'name' => 'hp_slim_desktop_s01', 'modal_id' => 25],
    [ 'name' => 'hp_elitedesk_705_g5', 'modal_id' => 25],
    [ 'name' => 'hp_z2_mini_g5_workstation', 'modal_id' => 25],
 
  
    [ 'name' => 'lenovo_thinkcentre_m70s', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkcentre_m80s', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkcentre_m90s', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkcentre_m70q_tiny', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkcentre_m90q_tiny', 'modal_id' => 26],
    [ 'name' => 'lenovo_ideacentre_3', 'modal_id' => 26],
    [ 'name' => 'lenovo_ideacentre_5', 'modal_id' => 26],
    [ 'name' => 'lenovo_ideacentre_gaming_5', 'modal_id' => 26],
    [ 'name' => 'lenovo_legion_t5', 'modal_id' => 26],
    [ 'name' => 'lenovo_legion_t7', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkstation_p340', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkstation_p350', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkstation_p360', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkstation_p520', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkstation_p720', 'modal_id' => 26],
    [ 'name' => 'lenovo_thinkstation_p920', 'modal_id' => 26],
    [ 'name' => 'lenovo_yoga_aio_7', 'modal_id' => 26],
   
   
    [ 'name' => 'samsung_crystal_43_inch', 'modal_id' => 27],
    [ 'name' => 'samsung_crystal_50_inch', 'modal_id' => 27],
    [ 'name' => 'samsung_crystal_55_inch', 'modal_id' => 27],
    [ 'name' => 'samsung_qled_55_inch', 'modal_id' => 27],
    [ 'name' => 'samsung_qled_65_inch', 'modal_id' => 27],
    [ 'name' => 'samsung_qled_75_inch', 'modal_id' => 27],
    [ 'name' => 'samsung_neo_qled_55', 'modal_id' => 27],
    [ 'name' => 'samsung_neo_qled_65', 'modal_id' => 27],
    [ 'name' => 'samsung_neo_qled_75', 'modal_id' => 27],
    [ 'name' => 'samsung_frame_55', 'modal_id' => 27],
    [ 'name' => 'samsung_frame_65', 'modal_id' => 27],
 
    [ 'name' => 'lg_up7550_43_inch', 'modal_id' => 28],
    [ 'name' => 'lg_up7550_50_inch', 'modal_id' => 28],
    [ 'name' => 'lg_up7550_55_inch', 'modal_id' => 28],
    [ 'name' => 'lg_nano80_55_inch', 'modal_id' => 28],
    [ 'name' => 'lg_nano80_65_inch', 'modal_id' => 28],
    [ 'name' => 'lg_nano90_55_inch', 'modal_id' => 28],
    [ 'name' => 'lg_nano90_65_inch', 'modal_id' => 28],
    [ 'name' => 'lg_c1_oled_55_inch', 'modal_id' => 28],
    [ 'name' => 'lg_c1_oled_65_inch', 'modal_id' => 28],
    [ 'name' => 'lg_c2_oled_55_inch', 'modal_id' => 28],
    [ 'name' => 'lg_c2_oled_65_inch', 'modal_id' => 28],
   
    
    [ 'name' => 'sony_bravia_x80j_43', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_x80j_50', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_x80j_55', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_x85j_55', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_x85j_65', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_x90j_55', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_x90j_65', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_a80j_oled_55', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_a80j_oled_65', 'modal_id' => 29],
    [ 'name' => 'sony_bravia_a90j_oled_65', 'modal_id' => 29],
 

    [ 'name' => 'tcl_p725_43_inch', 'modal_id' => 30],
    [ 'name' => 'tcl_p725_50_inch', 'modal_id' => 30],
    [ 'name' => 'tcl_p725_55_inch', 'modal_id' => 30],
    [ 'name' => 'tcl_c725_qled_55', 'modal_id' => 30],
    [ 'name' => 'tcl_c725_qled_65', 'modal_id' => 30],
    [ 'name' => 'tcl_c825_mini_led_55', 'modal_id' => 30],
    [ 'name' => 'tcl_c825_mini_led_65', 'modal_id' => 30],
    [ 'name' => 'tcl_c935_mini_led_65', 'modal_id' => 30],
    [ 'name' => 'tcl_c935_mini_led_75', 'modal_id' => 30],
    [ 'name' => 'tcl_c945_mini_led_75', 'modal_id' => 30],
    [ 'name' => 'tcl_x925_pro_8k_65', 'modal_id' => 30],
    [ 'name' => 'tcl_x925_pro_8k_75', 'modal_id' => 30],
    [ 'name' => 'tcl_s546_50_inch', 'modal_id' => 30],
    [ 'name' => 'tcl_s546_55_inch', 'modal_id' => 30],
    [ 'name' => 'tcl_s546_65_inch', 'modal_id' => 30],
];
        DB::table('submodals')->insert($submodals);


     $advertisings = [
            // سيارات (10 إعلانات)
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 1, 'submodal_id' => 1, 'created_at' => now(), 'updated_at' => now()], // تويوتا كورولا
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 1, 'submodal_id' => 2, 'created_at' => now(), 'updated_at' => now()], // تويوتا كامري
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 2, 'submodal_id' => 9, 'created_at' => now(), 'updated_at' => now()], // نيسان صني
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 2, 'submodal_id' => 10, 'created_at' => now(), 'updated_at' => now()], // نيسان التيما
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 3, 'submodal_id' => 17, 'created_at' => now(), 'updated_at' => now()], // ميتسوبيشي لانسر
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 4, 'submodal_id' => 24, 'created_at' => now(), 'updated_at' => now()], // فولكس فاجن جولف
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 5, 'submodal_id' => 31, 'created_at' => now(), 'updated_at' => now()], // كيا بيكانتو
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 7, 'submodal_id' => 57, 'created_at' => now(), 'updated_at' => now()], // مرسيدس E200
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 8, 'submodal_id' => 65, 'created_at' => now(), 'updated_at' => now()], // بي إم دبليو 320i
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 1, 'modal_id' => 8, 'submodal_id' => 70, 'created_at' => now(), 'updated_at' => now()], // بي إم دبليو X5

            // دراجات نارية (5 إعلانات)
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 2, 'modal_id' => 9, 'submodal_id' => 58, 'created_at' => now(), 'updated_at' => now()], // هوندا CBR500R
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 2, 'modal_id' => 10, 'submodal_id' => 68, 'created_at' => now(), 'updated_at' => now()], // ياماها R1
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 2, 'modal_id' => 11, 'submodal_id' => 79, 'created_at' => now(), 'updated_at' => now()], // كاواساكي نينجا
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 2, 'modal_id' => 12, 'submodal_id' => 89, 'created_at' => now(), 'updated_at' => now()], // سوزوكي جي إس إكس آر
            ['user_id' => 1, 'category_id' => 1, 'sub_category_id' => 2, 'modal_id' => 13, 'submodal_id' => 99, 'created_at' => now(), 'updated_at' => now()], // هارلي ديفيدسون سبورتستر

            // موبايلات (8 إعلانات)
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 14, 'submodal_id' => 106, 'created_at' => now(), 'updated_at' => now()], // آيفون 11
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 14, 'submodal_id' => 110, 'created_at' => now(), 'updated_at' => now()], // آيفون 13
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 15, 'submodal_id' => 118, 'created_at' => now(), 'updated_at' => now()], // سامسونج جالاكسي S20
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 15, 'submodal_id' => 124, 'created_at' => now(), 'updated_at' => now()], // سامسونج جالاكسي S22
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 16, 'submodal_id' => 136, 'created_at' => now(), 'updated_at' => now()], // شاومي ريدمي نوت 10
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 16, 'submodal_id' => 142, 'created_at' => now(), 'updated_at' => now()], // شاومي مي 11
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 17, 'submodal_id' => 148, 'created_at' => now(), 'updated_at' => now()], // أوبو فايند X3
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 3, 'modal_id' => 17, 'submodal_id' => 154, 'created_at' => now(), 'updated_at' => now()], // أوبو رينو 6

            // لابتوبات (7 إعلانات)
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 18, 'submodal_id' => 160, 'created_at' => now(), 'updated_at' => now()], // ماك بوك اير M1
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 18, 'submodal_id' => 164, 'created_at' => now(), 'updated_at' => now()], // ماك بوك برو 13
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 19, 'submodal_id' => 171, 'created_at' => now(), 'updated_at' => now()], // ديل XPS 13
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 20, 'submodal_id' => 188, 'created_at' => now(), 'updated_at' => now()], // HP بافيليون 15
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 21, 'submodal_id' => 198, 'created_at' => now(), 'updated_at' => now()], // لينوفو ثينك باد X1
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 22, 'submodal_id' => 205, 'created_at' => now(), 'updated_at' => now()], // ايسر أسباير
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 4, 'modal_id' => 22, 'submodal_id' => 207, 'created_at' => now(), 'updated_at' => now()], // ايسر نايترو

            // أجهزة كمبيوتر (5 إعلانات)
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 5, 'modal_id' => 23, 'submodal_id' => 213, 'created_at' => now(), 'updated_at' => now()], // آي ماك
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 5, 'modal_id' => 24, 'submodal_id' => 220, 'created_at' => now(), 'updated_at' => now()], // ديل اوبتيبلكس
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 5, 'modal_id' => 25, 'submodal_id' => 234, 'created_at' => now(), 'updated_at' => now()], // HP EliteDesk
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 5, 'modal_id' => 26, 'submodal_id' => 248, 'created_at' => now(), 'updated_at' => now()], // لينوفو ثينك سنتر
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 5, 'modal_id' => 26, 'submodal_id' => 253, 'created_at' => now(), 'updated_at' => now()], // لينوفو ليجيون

            // تلفزيونات (5 إعلانات)
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 6, 'modal_id' => 27, 'submodal_id' => 260, 'created_at' => now(), 'updated_at' => now()], // سامسونج كريستال
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 6, 'modal_id' => 28, 'submodal_id' => 271, 'created_at' => now(), 'updated_at' => now()], // LG UP7550
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 6, 'modal_id' => 29, 'submodal_id' => 281, 'created_at' => now(), 'updated_at' => now()], // سوني برافيا
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 6, 'modal_id' => 30, 'submodal_id' => 291, 'created_at' => now(), 'updated_at' => now()], // TCL P725
            ['user_id' => 1, 'category_id' => 3, 'sub_category_id' => 6, 'modal_id' => 31, 'submodal_id' => 306, 'created_at' => now(), 'updated_at' => now()], // هايسنس LED

            // عقارات تجارية (5 إعلانات)
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 7, 'modal_id' => 32, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // شقة تجارية
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 7, 'modal_id' => 33, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // مصنع
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 7, 'modal_id' => 32, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // شقة تجارية
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 7, 'modal_id' => 33, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // مصنع
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 7, 'modal_id' => 32, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // شقة تجارية

            // عقارات سكنية (5 إعلانات)
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 8, 'modal_id' => 34, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // شقة سكنية
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 8, 'modal_id' => 35, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // فيلا
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 8, 'modal_id' => 36, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // استوديو
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 8, 'modal_id' => 37, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // دوبلكس
            ['user_id' => 1, 'category_id' => 2, 'sub_category_id' => 8, 'modal_id' => 34, 'submodal_id' => null, 'created_at' => now(), 'updated_at' => now()], // شقة سكنية
        ];

        DB::table('advertisings')->insert($advertisings);


      
        // $attributesData = [
        //     // الإعلان 1 (سيارة تويوتا كورولا)
        
        //     ['advertising_id' => 1, 'attribute_id' => 2, 'value' => '2018'],
        //     ['advertising_id' => 1, 'attribute_id' => 3, 'value' => '45000 كم'],
        //     ['advertising_id' => 1, 'attribute_id' => 4, 'value' => 'أبيض'],
        //     ['advertising_id' => 1, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 1, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 1, 'attribute_id' => 7, 'value' => 'سيدان'],
        //     ['advertising_id' => 1, 'attribute_id' => 8, 'value' => '1600 cc'],
        //     ['advertising_id' => 1, 'attribute_id' => 9, 'value' => '120 حصان'],
        //     ['advertising_id' => 1, 'attribute_id' => 10, 'value' => 'دفع أمامي'],
        //     ['advertising_id' => 1, 'attribute_id' => 11, 'value' => '4'],
        //     ['advertising_id' => 1, 'attribute_id' => 12, 'value' => '5'],
        //     ['advertising_id' => 1, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 2 (سيارة نيسان صني)
           
        //     ['advertising_id' => 2, 'attribute_id' => 2, 'value' => '2016'],
        //     ['advertising_id' => 2, 'attribute_id' => 3, 'value' => '80000 كم'],
        //     ['advertising_id' => 2, 'attribute_id' => 4, 'value' => 'فضي'],
        //     ['advertising_id' => 2, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 2, 'attribute_id' => 6, 'value' => 'مانيوال'],
        //     ['advertising_id' => 2, 'attribute_id' => 7, 'value' => 'سيدان'],
        //     ['advertising_id' => 2, 'attribute_id' => 8, 'value' => '1500 cc'],
        //     ['advertising_id' => 2, 'attribute_id' => 9, 'value' => '105 حصان'],
        //     ['advertising_id' => 2, 'attribute_id' => 10, 'value' => 'دفع أمامي'],
        //     ['advertising_id' => 2, 'attribute_id' => 11, 'value' => '4'],
        //     ['advertising_id' => 2, 'attribute_id' => 12, 'value' => '5'],
        //     ['advertising_id' => 2, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 3 (سيارة مرسيدس E200)
           
        
        //     ['advertising_id' => 3, 'attribute_id' => 3, 'value' => '25000 كم'],
        //     ['advertising_id' => 3, 'attribute_id' => 4, 'value' => 'أسود'],
        //     ['advertising_id' => 3, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 3, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 3, 'attribute_id' => 7, 'value' => 'سيدان'],
        //     ['advertising_id' => 3, 'attribute_id' => 8, 'value' => '2000 cc'],
        //     ['advertising_id' => 3, 'attribute_id' => 9, 'value' => '184 حصان'],
        //     ['advertising_id' => 3, 'attribute_id' => 10, 'value' => 'دفع خلفي'],
        //     ['advertising_id' => 3, 'attribute_id' => 11, 'value' => '4'],
        //     ['advertising_id' => 3, 'attribute_id' => 12, 'value' => '5'],
        //     ['advertising_id' => 3, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 4 (دراجة هوندا CBR500R)
         
        //     ['advertising_id' => 4, 'attribute_id' => 17, 'value' => '2019'],
        //     ['advertising_id' => 4, 'attribute_id' => 18, 'value' => '500 cc'],
        //     ['advertising_id' => 4, 'attribute_id' => 19, 'value' => '12000 كم'],
        //     ['advertising_id' => 4, 'attribute_id' => 20, 'value' => 'أحمر'],
        //     ['advertising_id' => 4, 'attribute_id' => 21, 'value' => 'مستعمل'],
        //     ['advertising_id' => 4, 'attribute_id' => 22, 'value' => 'بنزين'],
        //     ['advertising_id' => 4, 'attribute_id' => 23, 'value' => 'مانيوال'],
        //     ['advertising_id' => 4, 'attribute_id' => 24, 'value' => 'سبورت'],

        //     // الإعلان 5 (دراجة ياماها R1)
          
        //     ['advertising_id' => 5, 'attribute_id' => 17, 'value' => '2021'],
        //     ['advertising_id' => 5, 'attribute_id' => 18, 'value' => '1000 cc'],
        //     ['advertising_id' => 5, 'attribute_id' => 19, 'value' => '5000 كم'],
        //     ['advertising_id' => 5, 'attribute_id' => 20, 'value' => 'أزرق'],
        //     ['advertising_id' => 5, 'attribute_id' => 21, 'value' => 'جديد'],
        //     ['advertising_id' => 5, 'attribute_id' => 22, 'value' => 'بنزين'],
        //     ['advertising_id' => 5, 'attribute_id' => 23, 'value' => 'مانيوال'],
        //     ['advertising_id' => 5, 'attribute_id' => 24, 'value' => 'سبورت'],

        //     // الإعلان 6 (موبايل آيفون 11)
         
        //     ['advertising_id' => 6, 'attribute_id' => 26, 'value' => '64 جيجا'],
        //     ['advertising_id' => 6, 'attribute_id' => 27, 'value' => '4 جيجا'],
        //     ['advertising_id' => 6, 'attribute_id' => 28, 'value' => '6.1 إنش'],
        //     ['advertising_id' => 6, 'attribute_id' => 29, 'value' => '12 ميجا بكسل'],
        //     ['advertising_id' => 6, 'attribute_id' => 30, 'value' => '3110 مللي أمبير'],
        //     ['advertising_id' => 6, 'attribute_id' => 31, 'value' => 'iOS'],
        //     ['advertising_id' => 6, 'attribute_id' => 32, 'value' => 'مستعمل'],
        //     ['advertising_id' => 6, 'attribute_id' => 33, 'value' => 'نعم'],

        //     // الإعلان 7 (موبايل سامسونج S20)
        
        //     ['advertising_id' => 7, 'attribute_id' => 26, 'value' => '128 جيجا'],
        //     ['advertising_id' => 7, 'attribute_id' => 27, 'value' => '8 جيجا'],
        //     ['advertising_id' => 7, 'attribute_id' => 28, 'value' => '6.2 إنش'],
        //     ['advertising_id' => 7, 'attribute_id' => 29, 'value' => '64 ميجا بكسل'],
        //     ['advertising_id' => 7, 'attribute_id' => 30, 'value' => '4000 مللي أمبير'],
        //     ['advertising_id' => 7, 'attribute_id' => 31, 'value' => 'Android'],
        //     ['advertising_id' => 7, 'attribute_id' => 32, 'value' => 'جديد'],
        //     ['advertising_id' => 7, 'attribute_id' => 33, 'value' => 'نعم'],

        //     // الإعلان 8 (لابتوب ماك بوك اير)
         
        //     ['advertising_id' => 8, 'attribute_id' => 35, 'value' => 'M1'],
        //     ['advertising_id' => 8, 'attribute_id' => 36, 'value' => '8 جيجا'],
        //     ['advertising_id' => 8, 'attribute_id' => 37, 'value' => '256 جيجا'],
        //     ['advertising_id' => 8, 'attribute_id' => 38, 'value' => 'M1 GPU'],
        //     ['advertising_id' => 8, 'attribute_id' => 39, 'value' => '13.3 إنش'],
        //     ['advertising_id' => 8, 'attribute_id' => 40, 'value' => 'جيدة'],
        //     ['advertising_id' => 8, 'attribute_id' => 41, 'value' => 'macOS'],
        //     ['advertising_id' => 8, 'attribute_id' => 42, 'value' => 'جديد'],

        //     // الإعلان 9 (لابتوب ديل XPS)
      
        //     ['advertising_id' => 9, 'attribute_id' => 35, 'value' => 'i7'],
        //     ['advertising_id' => 9, 'attribute_id' => 36, 'value' => '16 جيجا'],
        //     ['advertising_id' => 9, 'attribute_id' => 37, 'value' => '512 جيجا'],
        //     ['advertising_id' => 9, 'attribute_id' => 38, 'value' => 'NVIDIA'],
        //     ['advertising_id' => 9, 'attribute_id' => 39, 'value' => '15.6 إنش'],
        //     ['advertising_id' => 9, 'attribute_id' => 40, 'value' => 'جيدة'],
        //     ['advertising_id' => 9, 'attribute_id' => 41, 'value' => 'Windows'],
        //     ['advertising_id' => 9, 'attribute_id' => 42, 'value' => 'مستعمل'],

        //     // الإعلان 10 (تلفزيون سامسونج)
    
        //     ['advertising_id' => 10, 'attribute_id' => 51, 'value' => '55 إنش'],
        //     ['advertising_id' => 10, 'attribute_id' => 52, 'value' => '4K'],
        //     ['advertising_id' => 10, 'attribute_id' => 53, 'value' => 'نعم'],
        //     ['advertising_id' => 10, 'attribute_id' => 54, 'value' => 'LED'],
        //     ['advertising_id' => 10, 'attribute_id' => 55, 'value' => 'جديد'],
        //     ['advertising_id' => 10, 'attribute_id' => 56, 'value' => 'نعم'],

           
        //     ['advertising_id' => 11, 'attribute_id' => 2, 'value' => '2021'],
        //     ['advertising_id' => 11, 'attribute_id' => 3, 'value' => '35000 كم'],
        //     ['advertising_id' => 11, 'attribute_id' => 4, 'value' => 'أبيض'],
        //     ['advertising_id' => 11, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 11, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 11, 'attribute_id' => 7, 'value' => 'SUV'],
        //     ['advertising_id' => 11, 'attribute_id' => 8, 'value' => '2000 cc'],
        //     ['advertising_id' => 11, 'attribute_id' => 9, 'value' => '150 حصان'],
        //     ['advertising_id' => 11, 'attribute_id' => 10, 'value' => 'دفع أمامي'],
        //     ['advertising_id' => 11, 'attribute_id' => 11, 'value' => '5'],
        //     ['advertising_id' => 11, 'attribute_id' => 12, 'value' => '5'],
        //     ['advertising_id' => 11, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 12 (دراجة كاواساكي نينجا)
    
        //     ['advertising_id' => 12, 'attribute_id' => 17, 'value' => '2020'],
        //     ['advertising_id' => 12, 'attribute_id' => 18, 'value' => '650 cc'],
        //     ['advertising_id' => 12, 'attribute_id' => 19, 'value' => '8000 كم'],
        //     ['advertising_id' => 12, 'attribute_id' => 20, 'value' => 'أخضر'],
        //     ['advertising_id' => 12, 'attribute_id' => 21, 'value' => 'مستعمل'],
        //     ['advertising_id' => 12, 'attribute_id' => 22, 'value' => 'بنزين'],
        //     ['advertising_id' => 12, 'attribute_id' => 23, 'value' => 'مانيوال'],
        //     ['advertising_id' => 12, 'attribute_id' => 24, 'value' => 'سبورت'],

        //     // الإعلان 13 (موبايل شاومي ريدمي نوت 10)
    
        //     ['advertising_id' => 13, 'attribute_id' => 26, 'value' => '128 جيجا'],
        //     ['advertising_id' => 13, 'attribute_id' => 27, 'value' => '6 جيجا'],
        //     ['advertising_id' => 13, 'attribute_id' => 28, 'value' => '6.5 إنش'],
        //     ['advertising_id' => 13, 'attribute_id' => 29, 'value' => '48 ميجا بكسل'],
        //     ['advertising_id' => 13, 'attribute_id' => 30, 'value' => '5000 مللي أمبير'],
        //     ['advertising_id' => 13, 'attribute_id' => 31, 'value' => 'Android'],
        //     ['advertising_id' => 13, 'attribute_id' => 32, 'value' => 'مستعمل'],
        //     ['advertising_id' => 13, 'attribute_id' => 33, 'value' => 'نعم'],

        //     // الإعلان 14 (لابتوب لينوفو ثينك باد)
        
        //     ['advertising_id' => 14, 'attribute_id' => 35, 'value' => 'i5'],
        //     ['advertising_id' => 14, 'attribute_id' => 36, 'value' => '8 جيجا'],
        //     ['advertising_id' => 14, 'attribute_id' => 37, 'value' => '256 جيجا'],
        //     ['advertising_id' => 14, 'attribute_id' => 38, 'value' => 'Intel UHD'],
        //     ['advertising_id' => 14, 'attribute_id' => 39, 'value' => '14 إنش'],
        //     ['advertising_id' => 14, 'attribute_id' => 40, 'value' => 'جيدة'],
        //     ['advertising_id' => 14, 'attribute_id' => 41, 'value' => 'Windows'],
        //     ['advertising_id' => 14, 'attribute_id' => 42, 'value' => 'مستعمل'],

        //     // الإعلان 15 (تلفزيون LG)
        
        //     ['advertising_id' => 15, 'attribute_id' => 51, 'value' => '65 إنش'],
        //     ['advertising_id' => 15, 'attribute_id' => 52, 'value' => '4K'],
        //     ['advertising_id' => 15, 'attribute_id' => 53, 'value' => 'نعم'],
        //     ['advertising_id' => 15, 'attribute_id' => 54, 'value' => 'LED'],
        //     ['advertising_id' => 15, 'attribute_id' => 55, 'value' => 'جديد'],
        //     ['advertising_id' => 15, 'attribute_id' => 56, 'value' => 'نعم'],

        //     // الإعلان 16 (عقار تجاري - مكتب)
       
        //     ['advertising_id' => 16, 'attribute_id' => 58, 'value' => 'مكتب'],
        //     ['advertising_id' => 16, 'attribute_id' => 59, 'value' => '80 م²'],
        //     ['advertising_id' => 16, 'attribute_id' => 60, 'value' => 'وسط البلد'],
        //     ['advertising_id' => 16, 'attribute_id' => 61, 'value' => '3'],
        //     ['advertising_id' => 16, 'attribute_id' => 62, 'value' => 'جديد'],
        //     ['advertising_id' => 16, 'attribute_id' => 63, 'value' => 'مفروش'],
        //     ['advertising_id' => 16, 'attribute_id' => 64, 'value' => 'لوكس'],
           

        //     // الإعلان 17 (عقار سكني - فيلا)
          
        //     ['advertising_id' => 17, 'attribute_id' => 67, 'value' => 'فيلا'],
        //     ['advertising_id' => 17, 'attribute_id' => 68, 'value' => '300 م²'],
        //     ['advertising_id' => 17, 'attribute_id' => 69, 'value' => '4'],
        //     ['advertising_id' => 17, 'attribute_id' => 70, 'value' => '3'],
        //     ['advertising_id' => 17, 'attribute_id' => 71, 'value' => '2'],
        //     ['advertising_id' => 17, 'attribute_id' => 72, 'value' => 'مفروش'],
        //     ['advertising_id' => 17, 'attribute_id' => 73, 'value' => 'سوبر لوكس'],
        //     ['advertising_id' => 17, 'attribute_id' => 74, 'value' => '1'],
        //     ['advertising_id' => 17, 'attribute_id' => 75, 'value' => '10 سنوات'],
           


        //     // الإعلان 18 (لابتوب لينوفو ليغيون)
          
        //     ['advertising_id' => 18, 'attribute_id' => 35, 'value' => 'i7'],
        //     ['advertising_id' => 18, 'attribute_id' => 36, 'value' => '16 جيجا'],
        //     ['advertising_id' => 18, 'attribute_id' => 42, 'value' => 'مستعمل'],

        //     // الإعلان 19 (تلفزيون تي سي إل)
          
        //     ['advertising_id' => 19, 'attribute_id' => 51, 'value' => '50 إنش'],
        //     ['advertising_id' => 19, 'attribute_id' => 55, 'value' => 'جديد'],

        //     // الإعلان 20 (تلفزيون سوني)
          
        //     ['advertising_id' => 20, 'attribute_id' => 51, 'value' => '55 إنش'],
        //     ['advertising_id' => 20, 'attribute_id' => 55, 'value' => 'جديد'],

        //     // الإعلان 21 (عقار تجاري - محل)
          
        //     ['advertising_id' => 21, 'attribute_id' => 58, 'value' => 'محل'],
        //     ['advertising_id' => 21, 'attribute_id' => 59, 'value' => '50 م²'],
           
        //     // الإعلان 22 (عقار تجاري - مستودع)
         
        //     ['advertising_id' => 22, 'attribute_id' => 58, 'value' => 'مستودع'],
        //     ['advertising_id' => 22, 'attribute_id' => 59, 'value' => '200 م²'],
           

        //     // الإعلان 23 (عقار سكني - دوبلكس)

        //     ['advertising_id' => 23, 'attribute_id' => 67, 'value' => 'دوبلكس'],
        //     ['advertising_id' => 23, 'attribute_id' => 68, 'value' => '180 م²'],
         

        //     // الإعلان 24 (عقار سكني - بنتهاوس)
       
        //     ['advertising_id' => 24, 'attribute_id' => 67, 'value' => 'بنتهاوس'],
        //     ['advertising_id' => 24, 'attribute_id' => 68, 'value' => '200 م²'],
           
        //     // الإعلان 25 (عقار سكني - شقة)
          
        //     ['advertising_id' => 25, 'attribute_id' => 67, 'value' => 'شقة'],
        //     ['advertising_id' => 25, 'attribute_id' => 68, 'value' => '120 م²'],
        //     ['advertising_id' => 25, 'attribute_id' => 69, 'value' => '2'],
        //     ['advertising_id' => 25, 'attribute_id' => 70, 'value' => '1'],
        //     ['advertising_id' => 25, 'attribute_id' => 71, 'value' => '1'],
        //     ['advertising_id' => 25, 'attribute_id' => 72, 'value' => 'نعم'],
        //     ['advertising_id' => 25, 'attribute_id' => 73, 'value' => 'لوكس'],
        //     ['advertising_id' => 25, 'attribute_id' => 74, 'value' => '3'],
        //     ['advertising_id' => 25, 'attribute_id' => 75, 'value' => '3 سنوات'],
         
        //     // الإعلان 26 (عقار سكني - فيلا)
         
        //     ['advertising_id' => 26, 'attribute_id' => 67, 'value' => 'فيلا'],
        //     ['advertising_id' => 26, 'attribute_id' => 68, 'value' => '400 م²'],
        //     ['advertising_id' => 26, 'attribute_id' => 69, 'value' => '5'],
        //     ['advertising_id' => 26, 'attribute_id' => 70, 'value' => '4'],
        //     ['advertising_id' => 26, 'attribute_id' => 71, 'value' => '3'],
        //     ['advertising_id' => 26, 'attribute_id' => 72, 'value' => 'نعم'],
        //     ['advertising_id' => 26, 'attribute_id' => 73, 'value' => 'سوبر لوكس'],
        //     ['advertising_id' => 26, 'attribute_id' => 74, 'value' => '2'],
        //     ['advertising_id' => 26, 'attribute_id' => 75, 'value' => '5 سنوات'],
          

         
        //     ['advertising_id' => 25, 'attribute_id' => 35, 'value' => 'i5'],
        //     ['advertising_id' => 25, 'attribute_id' => 36, 'value' => '8 جيجا'],
        //     ['advertising_id' => 25, 'attribute_id' => 37, 'value' => '512 جيجا'],
        //     ['advertising_id' => 25, 'attribute_id' => 38, 'value' => 'GTX 1650'],
        //     ['advertising_id' => 25, 'attribute_id' => 42, 'value' => 'مستعمل'],

        //     // الإعلان 26 (موبايل أوبو رينو 5)
          
        //     ['advertising_id' => 26, 'attribute_id' => 26, 'value' => '128 جيجا'],
        //     ['advertising_id' => 26, 'attribute_id' => 27, 'value' => '8 جيجا'],
        //     ['advertising_id' => 26, 'attribute_id' => 32, 'value' => 'مستعمل'],

        //     // الإعلان 27 (سيارة بي إم دبليو X5)
           
        //     ['advertising_id' => 27, 'attribute_id' => 2, 'value' => '2019'],
        //     ['advertising_id' => 27, 'attribute_id' => 3, 'value' => '60000 كم'],
        //     ['advertising_id' => 27, 'attribute_id' => 4, 'value' => 'أسود'],
        //     ['advertising_id' => 27, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 27, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 27, 'attribute_id' => 7, 'value' => 'SUV'],
        //     ['advertising_id' => 27, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 28 (دراجة هارلي ديفيدسون)
         
        //     ['advertising_id' => 28, 'attribute_id' => 17, 'value' => '2020'],
        //     ['advertising_id' => 28, 'attribute_id' => 18, 'value' => '1200 cc'],
        //     ['advertising_id' => 28, 'attribute_id' => 21, 'value' => 'مستعمل'],

        //     // الإعلان 29 (تلفزيون هايسنس)
       
        //     ['advertising_id' => 29, 'attribute_id' => 51, 'value' => '43 إنش'],
        //     ['advertising_id' => 29, 'attribute_id' => 55, 'value' => 'جديد'],

        //     // الإعلان 30 (عقار تجاري - عيادة)
     
        //     ['advertising_id' => 30, 'attribute_id' => 58, 'value' => 'عيادة'],
        //     ['advertising_id' => 30, 'attribute_id' => 59, 'value' => '90 م²'],
          

        //     // الإعلان 31 (موبايل شاومي ريدمي نوت 11)
           
        //     ['advertising_id' => 31, 'attribute_id' => 26, 'value' => '64 جيجا'],
        //     ['advertising_id' => 31, 'attribute_id' => 27, 'value' => '4 جيجا'],
        //     ['advertising_id' => 31, 'attribute_id' => 32, 'value' => 'جديد'],

        //     // الإعلان 32 (لابتوب ماك بوك برو)
        
        //     ['advertising_id' => 32, 'attribute_id' => 35, 'value' => 'M1 Pro'],
        //     ['advertising_id' => 32, 'attribute_id' => 36, 'value' => '16 جيجا'],
        //     ['advertising_id' => 32, 'attribute_id' => 42, 'value' => 'جديد'],

        //     // الإعلان 33 (سيارة ميتسوبيشي باجيرو)
            
        //     ['advertising_id' => 33, 'attribute_id' => 2, 'value' => '2018'],
        //     ['advertising_id' => 33, 'attribute_id' => 3, 'value' => '70000 كم'],
        //     ['advertising_id' => 33, 'attribute_id' => 4, 'value' => 'أبيض'],
        //     ['advertising_id' => 33, 'attribute_id' => 5, 'value' => 'ديزل'],
        //     ['advertising_id' => 33, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 33, 'attribute_id' => 7, 'value' => 'SUV'],
        //     ['advertising_id' => 33, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 34 (دراجة هوندا سي بي آر 1000)
     
        //     ['advertising_id' => 34, 'attribute_id' => 17, 'value' => '2021'],
        //     ['advertising_id' => 34, 'attribute_id' => 18, 'value' => '1000 cc'],
        //     ['advertising_id' => 34, 'attribute_id' => 21, 'value' => 'مستعمل'],

        //     // الإعلان 35 (تلفزيون تي سي إل 4K)
       
        //     ['advertising_id' => 35, 'attribute_id' => 51, 'value' => '55 إنش'],
        //     ['advertising_id' => 35, 'attribute_id' => 55, 'value' => 'جديد'],

        //     // الإعلان 36 (عقار سكني - تاون هاوس)
         
        //     ['advertising_id' => 36, 'attribute_id' => 67, 'value' => 'تاون هاوس'],
        //     ['advertising_id' => 36, 'attribute_id' => 68, 'value' => '160 م²'],
        //     ['advertising_id' => 36, 'attribute_id' => 76, 'value' => 'بيع'],

        //     // الإعلان 37 (موبايل سامسونج A52)
          
        //     ['advertising_id' => 37, 'attribute_id' => 26, 'value' => '128 جيجا'],
        //     ['advertising_id' => 37, 'attribute_id' => 27, 'value' => '6 جيجا'],
        //     ['advertising_id' => 37, 'attribute_id' => 32, 'value' => 'جديد'],

        //     // الإعلان 38 (لابتوب ديل انسبيرون)
        //  ,
        //     ['advertising_id' => 38, 'attribute_id' => 35, 'value' => 'i5'],
        //     ['advertising_id' => 38, 'attribute_id' => 36, 'value' => '8 جيجا'],
        //     ['advertising_id' => 38, 'attribute_id' => 42, 'value' => 'مستعمل'],

        //     // الإعلان 39 (سيارة كيا سيراتو)
        
        //     ['advertising_id' => 39, 'attribute_id' => 2, 'value' => '2019'],
        //     ['advertising_id' => 39, 'attribute_id' => 3, 'value' => '50000 كم'],
        //     ['advertising_id' => 39, 'attribute_id' => 4, 'value' => 'أبيض'],
        //     ['advertising_id' => 39, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 39, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 39, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 40 (دراجة سوزوكي جي اس اكس اس 1000)
      
        //     ['advertising_id' => 40, 'attribute_id' => 17, 'value' => '2022'],
        //     ['advertising_id' => 40, 'attribute_id' => 18, 'value' => '1000 cc'],
        //     ['advertising_id' => 40, 'attribute_id' => 21, 'value' => 'جديد'],

        //     // الإعلان 41 (تلفزيون سامسونج QLED)
      
        //     ['advertising_id' => 41, 'attribute_id' => 51, 'value' => '65 إنش'],
        //     ['advertising_id' => 41, 'attribute_id' => 55, 'value' => 'جديد'],

        //     // الإعلان 42 (عقار تجاري - صيدلية)
          
        //     ['advertising_id' => 42, 'attribute_id' => 58, 'value' => 'صيدلية'],
        //     ['advertising_id' => 42, 'attribute_id' => 59, 'value' => '70 م²'],
        //     ['advertising_id' => 42, 'attribute_id' => 65, 'value' => 'بيع'],

        //     // الإعلان 43 (موبايل آيفون 13)
       
        //     ['advertising_id' => 43, 'attribute_id' => 26, 'value' => '128 جيجا'],
        //     ['advertising_id' => 43, 'attribute_id' => 27, 'value' => '4 جيجا'],
        //     ['advertising_id' => 43, 'attribute_id' => 32, 'value' => 'جديد'],

        //     // الإعلان 44 (لابتوب اسوس روج)
      
        //     ['advertising_id' => 44, 'attribute_id' => 35, 'value' => 'i7'],
        //     ['advertising_id' => 44, 'attribute_id' => 36, 'value' => '16 جيجا'],
        //     ['advertising_id' => 44, 'attribute_id' => 42, 'value' => 'مستعمل'],

        //     // الإعلان 45 (سيارة فولكس فاجن جولف)
         
        //     ['advertising_id' => 45, 'attribute_id' => 2, 'value' => '2020'],
        //     ['advertising_id' => 45, 'attribute_id' => 3, 'value' => '40000 كم'],
        //     ['advertising_id' => 45, 'attribute_id' => 4, 'value' => 'أزرق'],
        //     ['advertising_id' => 45, 'attribute_id' => 5, 'value' => 'بنزين'],
        //     ['advertising_id' => 45, 'attribute_id' => 6, 'value' => 'أوتوماتيك'],
        //     ['advertising_id' => 45, 'attribute_id' => 13, 'value' => 'مستعمل'],

        //     // الإعلان 46 (دراجة ياماها MT-07)
        
        //     ['advertising_id' => 46, 'attribute_id' => 17, 'value' => '2021'],
        //     ['advertising_id' => 46, 'attribute_id' => 18, 'value' => '700 cc'],
        //     ['advertising_id' => 46, 'attribute_id' => 21, 'value' => 'مستعمل'],

        //     // الإعلان 47 (تلفزيون LG OLED)
         
        //     ['advertising_id' => 47, 'attribute_id' => 51, 'value' => '55 إنش'],
        //     ['advertising_id' => 47, 'attribute_id' => 55, 'value' => 'جديد'],

        //     // الإعلان 48 (عقار سكني - دوبلكس)
        
        //     ['advertising_id' => 48, 'attribute_id' => 67, 'value' => 'دوبلكس'],
        //     ['advertising_id' => 48, 'attribute_id' => 68, 'value' => '200 م²'],
        //     ['advertising_id' => 48, 'attribute_id' => 76, 'value' => 'بيع'],

        //     // الإعلان 49 (موبايل ون بلس 9)
       
        //     ['advertising_id' => 49, 'attribute_id' => 26, 'value' => '128 جيجا'],
        //     ['advertising_id' => 49, 'attribute_id' => 27, 'value' => '8 جيجا'],
        //     ['advertising_id' => 49, 'attribute_id' => 32, 'value' => 'مستعمل'],

        //     // الإعلان 50 (لابتوب لينوفو يوجا)
      
        //     ['advertising_id' => 50, 'attribute_id' => 35, 'value' => 'i5'],
        //     ['advertising_id' => 50, 'attribute_id' => 36, 'value' => '8 جيجا'],
        //     ['advertising_id' => 50, 'attribute_id' => 42, 'value' => 'مستعمل'],
    
           
        // ];

        // DB::table('advertising_attribute')->insert($attributesData);
    }
}
   
