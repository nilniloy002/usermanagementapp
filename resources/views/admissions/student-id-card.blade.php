@extends('layouts.app')
@section('page-title', 'Student ID Card - ' . $student->name)
@section('page-heading', 'Student ID Card')
@section('breadcrumbs')
<li class="breadcrumb-item">
   <a href="{{ route('student-admissions.index') }}">Student Admissions</a>
</li>
<li class="breadcrumb-item active">ID Card - {{ $student->name }}</li>
@stop
@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card shadow">
            <div class="card-header bg-primary text-white">
               <h5 class="mb-0">
                  <i class="fas fa-id-card mr-2"></i> Student ID Card - {{ $student->student_id }}
               </h5>
            </div>
            <div class="card-body text-center">
               <!-- Action Buttons -->
         <!-- Action Buttons -->
                <div class="mb-4 text-right">
                    <a href="{{ route('student-admissions.download-id-card', $student->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    <a href="{{ route('student-admissions.download-id-card-image', $student->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-image"></i> Download Image
                    </a>
                    <a href="{{ route('student-admissions.show', $student->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
               <!-- ID Card -->
               <div class="d-flex justify-content-center">
                  <div class="id-card-wrapper">
                     <div class="id-card">
                        <!-- Header -->
                        <div class="id-header">
                           <img src="{{ asset('assets/img/sts-logo.png') }}" alt="Logo" class="id-logo">
                        </div>
                        <!-- Body -->
                        <div class="id-body">
                           <div class="photo">
                              @php
                              $filename = basename($student->photo_data);
                              $filePath = public_path('student_photos/' . $filename);
                              $imageUrl = asset('student_photos/' . $filename);
                              @endphp
                              @if(file_exists($filePath))
                              <img src="{{ $imageUrl }}" alt="Student Photo">
                              @else
                              <div class="no-photo">
                                 <i class="fas fa-user"></i>
                              </div>
                              @endif
                           </div>
                           <div class="student-info">
                              <h4 class="student-name">{{ $student->name }}</h4>
                              <p class="student-id">ID No: {{ $student->student_id }}</p>
                              <p class="student-role">{{ $student->course_name }}</p>
                              <p class="student-batch">Batch: {{ $student->batch_code }}</p>
                           </div>
                        </div>
                        <div class="sign">
                           <img src="{{ asset('assets/img/sign.png') }}" alt="Authorized Signature">
                           <div class="signature-line"></div>
                           <p>Authorized Signature</p>
                        </div>
                        <!-- Footer -->
                        <div class="id-footer">
                           <div class="signature-section">
                              <small>www.stsit.institute</small>
                           </div>
                        </div>
                        <!-- <div class="footer">
                           www.stsit.institute
                           </div> -->
                     </div>
                  </div>
               </div>
               <!-- Student Details -->
               <div class="card mt-4">
                  <div class="card-header">
                     <h6 class="mb-0">Student Details</h6>
                  </div>
                  <div class="card-body text-left">
                     <div class="row">
                        <div class="col-md-6">
                           <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
                           <p><strong>Application No:</strong> {{ $student->application_number }}</p>
                           <p><strong>Email:</strong> {{ $student->email }}</p>
                           <p><strong>Mobile:</strong> {{ $student->mobile }}</p>
                        </div>
                        <div class="col-md-6">
                           <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                           <p><strong>Date of Birth:</strong> {{ $student->dob->format('d M Y') }}</p>
                           <p><strong>Course:</strong> {{ $student->course_name }}</p>
                           <p><strong>Batch:</strong> {{ $student->batch_code }}</p>
                           <p><strong>Valid Until:</strong> {{ \Carbon\Carbon::now()->addYear()->format('M Y') }}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('styles')
<style>
   @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
   .id-card-wrapper {
   background: #f1f1f1;
   padding: 20px;
   border-radius: 10px;
   }
   .id-card {
   width: 320px;
   height: 500px;
   background: #fff;
   border-radius: 10px;
   overflow: hidden;
   border: 2px solid #f38020;
   box-shadow: 0 8px 20px rgba(0,0,0,0.1);
   display: flex;
   flex-direction: column;
   font-family: 'Roboto', sans-serif;
   }
   /* Header */
   .id-header {
   background: #192335;
   padding: 15px;
   text-align: center;
   }
   .id-logo {
   width: 80px;
   height: auto;
   }
   /* Body */
   .id-body {
   flex: 1;
   background: #fff;
   padding: 30px 10px 10px;
   text-align: center;
   }
   .photo {
   width: 110px;
   height: 110px;
   border: 4px solid #f38020;
   border-radius: 50%;
   margin: 0 auto 15px;
   overflow: hidden;
   }
   .photo img {
   width: 100%;
   height: 100%;
   object-fit: cover;
   }
   .no-photo {
   width: 100%;
   height: 100%;
   background: #eee;
   display: flex;
   justify-content: center;
   align-items: center;
   color: #999;
   font-size: 40px;
   }
   .student-info h4 {
   margin-bottom: 5px;
   font-weight: 700;
   color: #192335;
   }
   .student-role {
   font-size: 14px;
   color: #555;
   margin-bottom: 2px;
   }
   .student-batch {
   font-size: 13px;
   color: #555;
   margin-bottom: 5px;
   }
   .student-id {
   font-size: 13px;
   color: #f38020;
   font-weight: 600;
   }
   /* Footer */
   .id-footer
   {
   background: #192335;
   padding: 8px 5px;
   color: #ffffff;
   }
   .signature-section {
   display: flex;
   justify-content: center;
   padding: 0 15px;
   }
   .signature-block {
   text-align: center;
   width: 45%;
   }
   .signature-line {
   border-top: 1px solid #192335;
   width: 50% !important;
   margin: 0px auto !important;
   margin-bottom: 5px;
   }
   .signature-block small {
   font-size: 11px;
   color: #192335;
   font-weight: 600;
   }
   .sign img {
   width: 15% !important;
   height: auto !important;
   }
   .footer {
   position: absolute;
   bottom: 0;
   width: 100%;
   background: #001f3f;
   color: white;
   text-align: center;
   padding: 8px 0;
   font-size: 12px;
   }
   /* Print */
   @media print {
   .btn, .card-header, .card.mt-4 { display: none !important; }
   .id-card-wrapper { background: none; padding: 0; }
   .id-card { border: none; box-shadow: none; }
   }
</style>
@endsection