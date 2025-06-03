@extends('layouts.site')


@section('content')

@if ($products->isNotEmpty()) 
    @foreach ($products as $product)
        <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
        <br>
    @endforeach
@endif

@endsection
