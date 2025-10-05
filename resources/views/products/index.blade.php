<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add New Product</a>
        
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Rs. {{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>