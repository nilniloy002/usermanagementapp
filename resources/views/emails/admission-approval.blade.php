<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Approved - STS Institute</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            -webkit-font-smoothing: antialiased;

        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #192335 0%, #2d3748 100%);
            padding: 8px 5px;
            text-align: center;
            border-bottom: 4px solid #f38020;
        }
        
        .logo {
            margin-top:10px;
            max-width: 80px;
            height: auto;
        }
        
        .brand-text {
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            /* letter-spacing: 1px; */
            margin-bottom: 5px;
        }
        
        .tagline {
            color: #f38020;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            /* letter-spacing: 2px; */
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #f38020 50%, transparent 100%);
            margin: 25px 0;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 14px;
            font-weight: 400;
            color: #192335;
            margin-bottom: 25px;
            line-height: 28px;
        }
        
        .student-name {
            color: #f38020;
            font-weight: 700;
        }
        
        .course-name {
            color: #192335;
            font-weight: 700;
        }
        
        .approval-badge {
            color: green;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            /* letter-spacing: 1px; */
        }
        
        .student-id-section {
            background: linear-gradient(135deg, #f38020 0%, #ffd322 100%);
            color: rgb(0, 0, 0);
            border-radius: 6px;
            text-align: center;
            padding: 8px;
        }
        
        .student-id-label {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            color: #fff;
        }
        
        .student-id {
            font-size: 14px;
            font-weight: 800;
            /* letter-spacing: 1px; */
            display: inline-block;
            backdrop-filter: blur(10px);
        }
        
        .welcome-section {
            padding: 10px 0px;
        }
        
        .welcome-text {
            font-size: 14px;
            color: #192335;
            font-weight: 400;
        }
        
        .institute-name {
            color: #f38020;
            font-weight: 700;
        }
        
        .assets-section {
            margin: 15px 0;
        }
        
        .section-title {
            color: #192335;
            font-size: 14px;
            font-weight: 700;
            /* margin-bottom: 20px; */
            padding-bottom: 10px;
            border-bottom: 2px solid #f38020;
            display: inline-block;
        }
        
        .assets-list {
            list-style: none !important;
            padding: 0;
        }
        
        .assets-list li {
            padding: 6px 0 !important;
            border-bottom: 1px solid #e9ecef;
            display:flex !important;
            color: #192335 !important;
            font-weight: 500 !important;
            position: relative !important;
            /* padding-left: 25px !important; */
            font-size: 14px !important;
        }
        
        /* .assets-list li:before {
            content: "•" !important;
            color: #f38020 !important;
            font-weight: bold !important;
            position: absolute !important;
            left: 0 !important;
            font-size: 14px !important;
        }  */

        .assets-list li:last-child {
            border-bottom: none !important;
        }

        .assets-list-link {
            /* text-decoration: none !important; */
            color:  #0159fd !important;

        }
  
        .social-section {
            margin: 15px 0;
        }
        
        .social-title {
            color: #192335;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px !important;
        }
        
        .social-buttons {
            /* display: flex !important; */
            gap: 15px !important;
            flex-wrap: wrap !important;
        }
        
        .social-button {
            background: #192335 !important;
            color:  #ffffff !important;
            padding: 10px 8px !important;
            border-radius: 5px !important;
            text-decoration: none !important;
            font-weight: 500 !important;
            font-size: 12px !important;
            flex: 1 !important;
            min-width: 100px !important;
            text-align: center !important;
            transition: all 0.3s ease !important;
        }
        
        .social-button:hover {
            background: #192335;
            transform: translateY(-2px);
        }
        
        .contact-section {
            margin: 30px 0;
        }
        
        .contact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .contact-table td {
            padding: 6px 7px;
            border-bottom: 1px solid #e9ecef;
            color: #192335;
            font-weight: 500;
            font-size: 14px;
        }
        
        .contact-table tr:last-child td {
            border-bottom: none;
        }
        
        .contact-department {
            color: #192335;
            font-weight: 600;
            width: 50%;
        }
        
        .contact-number {
            color: #f38020;
            font-weight: 700;
            text-align: right;
        }
        
        .footer {
            background: #192335;
            color: white;
            padding: 10px 5px;
            text-align: center;
            border-top: 4px solid #f38020;
        }
        
        .signature {
            font-size: 14px;
            font-weight: 600;
            /* margin-bottom: 20px; */
            color: #192335;
        }
        
        .copyright {
            font-size: 14px;
            color: #ffffff;
            /* margin-top: 15px; */
        }
        
        /* Responsive Design */
        @media (max-width: 600px) {
            .content {
                padding: 25px 20px;
            }
            
            .student-id {
                font-size: 22px;
                padding: 8px 16px;
            }
            
            /* .social-buttons {
                flex-direction: column;
            } */
            
            .social-button {
                min-width: auto;
            }
            
            .contact-table td {
                padding: 10px;
                font-size: 14px;
            }
            
            .contact-department {
                width: 50%;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                padding: 20px 15px;
            }
            
            .brand-text {
                font-size: 20px;
            }
            
            .student-id {
                font-size: 18px;
            }
            
            .contact-table {
                font-size: 14px;
            }
        }

    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="https://sourceforces-img.sgp1.cdn.digitaloceanspaces.com/sts-logo.png" alt="STS Institute" class="logo">
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <span class="student-name">{{ $student->name }}</span>,<br>
                Congratulations! Your admission to the <span class="course-name">{{ $student->course->course_name ?? 'N/A' }}</span> program has been  
                <span class="approval-badge">successfully approved</span>.
            </div>

            <!-- Class schedule Section -->
            <div class="greeting">
                    Class Schedule: <span class="institute-name"> {{ $student->batch->batch_schedule ?? 'N/A' }}</span>
            </div>
            
            <!-- <div class="divider"></div> -->
            
            <!-- Student ID Section -->
            <div class="student-id-section">
                <div class="student-id-label">YOUR STUDENT ID <span class="student-id">{{ $student->student_id }}</span></div>
            </div>
            
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-text">
                    Welcome to <span class="institute-name">STS Institute.</span> We are excited to support your learning journey ahead!
                </div>
            </div>
            
            <!-- <div class="divider"></div> -->

            <!-- Course Assets - Only for IELTS On Computer -->
            @if($student->course && strpos(strtolower($student->course->course_name), 'ielts on computer') !== false)
            <div class="assets-section">
                <div class="section-title">Guide Assets - {{ $student->course->course_name }}</div>
                <ul class="assets-list">
                    <li><a href="https://cdielts.sts.institute/" class="assets-list-link" target="_blank">CD IELTS Practice Website</a></li>
                    <li><a href="https://ebook.stsinstitute.site/public/student/login" class="assets-list-link" target="_blank">E-books Login Portal</a></li>
                    <li><a href="https://elearning.sts.institute/product/ielts-grammar/" class="assets-list-link" target="_blank">Recorded Video Lessons (Grammar)</a></li>
                    <li><a href="https://elearning.sts.institute/product/ielts-grammar/" class="assets-list-link" target="_blank">Lab Practice Booking</a></li>
                </ul>
            </div>
            @endif
            
            <!-- Social Media -->
            <div class="social-section">
                <div class="social-title">Stay Connected:</div>
                <div class="social-buttons">
                    <a href="https://sts.institute/" class="social-button">Website</a>
                    <a href="https://www.facebook.com/TrainingSTS/" class="social-button">Facebook</a>
                    <a href="https://www.instagram.com/sts.it/" class="social-button">Instagram</a>
                    <a href="https://www.youtube.com/@STSinstitute" class="social-button">YouTube</a>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-section">
                <div class="section-title">☎️ Need Help? Contact Our Teams:</div>
                <table class="contact-table">
                    <tr>
                        <td class="contact-department">Admission/ Support Desk</td>
                        <td class="contact-number">01914-442202</td>
                    </tr>
                    <tr>
                        <td class="contact-department">IELTS/ PTE Registration/ Mock </td>
                        <td class="contact-number">01914-442203</td>
                    </tr>
                    <tr>
                        <td class="contact-department">IELTS Department</td>
                        <td class="contact-number">01914-442204</td>
                    </tr>
                    <tr>
                        <td class="contact-department">Language Department</td>
                        <td class="contact-number">01914-442205</td>
                    </tr>
                </table>
                <div class="divider" style="background: linear-gradient(90deg, transparent 0%, #f38020 50%, transparent 100%); margin: 20px auto; width: 80%;"></div>

                <div class="signature">Warm Regards,<br>Student Onboarding Team 
</div>
            <img src="https://sourceforces-img.sgp1.cdn.digitaloceanspaces.com/sts-logo.png" alt="STS Institute" class="logo">

            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="copyright">
                © 2025 STS Institute – Secret to Success
            </div>
        </div>
    </div>
</body>
</html>