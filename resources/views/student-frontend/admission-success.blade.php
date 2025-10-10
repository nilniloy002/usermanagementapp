<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted Successfully</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Application Submitted Successfully!</h1>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-gray-600 mb-2">Your application number:</p>
            <p class="text-xl font-bold text-indigo-600">{{ $admission->application_number }}</p>
        </div>
        
        <p class="text-gray-600 mb-6">
            Thank you for submitting your application. We will review your information and contact you shortly.
        </p>
        
        <div class="space-y-3">
            <a href="{{ route('admission.form') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg transition">
                Submit Another Application
            </a>
            <a href="/" class="block w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg transition">
                Return to Homepage
            </a>
        </div>
    </div>
</body>
</html>