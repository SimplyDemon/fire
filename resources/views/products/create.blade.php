@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="card-header">Добавить продукт</h1>
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

                    <form method="post" action="{{ route( 'products.store' ) }}" class="p-4">
                        @csrf
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{old('title','')}}"
                                   required>
                        </div>
                        <div class=form-group">
                            <label for="description">Описание</label>
                            <textarea class="form-control" name="description" id="description"
                                      rows="3">{{old('description','')}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input class="form-control" type="number" name="price" id="price"
                                   value="{{old('price',0)}}" required>
                        </div>
                        @if($categories)
                            <div class=form-group">
                                <label for="categories">Категории</label>
                                <select name="categories[]" id="categories" class="form-select" multiple="multiple"
                                        aria-label="multiple select example" required>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category->id}}" {{ (collect(old('categories'))->contains($category->id)) ? 'selected':'' }}>{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group py-2">
                            <button type="submit" class="btn btn-primary">
                                Добавить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
