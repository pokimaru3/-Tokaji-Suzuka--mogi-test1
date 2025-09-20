@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="profile-contents">
        <div class="profile-section">
            <div class="profile-icon">
                <img id="preview" src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/default-user.png') }}" alt="プロフィール画像" class="profile-image">
            </div>
            <h2 class="profile-name">{{ Auth::user()->name }}</h2>
            <a href="/mypage/profile" class="edit-button">プロフィールを編集</a>
        </div>
        <div class="product-list">
            <div class="tabs">
                <a href="{{ url('/mypage?tab=sell') }}" class="tab{{ request('tab') !== 'purchase' ? ' active' : '' }}">出品した商品</a>
                <a href="{{ url('/mypage?tab=purchase') }}" class="tab{{ request('tab') === 'purchase' ? ' active' : '' }}">購入した商品</a>
            </div>
            @if(request('tab') === 'purchase')
                <div class="grid">
                    @foreach ($purchaseItems as $item)
                        <a href="/item/{{ $item->id }}" class="item-card-link">
                            <div class="item-card">
                                <img class="item-card__image" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                                <p class="item-card__name">{{ $item->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="grid">
                    @foreach ($sellItems as $item)
                        @if($item->user_id === auth()->id())
                            <div class="item-card">
                                <img class="item-card__image" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                                <p class="item-card__name">{{ $item->name }}</p>
                            </div>
                        @else
                            <a href="/item/{{ $item->id }}" class="item-card-link">
                                <div class="item-card">
                                    <img class="item-card__image" src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                                    <p class="item-card__name">{{ $item->name }}</p>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
