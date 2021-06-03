@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Редактировать категорию</div>
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

                    <form method="post" action="{{ route( 'categories.update', ['category' => $single ] ) }}"
                          class="p-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{$single->id}}">
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input class="form-control" type="text" name="title" id="title"
                                   value="{{old('title', $single->title)}}"
                                   required>
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
