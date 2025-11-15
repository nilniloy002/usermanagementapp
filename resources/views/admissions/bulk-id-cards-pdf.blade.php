<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bulk Student ID Cards</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        
        @page {
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background: white;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .id-card-container {
            display: inline-block;
            margin: 5px;
            page-break-inside: avoid;
        }
        
        .id-card {
            width: 306px;
            height: 486px;
            border: 2px solid #192335;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            position: relative;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        
        .id-card-header {
            background: #192335;
            color: white;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 60px;
        }
        
        .logo-section {
            flex-shrink: 0;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 5px;
            background: white;
            padding: 2px;
        }
        
        .header-text {
            flex: 1;
        }
        
        .header-text h4 {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
        }
        
        .header-text p {
            margin: 2px 0 0 0;
            font-size: 9px;
            color: #f38020;
            font-weight: 500;
        }
        
        .id-card-body {
            padding: 12px;
            display: flex;
            gap: 10px;
            height: calc(100% - 100px);
            box-sizing: border-box;
        }
        
        .photo-section {
            flex-shrink: 0;
        }
        
        .id-photo {
            width: 70px;
            height: 85px;
            border: 2px solid #f38020;
            border-radius: 3px;
            object-fit: cover;
        }
        
        .no-photo {
            width: 70px;
            height: 85px;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 8px;
            text-align: center;
        }
        
        .info-section {
            flex: 1;
            font-size: 9px;
        }
        
        .info-item {
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px dashed #e9ecef;
        }
        
        .info-item strong {
            color: #192335;
            display: inline-block;
            width: 45px;
            font-weight: 600;
        }
        
        .student-id {
            font-weight: 700;
            color: #f38020;
            font-size: 10px;
        }
        
        .id-card-footer {
            background: #f8f9fa;
            padding: 6px 10px;
            border-top: 2px solid #f38020;
            text-align: center;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        .contact-info {
            text-align: center;
            margin-bottom: 4px;
        }
        
        .contact-info small {
            color: #192335;
            font-size: 7px;
            font-weight: 500;
        }
        
        .signature-area {
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #192335;
            width: 60%;
            margin: 0 auto 2px;
        }
        
        .signature-area small {
            color: #192335;
            font-size: 7px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    @foreach($students as $index => $student)
        <div class="id-card-container">
            <div class="id-card">
                <div class="id-card-header">
                    <div class="logo-section">
                        <img src="https://sourceforces-img.sgp1.cdn.digitaloceanspaces.com/sts-logo.png" 
                             alt="STS Logo" 
                             class="logo">
                    </div>
                    <div class="header-text">
                        <h4>STUDENT ID CARD</h4>
                        <p>Source Force Technology</p>
                    </div>
                </div>
                
                <div class="id-card-body">
                    <div class="photo-section">
                        @if($student->photo_url && file_exists(public_path(str_replace(url('/'), '', $student->photo_url))))
                            <img src="{{ $student->photo_url }}" class="id-photo" alt="Student Photo">
                        @else
                            <div class="no-photo">
                                <span>👤</span>
                                <span>No Photo</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="info-section">
                        <div class="info-item">
                            <strong>ID:</strong> 
                            <span class="student-id">{{ $student->student_id }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Name:</strong> 
                            {{ $student->name }}
                        </div>
                        <div class="info-item">
                            <strong>Course:</strong> 
                            {{ $student->course_name }}
                        </div>
                        <div class="info-item">
                            <strong>Batch:</strong> 
                            {{ $student->batch_code }}
                        </div>
                        <div class="info-item">
                            <strong>Valid Until:</strong> 
                            {{ \Carbon\Carbon::now()->addYear()->format('M Y') }}
                        </div>
                    </div>
                </div>
                
                <div class="id-card-footer">
                    <div class="contact-info">
                        <small>Phone: +880 1234-567890 | Email: info@sourceforcetech.com</small>
                    </div>
                    <div class="signature-area">
                        <div class="signature-line"></div>
                        <small>Authorized Signature</small>
                    </div>
                </div>
            </div>
        </div>

        @if(($index + 1) % 2 == 0)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>