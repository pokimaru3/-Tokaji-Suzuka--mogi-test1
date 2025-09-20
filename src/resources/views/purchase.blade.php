@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="purchase-container">
        <form action="/purchase/{{ $item->id }}" method="post" class="purchase-form">
            @csrf
            <div class="purchase-left">
                <div class="purchase-item-header">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="purchase-img">
                    <div class="purchase-item-info">
                        <h2 class="purchase-item-name">{{ $item->name }}</h2>
                        <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>
                <div class="payment-container">
                    <label for="payment_method" class="payment_method">支払い方法</label>
                    <select name="payment_method" id="payment_method" class="payment-select">
                        <option value="" selected disabled>選択してください</option>
                        <option value="convenience_store">コンビニ払い</option>
                        <option value="credit_card">カード払い</option>
                    </select>
                </div>
                <div class="shipping-address">
                    <div class="shipping-address-header">
                        <h3 class="shipping-address-heading">配送先</h3>
                        <a href="/purchase/address/{{ $item->id }}" class="address-link">変更する</a>
                    </div>
                    @if(isset($shippingAddress))
                        <p>{{ $shippingAddress->postal_code }}</p>
                        <p>{{ $shippingAddress->address }}</p>
                        <p>{{ $shippingAddress->building }}</p>
                    @else
                        <p>{{ optional($user->address)->postal_code }}</p>
                        <p>{{ optional($user->address)->address }}</p>
                        <p>{{ optional($user->address)->building }}</p>
                    @endif
                    <input type="hidden" name="postal_code" value="{{ $shippingAddress->postal_code ?? optional($user->address)->postal_code }}">
                    <input type="hidden" name="address" value="{{ $shippingAddress->address ?? optional($user->address)->address }}">
                    <input type="hidden" name="building" value="{{ $shippingAddress->building ?? optional($user->address)->building }}">
                </div>
            </div>
            <div class="purchase-right">
                <div class="summary-box">
                    <div class="order-summary">
                        <span class="summary-label">商品代金</span>
                        <span class="summary-price">¥{{ number_format($item->price) }}</span>
                    </div>
                </div>
                <div class="summary-box">
                    <div class="payment-summary">
                        <span class="summary-label">支払い方法</span>
                        <span class="payment-display" id="payment_display">選択してください</span>
                    </div>
                </div>
                <button type="submit" class="purchase-btn">購入する</button>
            </div>
        </form>
    </div>

        <script>
            const paymentSelect = document.getElementById('payment_method');
            const paymentDisplay = document.getElementById('payment_display');

            paymentSelect.addEventListener('change', function() {
                const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
                paymentDisplay.textContent = selectedText;
            });
        </script>
@endsection