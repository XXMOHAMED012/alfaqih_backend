<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'name' => $this->name,
            'price' => (int)$this->price,
            'status' => $this->status,
            'type' => $this->type,
            'img' => $this->img,
            'imgs' => explode("|", $this->imgs),
            'specs' => json_decode($this->specs),
            'des' => $this->des,
        ];
    }
}
