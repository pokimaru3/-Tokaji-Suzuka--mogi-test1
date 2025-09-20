@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/listing.css') }}">
@endsection

@section('content')
    <div class="listing">
        <h2 class="listing-form__heading">商品の出品</h2>
        <div class="listing-form__inner">
            <form action="/sell" method="post" enctype="multipart/form-data" class="listing-form__form">
                @csrf
                <div class="listing-form__group">
                    <label class="listing-form__label">商品画像</label>
                    <div class="image-select-wrapper">
                        <input class="listing-form__input" type="file" name="image" id="image" style="display:none;" accept="image/*">
                        <label class="custom-file-btn" for="image" id="select-btn">商品を選択する</label>
                        <div class="preview">
                            <img id="preview" src="#" alt="プレビュー" style="max-width:200px; display:none;">
                        </div>
                    </div>
                    @error('image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <h3 class="listing-form__subheading">商品の詳細</h3>
                <div class="listing-form__group">
                    <label class="listing-form__label">カテゴリー</label>
                    <div class="categories">
                        @foreach($categories as $category)
                            <input type="checkbox" id="cat{{ $category->id }}" name="categories[]" value="{{ $category->id }}">
                            <label for="cat{{ $category->id }}">{{ $category->name }}</label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="listing-form__group">
                        <label class="listing-form__label" for="condition">商品の状態</label>
                        <select name="condition" id="condition">
                            <option value="" selected disabled>選択してください</option>
                            <option value="良好">良好</option>
                            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                            <option value="状態が悪い">状態が悪い</option>
                        </select>
                        @error('condition')
                            <p class="error">{{ $message }}</p>
                        @enderror
                </div>
                <h3 class="listing-form__subheading">商品名と説明</h3>
                <div class="listing-form__group">
                    <label class="listing-form__label">商品名</label>
                    <input class="listing-form__input" type="text" name="name" value="{{ old('name') }}">
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="listing-form__group">
                    <label class="listing-form__label">ブランド名</label>
                    <input class="listing-form__input" type="text" name="brand_name" value="{{ old('brand_name') }}">
                </div>
                <div class="listing-form__group">
                    <label class="listing-form__label">商品の説明</label>
                    <textarea class="listing-form__input" name="description" rows="5" cols="30" >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="listing-form__group">
                    <label class="listing-form__label">販売価格</label>
                    <div class="price-input">
                        <span class="yen">¥</span>
                        <input class="listing-form__input price-field" type="text" name="price" value="{{ old('price') }}">
                    </div>
                    @error('price')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <button class="listing-form__btn" type="submit">出品する</button>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const selectBtn = document.getElementById('select-btn');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                selectBtn.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            selectBtn.style.display = 'inline-block';
        }
    });
    </script>
@endsection
