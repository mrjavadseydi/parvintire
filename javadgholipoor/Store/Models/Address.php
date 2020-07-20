<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;
use LaraBase\World\models\City;
use LaraBase\World\models\Province;
use LaraBase\World\models\Region;
use LaraBase\World\models\Shingle;
use LaraBase\World\models\Town;

class Address extends CoreModel
{

    protected $table = 'addresses';

    protected $fillable = [
        'id',
        'user_id',
        'province_id',
        'city_id',
        'town_id',
        'region_id',
        'shingle_id',
        'name_family',
        'mobile',
        'address',
        'postal_code',
        'success_orders',
        'status',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];

    public function address()
    {
        $address = '';
        $province = Province::find($this->province_id);
        $city = City::find($this->city_id);

        $address = $province->name . ', ' . $city->name . ', ';

        if (!empty($this->town_id)) {
            $town = Town::find($this->town_id);
            $address .= $town->name . ', ';

            if (!empty($this->region_id)) {
                $region = Region::find($this->region_id);
                $address .= $region->name . ', ';
            }

        }

        $address .= $this->address;

        return $address;

    }

    public function shingles()
    {
        $regionId = $this->region_id;

        if ($regionId != null) {
            $shingle = Shingle::where([
                'relation' => 'region',
                'relation_id' => $regionId
            ])->get();

            if ($shingle->count() > 0)
                return $shingle;
        }

        $townId = $this->town_id;

        if ($townId != null) {
            $shingle = Shingle::where([
                'relation' => 'town',
                'relation_id' => $townId
            ])->get();

            if ($shingle->count() > 0)
                return $shingle;
        }

        $cityId = $this->city_id;

        if ($cityId != null) {
            $shingle = Shingle::where([
                'relation' => 'city',
                'relation_id' => $cityId
            ])->get();

            if ($shingle->count() > 0)
                return $shingle;
        }

        $provinceId = $this->province_id;

        if ($provinceId != null) {
            $shingle = Shingle::where([
                'relation' => 'province',
                'relation_id' => $provinceId
            ])->get();

            if ($shingle->count() > 0)
                return $shingle;
        }

        return null;
    }

    public function shingle($shippingId)
    {
        $regionId = $this->region_id;

        if ($regionId != null) {
            $shingle = Shingle::where([
                'relation' => 'region',
                'relation_id' => $regionId,
                'shipping_id' => $shippingId
            ])->first();

            if ($shingle != null)
                return $shingle;
        }

        $townId = $this->town_id;

        if ($townId != null) {
            $shingle = Shingle::where([
                'relation' => 'town',
                'relation_id' => $townId,
                'shipping_id' => $shippingId
            ])->first();

            if ($shingle != null)
                return $shingle;
        }

        $cityId = $this->city_id;

        if ($cityId != null) {
            $shingle = Shingle::where([
                'relation' => 'city',
                'relation_id' => $cityId,
                'shipping_id' => $shippingId
            ])->first();

            if ($shingle != null)
                return $shingle;
        }

        $provinceId = $this->province_id;

        if ($provinceId != null) {
            $shingle = Shingle::where([
                'relation' => 'province',
                'relation_id' => $provinceId,
                'shipping_id' => $shippingId
            ])->first();

            if ($shingle != null)
                return $shingle;
        }

        return null;
    }

}
