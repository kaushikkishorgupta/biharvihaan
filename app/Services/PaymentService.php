<?php

namespace App\Services;

use App\Core\Database;
use Exception;

class PaymentService {
    private $keyId;
    private $keySecret;
    private $db;

    public function __construct() {
        $this->keyId = env('RAZORPAY_KEY_ID', 'rzp_test_BiharVihaan');
        $this->keySecret = env('RAZORPAY_KEY_SECRET', 'bihar_vihaan_secret_key');
        $this->db = Database::getInstance();
    }

    /**
     * Create a Razorpay Order
     * @param float $amount Amount in Rupees
     * @param string $receiptId Internal invoice/receipt ID
     * @param array $notes Optional metadata notes
     * @return array Order data from Razorpay API
     */
    public function createRazorpayOrder($amount, $receiptId, $notes = []) {
        $amountInPaise = intval($amount * 100);
        
        // If key secret is not set, simulate successful order creation
        if (empty($this->keySecret) || $this->keyId === 'rzp_test_BiharVihaan') {
            return [
                'id' => 'order_mock_' . uniqid(),
                'entity' => 'order',
                'amount' => $amountInPaise,
                'amount_paid' => 0,
                'amount_due' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => $receiptId,
                'status' => 'created',
                'attempts' => 0,
                'notes' => $notes,
                'created_at' => time()
            ];
        }

        $url = 'https://api.razorpay.com/v1/orders';
        $postData = [
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'receipt' => $receiptId,
            'notes' => $notes
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->keyId . ':' . $this->keySecret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception("Razorpay Order Creation Error: " . $err);
        }
        curl_close($ch);

        $data = json_decode($response, true);
        if (isset($data['error'])) {
            throw new Exception("Razorpay API Error: " . ($data['error']['description'] ?? 'Unknown Error'));
        }

        return $data;
    }

    /**
     * Verify payment signature from client side checkout
     */
    public function verifyPaymentSignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature) {
        if (empty($this->keySecret) || $this->keyId === 'rzp_test_BiharVihaan') {
            // Mock mode always succeeds
            return true;
        }

        $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $this->keySecret);
        return hash_equals($expectedSignature, $razorpaySignature);
    }

    /**
     * Log the payment transaction details into the database
     */
    public function logPayment($userId, $orderId, $transactionId, $amount, $gateway, $status, $refType, $refId) {
        $sql = "INSERT INTO payments (user_id, order_id, transaction_id, amount, gateway, status, reference_type, reference_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            $userId,
            $orderId,
            $transactionId,
            $amount,
            $gateway,
            $status,
            $refType,
            $refId
        ]);
    }

    /**
     * Calculate GST details on an order item amount
     * @param float $totalAmount Total price of item inclusive of GST
     * @param float $gstRate Standard GST rate percentage (e.g. 18.00)
     * @return array [taxable_value, cgst, sgst, rate]
     */
    public function calculateGst($totalAmount, $gstRate = 18.00) {
        // formula: Taxable Value = Total Amount / (1 + GST Rate / 100)
        $rateDecimal = $gstRate / 100;
        $taxableValue = $totalAmount / (1 + $rateDecimal);
        $gstAmount = $totalAmount - $taxableValue;
        
        // Split CGST (Central GST) and SGST (State GST) equally (9% each for 18% GST)
        $cgst = $gstAmount / 2;
        $sgst = $gstAmount / 2;

        return [
            'taxable_value' => round($taxableValue, 2),
            'gst_amount' => round($gstAmount, 2),
            'cgst' => round($cgst, 2),
            'sgst' => round($sgst, 2),
            'rate' => $gstRate
        ];
    }

    /**
     * Generate HTML Invoice template with professional GST summary
     */
    public function generateHtmlInvoice($order, $items) {
        $companyName = "Bihar Vihaan Ecosystems Pvt. Ltd.";
        $gstin = env('COMPANY_GSTIN', '10AAAAA1111A1Z1');
        $address = "Maurya Lok Complex, Block C, Patna, Bihar - 800001";
        
        $subtotal = $order['subtotal'];
        $gst_amount = $order['gst_amount'];
        $total = $order['total_price'];

        $html = '<div style="font-family: Arial, sans-serif; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">';
        
        // Header
        $html .= '<div style="display: flex; justify-content: space-between; border-bottom: 2px solid #14b8a6; padding-bottom: 15px;">';
        $html .= '<div>';
        $html .= '<h2 style="color: #14b8a6; margin: 0;">BIHAR VIHAAN</h2>';
        $html .= '<p style="font-size: 12px; margin: 5px 0 0 0; color: #666;">' . $companyName . '<br>' . $address . '<br><strong>GSTIN:</strong> ' . $gstin . '</p>';
        $html .= '</div>';
        $html .= '<div style="text-align: right;">';
        $html .= '<h3 style="margin: 0; color: #555;">TAX INVOICE</h3>';
        $html .= '<p style="font-size: 12px; margin: 5px 0 0 0; color: #666;">';
        $html .= '<strong>Invoice No:</strong> BV-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . '<br>';
        $html .= '<strong>Date:</strong> ' . date('d-m-Y', strtotime($order['created_at'])) . '<br>';
        $html .= '<strong>Status:</strong> <span style="color: green; font-weight: bold;">' . strtoupper($order['payment_status']) . '</span>';
        $html .= '</p>';
        $html .= '</div>';
        $html .= '</div>';

        // Billing Details
        $html .= '<div style="margin: 20px 0; display: flex; justify-content: space-between; font-size: 13px;">';
        $html .= '<div>';
        $html .= '<strong>Billed To:</strong><br>';
        $html .= htmlspecialchars($order['billing_name']) . '<br>';
        $html .= htmlspecialchars($order['billing_phone']) . '<br>';
        $html .= htmlspecialchars($order['billing_email']) . '<br>';
        $html .= nl2br(htmlspecialchars($order['billing_address']));
        $html .= '</div>';
        $html .= '<div style="text-align: right;">';
        $html .= '<strong>Payment Gateway:</strong> Razorpay UPI/Card<br>';
        $html .= '<strong>Order Ref:</strong> ' . ($order['razorpay_order_id'] ?? 'N/A') . '<br>';
        $html .= '<strong>Transaction Ref:</strong> ' . ($order['razorpay_payment_id'] ?? 'N/A');
        $html .= '</div>';
        $html .= '</div>';

        // Table
        $html .= '<table style="width: 100%; border-collapse: collapse; font-size: 13px; margin: 20px 0;">';
        $html .= '<thead style="background-color: #f3f4f6; text-align: left;">';
        $html .= '<tr>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Product / Service Description</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Unit Price (Excl. GST)</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd; text-align: center;">Qty</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd; text-align: center;">GST %</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total (Incl. GST)</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($items as $item) {
            $gst_info = $this->calculateGst($item['price'] * $item['quantity'], $item['gst_rate'] ?? 18.00);
            $unit_price_excl = round($gst_info['taxable_value'] / $item['quantity'], 2);
            
            $html .= '<tr>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . htmlspecialchars($item['name']) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: right;">₹' . number_format($unit_price_excl, 2) . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $item['quantity'] . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . ($item['gst_rate'] ?? 18.00) . '%</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: right;">₹' . number_format($item['price'] * $item['quantity'], 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        // Totals & Taxes Breakup
        $html .= '<div style="display: flex; justify-content: space-between; font-size: 13px; border-top: 1px solid #ddd; padding-top: 15px;">';
        $html .= '<div>';
        $html .= '<strong>GST Breakup Summary:</strong><br>';
        $cgst_split = round($gst_amount / 2, 2);
        $sgst_split = round($gst_amount / 2, 2);
        $html .= 'Central GST (CGST 9% Avg): ₹' . number_format($cgst_split, 2) . '<br>';
        $html .= 'State GST (SGST 9% Avg): ₹' . number_format($sgst_split, 2) . '<br>';
        $html .= 'Total GST Tax Liability: ₹' . number_format($gst_amount, 2);
        $html .= '</div>';
        $html .= '<div style="text-align: right; width: 250px;">';
        $html .= '<div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>Taxable Subtotal:</span><span>₹' . number_format($subtotal, 2) . '</span></div>';
        $html .= '<div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>Total GST:</span><span>₹' . number_format($gst_amount, 2) . '</span></div>';
        $html .= '<div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; border-top: 1px solid #ccc; padding-top: 5px; color: #14b8a6;"><span>Total Payable:</span><span>₹' . number_format($total, 2) . '</span></div>';
        $html .= '</div>';
        $html .= '</div>';

        // Footer terms
        $html .= '<div style="margin-top: 30px; font-size: 11px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 15px;">';
        $html .= 'This is a computer-generated tax invoice. No signature required. Subject to Patna jurisdiction. Thank you for supporting Bihar artisans and services!';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Coupon Code validation
     */
    public function validateCoupon($code) {
        $sql = "SELECT * FROM coupons WHERE code = ? AND expires_at >= CURDATE() LIMIT 1";
        $coupon = $this->db->queryRow($sql, [$code]);
        if ($coupon) {
            return [
                'success' => true,
                'code' => $coupon['code'],
                'discount_type' => $coupon['discount_type'],
                'value' => floatval($coupon['value'])
            ];
        }
        return ['success' => false, 'message' => 'Invalid or expired coupon code.'];
    }

    /**
     * Vendor Payout and Commission Splits calculation
     */
    public function calculateCommission($totalPrice, $vendorUserId) {
        $sql = "SELECT commission_rate FROM vendor_profiles WHERE user_id = ? LIMIT 1";
        $vendor = $this->db->queryRow($sql, [$vendorUserId]);
        $rate = $vendor ? floatval($vendor['commission_rate']) : 10.00; // defaults to 10% flat

        $commissionAmount = $totalPrice * ($rate / 100);
        $vendorPayout = $totalPrice - $commissionAmount;

        return [
            'vendor_payout' => round($vendorPayout, 2),
            'admin_commission' => round($commissionAmount, 2),
            'rate' => $rate
        ];
    }
}
