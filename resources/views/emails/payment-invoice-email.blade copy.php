<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice - STS Institute</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            -webkit-font-smoothing: antialiased;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #192335 0%, #2d3748 100%);
            padding: 8px 5px;
            text-align: center;
            border-bottom: 4px solid #f38020;
        }
        
        .logo {
            margin-top: 10px;
            max-width: 80px;
            height: auto;
        }
        
        .brand-text {
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .tagline {
            color: #f38020;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .content {
            padding: 30px 25px;
        }
        
        .greeting {
            font-size: 14px;
            font-weight: 400;
            color: #192335;
            margin-bottom: 20px;
            line-height: 28px;
        }
        
        .student-name {
            color: #f38020;
            font-weight: 700;
        }
        
        .invoice-info-card {
            background: #f8f9fa;
            border-left: 4px solid #f38020;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #dee2e6;
        }
        
        .invoice-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .invoice-label {
            font-weight: 600;
            color: #192335;
            font-size: 14px;
        }
        
        .invoice-value {
            font-weight: 700;
            color: #192335;
            font-size: 14px;
        }
        
        .amount-value {
            color: #28a745;
            font-weight: 700;
        }
        
        .due-amount {
            color: #dc3545;
        }
        
        .payment-status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-partial {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-pending {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .payment-details {
            margin: 25px 0;
            padding: 15px;
            background: #e8f4fd;
            border-radius: 6px;
            border: 1px solid #b8daff;
        }
        
        .section-title {
            color: #192335;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f38020;
            display: inline-block;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        
        .detail-item {
            font-size: 14px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        
        .detail-value {
            color: #192335;
            font-weight: 500;
        }
        
        .attachment-section {
            margin: 25px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 2px dashed #dee2e6;
        }
        
        .attachment-icon {
            font-size: 24px;
            color: #f38020;
            margin-bottom: 10px;
        }
        
        .download-btn {
            display: inline-block;
            background: #f38020;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        
        .download-btn:hover {
            background: #e6731c;
        }
        
        .important-note {
            margin: 25px 0;
            padding: 15px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
        }
        
        .note-title {
            color: #856404;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .note-content {
            color: #856404;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .contact-section {
            margin: 25px 0;
        }
        
        .contact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .contact-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
            color: #192335;
            font-weight: 500;
            font-size: 14px;
        }
        
        .contact-table tr:last-child td {
            border-bottom: none;
        }
        
        .contact-department {
            color: #192335;
            font-weight: 600;
            width: 60%;
        }
        
        .contact-number {
            color: #f38020;
            font-weight: 700;
            text-align: right;
        }
        
        .social-section {
            margin: 20px 0;
        }
        
        .social-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .social-button {
            background: #192335;
            color: #ffffff;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            font-size: 12px;
            flex: 1;
            min-width: 100px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .social-button:hover {
            background: #2d3748;
        }
        
        .signature {
            font-size: 14px;
            font-weight: 600;
            margin: 25px 0;
            color: #192335;
        }
        
        .footer {
            background: #192335;
            color: white;
            padding: 15px 20px;
            text-align: center;
            border-top: 4px solid #f38020;
        }
        
        .copyright {
            font-size: 12px;
            color: #adb5bd;
        }
        
        .footer-links {
            margin-top: 10px;
        }
        
        .footer-link {
            color: #f38020;
            text-decoration: none;
            font-size: 12px;
            margin: 0 10px;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .content {
                padding: 20px 15px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .social-buttons {
                flex-direction: column;
            }
            
            .social-button {
                min-width: auto;
                margin-bottom: 5px;
            }
            
            .invoice-row {
                flex-direction: column;
            }
            
            .invoice-value {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="https://sourceforces-img.sgp1.cdn.digitaloceanspaces.com/sts-logo.png" alt="STS Institute" class="logo">
            <div class="tagline">PAYMENT INVOICE RECEIPT</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <span class="student-name">{{ $payment->studentAdmission->name ?? 'Student' }}</span>,<br>
                This email confirms that we have received your payment for <strong>{{ $payment->payment_category }}</strong>.
                Please find the payment details below:
            </div>
            
            <!-- Invoice Info Card -->
            <div class="invoice-info-card">
                <div class="invoice-row">
                    <span class="invoice-label">Invoice Reference:</span>
                    <span class="invoice-value">#{{ $payment->id }}</span>
                </div>
                <div class="invoice-row">
                    <span class="invoice-label">Date & Time:</span>
                    <span class="invoice-value">{{ $payment->created_at->format('F d, Y - h:i A') }}</span>
                </div>
                <div class="invoice-row">
                    <span class="invoice-label">Payment Status:</span>
                    <span class="payment-status-badge {{ 
                        $payment->due_amount <= 0 ? 'status-paid' : 
                        ($payment->deposit_amount > 0 ? 'status-partial' : 'status-pending') 
                    }}">
                        {{ $payment->due_amount <= 0 ? 'FULLY PAID' : ($payment->deposit_amount > 0 ? 'PARTIALLY PAID' : 'PENDING') }}
                    </span>
                </div>
                <div class="invoice-row">
                    <span class="invoice-label">Total Due:</span>
                    <span class="invoice-value due-amount">৳{{ number_format($payment->due_amount, 2) }}</span>
                </div>
                <div class="invoice-row">
                    <span class="invoice-label">Amount Paid:</span>
                    <span class="invoice-value amount-value">৳{{ number_format($payment->deposit_amount, 2) }}</span>
                </div>
                @if($payment->due_amount > 0)
                <div class="invoice-row">
                    <span class="invoice-label">Balance Due:</span>
                    <span class="invoice-value due-amount">৳{{ number_format($payment->due_amount - $payment->deposit_amount, 2) }}</span>
                </div>
                @endif
            </div>
            
            <!-- Payment Details -->
            <div class="payment-details">
                <div class="section-title">Payment Information</div>
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Payment Method:</span><br>
                        <span class="detail-value">{{ $payment->payment_method_name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Received By:</span><br>
                        <span class="detail-value">{{ $payment->payment_received_by ?? 'System' }}</span>
                    </div>
                    @if($payment->transaction_id)
                    <div class="detail-item">
                        <span class="detail-label">Transaction ID:</span><br>
                        <span class="detail-value">{{ $payment->transaction_id }}</span>
                    </div>
                    @endif
                    @if($payment->serial_number)
                    <div class="detail-item">
                        <span class="detail-label">Serial Number:</span><br>
                        <span class="detail-value">{{ $payment->serial_number }}</span>
                    </div>
                    @endif
                    @if($payment->purpose)
                    <div class="detail-item">
                        <span class="detail-label">Purpose:</span><br>
                        <span class="detail-value">{{ $payment->purpose }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Student Information -->
            @if($payment->studentAdmission)
            <div class="payment-details">
                <div class="section-title">Student Information</div>
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Student ID:</span><br>
                        <span class="detail-value">{{ $payment->studentAdmission->student_id ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Application No:</span><br>
                        <span class="detail-value">{{ $payment->studentAdmission->application_number }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Course:</span><br>
                        <span class="detail-value">{{ $payment->studentAdmission->course->course_name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Batch:</span><br>
                        <span class="detail-value">{{ $payment->studentAdmission->batch_code ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Attachment Section -->
            <div class="attachment-section">
                <div class="attachment-icon">📎</div>
                <div class="section-title">Payment Invoice Attached</div>
                <p style="font-size: 14px; color: #495057; margin: 10px 0;">
                    Your official payment invoice is attached to this email as a PDF document. 
                    Please keep this invoice for your records.
                </p>
                <p style="font-size: 14px; color: #495057; margin: 10px 0;">
                    <strong>File Name:</strong> payment-invoice-{{ $payment->id }}.pdf
                </p>
            </div>
            
            <!-- Important Notes -->
            <div class="important-note">
                <div class="note-title">📋 Important Notes:</div>
                <div class="note-content">
                    1. Please keep this email and the attached invoice for future reference.<br>
                    2. This is a computer-generated invoice and does not require a signature.<br>
                    3. For any discrepancies, please contact our accounts department within 7 days.<br>
                    4. All payments are non-refundable as per institute policy.<br>
                    5. For installment payments, please ensure timely payment of remaining dues.
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-section">
                <div class="section-title">📞 Need Assistance?</div>
                <table class="contact-table">
                    <tr>
                        <td class="contact-department">Accounts & Billing Department</td>
                        <td class="contact-number">01914-442202</td>
                    </tr>
                    <tr>
                        <td class="contact-department">Student Support Desk</td>
                        <td class="contact-number">01914-442203</td>
                    </tr>
                    <tr>
                        <td class="contact-department">Course-specific Queries</td>
                        <td class="contact-number">01914-442204</td>
                    </tr>
                    <tr>
                        <td class="contact-department">Emergency Contact</td>
                        <td class="contact-number">01914-442205</td>
                    </tr>
                </table>
            </div>
            
            <!-- Social Media -->
            <div class="social-section">
                <div class="section-title">🌐 Stay Connected</div>
                <div class="social-buttons">
                    <a href="https://sts.institute/" class="social-button" target="_blank">Website</a>
                    <a href="https://www.facebook.com/TrainingSTS/" class="social-button" target="_blank">Facebook</a>
                    <a href="https://www.instagram.com/sts.it/" class="social-button" target="_blank">Instagram</a>
                    <a href="https://www.youtube.com/@STSinstitute" class="social-button" target="_blank">YouTube</a>
                </div>
            </div>
            
            <!-- Signature -->
            <div class="signature">
                Warm Regards,<br>
                <strong>Accounts Department</strong><br>
                STS Institute - Secret to Success
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="copyright">
                © {{ date('Y') }} STS Institute. All rights reserved.
            </div>
            <div class="footer-links">
                <a href="https://sts.institute/privacy" class="footer-link" target="_blank">Privacy Policy</a> |
                <a href="https://sts.institute/terms" class="footer-link" target="_blank">Terms of Service</a> |
                <a href="https://sts.institute/contact" class="footer-link" target="_blank">Contact Us</a>
            </div>
        </div>
    </div>
</body>
</html>