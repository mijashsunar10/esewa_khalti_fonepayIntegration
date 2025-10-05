<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Products</h1>
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>Price: Rs. {{ number_format($product->price, 2) }}</strong></p>
                        <a href="{{ route('esewa.checkout', $product->slug) }}" class="btn btn-primary">Buy with eSewa</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>