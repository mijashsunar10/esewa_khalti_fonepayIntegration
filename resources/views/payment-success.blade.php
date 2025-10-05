<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <h2>Payment Successful!</h2>
            <p>Thank you for purchasing {{ $product->name }}</p>
            <p>Transaction ID: {{ $transaction['transaction_uuid'] ?? 'N/A' }}</p>
            <p>Amount: Rs. {{ number_format($transaction['total_amount'] ?? 0, 2) }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Back to Products</a>
        </div>
    </div>
</body>
</html>