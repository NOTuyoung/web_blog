@extends('layouts.app')

@section('title', 'Создать пост')

@section('content')
    <h1 style="color: aliceblue">Редактирование поста</h1>
    <form method="POST" action="{{ url('/update/' . $post->id) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Заголовок:</label><br>
            <input value={{ $post->title }} type="text" name="title">
        </p>
        <p>
            <label>Содержимое:</label><br>
            <textarea name="content">{{ $post->content }}</textarea>
        </p>
        <button type="submit">Сохранить</button>
    </form>
    <a href="{{ url('/posts') }}">Назад к списку</a>
@endsection

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif