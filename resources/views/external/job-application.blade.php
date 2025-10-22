<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for {{ $job->title }} - GESL Careers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gesl-primary: #1a365d;
            --gesl-secondary: #2d3748;
            --gesl-accent: #3182ce;
            --gesl-light: #f7fafc;
            --gesl-border: #e2e8f0;
            --gesl-text: #2d3748;
            --gesl-text-light: #718096;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: var(--gesl-text);
            line-height: 1.6;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--gesl-primary) !important;
        }

        .application-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .company-header {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            border: 1px solid var(--gesl-border);
        }

        .company-logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 1rem;
        }

        .company-header h1 {
            color: var(--gesl-primary);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .company-header .subtitle {
            color: var(--gesl-text-light);
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .job-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid var(--gesl-border);
        }

        .job-header {
            background: linear-gradient(135deg, var(--gesl-primary) 0%, var(--gesl-secondary) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .job-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .job-meta {
            display: flex;
            justify-content: center;
            gap: 2rem;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .job-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .application-form {
            padding: 2.5rem;
        }

        .section-title {
            color: var(--gesl-primary);
            font-size: 1.25rem;
            font-weight: 600;
            margin: 2.5rem 0 1.5rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--gesl-accent);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--gesl-text);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--gesl-border);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--gesl-accent);
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--gesl-text-light);
        }

        .row {
            margin-bottom: 1.5rem;
        }

        .btn-submit {
            background: var(--gesl-accent);
            border: none;
            color: white;
            padding: 0.875rem 2.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(49, 130, 206, 0.2);
        }

        .btn-submit:hover {
            background: #2c5aa0;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(49, 130, 206, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background-color: #f0fff4;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }

        .alert-danger {
            background-color: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }

        .alert-warning {
            background-color: #fef5e7;
            color: #7c2d12;
            border-left: 4px solid #ed8936;
        }

        .file-upload-area {
            border: 2px dashed var(--gesl-border);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background-color: var(--gesl-light);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--gesl-accent);
            background-color: #edf2f7;
        }

        .file-upload-area.dragover {
            border-color: var(--gesl-accent);
            background-color: #ebf4ff;
        }

        .file-upload-icon {
            font-size: 2.5rem;
            color: var(--gesl-text-light);
            margin-bottom: 1rem;
        }

        .file-upload-text {
            color: var(--gesl-text);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .file-upload-subtext {
            color: var(--gesl-text-light);
            font-size: 0.875rem;
        }

        .required-field::after {
            content: " *";
            color: #e53e3e;
        }

        .footer {
            text-align: center;
            padding: 2rem 0;
            color: var(--gesl-text-light);
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .application-container {
                padding: 1rem;
            }

            .company-header, .application-form {
                padding: 1.5rem;
            }

            .company-header h1 {
                font-size: 2rem;
            }

            .job-header {
                padding: 1.5rem;
            }

            .job-header h2 {
                font-size: 1.5rem;
            }

            .job-meta {
                flex-direction: column;
                gap: 1rem;
            }

            .section-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="/images/gesl_logo.png" alt="GESL Logo" class="me-2" style="height: 40px;">
                GESL Careers
            </a>
        </div>
    </nav>

    <div class="application-container">
        <!-- Company Header -->
        <div class="company-header">
            <img src="/images/gesl_logo.png" alt="GESL Logo" class="company-logo">
            <h1>Join Our Team</h1>
            <p class="subtitle">Building Excellence in Technology & Infrastructure</p>
        </div>

        <!-- Job Details Card -->
        <div class="job-card">
            <div class="job-header">
                <h2>{{ $job->title }}</h2>
                <div class="job-meta">
                    <div class="job-meta-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $job->department }}</span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $job->location }}</span>
                    </div>
                    <div class="job-meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ ucfirst($job->type) }}</span>
                    </div>
                </div>
            </div>

            <div class="application-form">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($job->status !== 'open')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Applications Closed</strong>
                        <p class="mb-0 mt-2">This job position is currently not accepting new applications. The status is: <strong>{{ ucfirst($job->status) }}</strong></p>
                    </div>
                @endif

                @if($job->status === 'open')
                <form action="{{ route('external.job.application.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">

                    <!-- Personal Information -->
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label required-field">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="Enter your first name">
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label required-field">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Enter your last name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="email" class="form-label required-field">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label required-field">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+233 XX XXX XXXX">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Address Information
                    </h3>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="address" class="form-label">Street Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter your full street address">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="Enter city">
                        </div>
                        <div class="col-md-4">
                            <label for="region" class="form-label">Region/State</label>
                            <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region') }}" placeholder="Enter region">
                        </div>
                        <div class="col-md-4">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" placeholder="Enter country">
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <h3 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Professional Information
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality') }}" placeholder="Enter nationality">
                        </div>
                        <div class="col-md-6">
                            <label for="education_level" class="form-label">Education Level</label>
                            <select class="form-select @error('education_level') is-invalid @enderror" id="education_level" name="education_level">
                                <option value="">Select Education Level</option>
                                <option value="high_school" {{ old('education_level') == 'high_school' ? 'selected' : '' }}>High School</option>
                                <option value="diploma" {{ old('education_level') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="bachelors" {{ old('education_level') == 'bachelors' ? 'selected' : '' }}>Bachelor's Degree</option>
                                <option value="masters" {{ old('education_level') == 'masters' ? 'selected' : '' }}>Master's Degree</option>
                                <option value="phd" {{ old('education_level') == 'phd' ? 'selected' : '' }}>PhD</option>
                                <option value="other" {{ old('education_level') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="experience_years" class="form-label">Years of Experience</label>
                            <select class="form-select @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years">
                                <option value="">Select Years of Experience</option>
                                <option value="0" {{ old('experience_years') == '0' ? 'selected' : '' }}>Less than 1 year</option>
                                <option value="1" {{ old('experience_years') == '1' ? 'selected' : '' }}>1 year</option>
                                <option value="2" {{ old('experience_years') == '2' ? 'selected' : '' }}>2 years</option>
                                <option value="3" {{ old('experience_years') == '3' ? 'selected' : '' }}>3 years</option>
                                <option value="4" {{ old('experience_years') == '4' ? 'selected' : '' }}>4 years</option>
                                <option value="5" {{ old('experience_years') == '5' ? 'selected' : '' }}>5 years</option>
                                <option value="6" {{ old('experience_years') == '6' ? 'selected' : '' }}>6-10 years</option>
                                <option value="11" {{ old('experience_years') == '11' ? 'selected' : '' }}>11-15 years</option>
                                <option value="16" {{ old('experience_years') == '16' ? 'selected' : '' }}>16+ years</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cover Letter -->
                    <h3 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Cover Letter
                    </h3>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="cover_letter" class="form-label">Tell us why you're interested in this position</label>
                            <textarea class="form-control @error('cover_letter') is-invalid @enderror" id="cover_letter" name="cover_letter" rows="6" placeholder="Describe your motivation, relevant experience, and why you would be a great fit for this role...">{{ old('cover_letter') }}</textarea>
                            <div class="form-text">Maximum 2000 characters. This helps us understand your interest and fit for the role.</div>
                        </div>
                    </div>

                    <!-- Resume Upload -->
                    <h3 class="section-title">
                        <i class="fas fa-upload"></i>
                        Resume/CV
                    </h3>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label">Upload your resume (PDF, DOC, or DOCX - Max 5MB)</label>
                            <div class="file-upload-area" onclick="document.getElementById('resume').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">Click to upload or drag and drop your resume here</div>
                                <div class="file-upload-subtext">Supported formats: PDF, DOC, DOCX (Maximum 5MB)</div>
                                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" style="display: none;" onchange="updateFileName(this)">
                            </div>
                            <div id="file-name" class="mt-3 text-success fw-semibold" style="display: none;"></div>
                            @error('resume')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>Submit Application
                        </button>
                        <p class="mt-3 text-muted small">
                            By submitting this application, you agree to our terms and conditions.
                            We will process your information in accordance with our privacy policy.
                        </p>
                    </div>
                </form>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-lock fa-4x" style="color: var(--gesl-text-light);"></i>
                        </div>
                        <h4 style="color: var(--gesl-text);">Applications are currently closed for this position</h4>
                        <p style="color: var(--gesl-text-light);">Please check back later or contact HR for more information.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2025 GESL. All rights reserved. | <a href="#" style="color: var(--gesl-accent);">Privacy Policy</a> | <a href="#" style="color: var(--gesl-accent);">Terms of Service</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateFileName(input) {
            const fileNameDiv = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileNameDiv.textContent = 'Selected file: ' + input.files[0].name;
                fileNameDiv.style.display = 'block';
            } else {
                fileNameDiv.style.display = 'none';
            }
        }

        // Drag and drop functionality
        const fileUploadArea = document.querySelector('.file-upload-area');
        const fileInput = document.getElementById('resume');

        if (fileUploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                fileUploadArea.classList.add('dragover');
            }

            function unhighlight(e) {
                fileUploadArea.classList.remove('dragover');
            }

            fileUploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    updateFileName(fileInput);
                }
            }
        }
    </script>
</body>
</html>