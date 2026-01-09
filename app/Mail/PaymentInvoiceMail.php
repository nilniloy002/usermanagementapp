<?php

namespace Vanguard\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Vanguard\StudentPayment;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(StudentPayment $payment)
    {
        $this->payment = $payment;
        
        // Generate PDF content
        $this->pdfContent = Pdf::loadView('pdf.payment-invoice-pdf', [
            'payment' => $payment,
            'student' => $payment->studentAdmission
        ])->output();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $ccAddresses = [
            'devjit.sts@gmail.com',
        ];
        $studentName = $this->payment->studentAdmission->name ?? 'Student';
        $invoiceNumber = $this->payment->id;
        
        return $this->subject("Payment Invoice #{$invoiceNumber} - STS Institute")
            ->cc($ccAddresses)
            ->view('emails.payment-invoice-email')
            ->attachData($this->pdfContent, "payment-invoice-{$invoiceNumber}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}