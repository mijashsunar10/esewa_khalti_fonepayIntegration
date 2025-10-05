<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <h4 class="alert-heading">Payment Successful!</h4>
            <p>Thank you for your purchase. Your payment has been processed successfully via {{ strtoupper($payment->payment_method) }}.</p>
            <hr>
            <p class="mb-0">
                Transaction ID: {{ $payment->transaction_id }}<br>
                Amount: Rs. {{ number_format($payment->amount, 2) }}<br>
                Product: {{ $payment->product->name }}<br>
                Payment Method: {{ strtoupper($payment->payment_method) }}<br>
                Date: {{ $payment->updated_at->format('Y-m-d H:i:s') }}
            </p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Back to Products</a>
    </div>
</body>
</html>