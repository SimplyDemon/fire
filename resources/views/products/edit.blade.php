@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="card-header">Редактировать продукт</h1>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors)
                        <ul class="list-group">
                            @foreach ($errors->all() as $error)
                                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if (Session::has('message'))
                        <li class="list-group-item">{!! session('message') !!}</li>
                    @endif

                    <form method="post" action="{{ route( 'products.update', ['product' => $single ] ) }}" class="p-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{$single->id}}">
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input class="form-control" type="text" name="title" id="title"
                                   value="{{old('title', $single->title)}}"
                                   required>
                        </div>
                        <div class=form-group">
                            <label for="description">Описание</label>
                            <textarea class="form-control" name="description" id="description"
                                      rows="3">{{old('description', $single->description)}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input class="form-control" type="number" name="price" id="price"
                                   value="{{old('price', $single->price)}}" required>
                        </div>

                        <div class=form-group">
                            <label for="categories">Категории</label>
                            <select name="categories[]" id="categories" class="form-select" multiple="multiple"
                                    aria-label="multiple select example" required>
                                @foreach($categories as $category)
                                    <option
                                        value="{{$category->id}}"
                                    @if(old('categories'))
                                        {{ (collect(old('categories'))->contains($category->id)) ? 'selected' : '' }}
                                        @else
                                        {{ ($single->categories->contains($category->id)) ? 'selected' : '' }}
                                        @endif
                                    >{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group py-2">
                            <input class="btn btn-primary" type="submit" name="method" value="Применить">
                            <input class="btn btn-primary" type="submit" name="method" value="Сохранить">
                            <button class="btn btn-primary" onclick="history.back(); return false;">Назад</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
