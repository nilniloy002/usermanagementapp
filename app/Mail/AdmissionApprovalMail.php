<?php

namespace Vanguard\Mail;

use Vanguard\StudentAdmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $invoicePdf;

    /**
     * Create a new message instance.
     */
    public function __construct(StudentAdmission $student)
    {
        $this->student = $student;
        $this->invoicePdf = $this->generateInvoicePdf($student);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $ccAddresses = [
        'devjit.sts@gmail.com',
        // 'another.email@sts.institute',
    ];
        return $this->from('regmockteststs@sts.institute', 'STS Institute')
                    ->cc($ccAddresses)
                    ->subject('Admission Approved - ' . $this->student->student_id)
                    ->view('emails.admission-approval')
                    ->attachData($this->invoicePdf, 'invoice-' . $this->student->student_id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Generate invoice PDF
     */
    private function generateInvoicePdf($student)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('student'));
        return $pdf->output();
    }
}