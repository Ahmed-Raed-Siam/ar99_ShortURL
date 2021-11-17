<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'link' => $this->link,
            'code' => $this->code,
            'private_short_link' => route('dashboard.shorten.link', $this->code),
            'public_short_link' => route('shorten.link', $this->code),
            'total_hits' => $this->total_hits,
            '_links' => [
                '_self' => route('dashboard.links.show', $this->id),
            ],
        ];
    }
}
