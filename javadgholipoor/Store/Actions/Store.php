<?php

namespace LaraBase\Store\Actions;

use LaraBase\Attributes\Models\Attribute;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeKeyPostType;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\Posts\Models\PostAttribute;
use LaraBase\Store\Models\Product;
use LaraBase\Store\Models\ProductFile;
use LaraBase\Store\Models\ProductAttribute;

trait Store {

    public function products() {

        $output = [];

        $postTypeAttributes = AttributeRelation::where(['key' => 'attribute_postType', 'more' => $this->post_type])->get();
        $attributes = Attribute::where('type', 'product')->whereIn('id', $postTypeAttributes->pluck('value')->toArray())->get();

        $attributeKeys = AttributeRelation::where('key', 'attribute_key')->whereIn('value', $postTypeAttributes->pluck('value')->toArray())->get();
        $keys = AttributeKey::whereIn('id', $attributeKeys->pluck('more')->toArray())->orderBy('id', 'asc')->get();

        $attributeValues = AttributeRelation::where('key', 'key_value')->whereIn('value', $attributeKeys->pluck('more')->toArray())->get();
        $values = AttributeValue::whereIn('id', $attributeValues->pluck('more')->toArray())->orderBy('id', 'desc')->get();

        $attrs = [];
        foreach ($attributes as $attribute) {

            $attributeId = $attribute->id;

            $keysArray = [];
            foreach ($keys->whereIn('id', $attributeKeys->where('value', $attributeId)->pluck('more')->toArray())->filter() as $key) {

                $keyId = $key->id;

                $valuesArray = [];
                foreach ($values->whereIn('id', $attributeValues->where('value', $keyId)->pluck('more')->toArray())->filter() as $value) {

                    $valueId = $value->id;

                    $valuesArray[$valueId] = [
                        'id' => $valueId,
                        'title' => $value->title,
                        'record' => $value
                    ];

                }

                $keysArray[$keyId] = [
                    'id' => $keyId,
                    'title' => $key->title,
                    'values' => $valuesArray,
                    'record' => $key
                ];

            }

            $attrs[$attributeId] = [
                'id' => $attributeId,
                'title' => $attribute->title,
                'keys' => $keysArray,
                'record' => $attribute
            ];

        }

        $output['attributes'] = $attrs;
        $output['keys'] = $keys;
        $output['values'] = $values;

        $output['products'] = Product::where('post_id', $this->id)->orderBy('sort', 'asc')->get();
        $output['files'] = ProductFile::where(['post_id' => $this->id])->with('attachment')->orderBy('sort', 'asc')->get();

        $productAttributes = [];
        $getProductAttributes = ProductAttribute::whereIn('product_id', $output['products']->pluck('product_id')->toArray())->get();
        foreach ($output['products'] as $product) {
            $productId = $product->product_id;
            foreach ($getProductAttributes->where('product_id', $productId)->filter() as $productAttribute) {
                $productAttributes[$productId][$productAttribute->attribute_id] = $productAttribute->key_id;
            }
        }

        $output['productAttributes'] = $productAttributes;
        return $output;

    }

    public function price() {

    }

    public function minPrice() {

    }

    public function maxPrice() {

    }

}
