<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Panga" />
    <meta name="description" content="Company Signup Form" />
    <link rel="icon" href="{{ asset('images/logo 1.png') }}" />
    <link rel="stylesheet" href="{{ asset('style/auth-2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/mediaQuery.css') }}" />

    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet" />

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>StationMgt | Company</title>


    <style>
        /* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow-y: auto;
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 30px;
    border-radius: 8px;
    width: 80%;
    max-width: 800px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    position: relative;
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 28px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
}

.close-modal:hover {
    color: #333;
}

.terms-content {
    max-height: 60vh;
    overflow-y: auto;
    padding: 15px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    margin: 20px 0;
}

.terms-content h3 {
    color: #069a9a;
    margin-top: 20px;
}

.terms-content p {
    margin-bottom: 15px;
    line-height: 1.6;
}

.terms-footer {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
}

.btn-primary {
    background-color: #069a9a;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary {
    background-color: #f5f5f5;
    color: #333;
    padding: 10px 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}
    </style>
</head>

<body id="company-body">
    <div class="picture-roll">
        <div class="slider"></div>
    </div>
    <div class="form-box">
        <form id="Signin" class="input-group" action="{{ route('register.company.store') }}" method="POST">
            @csrf
            <h2 id="intro">Company Account</h2>

            <div class="company-name">
                <input type="text" name="company_name" required />
                <label>Company Name</label>
            </div>

            <div class="company-email">
                <input type="email" name="company_email" required />
                <label>Company Email</label>
            </div>

            <div class="company-phone">
                <input type="text" name="company_phone" required />
                <label>Company Phone</label>
            </div>

            <div class="primary-email">
                <input type="email" name="primary_email" required />
                <label>Primary Email</label>
            </div>

            <div class="pincode">
                <input type="password" name="pin_code" required maxlength="4" minlength="4" pattern="\d{4}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);" />
                <label>Pin (4 digits)</label>
            </div>

            <div class="terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required />
                <label for="terms">
                    I agree to the
                    <a href="#" id="termsLink" aria-label="View Terms and Conditions">Terms and Conditions</a>
                </label>
            </div>

            <button type="submit" class="submit">Sign up</button>

            <div class="login-link">
                <p>
                    Already have an account?
                    <a href="{{ route('auth.login') }}">Login here</a>
                </p>
            </div>
        </form>
    </div>

    <!-- SweetAlert for Success and Error Messages -->

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Try Again',
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Validation Error!',
                text: '{{ implode(' ', $errors->all()) }}',
                icon: 'error',
                confirmButtonText: 'Try Again',
            });
        </script>
    @endif








    <!-- Terms and Conditions Modal -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Terms and Conditions</h2>
        <div class="terms-content">
            <h3>1. Acceptance of Terms</h3>
            <p>By using the STAK ERP platform ("Platform"), you agree to be bound by these Terms and Conditions ("Terms"). These Terms constitute a legally binding agreement between you and STAK, the provider of the Platform.</p>

            <h3>2. Account Registration and Access</h3>
            <p>2.1 You must provide accurate and complete information during registration. STAK reserves the right to terminate or suspend accounts providing false or misleading information.</p>
            <p>2.2 You are responsible for maintaining the confidentiality of your account credentials. Any unauthorized use of your account is strictly prohibited.</p>

            <h3>3. Platform Features and Usage</h3>
            <p>3.1 The Platform provides various enterprise resource planning (ERP) functionalities including customer relationship management (CRM), contract management, and user management.</p>
            <p>3.2 You agree to use the Platform only for legitimate business purposes and in compliance with all applicable laws and regulations.</p>

            <h3>4. Data Privacy and Security</h3>
            <p>4.1 STAK collects and processes personal data in accordance with our Privacy Policy. By using the Platform, you consent to the collection and processing of your data as described in our Privacy Policy.</p>
            <p>4.2 STAK implements reasonable security measures to protect your data. However, you acknowledge that no system is completely secure and STAK cannot guarantee absolute security.</p>

            <h3>5. Contract Management</h3>
            <p>5.1 The Platform provides tools for creating, managing, and signing contracts. You are responsible for ensuring the accuracy and legality of any contracts created through the Platform.</p>
            <p>5.2 STAK acts solely as a facilitator for contract management and is not a party to any contracts created through the Platform.</p>

            <h3>6. Customer Relationship Management (CRM)</h3>
            <p>6.1 The Platform includes CRM features for managing customer interactions, campaigns, and activities. You are responsible for maintaining the accuracy of customer data entered into the system.</p>
            <p>6.2 STAK may provide analytics and insights based on your CRM data, but you acknowledge that such insights are for informational purposes only and should not be considered professional advice.</p>

            <h3>7. User Management</h3>
            <p>7.1 Companies may create and manage multiple user accounts within their organization. The company administrator is responsible for managing user permissions and access levels.</p>
            <p>7.2 STAK reserves the right to suspend or terminate user accounts that violate these Terms or engage in unauthorized activities.</p>

            <h3>8. Intellectual Property</h3>
            <p>8.1 All intellectual property rights in the Platform, including but not limited to trademarks, copyrights, and patents, are owned by STAK.</p>
            <p>8.2 You retain ownership of any content you upload to the Platform, but grant STAK a non-exclusive, worldwide, royalty-free license to use such content for the purpose of providing the Platform services.</p>

            <h3>9. Service Availability</h3>
            <p>9.1 STAK strives to maintain high availability of the Platform but does not guarantee uninterrupted service. We may need to perform maintenance or updates that temporarily affect service availability.</p>
            <p>9.2 In the event of service disruption, STAK will use reasonable efforts to restore service as quickly as possible.</p>

            <h3>10. Payment Terms</h3>
            <p>10.1 Subscription fees are billed monthly/annually in advance, as specified in your subscription agreement.</p>
            <p>10.2 Payment is due upon receipt of invoice. Late payments may result in suspension of service.</p>

            <h3>11. Termination and Suspension</h3>
            <p>11.1 Either party may terminate these Terms at any time with 30 days written notice.</p>
            <p>11.2 STAK reserves the right to terminate or suspend your access to the Platform immediately if you breach these Terms or if required by law.</p>

            <h3>12. Limitation of Liability</h3>
            <p>12.1 STAK's total liability to you for any claim arising out of or in connection with these Terms shall be limited to the amount paid by you for the Platform services in the 12 months preceding the claim.</p>
            <p>12.2 STAK shall not be liable for any indirect, incidental, special, or consequential damages, including but not limited to loss of profits, even if advised of the possibility of such damages.</p>

            <h3>13. Governing Law</h3>
            <p>13.1 These Terms shall be governed by and construed in accordance with the laws of [Your Country], without regard to its conflict of law principles.</p>
            <p>13.2 Any disputes arising out of or in connection with these Terms shall be resolved through binding arbitration in accordance with the rules of [Your Arbitration Body].</p>

            <h3>14. Changes to Terms</h3>
            <p>14.1 STAK reserves the right to modify these Terms at any time. Any changes will be effective immediately upon posting on the Platform.</p>
            <p>14.2 Your continued use of the Platform after such changes constitutes your acceptance of the modified Terms.</p>

            <h3>15. Entire Agreement</h3>
            <p>15.1 These Terms constitute the entire agreement between you and STAK regarding the Platform and supersede all prior agreements and understandings.</p>
            <p>15.2 If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions shall continue in full force and effect.</p>
        </div>
        <div class="terms-footer">
            <button id="acceptTerms" class="btn-primary">I Accept</button>
            <button id="declineTerms" class="btn-secondary">Decline</button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const termsLink = document.querySelector('a[href="#"]');
        const termsModal = document.getElementById('termsModal');
        const closeModal = document.querySelector('.close-modal');
        const acceptBtn = document.getElementById('acceptTerms');
        const declineBtn = document.getElementById('declineTerms');
        const termsCheckbox = document.getElementById('terms');
    
        // Open modal when Terms link is clicked
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    
        // Close modal when X is clicked
        closeModal.addEventListener('click', function() {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    
        // Accept terms
        acceptBtn.addEventListener('click', function() {
            termsCheckbox.checked = true;
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    
        // Decline terms
        declineBtn.addEventListener('click', function() {
            termsCheckbox.checked = false;
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    
        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === termsModal) {
                termsModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    });
    </script>
</body>

</html>
