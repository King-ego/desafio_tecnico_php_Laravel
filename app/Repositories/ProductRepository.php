<?php

namespace App\Repositories;

use App\Exceptions\DefaultException;
use App\Models\Api\Product\Image;
use App\Models\Api\Product\Product;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Facades\DB;

class ProductRepository extends AbstractRepository
{
    protected $model = Product::class;

    public function createProduct($data)
    {
        DB::beginTransaction();

        try {
            $product = $this->create($data);

            foreach ($data['images'] as $path) {
                $images[] = $path;
            }
            //dd($images);

            $product->images()->createMany([
                ['path' => $images[0]],
                ['path' => $images[1]],
                ['path' => $images[2]]
            ]);

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct($data, $id)
    {
        try {
            $product = $this->findOrFail($id);

            // dd($product);

            if ($product == null) {
                throw new DefaultException('Product don\'t found', 422);
            }

            $product->update($data);

            return $product;
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteProduct($id)
    {
        $product = $this->find($id);

        if (!$product) {
            throw new DefaultException('It was not possible to delete this product', 500);
        }

        $product->delete();

        return true;
    }
}
