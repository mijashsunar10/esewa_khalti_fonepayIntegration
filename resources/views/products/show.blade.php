

<!DOCTYPE html>
<html>
<head>
    <title>{{ $product->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p class="lead">Rs. {{ number_format($product->price, 2) }}</p>
                <p>{{ $product->description }}</p>
                
                <a href="{{ route('khalti.checkout', $product) }}" class="btn btn-success btn-lg">
                    Pay with Khalti
                </a>

                  <a href="{{ route('khalti.checkout', $product) }}" class="btn payment-btn khalti-btn">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDIyQzE3LjUyMjggMjIgMjIgMTcuNTIyOCAyMiAxMkMyMiA2LjQ3NzE1IDE3LjUyMjggMiAxMiAyQzYuNDc3MTUgMiAyIDYuNDc3MTUgMiAxMkMyIDE3LjUyMjggNi40NzcxNSAyMiAxMiAyMloiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xMi43MTQzIDE2LjI4NTdDMTUuMDI4NiAxNi4yODU3IDE2LjI4NTcgMTQuNzE0MyAxNi4yODU3IDEyLjI4NTdDMTYuMjg1NyA5Ljg1NzE0IDE1LjAyODYgOC4yODU3MSAxMi43MTQzIDguMjg1NzFDMTEuMTQyOSA4LjI4NTcxIDkuODU3MTQgOS4xNDI4NiA5LjQyODU3IDEwLjcxNDNIMTIuMjg1N1YxMy44NTcxSDguNTcxNDNDOC41NzE0MyAxNC40Mjg2IDguNTcxNDMgMTUuMTQyOSA4Ljg1NzE0IDE1LjcxNDNDOS4yODU3MSAxNi41NzE0IDEwLjE0MjkgMTYuODU3MSAxMS4xNDI5IDE2Ljg1NzFDMTIuMTQyOSAxNi44NTcxIDEzIDE2LjQyODYgMTMuNDI4NiAxNS43MTQzSDE2LjI4NTdDMTUuNzE0MyAxNi4xNDI5IDE0LjQyODYgMTYuMjg1NyAxMi43MTQzIDE2LjI4NTdaIiBmaWxsPSIjNUMyRDkxIi8+Cjwvc3ZnPgo=" class="payment-icon" alt="Khalti">
                        Pay with Khalti
                    </a>
                
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
            </div>
        </div>
    </div>
</body>
</html>