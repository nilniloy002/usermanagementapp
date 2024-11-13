<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('Candidate Result')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Apply Roboto font to the entire page */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #192335;
        }
        /* Hide elements that should not be printed */
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8" id="result-content">
            
            <!-- Logo and Heading -->
            <div class="mb-6 text-center">
                <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="Logo" class="mx-auto h-11">
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6 mt-5">@lang('Your Mock Test Result')</h2>
            </div>

            <!-- Grid Section for Mock Test Date, Name, and Mobile -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <!-- Mock Test Date -->
                <div class="text-center sm:text-left">
                    <div class="font-medium text-gray-600">@lang('Mock Test Date')</div>
                    <div>{{ \Carbon\Carbon::parse($result->mocktest_date)->format('d/m/Y') }}</div>
                </div>
                
                <!-- Name -->
                <div class="text-center sm:text-center">
                    <div class="font-medium text-gray-600">@lang('Name')</div>
                    <div class="font-bold">{{ $result->name }}</div>
                </div>
                
                <!-- Mobile -->
                <div class="text-center sm:text-right">
                    <div class="font-medium text-gray-600">@lang('Mobile')</div>
                    <div>{{ $result->mobile }}</div>
                </div>
            </div>

            <!-- Scores Section -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <table class="min-w-full table-auto border-collapse">
                    <tbody>
                        <!-- Listening Score -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Listening Score')</th>
                            <td class="px-4 py-2">{{($result->lstn_score) }}</td>
                        </tr>

                        <!-- Speaking Score -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Speaking Score')</th>
                            <td class="px-4 py-2">{{ ($result->speak_score) }}</td>
                        </tr>

                        <!-- Reading Score -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Reading Score')</th>
                            <td class="px-4 py-2">{{ $result->read_score }}</td>
                        </tr>

                        <!-- Writing Score -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Writing Score')</th>
                            <td class="px-4 py-2">{{ $result->wrt_score}}</td>
                        </tr>

                        <!-- Overall Band Score -->
                        <tr class="border-b bg-red-600 text-white">
                            <th class="px-4 py-2 text-left font-medium">@lang('Overall Band Score')</th>
                            <td class="px-4 py-2">{{ $result->overall_score }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Print/PDF Button -->
            <div class="mt-6 text-center">
                <button onclick="printResult()" class="print-button px-3 py-1 bg-[#192335] text-white text-sm font-thin rounded hover:bg-gray-700">
                    @lang('Print / Download PDF')
                </button>
            </div>


        </div>
    </div>

    <script>
        function printResult() {
            window.print();
        }
    </script>

</body>
</html>
