<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Payment;
use App\Services\Esewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EsewaController extends Controller
{
    public function checkout(Request $request, Product $product)
    {
        try {
            $esewa = new Esewa();
            
            // Set customer details
            $esewa->byCustomer(
                $request->customer_name ?? 'Test Customer',
                $request->customer_email ?? 'test@example.com',
                $request->customer_phone ?? '9800000000'
            );

            // Create pending payment record with unique transaction ID
            $transaction_id = 'esewa_' . uniqid();
            
            $payment = Payment::create([
                'product_id' => $product->id,
                'transaction_id' => $transaction_id,
                'amount' => $product->price,
                'status' => 'pending',
                'payment_method' => 'esewa',
                'customer_name' => $request->customer_name ?? 'Test Customer',
                'customer_email' => $request->customer_email ?? 'test@example.com',
                'customer_phone' => $request->customer_phone ?? '9800000000',
            ]);

            return $esewa->pay(
                $product->price,
                route('esewa.verification', ['product' => $product->slug, 'payment' => $payment->id]),
                $product->id,
                $product->name
            );
            
        } catch (\Exception $e) {
            Log::error('eSewa Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'eSewa payment initiation failed: ' . $e->getMessage());
        }
    }

    public function verification(Request $request, Product $product, Payment $payment)
    {
        try {
            Log::info('eSewa Callback Data:', $request->all());

            $esewa = new Esewa();
            $data = $request->all();

            // Check for failure first
            if ($request->has('q') && $request->q === 'fu') {
                $payment->update([
                    'status' => 'failed',
                    'esewa_response' => $data
                ]);

                return redirect()->route('payment.failed', $payment)
                    ->with('error', 'Payment was cancelled or failed.');
            }

            // Check for success
            if ($request->has('q') && $request->q === 'su') {
                // For eSewa sandbox, we'll consider it successful
                // In production, you should verify with eSewa API
                if (config('app.env') === 'local' || config('app.debug')) {
                    // Sandbox mode - auto verify
                    $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $data['transaction_uuid'] ?? $payment->transaction_id,
                        'esewa_response' => $data
                    ]);

                    return redirect()->route('payment.success', $payment)
                        ->with('success', 'Payment completed successfully via eSewa!');
                } else {
                    // Production mode - verify with eSewa
                    $isVerified = $esewa->verifyPayment($data);
                    
                    if ($isVerified) {
                        $payment->update([
                            'status' => 'completed',
                            'transaction_id' => $data['transaction_uuid'] ?? $payment->transaction_id,
                            'esewa_response' => $data
                        ]);

                        return redirect()->route('payment.success', $payment)
                            ->with('success', 'Payment completed successfully via eSewa!');
                    } else {
                        $payment->update([
                            'status' => 'failed',
                            'esewa_response' => $data
                        ]);

                        return redirect()->route('payment.failed', $payment)
                            ->with('error', 'Payment verification failed. Please contact support.');
                    }
                }
            }

            // If no query parameter, check for direct data
            if (!empty($data['transaction_code'])) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $data['transaction_code'] ?? $payment->transaction_id,
                    'esewa_response' => $data
                ]);

                return redirect()->route('payment.success', $payment)
                    ->with('success', 'Payment completed successfully via eSewa!');
            }

            // Default to failure if no conditions met
            $payment->update([
                'status' => 'failed',
                'esewa_response' => $data
            ]);

            return redirect()->route('payment.failed', $payment)
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('eSewa Verification Error: ' . $e->getMessage());
            
            $payment->update([
                'status' => 'failed',
                'esewa_response' => $request->all()
            ]);

            return redirect()->route('payment.failed', $payment)
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }

    /**
     * Manual verification endpoint for testing
     */
    public function manualVerify(Request $request, Payment $payment)
    {
        try {
            $esewa = new Esewa();
            $data = $request->all();

            $isVerified = $esewa->verifyPayment($data);
            
            if ($isVerified) {
                $payment->update([
                    'status' => 'completed',
                    'esewa_response' => $data
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}