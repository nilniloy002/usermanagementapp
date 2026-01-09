<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Form - STS Institute</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Apply for courses at STS Institute - IELTS, PTE, English Foundation, and Kids English. Start your educational journey with us today.">
    <meta name="keywords" content="student admission, IELTS course, PTE course, English Foundation, Kids English, STS Institute">
    <meta name="author" content="STS Institute">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Student Admission Form - STS Institute">
    <meta property="og:description" content="Apply for courses at STS Institute - IELTS, PTE, English Foundation, and Kids English.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="https://sts.institute/wp-content/uploads/2024/08/cropped-Logo-Fav.-Icon-02-192x192.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="https://sts.institute/wp-content/uploads/2024/08/cropped-Logo-Fav.-Icon-02-192x192.png">
    
    <!-- Stylesheets -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .camera-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        .camera-preview {
            width: 100%;
            height: 300px;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        .camera-controls {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .payment-extra {
            display: none;
        }
        .payment-option.selected {
            border-color: #4f46e5;
            background-color: #f8faff;
        }
        .course-option.selected {
            border-color: #4f46e5;
            background-color: #f0f9ff;
        }
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        .header-logo {
            height: 50px;
            width: auto;
        }
        @media (max-width: 768px) {
            .header-logo {
                height: 40px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header with Logo -->
        <div class="text-center mb-10">
            <div class="flex justify-center mb-6">
                <img src="https://sourceforces-img.sgp1.cdn.digitaloceanspaces.com/sts-logo.png" 
                     alt="STS Institute Logo" 
                     class="header-logo">
            </div>
            <h1 class="text-3xl font-bold text-indigo-700">Student Admission Form</h1>
            <p class="text-gray-600 mt-2">Please fill out all the required information accurately</p>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form id="admissionForm" class="p-6 md:p-8" enctype="multipart/form-data">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" id="csrf_token" value="<?php echo csrf_token(); ?>">

                <!-- Personal Information Section -->
                <div class="mb-10">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Personal Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- DOB with Three Dropdowns -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Day -->
                                <div>
                                    <select id="dob_day" name="dob_day" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        <option value="" disabled selected>Day</option>
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <!-- Month -->
                                <div>
                                    <select id="dob_month" name="dob_month" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        <option value="" disabled selected>Month</option>
                                        @php
                                            $months = [
                                                '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                                                '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                                                '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                                            ];
                                        @endphp
                                        @foreach($months as $key => $month)
                                            <option value="{{ $key }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Year -->
                                <div>
                                    <select id="dob_year" name="dob_year" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        <option value="" disabled selected>Year</option>
                                        @for($i = date('Y') - 10; $i >= date('Y') - 70; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <!-- Hidden field to store the final date -->
                            <input type="hidden" id="dob" name="dob">
                        </div>
                        
                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                            <div class="flex space-x-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="male" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" required>
                                    <span class="ml-2 text-gray-700">Male</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="gender" value="female" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" required>
                                    <span class="ml-2 text-gray-700">Female</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="mobile" name="mobile" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Emergency/Guardian Mobile -->
                        <div>
                            <label for="emergency_mobile" class="block text-sm font-medium text-gray-700 mb-1">Emergency/Guardian Mobile <span class="text-red-500">*</span></label>
                            <input type="tel" id="emergency_mobile" name="emergency_mobile" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>
                    
                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                        <textarea id="address" name="address" rows="3" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                    </div>
                    
                    <!-- Educational Background -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Educational Background <span class="text-red-500">*</span></label>
                        <select id="educational_background" name="educational_background" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="" disabled selected>Select Educational Background</option>
                            <option value="SSC">SSC</option>
                            <option value="HSC">HSC</option>
                            <option value="bachelor">Bachelor / Honours / Graduation</option>
                            <option value="masters">Master's / Post Graduation</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    
                    <!-- Other Education Field (Initially Hidden) -->
                    <div id="otherEducationContainer" class="mt-6 hidden">
                        <label for="other_education" class="block text-sm font-medium text-gray-700 mb-1">Specify Educational Background <span class="text-red-500">*</span></label>
                        <input type="text" id="other_education" name="other_education" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="e.g., Diploma, O-Level, A-Level, etc.">
                    </div>
                    
                    <!-- Academic Year -->
                    <div class="mt-6">
                        <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year <span class="text-red-500">*</span></label>
                        <input type="text" id="academic_year" name="academic_year" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="e.g., 2024-2025, Final Year, 2nd Year, etc.">
                    </div>
                </div>
                
                <!-- Course Selection Section -->
                <div class="mb-10">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Course Selection</h2>
                    
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course/Program Applied for <span class="text-red-500">*</span></label>
                        
                        @if($courses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($courses as $course)
                                    <label class="course-option relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none hover:border-indigo-500 transition-colors">
                                        <input type="radio" name="course_id" value="{{ $course->id }}" class="sr-only" required>
                                        <div class="flex w-full items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="text-sm">
                                                    <p class="font-medium text-gray-900">{{ $course->course_name }}</p>
                                                    <p class="text-gray-500 mt-1">Fee: ৳{{ number_format($course->course_fee, 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 text-green-600">
                                                    <i class="fas fa-check-circle h-5 w-5"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <!-- Selected Course Fee Display -->
                            <div id="selectedCourseInfo" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-600">Selected Course:</span>
                                        <span id="selectedCourseName" class="font-semibold text-blue-700 ml-2"></span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Course Fee:</span>
                                        <span id="selectedCourseFee" class="font-bold text-green-600 ml-2"></span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-yellow-50 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                                <p class="text-yellow-700">No courses are currently available for admission.</p>
                                <p class="text-yellow-600 text-sm mt-1">Please check back later or contact administration.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Photo Section -->
                <div class="mb-10">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Student Photo</h2>
                    
                    <div class="camera-container">
                        <!-- Camera Preview -->
                        <div class="camera-preview" id="cameraPreview">
                            <div class="text-center text-gray-500" id="placeholderText">
                                <i class="fas fa-camera text-4xl mb-2"></i>
                                <p>Camera preview will appear here</p>
                            </div>
                            <video id="video" autoplay playsinline></video>
                            <img id="capturedImage" alt="Captured photo">
                        </div>
                        
                        <!-- Camera Controls -->
                        <div class="camera-controls">
                            <button type="button" id="startCamera" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 px-6 rounded-lg transition flex items-center justify-center">
                                <i class="fas fa-camera mr-2"></i> Start Camera
                            </button>
                            <button type="button" id="capturePhoto" class="bg-green-600 hover:bg-green-700 text-white py-2.5 px-6 rounded-lg transition flex items-center justify-center hidden">
                                <i class="fas fa-camera-retro mr-2"></i> Take Picture
                            </button>
                            <button type="button" id="retakePhoto" class="bg-gray-600 hover:bg-gray-700 text-white py-2.5 px-6 rounded-lg transition flex items-center justify-center hidden">
                                <i class="fas fa-redo mr-2"></i> Retake
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hidden field for storing the captured image -->
                    <input type="hidden" id="photoData" name="photo_data">
                </div>
                
                <!-- Payment Section -->
                <div class="mb-10">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-6">Payment Information</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <label class="payment-option relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none">
                                <input type="radio" name="payment_method" value="cash" class="sr-only" required>
                                <div class="flex w-full items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900">Cash</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-money-bill-wave text-green-500 h-5 w-5"></i>
                                </div>
                            </label>
                            
                            <label class="payment-option relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none">
                                <input type="radio" name="payment_method" value="bkash" class="sr-only" required>
                                <div class="flex w-full items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900">bKash</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-mobile-alt text-pink-500 h-5 w-5"></i>
                                </div>
                            </label>
                            
                            <label class="payment-option relative flex cursor-pointer rounded-lg border border-gray-300 p-4 focus:outline-none">
                                <input type="radio" name="payment_method" value="bank" class="sr-only" required>
                                <div class="flex w-full items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900">Bank</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-university text-blue-500 h-5 w-5"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- bKash Extra Fields -->
                    <div id="bkashExtra" class="payment-extra mt-6">
                        <label for="txn_id" class="block text-sm font-medium text-gray-700 mb-1">Transaction ID/TxnID <span class="text-red-500">*</span></label>
                        <input type="text" id="txn_id" name="transaction_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    
                    <!-- Bank Extra Fields -->
                    <div id="bankExtra" class="payment-extra mt-6">
                        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">Serial Number/Reference <span class="text-red-500">*</span></label>
                        <input type="text" id="serial_number" name="serial_number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-lg transition flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Application
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer Note -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>All information provided will be kept confidential and used solely for admission purposes.</p>
            <p class="mt-2">© 2024 STS Institute. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Get CSRF token safely
        function getCsrfToken() {
            // Try hidden input first
            const csrfInput = document.getElementById('csrf_token');
            if (csrfInput && csrfInput.value) {
                console.log('CSRF token found in hidden input:', csrfInput.value.substring(0, 10) + '...');
                return csrfInput.value;
            }
            
            // Try other possible locations
            const tokenInputs = document.querySelectorAll('input[name="_token"]');
            for (let input of tokenInputs) {
                if (input.value) {
                    console.log('CSRF token found in _token input:', input.value.substring(0, 10) + '...');
                    return input.value;
                }
            }
            
            console.error('CSRF token not found');
            return null;
        }

        // Camera functionality
        const startCameraBtn = document.getElementById('startCamera');
        const capturePhotoBtn = document.getElementById('capturePhoto');
        const retakePhotoBtn = document.getElementById('retakePhoto');
        const cameraPreview = document.getElementById('cameraPreview');
        const placeholderText = document.getElementById('placeholderText');
        const video = document.getElementById('video');
        const capturedImage = document.getElementById('capturedImage');
        const photoData = document.getElementById('photoData');
        
        let stream = null;
        
        // Start camera when button is clicked
        startCameraBtn.addEventListener('click', async () => {
            try {
                // Get user media with constraints for front camera
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }, 
                    audio: false 
                });
                
                // Set video source to the stream
                video.srcObject = stream;
                
                // Show video and hide placeholder
                video.style.display = 'block';
                placeholderText.style.display = 'none';
                capturedImage.style.display = 'none';
                
                // Update button visibility
                startCameraBtn.classList.add('hidden');
                capturePhotoBtn.classList.remove('hidden');
                
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Error accessing camera: ' + err.message);
            }
        });
        
        // Capture photo when button is clicked
        capturePhotoBtn.addEventListener('click', () => {
            // Create a canvas element to capture the photo
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw current video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert canvas to data URL (JPEG format with 80% quality)
            const dataURL = canvas.toDataURL('image/jpeg', 0.8);
            
            // Store the image data in the hidden field
            photoData.value = dataURL;
            
            // Stop the camera stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            // Show the captured image and hide video
            capturedImage.src = dataURL;
            video.style.display = 'none';
            capturedImage.style.display = 'block';
            
            // Update button visibility
            capturePhotoBtn.classList.add('hidden');
            retakePhotoBtn.classList.remove('hidden');
        });
        
        // Retake photo when button is clicked
        retakePhotoBtn.addEventListener('click', () => {
            // Reset the display
            capturedImage.style.display = 'none';
            placeholderText.style.display = 'flex';
            photoData.value = '';
            
            // Update button visibility
            retakePhotoBtn.classList.add('hidden');
            startCameraBtn.classList.remove('hidden');
            
            // Restart the camera
            startCameraBtn.click();
        });
        
        // Payment method toggle
        const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
        const bkashExtra = document.getElementById('bkashExtra');
        const bankExtra = document.getElementById('bankExtra');
        
        paymentOptions.forEach(option => {
            option.addEventListener('change', () => {
                // Remove selected class from all payment options
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // Add selected class to the parent of the checked option
                if (option.checked) {
                    option.closest('.payment-option').classList.add('selected');
                }
                
                // Hide all extra fields first
                bkashExtra.style.display = 'none';
                bankExtra.style.display = 'none';
                
                // Clear required attributes
                document.getElementById('txn_id').required = false;
                document.getElementById('serial_number').required = false;
                
                // Show relevant extra fields
                if (option.value === 'bkash') {
                    bkashExtra.style.display = 'block';
                    document.getElementById('txn_id').required = true;
                } else if (option.value === 'bank') {
                    bankExtra.style.display = 'block';
                    document.getElementById('serial_number').required = true;
                }
            });
        });

        // Educational Background toggle
        function initializeEducationField() {
            const educationSelect = document.getElementById('educational_background');
            const otherEducationContainer = document.getElementById('otherEducationContainer');
            const otherEducationInput = document.getElementById('other_education');

            educationSelect.addEventListener('change', function() {
                if (this.value === 'others') {
                    otherEducationContainer.classList.remove('hidden');
                    otherEducationInput.required = true;
                } else {
                    otherEducationContainer.classList.add('hidden');
                    otherEducationInput.required = false;
                    otherEducationInput.value = '';
                }
            });
        }

        // DOB dropdown functionality
        function initializeDobSelection() {
            const daySelect = document.getElementById('dob_day');
            const monthSelect = document.getElementById('dob_month');
            const yearSelect = document.getElementById('dob_year');
            const dobHidden = document.getElementById('dob');

            function updateDob() {
                const day = daySelect.value;
                const month = monthSelect.value;
                const year = yearSelect.value;
                
                if (day && month && year) {
                    // Validate the date
                    const date = new Date(year, month - 1, day);
                    if (date.getDate() == day && date.getMonth() == month - 1 && date.getFullYear() == year) {
                        const formattedDate = `${year}-${month}-${day}`;
                        dobHidden.value = formattedDate;
                    } else {
                        dobHidden.value = '';
                        alert('Please select a valid date.');
                    }
                } else {
                    dobHidden.value = '';
                }
            }

            daySelect.addEventListener('change', updateDob);
            monthSelect.addEventListener('change', updateDob);
            yearSelect.addEventListener('change', updateDob);
        }

        // Course selection functionality
        function initializeCourseSelection() {
            const courseOptions = document.querySelectorAll('input[name="course_id"]');
            const selectedCourseInfo = document.getElementById('selectedCourseInfo');
            const selectedCourseName = document.getElementById('selectedCourseName');
            const selectedCourseFee = document.getElementById('selectedCourseFee');
            
            courseOptions.forEach(option => {
                option.addEventListener('change', function() {
                    // Remove selected class from all course options
                    document.querySelectorAll('.course-option').forEach(opt => {
                        opt.classList.remove('selected', 'border-indigo-500', 'bg-blue-50');
                        opt.classList.add('border-gray-300');
                    });
                    
                    // Add selected class to clicked option
                    if (this.checked) {
                        const courseOption = this.closest('.course-option');
                        courseOption.classList.add('selected', 'border-indigo-500', 'bg-blue-50');
                        courseOption.classList.remove('border-gray-300');
                        
                        // Get course details
                        const courseName = courseOption.querySelector('.font-medium').textContent;
                        const courseFee = courseOption.querySelector('.text-gray-500').textContent.replace('Fee: ', '');
                        
                        // Update selected course info
                        selectedCourseName.textContent = courseName;
                        selectedCourseFee.textContent = courseFee;
                        selectedCourseInfo.classList.remove('hidden');
                    }
                });
            });
            
            // Initialize course option styling
            document.querySelectorAll('.course-option').forEach(option => {
                option.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    if (radioInput) {
                        radioInput.checked = true;
                        
                        // Trigger change event
                        const event = new Event('change');
                        radioInput.dispatchEvent(event);
                    }
                });
            });
        }

        // Form submission with AJAX
        document.getElementById('admissionForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const requiredFields = [
                'name', 'dob', 'gender', 'mobile', 'emergency_mobile', 
                'email', 'address', 'educational_background', 'academic_year',
                'course_id', 'payment_method', 'photo_data'
            ];
            
            let isValid = true;
            let errorMessage = '';
            
            // Check if DOB is properly set
            const dobField = document.getElementById('dob');
            if (!dobField.value) {
                isValid = false;
                errorMessage += '• Please select a valid date of birth.\n';
            }
            
            // Check educational background
            const educationSelect = document.getElementById('educational_background');
            if (educationSelect.value === 'others') {
                const otherEducation = document.getElementById('other_education');
                if (!otherEducation.value.trim()) {
                    isValid = false;
                    errorMessage += '• Please specify your educational background.\n';
                }
            }
            
            // Check photo
            if (!photoData.value) {
                isValid = false;
                errorMessage += '• Please take a picture before submitting the form.\n';
            }
            
            // Check payment method extra fields
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (paymentMethod) {
                if (paymentMethod.value === 'bkash') {
                    const txnId = document.getElementById('txn_id');
                    if (!txnId.value.trim()) {
                        isValid = false;
                        errorMessage += '• Transaction ID is required for bKash payments.\n';
                    }
                } else if (paymentMethod.value === 'bank') {
                    const serialNumber = document.getElementById('serial_number');
                    if (!serialNumber.value.trim()) {
                        isValid = false;
                        errorMessage += '• Serial number is required for Bank payments.\n';
                    }
                }
            }
            
            if (!isValid) {
                alert('Please fix the following errors:\n' + errorMessage);
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
            submitBtn.disabled = true;
            this.classList.add('loading');
            
            try {
                const formData = new FormData(this);
                const csrfToken = getCsrfToken();
                
                if (!csrfToken) {
                    throw new Error('CSRF token not found. Please refresh the page and try again.');
                }
                
                console.log('Submitting form with CSRF token:', csrfToken.substring(0, 10) + '...');
                
                const response = await fetch('/student-admission', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                console.log('Response status:', response.status);
                
                const result = await response.json();
                console.log('Result:', result);
                
                if (response.status === 422) {
                    // Validation errors
                    let errorMessage = 'Please fix the following errors:\n';
                    
                    if (result.errors) {
                        for (const field in result.errors) {
                            errorMessage += `• ${result.errors[field][0]}\n`;
                        }
                    } else {
                        errorMessage = result.message || 'Please check your form and try again.';
                    }
                    
                    alert(errorMessage);
                    return;
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                if (result.success) {
                    alert('Application submitted successfully! Your application number: ' + result.application_number);
                    // Reset form
                    this.reset();
                    photoData.value = '';
                    capturedImage.style.display = 'none';
                    placeholderText.style.display = 'flex';
                    retakePhotoBtn.classList.add('hidden');
                    startCameraBtn.classList.remove('hidden');
                    
                    // Reset educational background fields
                    document.getElementById('otherEducationContainer').classList.add('hidden');
                    document.getElementById('other_education').value = '';
                    
                    // Reset payment extra fields
                    bkashExtra.style.display = 'none';
                    bankExtra.style.display = 'none';
                    
                    // Reset selected course info
                    document.getElementById('selectedCourseInfo').classList.add('hidden');
                    
                    // Remove selected class from payment and course options
                    document.querySelectorAll('.payment-option, .course-option').forEach(opt => {
                        opt.classList.remove('selected', 'border-indigo-500', 'bg-blue-50');
                    });
                    
                    // Redirect if needed
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    }
                } else {
                    alert(result.message || 'Failed to submit application. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                this.classList.remove('loading');
            }
        });

        // Initialize payment option styling
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });

        // Initialize all functionalities when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Debug: Check if CSRF token is available on page load
            const token = getCsrfToken();
            console.log('CSRF Token on load:', token ? token.substring(0, 10) + '...' : 'NOT FOUND');
            
            if (!token) {
                console.warn('CSRF token not found in form. This may cause submission issues.');
            }
            
            // Initialize all components
            initializeEducationField();
            initializeCourseSelection();
            initializeDobSelection();
        });
    </script>
</body>
</html>