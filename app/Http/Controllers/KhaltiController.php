<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Payment;
use App\Services\Khalti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KhaltiController extends Controller
{
    public function checkout(Product $product)
    {
        try {
            $khalti = new Khalti();
            
            // Set customer details (you can get these from auth user or form)
            $khalti->byCustomer(
                'Test Customer', // Replace with actual customer name
                'test@example.com', // Replace with actual email
                '9800000000' // Replace with actual phone
            );

            return $khalti->pay(
                $product->price,
                route('khalti.verification', ['product' => $product->slug]),
                $product->id,
                $product->name
            );
            
        } catch (\Exception $e) {
            Log::error('Khalti Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function verification(Request $request, Product $product)
    {
        try {
            $khalti = new Khalti();
            
            // Get pidx from request
            $pidx = $request->input('pidx');
            
            if (!$pidx) {
                return redirect()->route('products.show', $product)
                    ->with('error', 'Invalid payment response');
            }

            // Verify payment with Khalti
            $inquiry = $khalti->inquiry($pidx);
            
            // Save payment record
            $payment = Payment::create([
                'product_id' => $product->id,
                'transaction_id' => $pidx,
                'amount' => $product->price,
                'status' => $khalti->isSuccess($inquiry) ? 'completed' : 'failed',
                'customer_name' => 'Test Customer', // Replace with actual data
                'customer_email' => 'test@example.com', // Replace with actual data
                'customer_phone' => '9800000000', // Replace with actual data
                'khalti_response' => $inquiry
            ]);

            if ($khalti->isSuccess($inquiry)) {
                return redirect()->route('payment.success', $payment)
                    ->with('success', 'Payment completed successfully!');
            } else {
                return redirect()->route('payment.failed', $payment)
                    ->with('error', 'Payment failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            Log::error('Khalti Verification Error: ' . $e->getMessage());
            return redirect()->route('products.show', $product)
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    public function success(Payment $payment)
    {
        return view('payment.success', compact('payment'));
    }

    public function failed(Payment $payment)
    {
        return view('payment.failed', compact('payment'));
    }
}