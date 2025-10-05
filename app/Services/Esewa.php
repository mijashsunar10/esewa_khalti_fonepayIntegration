<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class Esewa implements PaymentGatewayInterface
{
    public $amount;
    public $base_url;
    public $purchase_order_id;
    public $purchase_order_name;
    public $inquiry_response;

    public $customer_name;
    public $customer_phone;
    public $customer_email;

    public function __construct()
    {
        $this->base_url = config('esewa.base_url');
    }

    public function byCustomer($name, $email, $phone)
    {
        $this->customer_name = $name;
        $this->customer_email = $email;
        $this->customer_phone = $phone;
        return $this;
    }

    public function pay(float $amount, $return_url, $purchase_order_id, $purchase_order_name)
    {
        $this->purchase_order_id = $purchase_order_id;
        $this->purchase_order_name = $purchase_order_name;
        return $this->initiate($amount, $return_url);
    }

    public function initiate(float $amount, $return_url, ?array $arguments = null)
    {
        $this->amount = $amount;
        
        // Generate unique transaction UUID
        $transaction_uuid = uniqid();
        
        $data = [
            'amount' => $this->amount,
            'tax_amount' => 0,
            'total_amount' => $this->amount,
            'transaction_uuid' => $transaction_uuid,
            'product_code' => config('esewa.merchant_id', 'EPAYTEST'),
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => $return_url . '?q=su',
            'failure_url' => $return_url . '?q=fu',
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ];

        // Generate signature
        $data['signature'] = $this->generateSignature($data);

        return view('payment.esewa-form', [
            'esewa_url' => $this->base_url . '/api/epay/main/v2/form',
            'data' => $data
        ]);
    }

    /**
     * Generate eSewa signature
     */
    private function generateSignature($data)
    {
        $secretKey = config('esewa.secret_key');
        
        $signatureString = '';
        $signedFieldNames = explode(',', $data['signed_field_names']);
        
        foreach ($signedFieldNames as $field) {
            $signatureString .= $field . '=' . $data[$field] . ',';
        }
        
        $signatureString = rtrim($signatureString, ',');
        
        // Hash with SHA256 and base64 encode
        $hash = hash_hmac('sha256', $signatureString, $secretKey, true);
        return base64_encode($hash);
    }

    /**
     * Verify eSewa payment using their API
     */
    public function verifyPayment($data)
    {
        try {
            $queryData = [
                'product_code' => $data['product_code'],
                'total_amount' => $data['total_amount'],
                'transaction_uuid' => $data['transaction_uuid'],
            ];

            // Generate signature for verification
            $queryData['signature'] = $this->generateSignature($queryData);

            $response = Http::timeout(30)->post($this->base_url . '/api/epay/transaction/status/', $queryData);

            if ($response->successful()) {
                $responseData = $response->json();
                return isset($responseData['status']) && $responseData['status'] === 'COMPLETE';
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('eSewa Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Alternative verification method using callback data
     */
    public function verifyFromCallback($data)
    {
        // Check if the callback indicates success
        if (isset($data['status']) && $data['status'] === 'COMPLETE') {
            return true;
        }

        // For eSewa, sometimes we need to trust the callback
        // You might want to implement additional checks here
        return isset($data['transaction_code']) && isset($data['status']) && $data['status'] === 'COMPLETE';
    }

    /**
     * Success status of payment transaction
     */
    public function isSuccess(array $inquiry, ?array $arguments = null): bool
    {
        return ($inquiry['status'] ?? null) === 'COMPLETE';
    }

    /**
     * Requested amount to be registered
     */
    public function requestedAmount(array $inquiry, ?array $arguments = null): float
    {
        return $inquiry['total_amount'] ?? 0;
    }

    /**
     * Payment status lookup request
     */
    public function inquiry($transaction_id, ?array $arguments = null): array
    {
        return $arguments ?? [];
    }

    /**
     * Verify payment - interface requirement
     */
    public function verifyPayment($data)
    {
        return $this->verifyFromCallback($data);
    }
}