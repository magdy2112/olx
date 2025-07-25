<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};



//   public function attribute()
//     {
//         return $this->belongsTo(Attribute::class);
//     }

//     public function getCastedValueAttribute()
//     {
//         return $this->castValue($this->attribute->type, $this->value);
//     }

//     protected function castValue(string $type, $value)
//     {
//         switch ($type) {
//             case 'number':
//                 return (float) $value;
//             case 'boolean':
//                 return filter_var($value, FILTER_VALIDATE_BOOLEAN);
//             case 'date':
//                 return Carbon::parse($value);
//             default:
//                 return (string) $value;
//         }
//     }
// }


////  attribute_value.php


// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Carbon\Carbon;

// class AttributeValue extends Model
// {
//     protected $fillable = ['attribute_id', 'product_id', 'value'];

//     public function attribute()
//     {
//         return $this->belongsTo(Attribute::class);
//     }

//     public function getCastedValueAttribute()
//     {
//         return $this->castValue($this->attribute->type, $this->value);
//     }

//     protected function castValue(string $type, $value)
//     {
//         switch ($type) {
//             case 'integer':
//                 return (int) $value;
//             case 'boolean':
//                 return filter_var($value, FILTER_VALIDATE_BOOLEAN);
//             case 'date':
//                 return Carbon::parse($value);
//             default:
//                 return (string) $value;
//         }
//     }
// }
// $attrValue = AttributeValue::with('attribute')->first();

// echo $attrValue->value; // القيمة كـ نص خام (string)
// echo $attrValue->casted_value; // القيمة بعد الكاست حسب النوع (مثلاً عدد صحيح أو boolean أو تاريخ)
