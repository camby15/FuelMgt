@extends('layouts.vertical-admin', ['page_title' => 'Site Settings'])
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Global Site Settings</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">General Application Settings</h5>
                                    <form method="POST" action="" id="settingsForm">    <!-- Replace with your form action -->
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="site_name" name="site_name" placeholder=" " value="{{ old('site_name', $settings->site_name ?? '') }}" required>
                                            <label for="site_name">Site Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="admin_email" name="admin_email" placeholder=" " value="{{ old('admin_email', $settings->admin_email ?? '') }}" required>
                                            <label for="admin_email">Admin Email</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="support_contact" name="support_contact" placeholder=" " value="{{ old('support_contact', $settings->support_contact ?? '') }}">
                                            <label for="support_contact">Support Contact</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="site_logo" class="form-label">Site Logo</label>
                                            <input class="form-control" type="file" id="site_logo" name="site_logo">
                                        </div>
                                        <div class="mb-3">
                                            <label for="favicon" class="form-label">Favicon</label>
                                            <input class="form-control" type="file" id="favicon" name="favicon">
                                        </div>
                                        <button type="submit" class="btn btn-success">Save Settings</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Notification Settings</h5>
                                    <form>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="notify_invites" checked>
                                            <label class="form-check-label" for="notify_invites">User Invite Emails</label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="notify_resets" checked>
                                            <label class="form-check-label" for="notify_resets">Password Reset Emails</label>
                                        </div>
                                        <button type="button" class="btn btn-primary">Save Notifications</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Security Settings</h5>
                                    <form>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="password_policy" placeholder=" ">
                                                <option>Minimum 8 characters</option>
                                                <option>Minimum 12 characters + symbols</option>
                                            </select>
                                            <label for="password_policy">Password Policy</label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="two_factor">
                                            <label class="form-check-label" for="two_factor">Enable Two-Factor Authentication</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="session_timeout" value="30" placeholder=" ">
                                            <label for="session_timeout">Session Timeout (minutes)</label>
                                        </div>
                                        <button type="button" class="btn btn-primary">Save Security</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">User Management Settings</h5>
                                    <form>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="default_role" placeholder=" ">
                                                <option>Member</option>
                                                <option>Manager</option>
                                                <option>Admin</option>
                                            </select>
                                            <label for="default_role">Default User Role</label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="account_activation">
                                            <label class="form-check-label" for="account_activation">Manual Account Activation</label>
                                        </div>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="registration_toggle" checked>
                                            <label class="form-check-label" for="registration_toggle">Enable User Registration</label>
                                        </div>
                                        <button type="button" class="btn btn-primary">Save User Management</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">API & Integrations</h5>
                                    <form>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="api_key" value="sk-xxxxxx" readonly placeholder=" ">
                                            <label for="api_key">API Key</label>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary mt-2" id="generateApiKey">Generate New Key</button>
                                        <button type="button" class="btn btn-outline-danger mt-2" id="revokeApiKey">Revoke Key</button>
                                        <button type="button" class="btn btn-primary">Save API Settings</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Billing & Subscription</h5>
                                    <form>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="payment_gateway" placeholder=" ">
                                                <option selected>ShrinqPay</option>
                                                <option>PayPal</option>
                                                <option>Stripe</option>
                                                <option>Manual</option>
                                                <option>MoMo</option>
                                            </select>
                                            <label for="payment_gateway">Payment Gateway</label>
                                        </div>
                                        <button type="button" class="btn btn-primary">Save Billing</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('settingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'Settings Saved',
            text: 'Global site settings have been updated!',
            timer: 2000,
            showConfirmButton: false
        });
    });
    // Demo JS for new features
    document.getElementById('generateApiKey')?.addEventListener('click', function() {
        Swal.fire('API Key Generated', 'A new API key has been created.', 'success');
    });
    document.getElementById('revokeApiKey')?.addEventListener('click', function() {
        Swal.fire('API Key Revoked', 'The API key has been revoked.', 'warning');
    });
    document.getElementById('saveBilling')?.addEventListener('click', function() {
        Swal.fire('Billing Saved', 'Billing settings have been updated.', 'success');
    });
</script>
@endsection
