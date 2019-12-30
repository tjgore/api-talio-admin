<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Product;
use App\Models\Specification;
use App\Models\Order;

/**
 * Middleware for authenticating if the store, product or specifications belong to the user
 */
class BelongsTo
{
    /**
     * Checks if the store or product belongs to the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action
        $store = $request->route('store');
        $product = $request->route('product');
        $specification = $request->route('specification');
        $order = $request->route('order');

        if($store) {
            $this->authenticateStore($store);
        }

        if($order) {
            $this->authenticateOrder($store, $order);
        }

        if($product) {
            $this->authenticateProduct($product);
        }
       
        if($specification) {
            $this->authenticateSpecifications($specification, $product);
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }

    /**
     * Authenticate if store belongs to user
     * 
     * @param Store $store
     */
    private function authenticateStore(Store $store)
    {
        if($store->admin_id !== Auth::user()->id) {
            return abort('403', 'User doesn\'t have access to this store');
        }
    }

    /**
     * Authenticate if order belongs to store
     * 
     * @param Store $store
     */
    private function authenticateOrder(Store $store, Order $order)
    {
        if($store->id !== $order->store_id) {
            return abort('403', 'Store doesn\'t have access to this order');
        }
    }

    /**
     * Authenticate if offering belongs to user store
     * 
     * @param Product $product 
     */
    private function authenticateProduct(Product $product)
    {
        if($product->store_id !== Auth::guard('admin')->user()->stores->first()->id) {
            return abort('403', 'User doesn\'t have access to this item');
        }
    }

    /**
     * Authenticate if specification belongs to product which belongs to the user previously checked
     * 
     * @param Specification $specification
     * @param Product $product
     */
    private function authenticateSpecifications(Specification $specification, Product $product)
    {
        if($product->id !== $specification->product_id) {
            return abort('403', 'User doesn\'t have access to this specification');
        }
    }
}
