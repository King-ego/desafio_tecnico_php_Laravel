<?php

namespace App\Http\Controllers\Api\Product;

use App\Exceptions\DefaultException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;//jbjk
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $product;

    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $product = $this->product->all();

            return response()->json(['products'=> $product]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();

        $data['user_id'] = 2; //Auth::user()->id

        $images = $request->file('images');

        if (count($images) > 3) {
            return response()->json(['message' => 'Maximum three images allowed'], 422);
        }

        //dd($images);

        if ($images) {

            $imagesUploaded = array();
            foreach ($images as $image) {

                $path= $image->store('images', 'public');

                $imagesUploaded[] = $path;

            }

            $data['images'] = $imagesUploaded;

        } else {
            unset($data['images']);
        }

        try {
            $product = $this->product->createProduct($data);

            return response()->json([
                'data' => [
                    'message' => 'product created successfully!'
                ]
            ], 201);
        } catch (DefaultException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {

        // response()->json(['message' => 'User doesn\'t found']);
        try {
            $product = $this->product->find($id);

            if (!$product) {
                return response()->json(['message' => 'product doesn\'t found']);
            }

            return response()->json(['data' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;//Auth::user()->id

        try {

            $this->product->updateProduct($data, $id);

            return response()->json([
                'data' => [
                    'message' => 'product updated successfully!'
                ]
            ], 200);

        }catch (DefaultException $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }

        catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->product->deleteProduct($id);

            return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
        } catch (DefaultException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }

    }
}
