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
        }
    </style>

</head>
<body style="background-color: #192335;">

    <div class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
              <!-- Logo and Heading -->
              <div class="mb-6 text-center">
                <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="Logo" class="mx-auto h-11">
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6 mt-5">@lang('Your Mock Test Result')</h2>
            </div>

            

            <!-- Table with result details -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <tbody>
                        <tr class="border-b">
                        <tr class="border-b">
                        <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Mock Test Date')</th>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($result->mocktest_date)->format('d/m/Y') }}</td>

                        </tr>
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Name')</th>
                            <td class="px-4 py-2">{{ $result->name }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Mobile')</th>
                            <td class="px-4 py-2">{{ $result->mobile }}</td>
                        </tr>
                        <!-- <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Listening Correct Answer')</th>
                            <td class="px-4 py-2">{{ $result->lstn_cor_ans }}</td>
                        </tr> -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Listening Score')</th>
                            <td class="px-4 py-2">{{ $result->lstn_score }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Speaking Score')</th>
                            <td class="px-4 py-2">{{ $result->speak_score }}</td>
                        </tr>
                        <!-- <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Reading Correct Answer')</th>
                            <td class="px-4 py-2">{{ $result->read_cor_ans }}</td>
                        </tr> -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Reading Score')</th>
                            <td class="px-4 py-2">{{ $result->read_score }}</td>
                        </tr>
                        <!-- <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Writing Task 1')</th>
                            <td class="px-4 py-2">{{ $result->wrt_task1 }}</td>
                        </tr> -->
                        <!-- <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Writing Task 2')</th>
                            <td class="px-4 py-2">{{ $result->wrt_task2 }}</td>
                        </tr> -->
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left font-medium text-gray-600">@lang('Writing Score')</th>
                            <td class="px-4 py-2">{{ $result->wrt_score }}</td>
                        </tr>
                        <tr class="border-b bg-red-600 text-white">
                    <th class="px-4 py-2 text-left font-medium">@lang('Overall Band Score')</th>
                    <td class="px-4 py-2">{{ $result->overall_score }}</td>
                </tr>
                    
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>
