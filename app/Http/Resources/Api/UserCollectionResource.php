<?php
/**
 * Created by PhpStorm.
 * User: OMP
 * Date: 26/01/2021
 * Time: 03:51 PM
 */

namespace App\Http\Resources\Api;


use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollectionResource extends ResourceCollection
{
    public static $wrap = 'users';
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}