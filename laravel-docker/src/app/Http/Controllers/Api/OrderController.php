<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders.
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItems = [];

            // Validate and calculate totals
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$product->name}"
                    ], 400);
                }

                $price = $product->sale_price ?? $product->price;
                $total = $price * $item['quantity'];
                $subtotal += $total;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $total,
                ];

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            // Calculate tax and shipping (you can customize these)
            $tax = $subtotal * 0.1; // 10% tax
            $shipping = 50; // flat shipping fee
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    ...$item
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified order (cancel only).
     */
    public function update(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)->findOrFail($id);

        if ($order->status === 'completed' || $order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update this order'
            ], 400);
        }

        $request->validate([
            'status' => 'required|in:cancelled',
        ]);

        $order->update([
            'status' => 'cancelled'
        ]);

        // Restore product stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)->findOrFail($id);

        if ($order->status !== 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Can only delete cancelled orders'
            ], 400);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}
