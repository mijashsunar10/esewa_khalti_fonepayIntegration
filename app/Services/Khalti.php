<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class Khalti implements PaymentGatewayInterface
{
    public $amount;
    public $base_url;
    public $purchase_order_id;
    public $purchase_order_name;
    public $inquiry_response;

    /*
    |--------------------------------------------------------------------------
    | Customer Detail
    |--------------------------------------------------------------------------
    */
    public $customer_name;
    public $customer_phone;
    public $customer_email;

    public function __construct()
    {
        $this->base_url = config('khalti.base_url');
    }

    public function byCustomer($name, $email, $phone)
    {
        $this->customer_name = $name;
        $this->customer_email = $email;
        $this->customer_phone = $phone;
        return $this;
    }

    /**
     * Function to perform some logic before payment process
     */
    public function pay(float $amount, $return_url, $purchase_order_id, $purchase_order_name)
    {
        $this->purchase_order_id = $purchase_order_id;
        $this->purchase_order_name = $purchase_order_name;
        return $this->initiate($amount, $return_url);
    }

    /**
     * Initiate Payment Gateway Transaction
     */
    public function initiate(float $amount, $return_url, ?array $arguments = null)
    {
        // Convert amount to paisa (Khalti requires amount in paisa)
        $this->amount = $amount * 100;
        
        $process_url = $this->base_url . 'epayment/initiate/';

        $data = [
            "return_url" => $return_url,
            "website_url" => url('/'),
            "amount" => $this->amount,
            "purchase_order_id" => $this->purchase_order_id,
            "purchase_order_name" => $this->purchase_order_name,
            "customer_info" => [
                "name" => $this->customer_name ?? 'Customer',
                "email" => $this->customer_email ?? 'customer@example.com',
                "phone" => $this->customer_phone ?? '9800000000'
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Key ' . config('khalti.secret_key'),
        ])->post($process_url, $data);

        if ($response->successful()) {
            $body = $response->json();
            return redirect()->away($body['payment_url']);
        } else {
            throw new Exception('Khalti transaction failed: ' . $response->body());
        }
    }

    /**
     * Success status of payment transaction
     */
    public function isSuccess(array $inquiry, ?array $arguments = null): bool
    {
        return ($inquiry['status'] ?? null) === 'Completed';
    }

    /**
     * Requested amount to be registered
     */
    public function requestedAmount(array $inquiry, ?array $arguments = null): float
    {
        return ($inquiry['total_amount'] ?? 0) / 100; // Convert back to rupees
    }

    /**
     * Payment status lookup request
     */
    public function inquiry($transaction_id, ?array $arguments = null): array
    {
        $process_url = $this->base_url . 'epayment/lookup/';
        
        $payload = [
            'pidx' => $transaction_id
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Key ' . config('khalti.secret_key'),
        ])->post($process_url, $payload);

        $this->inquiry_response = $response->json();
        return $this->inquiry_response;
    }
}