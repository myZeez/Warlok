<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Umkm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request, Umkm $umkm): RedirectResponse
    {
        abort_unless($umkm->status === 'active', 404);

        $enabledMethods = array_keys($umkm->enabledDeliveryMethods());

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:99'],
            'delivery_method' => ['required', Rule::in($enabledMethods)],
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_phone' => ['required', 'string', 'max:20'],
            'buyer_address' => [Rule::requiredIf(fn () => $request->input('delivery_method') !== 'pickup'), 'nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Never trust client-sent price/name -- re-fetch everything from the DB, scoped to
        // this umkm, active, and in stock, so a stale cart entry can't sneak in a bad order.
        $products = Product::query()
            ->whereIn('id', collect($validated['items'])->pluck('product_id'))
            ->where('umkm_id', $umkm->id)
            ->active()
            ->where('stock_status', 'tersedia')
            ->get()
            ->keyBy('id');

        $lineItems = collect($validated['items'])
            ->map(function ($item) use ($products) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $product->price * $item['qty'],
                ];
            })
            ->filter();

        if ($lineItems->isEmpty()) {
            return back()->withErrors(['items' => 'Produk yang kamu pilih sudah tidak tersedia.']);
        }

        $subtotal = $lineItems->sum('subtotal');
        $deliveryFee = $validated['delivery_method'] === 'self_delivery' ? $umkm->delivery_self_fee : 0;
        $total = $subtotal + $deliveryFee;

        $order = DB::transaction(function () use ($umkm, $validated, $lineItems, $subtotal, $deliveryFee, $total) {
            $order = Order::create([
                'umkm_id' => $umkm->id,
                'user_id' => Auth::id(),
                'buyer_name' => $validated['buyer_name'],
                'buyer_phone' => $validated['buyer_phone'],
                'buyer_address' => $validated['buyer_address'] ?? null,
                'delivery_method' => $validated['delivery_method'],
                'delivery_fee' => $deliveryFee,
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($lineItems as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'price' => $line['product']->price,
                    'quantity' => $line['qty'],
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $order;
        });

        // No buyer accounts exist in this app, so there's no real ownership record to check
        // against -- this just stops a stranger from reading someone else's name/phone/address
        // by guessing/incrementing the order URL, without requiring an account to check out.
        Session::push('recent_order_ids', $order->id);

        return redirect()->route('orders.confirmation', $order);
    }

    public function confirmation(Order $order): View
    {
        abort_unless(in_array($order->id, Session::get('recent_order_ids', [])), 404);

        $order->load(['items', 'umkm']);

        return view('orders.confirmation', ['order' => $order]);
    }

    public function myOrders(): View
    {
        $orders = Order::query()
            ->whereIn('id', Session::get('recent_order_ids', []))
            ->with(['items', 'umkm'])
            ->latest()
            ->get();

        return view('orders.my-orders', ['orders' => $orders]);
    }
}
