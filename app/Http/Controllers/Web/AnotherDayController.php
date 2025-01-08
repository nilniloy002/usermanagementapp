<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanguard\AnotherDay;
use Vanguard\Http\Requests\AnotherDay\CreateAnotherDayRequest;
use Vanguard\Http\Requests\AnotherDay\UpdateAnotherDayRequest;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Imports\AnotherDayImport;
use Illuminate\Support\Facades\Mail;
use DB;


class AnotherDayController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Modify the query to include the search functionality if needed
        $anotherDays = AnotherDay::query()
            ->where('candidate_email', 'like', '%' . $search . '%')
            ->orWhere('trainers_email', 'like', '%' . $search . '%')
            ->paginate(10); // Paginate with 10 results per page
        
        return view('anotherdays.index', compact('anotherDays'));
    }
    

    public function create()
    {
        return view('anotherdays.add-edit', ['edit' => false]);
    }

    public function store(CreateAnotherDayRequest $request)
    {
        AnotherDay::create($request->validated());

        return redirect()->route('another_days.index')->with('success', 'Booking created successfully.');
    }

    public function edit(AnotherDay $anotherDay)
    {
        return view('anotherdays.add-edit', compact('anotherDay'), ['edit' => true]);
    }

    public function update(UpdateAnotherDayRequest $request, AnotherDay $anotherDay)
    {
        $anotherDay->update($request->validated());

        return redirect()->route('another_days.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(AnotherDay $anotherDay)
    {
        $anotherDay->delete();

        return redirect()->route('another_days.index')->with('success', 'Booking deleted successfully.');
    }

     /**
     * Display the import form.
     */
    public function showImportForm()
    {
        return view('anotherdays.importanotherday');
    }

    /**
     * Handle the import of Another Day data.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
    
        try {
            $import = new AnotherDayImport();
            Excel::import($import, $request->file('file'));
    
            // Send emails for each imported record
            foreach ($import->getImportedData() as $data) {
                $anotherDay = AnotherDay::where($data)->first(); // Fetch the record created by the import
                if ($anotherDay) {
                    $this->sendEmails($data, $anotherDay->id);
                }
            }
    
            return redirect()->route('another_days.index')->with('success', 'Data imported successfully and emails sent.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import data: ' . $e->getMessage());
        }
    }
    

    // private function sendEmails($data, $anotherDayId)
    // {
    //     try {
    //         $trackingUrl = route('another_days.email.track', [
    //             'id' => $anotherDayId,
    //             'email' => $data['candidate_email']
    //         ]);
    
    //         Mail::send([], [], function ($message) use ($data, $trackingUrl) {
    //             $message->from('mocktest@sts.institute', 'Speaking Test Schedule | STS');
    //             $message->to($data['candidate_email'])
    //                 ->bcc($data['trainers_email'])
    //                 ->subject('Speaking Test Schedule | STS')
    //                 ->html("
    //                     <p>Your speaking test is scheduled as follows:</p>
    //                     <ul>
    //                         <li><strong>Date:</strong> {$data['speaking_date']}</li>
    //                         <li><strong>Time:</strong> {$data['speaking_time']}</li>
    //                         <li><strong>Zoom Link:</strong> <a href=\"{$data['zoom_link']}\">{$data['zoom_link']}</a></li>
    //                     </ul>
    //                     <img src='{$trackingUrl}' style='display:none;' />
    //                 ");
    //         });            
    
    //         // Log email as sent
    //         $this->logEmail($anotherDayId, $data['candidate_email'], 'sent', 'Mock Test Schedule', $data['zoom_link']);
    //     } catch (\Exception $e) {
    //         // Log email as failed
    //         $this->logEmail($anotherDayId, $data['candidate_email'], 'failed', 'Mock Test Schedule', $data['zoom_link']);
    //     }
    // }
    
    private function sendEmails(array $data, int $anotherDayId)
    {
        $trackingUrl = route('another_days.email.track', [
            'id' => $anotherDayId,
            'email' => $data['candidate_email']
        ]);
    
        $emailContent = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Speaking Test Schedule</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        margin: 0;
                        padding: 0;
                        background-color: #f9f9f9;
                    }
                    .container {
                        width: 100%;
                        max-width: 600px;
                        margin: 20px auto;
                        background: #ffffff;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .header h2 {
                        margin: 0;
                        font-size: 24px;
                        color: #333;
                    }
                    .content {
                        font-size: 16px;
                        color: #333;
                    }
                    .content ul {
                        list-style: none;
                        padding: 0;
                    }
                    .content ul li {
                        margin-bottom: 10px;
                    }
                    .content ul li strong {
                        font-weight: bold;
                    }
                    .footer {
                        text-align: center;
                        font-size: 14px;
                        color: #666;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Speaking Test Schedule | STS</h2>
                    </div>
                    <div class='content'>
                        <p>Your speaking test is scheduled as follows:</p>
                        <ul>
                            <li><strong>Date:</strong> {$data['speaking_date']}</li>
                            <li><strong>Time:</strong> {$data['speaking_time']}</li>
                            <li><strong>Zoom Link:</strong> <a href='{$data['zoom_link']}'>{$data['zoom_link']}</a></li>
                        </ul>
                    </div>
                    <div class='footer'>
                        <p>Thank you for choosing STS Institute. Best wishes for your test!</p>
                        <img src='{$trackingUrl}' style='display:none;' />
                    </div>
                </div>
            </body>
            </html>
        ";
    
        try {
            $emailSubject = "Speaking Test Schedule | {$data['speaking_date']} | {$data['speaking_time']}";
            
            Mail::send([], [], function ($message) use ($data, $emailContent, $emailSubject) {
                $message->from('mocktest@sts.institute', 'Speaking Test Schedule | STS')
                        ->to($data['candidate_email'])
                        ->cc('stsittrainingmgt@gmail.com')
                        ->bcc($data['trainers_email'])
                        ->subject($emailSubject)
                        ->html($emailContent);
            });
    
            $this->logEmail($anotherDayId, $data['candidate_email'], 'sent', $emailSubject, $emailContent);
        } catch (\Exception $e) {
            $emailSubject = "Speaking Test Schedule | {$data['speaking_date']} | {$data['speaking_time']}";
            $this->logEmail($anotherDayId, $data['candidate_email'], 'failed', $emailSubject, $emailContent);
        }
    }
    
    


    private function logEmail($anotherDayId, $recipient, $status, $subject, $body)
    {
        \DB::table('email_logs')->insert([
            'another_day_id' => $anotherDayId,
            'recipient' => $recipient,
            'status' => $status,
            'subject' => $subject,
            'body' => $body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function trackEmail(Request $request, $id)
    {
        $email = $request->query('email');
    
        \Log::info('Email opened', [
            'id' => $id,
            'email' => $email,
            'timestamp' => now(),
        ]);
    
        if ($email) {
            \DB::table('email_logs')
                ->where('another_day_id', $id)
                ->where('recipient', $email)
                ->update([
                    'status' => 'opened',
                    'opened_at' => now(),
                ]);
        }
    
        // Return a transparent pixel
        $pixel = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/wcAAmIB/QHo/RcAAAAASUVORK5CYII='
        );
    
        return response($pixel)->header('Content-Type', 'image/png');
    }
    

    public function emailReport()
    {
        $emailLogs = \DB::table('email_logs')
            ->join('another_days', 'email_logs.another_day_id', '=', 'another_days.id')
            ->select('email_logs.*')
            ->orderBy('email_logs.created_at', 'desc')
            ->get();

        return view('anotherdays.email-report', compact('emailLogs'));
    }


    
}
