<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger text-center">
            <h2>Payment Failed!</h2>
            <p>There was an issue processing your payment for {{ $product->name }}</p>
            <p>Please try again later.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Back to Products</a>
        </div>
    </div>
</body>
</html>