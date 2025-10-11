<?php

namespace Vanguard\Http\Controllers;

use Vanguard\StudentAdmission;
use Vanguard\Course;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class StudentAdmissionController extends Controller
{
    /**
     * Display the student admission form
     */
    public function studentAdmissionFrontend()
    {
        // Get active courses from database
        $courses = Course::where('status', 'On')->get();
        
        return view('student-frontend.student-admission-form', compact('courses'));
    }

    /**
     * Process student admission form submission
     */
    // public function store(Request $request)
    // {
    //     // Log the incoming request for debugging
    //     Log::info('Admission form submitted', $request->all());

    //     try {
    //         // Validate the request with better error messages
    //         $validated = $request->validate([
    //             'name' => 'required|string|max:255',
    //             'dob' => 'required|date|before:today',
    //             'gender' => 'required|in:male,female',
    //             'mobile' => 'required|string|max:20',
    //             'emergency_mobile' => 'required|string|max:20',
    //             'email' => 'required|email|max:255',
    //             'address' => 'required|string|max:1000',
    //             'course' => 'required|in:ielts,pte,english_foundation,kids_english',
    //             'photo_data' => 'required|string',
    //             'payment_method' => 'required|in:cash,bkash,bank',
    //             'transaction_id' => 'nullable|string|max:255',
    //             'serial_number' => 'nullable|string|max:255',
    //         ], [
    //             'photo_data.required' => 'Please take a student photo before submitting.',
    //             'dob.before' => 'Date of birth must be in the past.',
    //         ]);

    //         // Handle conditional validation for payment methods
    //         if ($request->payment_method === 'bkash' && empty($request->transaction_id)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Transaction ID is required for bKash payments.',
    //                 'errors' => ['transaction_id' => ['Transaction ID is required for bKash payments.']]
    //             ], 422);
    //         }

    //         if ($request->payment_method === 'bank' && empty($request->serial_number)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Serial number is required for Bank payments.',
    //                 'errors' => ['serial_number' => ['Serial number is required for Bank payments.']]
    //             ], 422);
    //         }

    //         // Handle photo data (base64 to file)
    //         $photoPath = null;
    //         if ($request->photo_data && $request->photo_data !== '{{ csrf_token() }}') {
    //             $photoPath = $this->saveBase64Image($request->photo_data);
    //         }

    //         // Create student admission record
    //         $admission = StudentAdmission::create([
    //             'name' => $validated['name'],
    //             'dob' => $validated['dob'],
    //             'gender' => $validated['gender'],
    //             'mobile' => $validated['mobile'],
    //             'emergency_mobile' => $validated['emergency_mobile'],
    //             'email' => $validated['email'],
    //             'address' => $validated['address'],
    //             'course' => $validated['course'],
    //             'photo_data' => $photoPath,
    //             'payment_method' => $validated['payment_method'],
    //             'transaction_id' => $validated['transaction_id'] ?? null,
    //             'serial_number' => $validated['serial_number'] ?? null,
    //         ]);

    //         Log::info('Admission created successfully', ['id' => $admission->id, 'application_number' => $admission->application_number]);

    //         // Return success response
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Application submitted successfully!',
    //             'application_number' => $admission->application_number,
    //             'redirect_url' => route('admission.success', $admission->id)
    //         ]);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         Log::error('Validation error: ', $e->errors());
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Please check the form for errors.',
    //             'errors' => $e->errors()
    //         ], 422);
            
    //     } catch (\Exception $e) {
    //         Log::error('Student admission error: ' . $e->getMessage());
    //         Log::error($e->getTraceAsString());
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to submit application. Please try again. Error: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Admission form submitted', $request->all());

        try {
            // Get active course IDs for validation
            $activeCourseIds = Course::where('status', 'On')->pluck('id')->toArray();
            
            // Validate the request with better error messages
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'dob' => 'required|date|before:today',
                'gender' => 'required|in:male,female',
                'mobile' => 'required|string|max:20',
                'emergency_mobile' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:1000',
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
            ]);

            // Handle conditional validation for payment methods
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

            // Get course details
            $course = Course::findOrFail($validated['course_id']);

            // Handle photo data (base64 to file)
            $photoPath = null;
            if ($request->photo_data && $request->photo_data !== '{{ csrf_token() }}') {
                $photoPath = $this->saveBase64Image($request->photo_data);
            }

            // Create student admission record - store course_id instead of course name and fee
            $admission = StudentAdmission::create([
                'name' => $validated['name'],
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'mobile' => $validated['mobile'],
                'emergency_mobile' => $validated['emergency_mobile'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'course_id' => $validated['course_id'], // Store course ID instead of course name
                'photo_data' => $photoPath,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'serial_number' => $validated['serial_number'] ?? null,
            ]);

            Log::info('Admission created successfully', ['id' => $admission->id, 'application_number' => $admission->application_number]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!',
                'application_number' => $admission->application_number,
                'redirect_url' => route('admission.success', $admission->id)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Student admission error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save base64 image to storage
     */
    private function saveBase64Image($base64Image)
    {
        // Check if image data is base64 and not a token string
        if (strpos($base64Image, 'data:image') !== 0) {
            throw new \Exception('Invalid image data format');
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $image = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if type is valid
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

        // Generate unique filename
        $filename = 'student_photos/' . Str::uuid() . '.' . $type;
        
        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists('student_photos')) {
            Storage::disk('public')->makeDirectory('student_photos');
        }
        
        // Store image
        Storage::disk('public')->put($filename, $image);
        
        return $filename;
    }
    /**
     * Display success page
     */
    public function success($id)
    {
        $admission = StudentAdmission::with('course')->findOrFail($id);
        
        return view('student-frontend.admission-success', compact('admission'));
    }
    /**
     * Admin: List all applications
     */
    // public function index()
    // {
    //     $applications = StudentAdmission::latest()->paginate(20);
        
    //     return view('admin.admissions.index', compact('applications'));
    // }

    /**
     * Admin: Show single application
     */
    // public function show($id)
    // {
    //     $application = StudentAdmission::findOrFail($id);
        
    //     return view('admin.admissions.show', compact('application'));
    // }

    /**
     * Admin: Update application status
     */
    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,approved,rejected'
    //     ]);

    //     $application = StudentAdmission::findOrFail($id);
    //     $application->update(['status' => $request->status]);

    //     return redirect()->back()->with('success', 'Application status updated successfully.');
    // }
}