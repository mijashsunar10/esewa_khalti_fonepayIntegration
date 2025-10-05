<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4 class="alert-heading">Payment Failed!</h4>
            <p>Your payment could not be processed. Please try again.</p>
            <hr>
            <p class="mb-0">
                Transaction ID: {{ $payment->transaction_id }}<br>
                Amount: Rs. {{ number_format($payment->amount, 2) }}<br>
                Product: {{ $payment->product->name }}
            </p>
        </div>
        <a href="{{ route('products.show', $payment->product) }}" class="btn btn-primary">Try Again</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
    </div>
</body>
</html>