@extends('layouts.vertical', ['page_title' => 'Lead Details'])

@section('css')
<style>
    .lead-details-card {
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        border-radius: 0.75rem;
        border: none;
        background: #fff;
    }
    .section-title {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #727cf5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .info-table {
        margin-bottom: 0;
    }
    .info-table th {
        background-color: #f8f9fa;
        padding: 1rem;
        font-weight: 600;
        color: #2c3e50;
        width: 200px;
        border: none;
        font-size: 0.9rem;
    }
    .info-table td {
        padding: 1rem;
        color: #727cf5 !important;
        border: none;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
    }
    .info-table td a {
        color: #727cf5;
        text-decoration: none;
        position: relative;
    }
    .info-table td a:hover {
        text-decoration: none;
        color: #5b68e4;
    }
    .info-table td a::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 1px;
        bottom: -2px;
        left: 0;
        background-color: #5b68e4;
        transform: scaleX(0);
        transition: transform 0.2s ease;
    }
    .info-table td a:hover::after {
        transform: scaleX(1);
    }
    .info-table tr {
        border-bottom: 1px solid #eef2f7;
        transition: background-color 0.2s ease;
    }
    .info-table tr:hover {
        background-color: #f8f9fa;
    }
    .info-table tr:last-child {
        border-bottom: none;
    }
    .section {
        background: #fff;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.03);
    }
    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 2rem;
    }
    .badge-new {
        background-color: #0acf97;
        color: white;
    }
    .badge-qualified {
        background-color: #727cf5;
        color: white;
    }
    .badge-contacted {
        background-color: #ffbc00;
        color: white;
    }
    .badge-converted {
        background-color: #28a745;
        color: white;
    }
    .badge-lost {
        background-color: #dc3545;
        color: white;
    }
    .back-btn {
        background: linear-gradient(135deg, #727cf5 0%, #5b68e4 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(114, 124, 245, 0.3);
        color: white;
        text-decoration: none;
    }
    .lead-header {
        background: linear-gradient(135deg, #727cf5 0%, #5b68e4 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.75rem 0.75rem 0 0;
        margin-bottom: 0;
    }
    .lead-name {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .lead-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Lead Details</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/CRM/crmdash') }}">CRM Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/CRM/crm') }}">CRM</a></li>
                        <li class="breadcrumb-item active">Lead Details</li>
                    </ol>
                </div>
                <a href="{{ route('any', 'company/CRM/crm') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to CRM
                </a>
            </div>
        </div>
    </div>

    <!-- Lead Details -->
    <div class="row">
        <div class="col-12">
            <div class="card lead-details-card">
                <!-- Lead Header -->
                <div class="lead-header">
                    <div class="lead-name">{{ $lead->name ?? 'Unnamed Lead' }}</div>
                    <div class="lead-subtitle">
                        <span class="badge badge-{{ strtolower($lead->status ?? 'new') }}">
                            {{ $lead->status ?? 'New' }}
                        </span>
                        @if($lead->source)
                            <span class="ms-3">Source: {{ $lead->source }}</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="row">
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="section">
                                <h5 class="section-title">
                                    <i class="fas fa-user me-2"></i>Contact Information
                                </h5>
                                <table class="table info-table">
                                    <tbody>
                                        @if($lead->name)
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $lead->name }}</td>
                                        </tr>
                                        @endif
                                        @if($lead->email)
                                        <tr>
                                            <th>Email</th>
                                            <td>
                                                <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                            </td>
                                        </tr>
                                        @endif
                                        @if($lead->phone)
                                        <tr>
                                            <th>Phone</th>
                                            <td>
                                                <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>
                                            </td>
                                        </tr>
                                        @endif
                                        @if($lead->source)
                                        <tr>
                                            <th>Source</th>
                                            <td>{{ $lead->source }}</td>
                                        </tr>
                                        @endif
                                        @if($lead->status)
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge badge-{{ strtolower($lead->status) }}">
                                                    {{ $lead->status }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Contact Person Information -->
                        <div class="col-md-6">
                            <div class="section">
                                <h5 class="section-title">
                                    <i class="fas fa-user-tie me-2"></i>Contact Person
                                </h5>
                                <table class="table info-table">
                                    <tbody>
                                        @if($lead->contact_person)
                                        <tr>
                                            <th>Contact Person</th>
                                            <td>{{ $lead->contact_person }}</td>
                                        </tr>
                                        @endif
                                        @if($lead->contact_person_email)
                                        <tr>
                                            <th>Contact Email</th>
                                            <td>
                                                <a href="mailto:{{ $lead->contact_person_email }}">{{ $lead->contact_person_email }}</a>
                                            </td>
                                        </tr>
                                        @endif
                                        @if($lead->contact_person_phone)
                                        <tr>
                                            <th>Contact Phone</th>
                                            <td>
                                                <a href="tel:{{ $lead->contact_person_phone }}">{{ $lead->contact_person_phone }}</a>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Information -->
                    @if($lead->appointment_date || $lead->appointment_time || $lead->appointment_type)
                    <div class="row">
                        <div class="col-12">
                            <div class="section">
                                <h5 class="section-title">
                                    <i class="fas fa-calendar-alt me-2"></i>Appointment Details
                                </h5>
                                <table class="table info-table">
                                    <tbody>
                                        @if($lead->appointment_date)
                                        <tr>
                                            <th>Appointment Date</th>
                                            <td>{{ \Carbon\Carbon::parse($lead->appointment_date)->format('M d, Y') }}</td>
                                        </tr>
                                        @endif
                                        @if($lead->appointment_time)
                                        <tr>
                                            <th>Appointment Time</th>
                                            <td>{{ \Carbon\Carbon::parse($lead->appointment_time)->format('h:i A') }}</td>
                                        </tr>
                                        @endif
                                        @if($lead->appointment_type)
                                        <tr>
                                            <th>Appointment Type</th>
                                            <td>{{ $lead->appointment_type }}</td>
                                        </tr>
                                        @endif
                                        @if($lead->appointment_notes)
                                        <tr>
                                            <th>Appointment Notes</th>
                                            <td>{{ $lead->appointment_notes }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($lead->notes)
                    <div class="row">
                        <div class="col-12">
                            <div class="section">
                                <h5 class="section-title">
                                    <i class="fas fa-sticky-note me-2"></i>Notes
                                </h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $lead->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Lead Information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>Lead Information
                                </h5>
                                <table class="table info-table">
                                    <tbody>
                                        <tr>
                                            <th>Lead ID</th>
                                            <td>#{{ $lead->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created</th>
                                            <td>{{ $lead->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        @if($lead->updated_at && $lead->updated_at != $lead->created_at)
                                        <tr>
                                            <th>Last Updated</th>
                                            <td>{{ $lead->updated_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
