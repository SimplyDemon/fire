@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="card-header">Добавить категорию</h1>
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

                    <form method="post" action="{{ route( 'categories.store' ) }}" class="p-4">
                        @csrf
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{old('title','')}}"
                                   required>
                        </div>
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
