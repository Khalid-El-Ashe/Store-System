<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Listeners\EmptyCart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutContrller extends Controller
{
    public function create(CartRepository $cart)
    {
        // check if the cart is empty
        if ($cart->get()->count() == 0) {
            return redirect()->route("home");
        }
        return view('front.checkout', ['cart' => $cart, 'countries' => Countries::getNames()]);
    }

    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([]);

        $items = $cart->get()->groupBy('product.store_id')->all(); // need to get collection of items

        // i need to use Database Transaction لازم كل العمليات تتم حتى يتم تنفيذ الطلب
        DB::beginTransaction();
        try {
            // i need to use foreache to get the items from collection
            foreach ($items as $store_id => $cart_items) {

                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod',
                ]);

                // i need to add the items of Order from Salls
                foreach ($cart_items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity
                    ]);
                }

                // now i need to add the customer information into the orderAddressTable
                foreach ($request->post('addr') as $type => $address) {
                    $address['type'] = $type;
                    $order->addresses()->create($address);
                }
            }

            DB::commit(); // اعتماد العملية

            // i need to make simple event
            // so i need to make Listener class to empty
            // and how can i Triger or Connect the EventListener
            // event('order.created', $order, Auth::user()); // in here i passed the arguments
            event(new OrderCreated($order)); // this event is created by class

        } catch (Throwable $e) {
            // طبعا هان صار استثناء يبقى لازم اتراجع عن العملية ما دام جزء لم يعمل
            DB::rollBack();
            throw $e;
            return response()->json(['', $e->getMessage()], 500);
        }
        // return redirect()->route('home')->with('success', 'is ordered');
    }
}
