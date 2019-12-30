<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

/**
 * Controller class for managing product orders
 */
class OrderController extends Controller
{
    /**
     * Get all product orders for the business
     * 
     * @param Store $store
     * @return JsonResponse
     */
    public function getAll(Store $store) :JsonResponse
    {
       $order = $store->orders()->get();
       
       return response()->json(['response' => $order]);
    }

    /**
     * Get product order details
     * 
     * @param Order $order 
     * @return JsonResponse
     */
    public function getOrderDetails(Order $order) :JsonResponse
    {
        return response()->json(['response' => $order]);
    }

    /**
     * Create an order
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) :JsonResponse
    {
        foreach($request->orders as $index => $order) {
            $rules = [
                'orders.' . $index . '.productId' => 'required|integer',
                'orders.' . $index . '.quantity' => 'required|integer',
                'orders.' . $index . '.currency' => ['required', Rule::in(Order::CURRENCY)],
                'orders.' . $index . '.price' => 'required|numeric',
                'orders.' . $index . '.payment_status_id' => 'required|int'
           ]; 
        }

        $rules['orders']= 'required|array|min:1';

        $this->validate($request, $rules);

        $productOrders = $this->getOrderProduct($request->orders);

        foreach($productOrders as $order) {
            Order::create([
                'user_id' => 1,
                'store_id' => $order['meta']['business_id'],
                'payment_status_id' => $order['payment_status_id'],
                'payment_method_id' => $order['payment_method_id'],
                'title' => $order['meta']['name'],
                'currency' => $order['currency'],
                'price' => $order['price'],
                'quantity' => $order['quantity'],
                'meta' => $order['meta'],
                'transaction_no' => 'gedbefdvfdvuuidnumber-goes-here'
            ]);
        }
        return $this->success('Order created');
    }

    /**
     * Get the order product
     * 
     * @param array $orders
     * @return array
     */
    public function getOrderProduct(array $orders) :array
    {
        foreach($orders as $index => $order) {
            //@todo add conditions if have variants query and get the variant else do this
            $orders[$index]['meta'] = Product::find($order['productId'])->toArray();
        }
        
        return $orders;
    }
}
