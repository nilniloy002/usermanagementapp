<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Student ID Card - {{ $student->student_id }}</title>
      <style>
         @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
         @page {
         margin: 0;
         padding: 0;
         size: 330px 510px; /* Width: 320px, Height: 500px */
         }
         body {
         font-family: 'Roboto', Arial, sans-serif;
         margin: 0;
         padding: 0;
         width: 330px;
         height: 510px;
         background: white;
         }
         .id-card-wrapper {
         width: 320px;
         height: 500px;
         background: #fff;
         /* border-radius: 10px; */
         /* overflow: hidden; */
         border: 2px solid #f38020;
         box-shadow: 0 4px 10px rgba(0,0,0,0.1);
         display: flex;
         flex-direction: column;
         margin: 0px auto;
         margin-top: 2px;
         }
         /* Header */
         .id-header {
         background: #192335;
         padding: 15px;
         text-align: center;
         height: 30px;
         display: flex;
         align-items: center;
         justify-content: center;
         }
         .id-logo {
         width: 60px;
         height: auto;
         max-height: 50px;
         }
         /* Body */
         .id-body {
         flex: 1;
         background: #fff;
         padding: 15px 5px 5px;
         display: flex;
         flex-direction: column;
         align-items: center;
         gap: 5px;
         height: 320px;
         }
         .photo {
         width: 100px;
         height: 100px;
         border: 3px solid #f38020;
         border-radius: 50%;
         overflow: hidden;
         flex-shrink: 0;
         margin: 0px auto !important;
         }
         .photo img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         }
         .no-photo {
         width: 100%;
         height: 100%;
         background: #f8f9fa;
         display: flex;
         justify-content: center;
         align-items: center;
         color: #999;
         font-size: 40px;
         }
         .student-info {
         text-align: center;
         width: 100%;
         }
         .student-info h4 {
         margin: 0 0 8px 0;
         font-size: 18px;
         font-weight: 700;
         color: #192335;
         line-height: 1.2;
         }
         .student-id {
         font-size: 14px;
         color: #f38020;
         font-weight: 600;
         margin: 0 0 8px 0;
         font-family: 'Roboto', Arial, sans-serif;
         }
         .student-role {
         font-size: 14px;
         color: #555;
         margin: 0 0 5px 0;
         line-height: 1.2;
         }
         .student-batch {
         font-size: 14px;
         color: #555;
         margin: 0 0 8px 0;
         line-height: 1.2;
         }
         .valid-until {
         font-size: 12px;
         color: #666;
         margin: 10px 0 0 0;
         }
         /* Signature Section */
         .sign {
         position: relative;
         text-align: center;
         padding: 10px 15px 0;
         /* margin-top: 10px; */
         height: 40px;
         top:-25px;
         }
         .sign img {
         width: 40px !important;
         height: auto !important;
         margin-bottom: 5px;
         }
         .signature-line {
         border-top: 1px solid #192335;
         width: 150px !important;
         margin: 0 auto 5px !important;
         }
         .sign p {
         margin: 0;
         font-size: 12px;
         color: #192335;
         font-weight: 600;
         }
         /* Footer */
         .id-footer {
         background: #192335;
         padding: 10px 15px;
         color: #ffffff;
         text-align: center;
         height: 20px;
         display: flex;
         align-items: center;
         justify-content: center;
         position:relative;
         top:10px;
         }
         .signature-section small {
         font-size: 12px;
         color: #ffffff;
         font-weight: 500;
         }
         /* Ensure everything stays on one page */
         * {
         box-sizing: border-box;
         }
         /* Prevent any overflow */
         html, body {
         overflow: hidden;
         }
      </style>
   </head>
   <body>
      <div class="id-card-wrapper">
         <!-- Header -->
         <div class="id-header">
            @if(file_exists(public_path('assets/img/sts-logo.png')))
            <img src="{{ public_path('assets/img/sts-logo.png') }}" alt="Logo" class="id-logo">
            @else
            <!-- Fallback text if logo doesn't exist -->
            <div style="color: white; font-size: 18px; font-weight: bold;">STS INSTITUTE</div>
            @endif
         </div>
         <!-- Body -->
         <div class="id-body">
            <div class="photo">
               @php
               $filename = basename($student->photo_data);
               $filePath = public_path('student_photos/' . $filename);
               @endphp
               @if($student->photo_data && file_exists($filePath))
               <img src="{{ $filePath }}" alt="Student Photo">
               @else
               <div class="no-photo">
                  <span>👤</span>
               </div>
               @endif
            </div>
            <div class="student-info">
               <h4>{{ $student->name }}</h4>
               <p class="student-id">Student ID: {{ $student->student_id }}</p>
               <p class="student-role">{{ $student->course_name }}</p>
               <p class="student-batch">Batch: {{ $student->batch_code }}</p>
            </div>
         </div>
         <!-- Signature -->
         <div class="sign">
            @if(file_exists(public_path('assets/img/sign.png')))
            <img src="{{ public_path('assets/img/sign.png') }}" alt="Authorized Signature">
            @endif
            <div class="signature-line"></div>
            <p>Authorized Signature</p>
         </div>
         <!-- Footer -->
         <div class="id-footer">
            <div class="signature-section">
               <small>www.stsit.institute</small>
            </div>
         </div>
      </div>
   </body>
</html>