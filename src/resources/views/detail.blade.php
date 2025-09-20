@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
    <div class="item-detail">
        <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="item-detail__image">
        <div class="item-detail__info">
            <h2 class="item-detail__name">{{ $item->name }}</h2>
            <p class="item-detail__brand">{{ $item->brand_name }}</p>
            <p class="item-detail__price">
                <span class="yen">¥</span>
                <span class="amount">{{ number_format($item->price) }}</span>
                <span class="tax">(税込)</span>
            </p>
            <div class="icon-group">
                @if(auth()->check())
                    <form action="/item/{{ $item->id }}/favorite" method="post" class="favorite-form">
                        @csrf
                        <button type="submit" class="favorite-button">
                            @php
                                $isLiked = auth()->user()->favorites->contains($item->id);
                            @endphp
                            <svg class="favorite-icon {{ auth()->user()->favorites->contains($item->id) ? 'liked' : '' }}"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
                                <polygon points="12 2 15 8.5 22 9.3 17 14 18.5 21 12 17.5 5.5 21 7 14 2 9.3 9 8.5 12 2" />
                            </svg>
                            <span class="favorite-count">
                                @if($item->favorites->count() > 0)
                                    {{ $item->favorites->count() }}
                                @endif
                            </span>
                        </button>
                    </form>
                @else
                    <a href="/login" class="favorite-button">
                        <svg class="favorite-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
                        <polygon points="12 2 15 8.5 22 9.3 17 14 18.5 21 12 17.5 5.5 21 7 14 2 9.3 9 8.5 12 2" />
                        </svg>
                        <span class="favorite-count">
                            @if($item->favorites->count() > 0)
                                {{ $item->favorites->count() }}
                            @endif
                        </span>
                    </a>
                @endif
                <div class="comment-icon">
                    @if(auth()->check())
                        <img src="{{ asset('images/吹き出し.png') }}" alt="コメント" class="comment-img">
                        <span class="comment-count">{{ $item->comments->count() ?: '' }}</span>
                    @else
                        <a href="/login" class="comment-link">
                            <img src="{{ asset('images/吹き出し.png') }}" alt="コメント" class="comment-img">
                            <span class="comment-count">{{ $item->comments->count() ?: '' }}</span>
                        </a>
                    @endif
                </div>
            </div>
            @php
                $sold = $item->is_sold || $item->orders()->exists();
            @endphp
            @if(!$sold && auth()->check())
                <a href="/purchase/{{ $item->id }}" class="purchase-button">購入手続きへ</a>
            @elseif(!$sold)
                <a href="/login" class="purchase-button">購入手続きへ</a>
            @else
                <span class="sold-label">Sold</span>
            @endif
            <h3 class="item-detail__heading">商品説明</h3>
            <p class="item-detail__detail">{{ $item->description }}</p>
            <h3 class="item-detail__heading">商品の情報</h3>
            @if($item->categories->isNotEmpty())
                <p class="item-detail__category">
                    <span class="category-label">カテゴリー</span>
                    @foreach ($item->categories as $category)
                        <span class="tag">{{ $category->name }}</span>
                    @endforeach
                </p>
            @endif
            <p class="item-detail__condition">
                <span class="condition-label">商品の状態</span>
                <span class="condition-value">{{ $item->condition }}</span>
            </p>
            <div class="comment-list">
                <h3 class="comment-title">コメント ({{ $item->comments->count() }}) </h3>
                @foreach($item->comments as $comment)
                    <div class="comment">
                        <div class="comment-header">
                            <img src="{{ $comment->user->image ? asset('storage/' . $comment->user->image) : asset('images/default-user.png') }}" alt="{{ $comment->user->name }}" class="comment-user-img">
                            <span class="comment-user-name">{{ $comment->user->name }}</span>
                        </div>
                        <p class="comment-content">{{ $comment->content }}</p>
                    </div>
                @endforeach
                <form action="/item/{{ $item->id }}/comment" method="post" class="comment-form" data-item-id="{{ $item->id }}">
                    @csrf
                    <label class="comment-label">商品へのコメント</label>
                    <textarea cols="30" rows="10" name="content" class="textarea" {{ $sold ? 'disabled' : '' }}></textarea>
                    <div class="comment-error"></div>
                    @if($sold)
                        <button type="button" class="comment-button disabled-button" disabled>
                            コメントを送信する
                        </button>
                    @else
                        @if(auth()->check())
                            <button type="submit" class="comment-button">
                                コメントを送信する
                            </button>
                        @else
                            <a href="/login" class="comment-button no-login">
                                コメントを送信する
                            </a>
                        @endif
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.favorite-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const url = form.action;
            const token = form.querySelector('input[name="_token"]').value;
            const button = form.querySelector('.favorite-button');
            const icon = button.querySelector('.favorite-icon');
            const countSpan = button.querySelector('.favorite-count');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'added') {
                    icon.classList.add('liked');
                } else if(data.status === 'removed') {
                    icon.classList.remove('liked');
                }
                countSpan.textContent = data.count || '';
            });
        });
    });

    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const url = form.action;
            const token = form.querySelector('input[name="_token"]').value;
            const content = form.querySelector('textarea[name="content"]').value;
            const commentList = document.querySelector('.comment-list');
            const commentCountSpan = document.querySelector('.comment-count');
            const errorDiv = form.querySelector('.comment-error');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content: content }),
            })
            .then(async response => {
                if (response.status === 401 || response.redirected) {
                    window.location.href = '/login';
                    return;
                }
                if (!response.ok) {
                    const errorData = await response.json();
                    if (errorData.errors && errorData.errors.content) {
                        errorDiv.innerHTML = `<p class="error-message">${errorData.errors.content[0]}</p>`;
                    }
                    throw new Error('Validation failed');
                }
                return response.json();
            })
            .then(data => {
                errorDiv.innerHTML = '';
                form.querySelector('textarea[name="content"]').value = '';

                const newComment = document.createElement('div');
                newComment.classList.add('comment');
                newComment.innerHTML = `
                    <div class="comment-header">
                        <img src="${data.user_image}" alt="${data.user_name}" class="comment-user-img">
                        <span class="comment-user-name">${data.user_name}</span>
                    </div>
                    <p class="comment-content">${data.content}</p>
                `;
                const commentTitle = commentList.querySelector('.comment-title');
                commentTitle.insertAdjacentElement('afterend', newComment);

                commentCountSpan.textContent = data.comment_count;
                commentTitle.textContent = `コメント (${data.comment_count})`;
            })
            .catch(error => console.error('Error:', error));
        });
    });
    </script>
@endsection