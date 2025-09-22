<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function purchaseForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $order = Order::where('item_id', $item_id)->where('user_id', $user->id)->first();
        $shippingAddress = optional($order)->shippingAddress;

        return view('purchase', compact('item', 'user', 'shippingAddress'));
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $order = Order::create([
            'user_id'     => Auth::id(),
            'item_id'     => $item->id,
            'status'      => 'paid'
        ]);

        $order->shippingAddress()->create([
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ]);

        $item->update(['is_sold' => true]);

        return redirect('/');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('address', compact('item_id', 'user'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );

        return redirect("/purchase/{$item_id}");
    }
}
