<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vanguard\Admission;
use Vanguard\Course;
use Vanguard\Batch;
use Vanguard\Payment;
use Vanguard\Http\Controllers\Controller;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::with('payments')->latest()->get();
        return view('admissions.index', compact('admissions'));
    }

    public function show($id)
    {
        $admission = Admission::with('course', 'batch', 'payments')->find($id);

        if (!$admission) {
            return abort(404);
        }

        // Get the last due amount (total due - total paid - total discount)
        $lastDueAmount = $this->calculateLastDueAmount($admission);

        return view('admissions.show', compact('admission', 'lastDueAmount'));
    }

    public function create()
    {
        $courses = Course::all();
        $batches = Batch::all(); // Add this line to fetch batches
        return view('admissions.add-edit', compact('courses', 'batches'), ['edit' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('upload/student', 'public');
        }

        $admission = Admission::create([
            'bill_id' => 'STS' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'admission_date' => $request->admission_date,
            'student_name' => $request->student_name,
            'phone_number' => $request->phone_number,
            'guardian_phone_number' => $request->guardian_phone_number,
            'course_id' => $request->course_id,
            'batch_code' => $request->batch_code, // Add batch_code
            'photo' => $photoPath,
        ]);

        Payment::create([
            'bill_id' => $admission->bill_id,
            'payment_date' => $admission->admission_date,
            'paid_amount' => $request->paid_amount,
            'discount_amount' => $request->discount_amount,
            'payment_process' => $request->payment_process,
            'payment_method' => $request->payment_method,
            'next_due_date' => $request->next_due_date,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admissions.index')->with('success', 'Admission created successfully.');
    }

    public function edit($id)
    {
        $admission = Admission::with('course', 'batch', 'payments')->find($id);
        $courses = Course::all();
        $batches = Batch::all(); // Add this line to fetch batches
        return view('admissions.add-edit', compact('admission', 'courses', 'batches'), ['edit' => true]);
    }

    public function update(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $admission->photo;
        if ($request->hasFile('photo')) {
            if ($admission->photo) {
                Storage::disk('public')->delete($admission->photo);
            }
            $photoPath = $request->file('photo')->store('upload/student', 'public');
        }

        $admission->update([
            //'bill_id' => $request->bill_id,
            'date' => $request->date,
            'phone_number' => $request->phone_number,
            'guardian_phone_number' => $request->guardian_phone_number,
            'course_id' => $request->course_id,
            'batch_code' => $request->batch_code, // Add batch_code
            'photo' => $photoPath,
        ]);

        $payment = Payment::where('bill_id', $admission->bill_id)->first();
        if ($payment) {
            $payment->update([
                'payment_date' => $admission->admission_date,
                'paid_amount' => $request->paid_amount,
                'discount_amount' => $request->discount_amount,
                'payment_process' => $request->payment_process,
                'payment_method' => $request->payment_method,
                'next_due_date' => $request->next_due_date,
                'remarks' => $request->remarks,
            ]);
        }

        return redirect()->route('admissions.index')->with('success', 'Admission updated successfully.');
    }

    public function destroy($id)
    {
        $admission = Admission::findOrFail($id);
        $admission->delete();

        return redirect()->route('admissions.index')->with('success', 'Admission deleted successfully.');
    }

    public function print($id)
    {
        $admission = Admission::with('course', 'batch', 'payments')->find($id);
        return view('admissions.print', compact('admission'));
    }

    public function getCourseDetails($id)
    {
        $course = Course::find($id);

        if ($course) {
            return response()->json([
                'course_fee' => $course->course_fee,
                'admission_fee' => $course->admission_fee,
            ]);
        } else {
            return response()->json(['error' => 'Course not found'], 404);
        }
    }

    public function getBatchDetails($id)
    {
        $course = Course::find($id);
    
        if ($course) {
            $batchCodes = $course->batches()->pluck('batch_code');
    
            return response()->json([
                'batch_codes' => $batchCodes,
            ]);
        } else {
            return response()->json(['error' => 'Course not found'], 404);
        }
    }

        public function calculateLastDueAmount(Admission $admission)
        {
            $totalDue = $admission->course->course_fee + $admission->course->admission_fee;
            $totalPaid = $admission->payments->sum('paid_amount');
            $totalDiscount = $admission->payments->sum('discount_amount');

            return $totalDue - ($totalPaid + $totalDiscount);
        }

    public function collectDue(Request $request, $id)
    {
        dd($request->all());
        // $admission = Admission::findOrFail($id);
        try {
            $admission = Admission::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Admission not found.');
        }

        // $request->validate([
        //     // 'paid_amount' => 'required|numeric|min:0',
        //     // 'payment_method' => 'required|in:cash,bKash,Nagad,Bank',
        //     // 'payment_process' => 'required|in:Partial Paid,Full Paid,1st Installment,2nd Installment,3rd Installment,4th Installment,Admission Fee',
        //     // 'next_due_date' => 'required|date',
        //     // 'remarks' => 'nullable|string',
        // ]);

        $lastDueAmount = $this->calculateLastDueAmount($admission);

        // Validate if the paid amount is not greater than the last due amount
        if ($request->paid_amount > $lastDueAmount) {
            return redirect()->back()->with('error', 'Paid amount cannot be greater than the last due amount.');
        }

        Payment::create([
            'bill_id' => $admission->bill_id,
            'payment_date' => now(),
            'paid_amount' => $request->paid_amount,
            'discount_amount' => 0, // Assuming no discount for due collection
            'payment_method' => $request->payment_method,
            'payment_process' => $request->payment_process,
            'next_due_date' => $request->next_due_date,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admissions.show', $admission->id)->with('success', 'Due collected successfully.');
    }

    public function showDueCollectionForm($billId)
    {
        $lastDueAmount = $this->calculateLastDueAmountByBillId($billId);
        
        return view('admissions.due-collection', compact('billId', 'lastDueAmount'));
    }

    public function calculateLastDueAmountByBillId($billId)
    {
        $admission = Admission::where('bill_id', $billId)->with('course', 'payments')->first();

        if (!$admission) {
            abort(404);
        }

        return $this->calculateLastDueAmount($admission);
    }

    
}
