@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="product-list">
        <div class="tabs">
            <a href="{{ url('/?keyword=' . request("keyword")) }}" class="tab{{ request("tab") !== 'mylist' ? ' active' : '' }}">おすすめ</a>
            <a href="{{ url('/?tab=mylist&keyword=' . request("keyword")) }}" class="tab{{ request("tab") === 'mylist' ? ' active' : '' }}">マイリスト</a>
        </div>
        @if(request('tab') === 'mylist')
            @auth
                <div class="grid">
                    @foreach ($favoriteItems as $item)
                        <a href="/item/{{ $item->id }}" class="item-card-link">
                            <div class="item-card">
                                <img class="item-card__image" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                                <p class="item-card__name">{{ $item->name }}</p>
                                @if($item->orders()->exists() || $item->is_sold)
                                    <span class="sold-label">Sold</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endauth
        @else
            <div class="grid">
                @foreach ($items as $item)
                    <a href="/item/{{ $item->id }}" class="item-card-link">
                        <div class="item-card">
                            <img class="item-card__image" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                            <p class="item-card__name">{{ $item->name }}</p>
                            @if($item->orders()->exists() || $item->is_sold)
                                <span class="sold-label">Sold</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection