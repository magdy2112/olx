<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            __('attributes.Title')       => $this->title,
            __('attributes.Description') => $this->description,
            __('attributes.Price')       => $this->price,
            __('attributes.Purpose')     => $this->purpose,
            'category_id'                => $this->category_id,
            'sub_category_id'            => $this->sub_category_id,
            'modal_id'                   => $this->modal_id,
            'submodal_id'                => $this->submodal_id,
            'status'                     => $this->status,

            'attributes' => $this->categoryattributes->map(function ($attr) {
               return [
        'id' => $attr->id,
        __( 'attributes.' . $attr->name ) => $attr->pivot->value,
    ];
            }),
        ];
    }
}
