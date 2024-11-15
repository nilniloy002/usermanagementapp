<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta Title, Description, and Keywords -->
    <meta name="title" content="Check Your Mock Test Result">
    <meta name="description" content="View your mock test results by entering the mock test date and mobile number. Get your test scores instantly.">
    <meta name="keywords" content="mock test, test result, candidate result, exam result, mock test score, IELTS result">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Check Your Mock Test Result">
    <meta property="og:description" content="View your mock test results by entering the mock test date and mobile number. Get your test scores instantly.">
    <meta property="og:image" content="{{ asset('path_to_og_image.jpg') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="https://sts.institute/wp-content/uploads/2024/08/cropped-Logo-Fav.-Icon-02-192x192.png" type="image/x-icon">

    <title>@lang('Check Candidate Mock Test Result')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <!-- Google Fonts (Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">


    <style>
        /* Apply Roboto font to the entire page */
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>

    @stack('styles')
</head>
<body style="background-color: #192335;">

    <!-- Page Wrapper -->
    <div class="min-h-screen flex items-center justify-center p-6">
        
        <!-- Form Container -->
        <div class="w-full max-w-lg sm:max-w-md bg-white p-8 rounded-lg shadow-md">
            
            <!-- Logo and Heading -->
            <div class="mb-6 text-center">
                <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="Logo" class="mx-auto h-11">
                <h2 class="text-3xl font-bold text-center flex-1 mt-5" style="font-size: 20px;color: #192335;">@lang('Check Your Mock Test Result')</h2>
            </div>
            
            <!-- Display Errors (if any) -->
            @include('partials.messages')

            <!-- Form Start -->
            {!! Form::open(['route' => 'candidate.result.search', 'method' => 'POST']) !!}

            <!-- Mock Test Date Input -->
            <div class="mb-4">
                <label for="mocktest_date" class="block text-sm font-medium text-gray-700">@lang('Mock Test Date')</label>
                <input type="text" id="mocktest_date" name="mocktest_date" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="dd/mm/yyyy" required>
            </div>

            <!-- Mobile Input -->
            <div class="mb-4">
                <label for="mobile" class="block text-sm font-medium text-gray-700">@lang('Mobile')</label>
                <input type="text" id="mobile" name="mobile" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="@lang('Mobile')" required>
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button type="submit" class="w-full py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    @lang('Get Result')
                </button>
            </div>

            {!! Form::close() !!}
            <!-- Form End -->
        </div>
        <!-- Form Container End -->

    </div>
    <!-- Page Wrapper End -->

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Initialize Flatpickr -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#mocktest_date", {
                dateFormat: "d/m/Y",  // Format the date as dd/mm/yyyy
                disableMobile: true    // Disable mobile datepicker style
            });
        });
    </script>

    @stack('scripts')

</body>
</html>
