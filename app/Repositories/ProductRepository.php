<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Product;

class ProductRepository extends  BaseRepository
{

    public function create(array $attributes)
    {
        $created = Product::query()->create([
            'name'      => data_get($attributes,'name','Product Name'),
            'body'      => data_get($attributes,'body'),
            'price'     => data_get($attributes,'price'),
        ]);
        throw_if(!$created, GeneralJsonException::class,'failed to create product');
        return $created;
    }

    public function update($product, array $attributes)
    {
        $updated = $product->update([
            'name'      => data_get($attributes,'name',),
            'body'      => data_get($attributes,'body'),
            'price'     => data_get($attributes,'price'),
        ]);

        throw_if(!$product, GeneralJsonException::class, 'failed to update product');
        return $product;
    }

    public function delete($product)
    {
        $deleted = $product->forceDelete();
        throw_if(!$deleted,GeneralJsonException::class,'failed to delete product');
        return $deleted;
    }
}
