@extends('layouts.site')


@section('content')
<a href="{{ route('home') }}">На головну</a>
    <!-- write here -->
     @if ($product)
     <p>{{ $product->name }}</p>
     @endif
@endsection