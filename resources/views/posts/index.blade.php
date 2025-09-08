@extends('layouts.app')

@section('title', 'Список постов')

@section('content')
    <h1 style="color:yellow">Все посты</h1>
    <ul>
        @if(session('success'))
            <div style="color:green">{{ session('success') }}</div>
        @endif
        @foreach ($posts as $post)
            <li>
                <a href="{{ url('/posts/' . $post->id) }}" style="color: aliceblue">{{ $post->title }}</a>
                <form action="{{ url('/posts/' . $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red; border: none; background: none;">
                        Удалить
                    </button>
                </form>
                <a href="{{ url('/posts/' . 'update/' . $post->id) }}" 
                    style="color: yellow; text-decoration: none;">
                        Редактировать
                </a>

                <p style="color: aqua">Автор поста - {{ $post->user ? $post->user->name : 'Неизвестный'}}</p>
            </li>
        @endforeach
    </ul>

    <div>
        {{ $posts->links() }}
    </div>
    
    <a href="{{ url('/posts/create') }}" style="color:chartreuse">Добавить пост</a>
@endsection