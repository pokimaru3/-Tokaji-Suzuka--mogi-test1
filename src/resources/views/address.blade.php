@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <div class="address-form">
        <h2 class="address-form__heading">住所の変更</h2>
        <div class="address-form__inner">
            <form action="/purchase/address/{{ $item_id }}" method="post" class="address-form__form">
                @csrf
                <div class="address-form__group">
                    <label class="address-form__label" for="postal_code">郵便番号</label>
                    <input class="address-form__input" type="text" name="postal_code" id="postal_code"
                    value="{{ old('postal_code', optional($user->address)->postal_code) }}">
                    @error('postal_code')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="address-form__group">
                    <label class="address-form__label" for="address">住所</label>
                    <input class="address-form__input" type="text" name="address" id="address"
                    value="{{ old('address', optional($user->address)->address) }}">
                    @error('address')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="address-form__group">
                    <label class="address-form__label" for="building">建物名</label>
                    <input class="address-form__input" type="text" name="building" id="building"
                    value="{{ old('building', optional($user->address)->building) }}">
                </div>
                <button class="address-form__btn" type="submit">更新する</button>
            </form>
        </div>
    </div>
@endsection
