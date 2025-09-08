@extends('layouts.app')

@section('title', 'Создать пост')

@section('content')
    <h1 style="color: aliceblue">Новый пост</h1>
    <form method="POST" action="{{ url('/posts') }}">
        @csrf
        <p>
            <label>Заголовок:</label><br>
            <input  type="text" name="title" value={{ old('title') }}>
        </p>
        <p>
            <label>Содержимое:</label><br>
            <textarea name="content">{{ old('content') }}</textarea>
        </p>
        <button type="submit">Сохранить</button>
    </form>
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