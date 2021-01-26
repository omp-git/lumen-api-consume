<?php
/**
 * Created by PhpStorm.
 * User: OMP
 * Date: 26/01/2021
 * Time: 03:51 PM
 */

namespace App\Http\Resources\Api;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'mobile' => $this->mobile,
            'avatar' => $this->avatar,
            'registered_at' => $this->created_at->format('Y-m-d H:i')
        ];
    }
}