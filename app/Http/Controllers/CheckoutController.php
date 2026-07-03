<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\PendingPayment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function getBcvRate()
    {
        $rate = Setting::getVal('tasas', ['euroBCV' => 36.50]);
        return response()->json($rate);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->where('status', 'active')->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'El código de cupón ingresado no es válido o ha expirado.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'code' => $coupon->code,
            'discount_percentage' => $coupon->discount_percentage
        ]);
    }

    public function submitPayment(Request $request)
    {
        $request->validate([
            'item_name' => ['required', 'string'],
            'amount' => ['required', 'string'],
            'payment_method' => ['required', 'string', 'in:mobile,binance'],
            'comprobante' => ['required', 'image', 'max:2048'], // Max 2MB image
        ]);

        $user = Auth::user();

        // Handle File Upload
        $receiptPath = null;
        if ($request->hasFile('comprobante')) {
            // Stores locally on disk: storage/app/public/receipts
            $receiptPath = $request->file('comprobante')->store('receipts', 'public');
        }

        // Create Pending Payment Queue Record
        $payment = PendingPayment::create([
            'user_id' => $user->id,
            'item_name' => $request->item_name,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method === 'mobile' ? 'Pago Móvil' : 'Binance Pay',
            'receipt_path' => $receiptPath,
            'status' => 'en_revision'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Comprobante enviado! Un administrador verificará y te otorgará acceso pronto.'
        ]);
    }
}
