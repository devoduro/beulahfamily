<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt - {{ $donation->donation_number ?? 'DON-' . str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .church-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        
        .church-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .church-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 24px;
            border-radius: 25px;
            display: inline-block;
        }
        
        .receipt-body {
            padding: 40px;
        }
        
        .receipt-number {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .receipt-number h2 {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .receipt-number .number {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
            font-family: 'Courier New', monospace;
        }
        
        .receipt-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .detail-section h3 {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        
        .detail-value {
            font-weight: 600;
            color: #1f2937;
        }
        
        .amount-highlight {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-highlight .label {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        
        .amount-highlight .amount {
            font-size: 36px;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .member-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
        }
        
        .guest-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #6b7280;
        }
        
        .receipt-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #e5e7eb;
        }
        
        .footer-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .thank-you {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .receipt-container {
                border: none;
                border-radius: 0;
                box-shadow: none;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .print-button:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Receipt</button>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="church-logo">
                🏛️
            </div>
            <div class="church-name">Beulah Family Church</div>
            <div class="church-subtitle">Building Lives, Transforming Communities</div>
            <div class="receipt-title">DONATION RECEIPT</div>
        </div>
        
        <!-- Body -->
        <div class="receipt-body">
            <!-- Receipt Number -->
            <div class="receipt-number">
                <h2>Receipt Number</h2>
                <div class="number">{{ $donation->receipt_number ?? $donation->donation_number ?? 'DON-' . str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            
            <!-- Amount Highlight -->
            <div class="amount-highlight">
                <div class="label">Total Donation Amount</div>
                <div class="amount">GHS {{ number_format($donation->amount, 2) }}</div>
            </div>
            
            <!-- Receipt Details -->
            <div class="receipt-details">
                <!-- Donation Information -->
                <div class="detail-section">
                    <h3>Donation Details</h3>
                    
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">{{ $donation->donation_date->format('F d, Y') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Type:</span>
                        <span class="detail-value">{{ ucwords(str_replace('_', ' ', $donation->donation_type)) }}</span>
                    </div>
                    
                    @if($donation->purpose)
                    <div class="detail-row">
                        <span class="detail-label">Purpose:</span>
                        <span class="detail-value">{{ $donation->purpose }}</span>
                    </div>
                    @endif
                    
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            <span class="status-badge status-{{ $donation->status }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </span>
                    </div>
                    
                    @if($donation->payment_method)
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value">{{ ucwords(str_replace('_', ' ', $donation->payment_method)) }}</span>
                    </div>
                    @endif
                    
                    @if($donation->reference_number)
                    <div class="detail-row">
                        <span class="detail-label">Reference:</span>
                        <span class="detail-value" style="font-family: monospace; font-size: 12px;">{{ $donation->reference_number }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Donor Information -->
                <div class="detail-section">
                    <h3>Donor Information</h3>
                    
                    @if($donation->member)
                        <div class="member-info">
                            <div class="detail-row">
                                <span class="detail-label">Member:</span>
                                <span class="detail-value">{{ $donation->member->first_name }} {{ $donation->member->last_name }}</span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Member ID:</span>
                                <span class="detail-value">{{ $donation->member->member_id }}</span>
                            </div>
                            
                            @if($donation->member->email)
                            <div class="detail-row">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">{{ $donation->member->email }}</span>
                            </div>
                            @endif
                            
                            @if($donation->member->phone)
                            <div class="detail-row">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">{{ $donation->member->phone }}</span>
                            </div>
                            @endif
                            
                            @if($donation->member->chapter)
                            <div class="detail-row">
                                <span class="detail-label">Chapter:</span>
                                <span class="detail-value">{{ $donation->member->chapter }}</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="guest-info">
                            <div class="detail-row">
                                <span class="detail-label">Name:</span>
                                <span class="detail-value">{{ $donation->donor_name }}</span>
                            </div>
                            
                            @if($donation->donor_email)
                            <div class="detail-row">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">{{ $donation->donor_email }}</span>
                            </div>
                            @endif
                            
                            @if($donation->donor_phone)
                            <div class="detail-row">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">{{ $donation->donor_phone }}</span>
                            </div>
                            @endif
                        </div>
                    @endif
                    
                    @if($donation->is_anonymous)
                    <div style="margin-top: 15px; padding: 10px; background: #fef3c7; border-radius: 8px; text-align: center;">
                        <span style="color: #92400e; font-weight: 600;">🔒 Anonymous Donation</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment Details (if Paystack) -->
            @if($donation->paystack_reference)
            <div class="detail-section">
                <h3>Payment Transaction Details</h3>
                
                @if($donation->net_amount && $donation->net_amount != $donation->amount)
                <div class="detail-row">
                    <span class="detail-label">Gross Amount:</span>
                    <span class="detail-value">GHS {{ number_format($donation->amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Transaction Fee:</span>
                    <span class="detail-value">GHS {{ number_format($donation->transaction_fee ?? 0, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Net Amount:</span>
                    <span class="detail-value">GHS {{ number_format($donation->net_amount, 2) }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Paystack Reference:</span>
                    <span class="detail-value" style="font-family: monospace; font-size: 12px;">{{ $donation->paystack_reference }}</span>
                </div>
                
                @if($donation->paystack_transaction_id)
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value" style="font-family: monospace; font-size: 12px;">{{ $donation->paystack_transaction_id }}</span>
                </div>
                @endif
                
                @if($donation->payment_channel)
                <div class="detail-row">
                    <span class="detail-label">Payment Channel:</span>
                    <span class="detail-value">{{ ucwords($donation->payment_channel) }}</span>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Notes -->
            @if($donation->notes)
            <div class="detail-section">
                <h3>Additional Notes</h3>
                <p style="color: #6b7280; line-height: 1.6;">{{ $donation->notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer">
            <div class="thank-you">Thank you for your generous donation!</div>
            <div class="footer-text">
                This receipt serves as acknowledgment of your donation to Beulah Family Church.<br>
                Please keep this receipt for your records and tax purposes.<br><br>
                
                <strong>Beulah Family Church</strong><br>
                Building Lives, Transforming Communities<br>
                Generated on: {{ now()->format('F d, Y \a\t g:i A') }}
            </div>
        </div>
    </div>
</body>
</html>
