<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;


/**
 * Controller for managing products 
 */
class ProductController extends Controller
{
    /**
     * Display paginated products
     */
    public function getAll()
    {
        $products = Product::orderByRaw('RAND()')->paginate(Product::PAGINATE);
        return response()->json($products);
    }

    /**
     * Get a product info
     * 
     * @param Product $product
     * @return JsonResponse
     */
    public function getDetails(Product $product) :JsonResponse
    {
        $product->variations = $product->variations->toArray();

        return response()->json($product);
    }
    
    /**
     * Get paginated products for one specific store
     * 
     * @param Store $store
     * @return JsonResponse
     */
    public function getStoreProducts(Store $store) :JsonResponse
    {
        $storeProducts = $store->products()->orderByRaw('RAND()')->paginate(Product::PAGINATE);
        return response()->json($storeProducts);
    }

    /**
     * Create a new product
     * 
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function create(Request $request, Store $store) :JsonResponse
    {
        $this->validateProduct($request);

        $product = new Product;
        $product->store_id = $store->id;
    
        $this->saveProduct($request, $product);

        return $this->success('Product created');
    }

    /**
     * Upload primary product image
     * 
     * @param Request $request
     * @param Business $business
     * @return JsonResponse
     */
    public function uploadImage(Request $request, Store $store)
    {
        $this->validate($request, [
            'image' => 'required|image|max:3000',
        ]);

        $filePath = Storage::disk('s3')->put('products/' . $store->id . '-business', $request->image, 'public');

        return $filePath;
    }

    /**
     * Upload product gallery
     */
    public function uploadGallery(Request $request, Store $store, Product $product)
    {   
        $this->validate($request, [
            'image' => 'required|array|max:5|min:1',
            'image.*' => 'required|image|mimes:jpeg,jpg,png|max:3000',
        ]);

        foreach($request->images as $image) {

            $filePath = Storage::disk('s3')->put('products/' . $store->id . '-business', $image, 'public');

            $image = new Image;
            $image->url = Storage::disk('s3')->url($filePath);
            $product->images()->save($image);
        }

        return $this->success('Gallery uploaded');
    }

    /**
     * Update Product
     */
    public function update(Request $request, Product $product)
    {
        $this->validateProduct($request);

        $this->saveProduct($request, $product);

        return $this->success('Product updated');
    }

    /**
     * ValidateProduct input data
     */
    private function validateProduct(Request $request) :void
    {
        $this->validate($request, [
            'status' => 'required|int',
            'name' => 'required|string|max:191',
            'price' => 'required|numeric',
            'image_url' => 'required|string',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'brand' => 'nullable|string'
        ]);
    }

    /**
     * Save Product
     */
    private function saveProduct(Request $request, Product $product) :void
    {
        $product->status_id = $request->status;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->primary_image_url = $request->image_url;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->save();
    }
}
