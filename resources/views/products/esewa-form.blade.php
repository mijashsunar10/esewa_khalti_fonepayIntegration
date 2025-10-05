<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .redirect-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #55A54F;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn {
            padding: 12px 24px;
            background: #55A54F;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn:hover {
            background: #4a9345;
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <div class="spinner"></div>
        <h3>Redirecting to eSewa...</h3>
        <p>Please wait while we redirect you to eSewa payment gateway.</p>
        
        <form id="esewaForm" action="{{ $esewa_url }}" method="POST">
            @foreach($data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>

        <p>If you are not redirected automatically, click the button below:</p>
        <button onclick="document.getElementById('esewaForm').submit()" class="btn">
            Proceed to eSewa
        </button>
    </div>

    <script>
        // Auto-submit form after 2 seconds
        setTimeout(function() {
            document.getElementById('esewaForm').submit();
        }, 2000);
    </script>
</body>
</html>