<?php

namespace Vanguard\Http\Controllers;

use Vanguard\StudentAdmission;
use Vanguard\StudentPayment;
use Vanguard\Course;
use Vanguard\Batch;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Vanguard\Mail\AdmissionApprovalMail;
use Vanguard\Mail\PaymentInvoiceMail;
use Illuminate\Support\Facades\Auth;
use DB;

class StudentAdmissionController extends Controller
{
    /**
     * Display the student admission form
     */
    public function studentAdmissionFrontend()
    {
        $courses = Course::where('status', 'On')->get();
        return view('student-frontend.student-admission-form', compact('courses'));
    }

   
    public function store(Request $request)
    {
        Log::info('Admission form submitted', $request->all());

        try {
            $activeCourseIds = Course::where('status', 'On')->pluck('id')->toArray();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'dob' => 'required|date|before:today',
                'gender' => 'required|in:male,female',
                'mobile' => 'required|string|max:20',
                'emergency_mobile' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:1000',
                'educational_background' => 'required|in:SSC,HSC,bachelor,masters,others',
                'other_education' => 'nullable|required_if:educational_background,others|string|max:255',
                'academic_year' => 'required|string|max:50',
                'course_id' => 'required|in:' . implode(',', $activeCourseIds),
                'photo_data' => 'required|string',
                'payment_method' => 'required|in:cash,bkash,bank',
                'transaction_id' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255',
            ], [
                'photo_data.required' => 'Please take a student photo before submitting.',
                'dob.before' => 'Date of birth must be in the past.',
                'course_id.required' => 'Please select a course.',
                'course_id.in' => 'Please select a valid course.',
                'educational_background.required' => 'Please select your educational background.',
                'other_education.required_if' => 'Please specify your educational background.',
                'academic_year.required' => 'Please enter your academic year.',
            ]);

            // Payment method validation
            if ($request->payment_method === 'bkash' && empty($request->transaction_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction ID is required for bKash payments.',
                    'errors' => ['transaction_id' => ['Transaction ID is required for bKash payments.']]
                ], 422);
            }

            if ($request->payment_method === 'bank' && empty($request->serial_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Serial number is required for Bank payments.',
                    'errors' => ['serial_number' => ['Serial number is required for Bank payments.']]
                ], 422);
            }

            // Handle photo data
            $photoPath = null;
            if ($request->photo_data && $request->photo_data !== '{{ csrf_token() }}') {
                $photoPath = $this->saveBase64Image($request->photo_data);
            }

            // Use transaction to ensure both records are created
            DB::beginTransaction();

            try {
                // Create student admission record
                $admission = StudentAdmission::create([
                    'name' => $validated['name'],
                    'dob' => $validated['dob'],
                    'gender' => $validated['gender'],
                    'mobile' => $validated['mobile'],
                    'emergency_mobile' => $validated['emergency_mobile'],
                    'email' => $validated['email'],
                    'address' => $validated['address'],
                    'educational_background' => $validated['educational_background'],
                    'other_education' => $validated['educational_background'] === 'others' ? $validated['other_education'] : null,
                    'academic_year' => $validated['academic_year'],
                    'course_id' => $validated['course_id'],
                    'photo_data' => $photoPath,
                ]);

                // Create payment record
                StudentPayment::create([
                    'student_admission_id' => $admission->id,
                    'application_number' => $admission->application_number,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => $validated['transaction_id'] ?? null,
                    'serial_number' => $validated['serial_number'] ?? null,
                    'deposit_amount' => 0,
                    'discount_amount' => 0,
                    'due_amount' => 0,
                    'next_due_date' => null,
                    'remarks' => null,
                    'payment_received_by' => null,
                ]);

                DB::commit();

                Log::info('Admission created successfully', ['id' => $admission->id, 'application_number' => $admission->application_number]);

                return response()->json([
                    'success' => true,
                    'message' => 'Application submitted successfully!',
                    'application_number' => $admission->application_number,
                    'redirect_url' => route('admission.success', $admission->id)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Student admission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again.'
            ], 500);
        }
    }

    /**
     * Save base64 image to storage
     */
    private function saveBase64Image($base64Image)
    {
        if (strpos($base64Image, 'data:image') !== 0) {
            throw new \Exception('Invalid image data format');
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $image = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Invalid image type');
            }

            $image = base64_decode($image);
            if ($image === false) {
                throw new \Exception('Base64 decode failed');
            }
        } else {
            throw new \Exception('Invalid image data');
        }

        $filename = 'student_photos/' . Str::uuid() . '.' . $type;
        
        if (!Storage::disk('public')->exists('student_photos')) {
            Storage::disk('public')->makeDirectory('student_photos');
        }
        
        Storage::disk('public')->put($filename, $image);
        
        return $filename;
    }

    /**
     * Display success page
     */
    public function success($id)
    {
        $admission = StudentAdmission::with('course', 'payment')->findOrFail($id);
        return view('student-frontend.admission-success', compact('admission'));
    }

        /**
         * Admin: List all applications with filters
         */
        public function index(Request $request)
        {
            $query = StudentAdmission::with(['course', 'batch', 'payment'])
                ->where('status', 'approved')
                // ->latest();
                ->orderBy('created_at', 'desc');

            
            // Course filter
            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }
            
            // Batch filter
            if ($request->filled('batch_id')) {
                $query->where('batch_id', $request->batch_id);
            }
            
            // Payment status filter
            if ($request->filled('payment_status')) {
                if ($request->payment_status === 'due') {
                    $query->whereHas('payment', function($q) {
                        $q->where('due_amount', '>', 0);
                    });
                } elseif ($request->payment_status === 'paid') {
                    $query->whereHas('payment', function($q) {
                        $q->where('due_amount', '<=', 0);
                    });
                }
            }
            
            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('application_number', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            $applications = $query->paginate(20);
            
            // Get courses for filter
            $courses = Course::where('status', 'On')->get();
            
            // Get batches based on selected course
            $selectedCourseId = $request->course_id;
            $batches = collect();
            
            if ($selectedCourseId) {
                // Get batches only for the selected course
                $batches = Batch::where('course_id', $selectedCourseId)
                    ->where('status', 'On')
                    ->orderBy('batch_code', 'ASC')
                    ->get();
            } else {
                // Get all active batches with course names
                $batches = Batch::where('status', 'On')
                    ->with('course')
                    ->orderBy('batch_code', 'ASC')
                    ->get();
            }
            
            return view('admissions.student-index', compact('applications', 'courses', 'batches', 'selectedCourseId'));
        }

        /**
         * Get batches for a specific course (AJAX)
         */

       public function getBatchesByCourse($courseId)
    {
            try {
                $batches = Batch::where('course_id', $courseId)
                    ->where('status', 'On')
                    ->orderBy('batch_code', 'ASC')
                    ->get(['id', 'batch_code']);
                
                return response()->json([
                    'success' => true,
                    'batches' => $batches
                ]);
            } catch (\Exception $e) {
                \Log::error('Error getting batches by course: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load batches'
                ], 500);
            }
        }

        private function exportStudents($students, $type)
        {
            $data = [];
            
            foreach ($students as $student) {
                $data[] = [
                    'Application No' => $student->application_number,
                    'Student ID' => $student->student_id,
                    'Name' => $student->name,
                    'Mobile' => $student->mobile,
                    'Email' => $student->email,
                    'Course' => $student->course_name,
                    'Batch' => $student->batch_code,
                    'Course Fee' => $student->course_fee,
                    'Deposit Amount' => $student->deposit_amount,
                    'Discount Amount' => $student->discount_amount,
                    'Due Amount' => $student->due_amount,
                    'Payment Method' => $student->payment->payment_method_name ?? 'N/A',
                    'Status' => ucfirst($student->status),
                    'Applied Date' => $student->created_at->format('d-m-Y'),
                    'Approved Date' => $student->approved_at ? $student->approved_at->format('d-m-Y') : 'N/A',
                ];
            }
            
            if ($type === 'csv') {
                return $this->exportToCsv($data);
            } elseif ($type === 'excel') {
                return $this->exportToExcel($data);
            } elseif ($type === 'pdf') {
                return $this->exportToPdf($data);
            }
        }

        private function exportToCsv($data)
        {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="students-' . date('Y-m-d') . '.csv"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
                
                // Add headers
                fputcsv($file, array_keys($data[0]));
                
                // Add data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }


    public function PendingStudentIndex()
    {
        $applications = StudentAdmission::with(['course', 'batch', 'payment'])
            ->where('status', 'pending')
            // ->latest()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admissions.pending-student-index', compact('applications'));
    }

    /**
     * Admin: Show single application
     */
    public function show($id)
    {
        $application = StudentAdmission::with(['course', 'batch', 'payment'])->findOrFail($id);
        return view('admissions.student-show', compact('application'));
    }

    /**
     * Admin: Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $application = StudentAdmission::findOrFail($id);
        $application->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

    /**
     * Get batches for a specific course admission
     */
    public function getCourseBatches($id)
    {
        try {
            $admission = StudentAdmission::with('course')->findOrFail($id);

            $batches = Batch::where('course_id', $admission->course_id)
                ->where('status', 'On')
                ->with('course')
                ->get()
                ->map(function ($batch) {
                    // Calculate available seats correctly
                    $availableSeats = $batch->total_seat - $batch->enrolled_students;
                    
                    return [
                        'id' => $batch->id,
                        'batch_code' => $batch->batch_code,
                        'available_seats' => $availableSeats,
                        'total_seat' => $batch->total_seat,
                        'enrolled_students' => $batch->enrolled_students,
                        'course_fee' => $batch->course ? $batch->course->course_fee : 0
                    ];
                })
                ->filter(function ($batch) {
                    // Only show batches with available seats
                    return $batch['available_seats'] > 0;
                })
                ->values();

            return response()->json($batches);

        } catch (\Exception $e) {
            Log::error('Error loading batches: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load batches'], 500);
        }
    }

    /**
     * Approve admission and assign batch
     */
    public function approveAdmission(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'batch_id' => 'required|exists:batches,id',
                'deposit_amount' => 'required|numeric|min:0',
                'discount_amount' => 'required|numeric|min:0',
                'due_amount' => 'required|numeric|min:0',
                'next_due_date' => 'nullable|date',
                'remarks' => 'nullable|string|max:500',
            ]);

            // Add custom validation for next_due_date
            $validator->after(function ($validator) use ($request) {
                $dueAmount = floatval($request->due_amount);
                if ($dueAmount > 0 && empty($request->next_due_date)) {
                    $validator->errors()->add('next_due_date', 'Next due date is required when there is due amount.');
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check the form for errors.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            $admission = StudentAdmission::with('payment')->findOrFail($id);
            $batch = Batch::findOrFail($validated['batch_id']);

            // Check if batch has available seats
            $availableSeats = $batch->total_seat - $batch->enrolled_students;
            if ($availableSeats <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected batch has no available seats.'
                ], 422);
            }

            // Check if course matches
            if ($batch->course_id != $admission->course_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected batch does not belong to the applied course.'
                ], 422);
            }

            // Validate amounts
            $courseFee = $admission->course->course_fee;
            $totalPayable = $courseFee - $validated['discount_amount'];
            $calculatedDue = $totalPayable - $validated['deposit_amount'];

            if (abs($calculatedDue - $validated['due_amount']) > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount calculation mismatch. Please check deposit, discount, and due amounts.'
                ], 422);
            }

            // Generate student ID based on course
            $studentId = StudentAdmission::generateStudentId($admission->course_id);

            // Use database transaction to ensure data consistency
            DB::transaction(function () use ($admission, $validated, $studentId, $batch) {
                // Update admission
                $admission->update([
                    'batch_id' => $validated['batch_id'],
                    'student_id' => $studentId,
                    'status' => 'approved',
                    'approved_at' => Carbon::now(),
                ]);

                // Update or create payment record
                if ($admission->payment) {
                    $admission->payment->update([
                        'student_id' => $studentId,
                        'deposit_amount' => $validated['deposit_amount'],
                        'discount_amount' => $validated['discount_amount'],
                        'due_amount' => $validated['due_amount'],
                        'next_due_date' => $validated['due_amount'] > 0 ? $validated['next_due_date'] : null,
                        'remarks' => $validated['remarks'],
                        'payment_received_by' => Auth::user()->first_name,
                    ]);
                } else {
                    StudentPayment::create([
                        'student_admission_id' => $admission->id,
                        'application_number' => $admission->application_number,
                        'student_id' => $studentId,
                        'payment_method' => 'cash', // Default for approval
                        'deposit_amount' => $validated['deposit_amount'],
                        'discount_amount' => $validated['discount_amount'],
                        'due_amount' => $validated['due_amount'],
                        'next_due_date' => $validated['due_amount'] > 0 ? $validated['next_due_date'] : null,
                        'remarks' => $validated['remarks'],
                        'payment_received_by' => Auth::user()->first_name, // Add this line

                    ]);
                }

                // Update batch enrolled count
                $batch->increment('enrolled_students');
            });

            // Refresh the admission data with relationships
            $admission->refresh();
            $admission->load(['course', 'batch', 'payment']);

            // Send email notification
            try {
                Mail::to($admission->email)->send(new \Vanguard\Mail\AdmissionApprovalMail($admission));
                Log::info('Admission approval email sent', ['student_id' => $studentId, 'email' => $admission->email]);
            } catch (\Exception $e) {
                Log::error('Failed to send admission email: ' . $e->getMessage());
                // Don't fail the whole process if email fails
            }

            Log::info('Admission approved', [
                'application_number' => $admission->application_number,
                'student_id' => $studentId,
                'batch_id' => $batch->id,
                'enrolled_students' => $batch->enrolled_students
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admission approved successfully! Email sent to student.',
                'student_id' => $studentId,
                'redirect_url' => route('student-admissions.index')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Admission approval error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve admission. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete an application
     */
    public function destroy($id)
    {
        try {
            $application = StudentAdmission::findOrFail($id);
            
            // Delete photo if exists
            if ($application->photo_data && Storage::disk('public')->exists($application->photo_data)) {
                Storage::disk('public')->delete($application->photo_data);
            }
            
            $application->delete();

            return redirect()->route('student-admissions.index')
                ->with('success', 'Application deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting application: ' . $e->getMessage());
            return redirect()->route('student-admissions.index')
                ->with('error', 'Failed to delete application. Please try again.');
        }
    }

    /**
     * Display ID card for a student
     */
    public function showIdCard($id)
    {
        $student = StudentAdmission::with(['course', 'batch', 'payment'])
            ->where('status', 'approved')
            ->findOrFail($id);

        return view('admissions.student-id-card', compact('student'));
    }

    /**
     * Generate PDF ID card
     */
    public function downloadIdCardPdf($id)
    {
        $student = StudentAdmission::with(['course', 'batch', 'payment'])
            ->where('status', 'approved')
            ->findOrFail($id);

        $pdf = Pdf::loadView('admissions.id-card-pdf', compact('student'));
        
        return $pdf->download("student-id-card-{$student->student_id}.pdf");
    }

    /**
     * Bulk ID card generation
     */
    public function bulkIdCards(Request $request)
    {
        $studentIds = $request->input('student_ids', []);
        
        $students = StudentAdmission::with(['course', 'batch', 'payment'])
            ->where('status', 'approved')
            ->whereIn('id', $studentIds)
            ->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'No approved students selected.');
        }

        $pdf = Pdf::loadView('admissions.bulk-id-cards-pdf', compact('students'));
        
        return $pdf->download("bulk-student-id-cards-".date('Y-m-d').".pdf");
    }

   
    public function downloadIdCardImage($id)
    {
        try {
            $student = StudentAdmission::with(['course', 'batch'])
                ->where('status', 'approved')
                ->findOrFail($id);

            // Create image canvas
            $image = Image::canvas(320, 500, '#ffffff');
            
            // Add main border (draw multiple lines to simulate thicker border)
            $this->drawThickRectangle($image, 0, 0, 319, 499, 2, '#f38020');

            /* ===== HEADER ===== */
            $image->rectangle(0, 0, 319, 80, function ($draw) {
                $draw->background('#192335');
            });

            // Add logo
            $logoPath = public_path('assets/img/sts-logo.png');
            if (file_exists($logoPath)) {
                $logo = Image::make($logoPath);
                $logo->resize(50, 50);
                $image->insert($logo, 'top-left', 135, 15);
            }

            /* ===== STUDENT PHOTO ===== */
            $this->addCircularStudentPhoto($image, $student);

            /* ===== STUDENT INFORMATION ===== */
            $this->addStudentInfo($image, $student);

            /* ===== SIGNATURE SECTION ===== */
            $this->addSignatureSection($image);

            /* ===== FOOTER ===== */
            $this->addFooter($image);

            // Generate image content
            $imageContent = $image->encode('png');

            // Return image as download
            return response($imageContent, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="student-id-card-' . $student->student_id . '.png"',
            ]);

        } catch (\Exception $e) {
            Log::error('ID Card Image Generation Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to generate ID card image. Please use PDF download.');
        }
    }

    /**
     * Draw a thick rectangle by drawing multiple lines (GD compatible)
     */
    private function drawThickRectangle($image, $x1, $y1, $x2, $y2, $thickness, $color)
    {
        for ($i = 0; $i < $thickness; $i++) {
            $image->rectangle($x1 + $i, $y1 + $i, $x2 - $i, $y2 - $i, function ($draw) use ($color) {
                $draw->border(1, $color);
            });
        }
    }

    /**
     * Draw a thick circle by drawing multiple circles (GD compatible)
     */
    private function drawThickCircle($image, $x, $y, $radius, $thickness, $color)
    {
        for ($i = 0; $i < $thickness; $i++) {
            $image->circle($radius - $i, $x, $y, function ($draw) use ($color) {
                $draw->border(1, $color);
            });
        }
    }

    private function addCircularStudentPhoto($image, $student)
    {
        $photoPath = null;
        if ($student->photo_data) {
            $filename = basename($student->photo_data);
            $photoPath = public_path('student_photos/' . $filename);
        }

        $centerX = 160;
        $centerY = 150;
        $radius = 50; // Radius for the circular photo

        if ($photoPath && file_exists($photoPath)) {
            try {
                // Load and resize the photo
                $photo = Image::make($photoPath);
                
                // Create a circular photo using a different approach
                // First, create a square image
                $squareSize = $radius * 2;
                $squarePhoto = Image::canvas($squareSize, $squareSize);
                
                // Resize and fit the photo to the square
                $photo->fit($squareSize, $squareSize);
                
                // Insert the square photo
                $squarePhoto->insert($photo, 'center');
                
                // Now create a circular mask by drawing a filled circle
                $mask = Image::canvas($squareSize, $squareSize);
                $mask->circle($squareSize, $radius, $radius, function ($draw) {
                    $draw->background('#ffffff');
                });
                
                // Apply the mask by using the mask method
                $squarePhoto->mask($mask->getCore(), true);
                
                // Insert the circular photo onto the main image
                $image->insert($squarePhoto, 'top-left', $centerX - $radius, $centerY - $radius);
                
            } catch (\Exception $e) {
                // If photo processing fails, fall back to placeholder
                Log::error('Photo processing error: ' . $e->getMessage());
                $this->addPhotoPlaceholder($image, $centerX, $centerY, $radius);
            }
        } else {
            $this->addPhotoPlaceholder($image, $centerX, $centerY, $radius);
        }
        
        // Add circular border around the photo
        $this->drawThickCircle($image, $centerX, $centerY, $radius + 2, 3, '#f38020');
    }

    private function addPhotoPlaceholder($image, $centerX, $centerY, $radius)
    {
        // Create circular placeholder background
        $image->circle($radius * 2, $centerX, $centerY, function ($draw) {
            $draw->background('#f8f9fa');
        });
        
        // Add user icon
        $image->text('👤', $centerX, $centerY, function ($font) {
            $font->size(30);
            $font->color('#999999');
            $font->align('center');
            $font->valign('middle');
        });
    }

    private function addStudentInfo($image, $student)
    {
        $y = 220;
        
        // Name
        $image->text($student->name, 160, $y, function ($font) {
            $font->size(16);
            $font->color('#192335');
            $font->align('center');
            $font->valign('top');
        });

        // ID
        $image->text('ID No: ' . $student->student_id, 160, $y + 25, function ($font) {
            $font->size(14);
            $font->color('#f38020');
            $font->align('center');
            $font->valign('top');
        });

        // Course
        $image->text($student->course_name, 160, $y + 50, function ($font) {
            $font->size(14);
            $font->color('#555555');
            $font->align('center');
            $font->valign('top');
        });

        // Batch
        $image->text('Batch: ' . $student->batch_code, 160, $y + 75, function ($font) {
            $font->size(13);
            $font->color('#555555');
            $font->align('center');
            $font->valign('top');
        });

        // Valid Until
        $image->text('Valid Until: ' . \Carbon\Carbon::now()->addYear()->format('M Y'), 160, $y + 100, function ($font) {
            $font->size(11);
            $font->color('#666666');
            $font->align('center');
            $font->valign('top');
        });
    }

    private function addSignatureSection($image)
    {
        $y = 370;
        
        // Add signature image if exists
        $signPath = public_path('assets/img/sign.png');
        if (file_exists($signPath)) {
            try {
                $sign = Image::make($signPath);
                $sign->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image->insert($sign, 'top-left', 135, $y - 10);
            } catch (\Exception $e) {
                Log::error('Signature image error: ' . $e->getMessage());
            }
        }

        // Signature line (draw multiple lines for thickness)
        for ($i = 0; $i < 2; $i++) {
            $image->line(85, $y + 25 + $i, 235, $y + 25 + $i, function ($draw) {
                $draw->color('#192335');
            });
        }

        // Signature text
        $image->text('Authorized Signature', 160, $y + 40, function ($font) {
            $font->size(11);
            $font->color('#192335');
            $font->align('center');
            $font->valign('top');
        });
    }

    private function addFooter($image)
    {
        $image->rectangle(0, 450, 319, 499, function ($draw) {
            $draw->background('#192335');
        });

        $image->text('www.stsit.institute', 160, 475, function ($font) {
            $font->size(12);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
    }
    /**
     * Helper function to add text with better visibility
     */
    private function addTextWithBackground($image, $text, $x, $y, $size, $color, $bgColor = null)
    {
        // If background color is provided, add a subtle background
        if ($bgColor) {
            $textWidth = strlen($text) * $size * 0.6; // Approximate text width
            $textHeight = $size * 1.2;
            
            $image->rectangle(
                $x - ($textWidth / 2) - 5, 
                $y - ($textHeight / 2) - 2,
                $x + ($textWidth / 2) + 5, 
                $y + ($textHeight / 2) + 2,
                function ($draw) use ($bgColor) {
                    $draw->background($bgColor);
                }
            );
        }
        
        // Add the text
        $image->text($text, $x, $y, function ($font) use ($size, $color) {
            $font->size($size);
            $font->color($color);
            $font->align('center');
            $font->valign('middle');
        });
    }


        /**
     * Payment Invoice Management
     */

    // Display payment invoice form
    public function paymentInvoiceForm()
    {
        $paymentCategories = [
            'Mock Tests',
            'Speaking Tests', 
            'Admission Due Collections',
            '2nd Semester Fee',
            '3rd Semester Fee',
            'Final Semester Fee',
            'Other'
        ];
        
        return view('admissions.payment-invoice-form', compact('paymentCategories'));
    }

    // Search existing student
    public function searchStudent(Request $request)
    {
        $query = $request->get('query');
        
        $students = StudentAdmission::where('status', 'approved')
            ->where(function($q) use ($query) {
                $q->where('student_id', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$query}%")
                ->orWhere('mobile', 'LIKE', "%{$query}%")
                ->orWhere('application_number', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'student_id', 'name', 'mobile', 'email', 'application_number']);
        
        return response()->json($students);
    }

    // Get student details for form auto-population
    public function getStudentDetails($id)
    {
        $student = StudentAdmission::with('payment')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                'gender' => $student->gender,
                'mobile' => $student->mobile,
                'emergency_mobile' => $student->emergency_mobile,
                'email' => $student->email,
                'address' => $student->address,
                'student_id' => $student->student_id,
                'application_number' => $student->application_number,
                'course_name' => $student->course_name,
                'batch_code' => $student->batch_code,
                'due_amount' => $student->payment ? $student->payment->due_amount : 0,
            ]
        ]);
    }

    // Store payment invoice
    public function storePaymentInvoice(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_type' => 'required|in:existing,new',
                'student_id' => 'required_if:student_type,existing|nullable',
                'name' => 'required|string|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female',
                'mobile' => 'required|string|max:20',
                'emergency_mobile' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:1000',
                'payment_category' => 'required|string|max:255',
                'purpose' => 'nullable|string|max:500',
                'payment_method' => 'required|in:cash,bkash,bank',
                'transaction_id' => 'required_if:payment_method,bkash|nullable|string|max:255',
                'serial_number' => 'required_if:payment_method,bank|nullable|string|max:255',
                'due_amount' => 'required|numeric|min:0',
                'deposit_amount' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'remarks' => 'nullable|string|max:500',
            ], [
                'student_id.required_if' => 'Please select a student.',
                'transaction_id.required_if' => 'Transaction ID is required for bKash payments.',
                'serial_number.required_if' => 'Serial number is required for Bank payments.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check the form for errors.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Use database transaction
            DB::beginTransaction();

            try {
                $studentAdmission = null;
                
                // Handle student based on type
                if ($validated['student_type'] === 'existing') {
                    // Get existing student
                    $studentAdmission = StudentAdmission::findOrFail($validated['student_id']);
                    
                    // Update student information if provided (optional updates)
                    $studentAdmission->update([
                        'mobile' => $validated['mobile'] ?? $studentAdmission->mobile,
                        'emergency_mobile' => $validated['emergency_mobile'] ?? $studentAdmission->emergency_mobile,
                        'email' => $validated['email'] ?? $studentAdmission->email,
                        'address' => $validated['address'] ?? $studentAdmission->address,
                    ]);
                } else {
                    // Create new student admission for new students
                    $studentAdmission = StudentAdmission::create([
                        'name' => $validated['name'],
                        'dob' => $validated['dob'] ?? null,
                        'gender' => $validated['gender'] ?? null,
                        'mobile' => $validated['mobile'],
                        'emergency_mobile' => $validated['emergency_mobile'] ?? null,
                        'email' => $validated['email'] ?? null,
                        'address' => $validated['address'] ?? null,
                        'status' => 'approved', // Auto-approve for payment invoice
                        'approved_at' => now(),
                    ]);
                }

                // Create payment record in student_payments table
                $payment = StudentPayment::create([
                    'student_admission_id' => $studentAdmission->id,
                    'application_number' => $studentAdmission->application_number,
                    'student_id' => $studentAdmission->student_id,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => $validated['transaction_id'] ?? null,
                    'serial_number' => $validated['serial_number'] ?? null,
                    'deposit_amount' => $validated['deposit_amount'],
                    'discount_amount' => $validated['discount_amount'] ?? 0,
                    'due_amount' => $validated['due_amount'],
                    'next_due_date' => null, // Can be calculated if needed
                    'remarks' => $validated['remarks'] ?? null,
                    'payment_received_by' => Auth::user()->first_name,
                    'payment_category' => $validated['payment_category'], // Add this field to student_payments table
                    'purpose' => $validated['purpose'] ?? null, // Add this field to student_payments table
                ]);

                DB::commit();

                try {
                    Mail::to($studentAdmission->email)->send(new PaymentInvoiceMail($payment));
                    Log::info('Payment invoice email sent', ['payment_id' => $payment->id, 'email' => $studentAdmission->email]);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment invoice email: ' . $e->getMessage());
                    // Continue even if email fails
                }
                
                Log::info('Payment invoice created successfully', [
                    'payment_id' => $payment->id,
                    'student_id' => $studentAdmission->id,
                    'amount' => $validated['deposit_amount']
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payment invoice created successfully! Email sent with invoice attachment.',
                    'payment_id' => $payment->id,
                    'redirect_url' => route('student-admissions.payment-invoice-receipt', $payment->id)
                ]);

                Log::info('Payment invoice created successfully', [
                    'invoice_type' => 'payment_invoice',
                    'student_id' => $studentAdmission->id,
                    'payment_id' => $payment->id,
                    'amount' => $validated['deposit_amount']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment invoice created successfully!',
                    'payment_id' => $payment->id,
                    'redirect_url' => route('student-admissions.payment-invoice-receipt', $payment->id)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment invoice error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment invoice. Please try again.'
            ], 500);
        }
    }

    // Display payment invoice receipt
    public function paymentInvoiceReceipt($id)
    {
        $payment = StudentPayment::with('studentAdmission')->findOrFail($id);
        
        return view('admissions.payment-invoice-receipt', compact('payment'));
    }

    // Download payment invoice PDF
    public function downloadPaymentInvoicePdf($id)
    {
        $payment = StudentPayment::with('studentAdmission')->findOrFail($id);
        
        $pdf = Pdf::loadView('admissions.payment-invoice-pdf', compact('payment'));
        
        return $pdf->download("payment-invoice-{$payment->id}.pdf");
    }

    // List all payment invoices
    public function paymentInvoicesIndex()
    {
        $payments = StudentPayment::with('studentAdmission')
            ->whereNotNull('payment_category')
            ->latest()
            ->paginate(20);
        
        return view('admissions.payment-invoices-index', compact('payments'));
    }

}