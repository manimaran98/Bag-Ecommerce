@extends('admin.layout')

@section('title', 'Stock #'.$stock->stock_id)

@section('content')
<h1 class="h3 mb-4">{{ $stock->stock_name }}</h1>
<div class="row g-4">
    <div class="col-md-4">
        <img src="{{ asset('assets/stockImg/'.$stock->stock_img) }}" class="img-fluid rounded border" alt="">
    </div>
    <div class="col-md-8">
        <dl class="row">
            <dt class="col-sm-4">ID</dt><dd class="col-sm-8">{{ $stock->stock_id }}</dd>
            <dt class="col-sm-4">Brand</dt><dd class="col-sm-8">{{ $stock->stock_brand }}</dd>
            <dt class="col-sm-4">Category</dt><dd class="col-sm-8">{{ $stock->stock_category }}</dd>
            <dt class="col-sm-4">Quantity</dt><dd class="col-sm-8">{{ $stock->stock_quantity }}</dd>
            <dt class="col-sm-4">Price</dt><dd class="col-sm-8">RM {{ number_format((float) $stock->stock_price, 2) }}</dd>
            <dt class="col-sm-4">Description</dt><dd class="col-sm-8">{{ $stock->stock_description }}</dd>
        </dl>
        <a href="{{ route('admin.stock.edit', $stock) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
@endsection
