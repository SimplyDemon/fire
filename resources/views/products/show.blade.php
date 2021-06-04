@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="card-header">{{$single->title}}</h1>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Session::has('message'))
                        <li class="list-group-item">{!! session('message') !!}</li>
                    @endif
                    <div class="container">
                        <h6>Цена</h6>
                        <p>{{$single->price}}</p>
                        @if($single->description)
                            <h6>Описание</h6>
                            <p>{{$single->description}}</p>
                        @endif
                        <h6>Категории</h6>
                        <p>
                            @foreach ($single->categories as $category)
                                {{ $category->title }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
