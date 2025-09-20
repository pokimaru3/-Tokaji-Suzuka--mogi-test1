@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/setting.css') }}">
@endsection

@section('content')
    <div class="setting">
        <h2 class="setting__heading">プロフィール設定</h2>
        <div class="setting-form__inner">
            <form class="setting-form__form" action="/mypage/profile" method="post" enctype="multipart/form-data">
                @csrf
                <div class="image-upload">
                    <img id="preview" class="profile-image" src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-user.png') }}" alt="プロフィール画像">
                    <button type="button" id="selectImageButton" class="upload-button">画像を選択する</button>
                    <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                    @error('image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="setting-form__group">
                    <label class="setting-form__label" for="name">ユーザー名</label>
                    <input class="setting-form__input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="setting-form__group">
                    <label class="setting-form__label" for="postal_code">郵便番号</label>
                    <input class="setting-form__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', optional($user->address)->postal_code) }}">
                    @error('postal_code')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="setting-form__group">
                    <label class="setting-form__label" for="address">住所</label>
                    <input class="setting-form__input" type="text" name="address" id="address" value="{{ old('address', optional($user->address)->address) }}">
                    @error('address')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="setting-form__group">
                    <label class="setting-form__label" for="building">建物名</label>
                    <input class="setting-form__input" type="text" name="building" id="building" value="{{ old('building', optional($user->address)->building) }}">
                </div>
                <button class="setting-form__btn" type="submit">更新する</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('selectImageButton').addEventListener('click', function () {
                document.getElementById('image').click();
            });
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    };
                reader.readAsDataURL(file);
            } else {
                preview.src = "{{ asset('images/default-user.png') }}";
            }
        });
    </script>

@endsection