<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab');

        $favoriteItems = auth()->check()
            ? auth()->user()->favorites()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('keyword') . '%');
            })
            ->with('orders')
            ->get()
            : collect();

        $items = Item::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('keyword') . '%');
            })
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', '!=', auth()->id());
            })
            ->get();

        return view('index', compact('items', 'favoriteItems'));
    }

    public function show($item_id)
    {
        $item = Item::with(['categories', 'comments.user', 'orders'])->findOrFail($item_id);
        $sold = $item->is_sold || $item->orders->isNotEmpty();
        return view('detail', compact('item', 'sold'));
    }

    public function toggle($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        if ($user->favorites()->where('item_id', $item_id)->exists()) {
            $user->favorites()->detach($item_id);
            $status = 'removed';
        } else {
            $user->favorites()->attach($item_id);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'count' => $item->favorites()->count(),
        ]);
    }

    public function postComment(CommentRequest $request, Item $item)
    {
        $comment = $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return response()->json([
            'content' => $comment->content,
            'user_name' => $comment->user->name,
            'user_image' => $comment->user->image
                ? asset('storage/' . $comment->user->image)
                : asset('images/default-user.png'),
            'comment_count' => $item->comments()->count(),
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('listing', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('images', 'public');
        $item = Item::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'brand_name'  => $request->brand_name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $imagePath,
            'condition'   => $request->condition,
        ]);
        if ($request->has('categories')) {
            $item->categories()->sync($request->categories);
        }
        return redirect("/");
    }
}
