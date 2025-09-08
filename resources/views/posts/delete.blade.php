@extends('layouts.app')

@section('title', 'Удаление поста')

@section('content')
    <h1>Удалить пост</h1>

    <p>Вы уверены, что хотите удалить пост: <strong>{{ $post->title }}</strong>?</p>
    <p>{{ $post->content }}</p>

    <form action="{{ url('/posts/' . $post->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" style="color: white; background: red; padding: 8px 12px; border: none; border-radius: 4px;">
            Удалить
        </button>
        <a href="{{ url('/posts') }}" style="margin-left: 10px;">Отмена</a>
    </form>
@endsection