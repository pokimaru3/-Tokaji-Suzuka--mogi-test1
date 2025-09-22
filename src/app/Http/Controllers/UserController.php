<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Models\Order;

class UserController extends Controller
{
    public function settingProfile()
    {
        $user = Auth::user();
        return view('setting', compact('user'));
    }

    public function storeProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profile_images', 'public');
            $user->image = $path;
        }

        $user->save();

        Address::create([
            'user_id'     => $user->id,
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ]);

        return redirect('/');
    }

    public function edit()
    {
        $user = User::with('address')->find(Auth::id());
        return view('edit', compact('user'));
    }

    public function editProfile(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->name = $request->name;

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('profile_images', 'public');
            $user->image = $path;
        }

        $user->save();

        if ($user->address) {
            $user->address->update([
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]);
        } else {
            Address::create([
                'user_id'     => $user->id,
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]);
        }

        return redirect('/');
    }

    public function profile()
    {
        $user = Auth::user();

        $sellItems = Item::where('user_id', $user->id)->get();

        $purchaseItems = Item::whereHas('orders', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('profile', compact('user', 'sellItems', 'purchaseItems'));
    }
}
