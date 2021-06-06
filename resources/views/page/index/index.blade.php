@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="card-header">Fire</h1>

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

                    <div class="card-body">
                        <table class="table-bordered">
                            <thead>
                            <tr>
                                <th>Товар</th>
                                @foreach($categories as $category)
                                    <th>{{$category->title}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($products as $single)
                                <tr>
                                    <td><a href="{{route('categories.show', $single->id)}}">{{$single->title}}</a></td>
                                    @foreach($categories as $category)
                                        <td class="text-center">
                                            @if($single->categories->contains($category->id))
                                                ✓
                                            @endif
                                        </td>
                                    @endforeach
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
