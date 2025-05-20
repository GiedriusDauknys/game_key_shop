<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'game_id' => $this->id,
            'title' => $this->title,
            'platform' => $this->platform,
            'release_date' => $this->release_date,
            'developer' => $this->developer->name,
            'category' => $this->category->name,
        ];

    }
}
