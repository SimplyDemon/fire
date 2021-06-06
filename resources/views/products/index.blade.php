@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="card-header">Товары</h1>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (Session::has('message'))
                            <li class="list-group-item">{!! session('message') !!}</li>
                        @endif
                    </div>
                    <a href="{{route('products.create')}}">
                        <button>Добавить продукт</button>
                    </a>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Краткое описание</th>
                                <th>Цена</th>
                                <th>Категории</th>
                                <th>Удалить</th>
                                <th>Редактировать</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($all as $single)
                                <tr>
                                    <td><a href="{{route('products.show', $single->id)}}">{{$single->title}}</a></td>
                                    <td>{{$single->description}}</td>
                                    <td>{{$single->price}}</td>
                                    <td>
                                        @foreach ($single->categories as $category)
                                            <a href="{{route('categories.show', $category)}}">{{ $category->title }}{{ !$loop->last ? ', ' : '' }}</a>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form method="post" action="{{route('products.destroy', $single->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{$single->id}}">
                                            <button type="submit">Удалить</button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $single->id) }}">
                                            <button type="submit">Редактировать</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection
