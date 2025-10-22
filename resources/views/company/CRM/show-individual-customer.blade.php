@extends('layouts.vertical', ['page_title' => 'Individual Customer Details'])
@php
use App\Helpers\CurrencyHelper;
@endphp     


@section('css')
<style>
    .customer-details-card {
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
    .badge-active {
        background-color: #0acf97;
        color: white;
    }
    .badge-inactive {
        background-color: #fa5c7c;
        color: white;
    }
    .badge-pending {
        background-color: #ffbc00;
        color: white;
    }
    .badge-suspended {
        background-color: #727cf5;
        color: white;
    }
    .customer-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .customer-avatar {
        width: 64px;
        height: 64px;
        background: #727cf5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .customer-title {
        flex-grow: 1;
        min-width: 200px;
    }
    .customer-title h4 {
        margin: 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .customer-title p {
        margin: 0.25rem 0 0;
        color: #6c757d;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .btn {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
    .btn i {
        font-size: 0.9rem;
    }
    .value-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .value-text {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 600;
    }
    .not-provided {
        color: #99a6b7;
        font-style: italic;
    }
    @media (max-width: 768px) {
        .info-table th,
        .info-table td {
            display: block;
            width: 100%;
        }
        .info-table th {
            padding-bottom: 0.5rem;
        }
        .info-table td {
            padding-top: 0.5rem;
        }
        .info-table tr {
            display: block;
            padding: 0.5rem 0;
        }
        .customer-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .action-buttons {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card customer-details-card">
                <div class="card-body">
                    <div class="customer-header">
                        <div class="customer-avatar">
                            {{ strtoupper(substr($customer->name ?? 'C', 0, 1)) }}
                        </div>
                        <div class="customer-title">
                            <h4>
                                {{ $customer->name }}
                                <span class="badge badge-{{ strtolower($customer->status) }}">{{ $customer->status }}</span>
                            </h4>
                            <p>{{ $customer->email ?: 'No email provided' }}</p>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('company.customers.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <a href="{{ route('company.customers.edit', $customer->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="section">
                        <h5 class="section-title">Basic Information</h5>
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{!! $customer->name ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                    <th>Title</th>
                                    <td>{!! $customer->title ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{!! $customer->email ? '<a href="mailto:'.e($customer->email).'">'.e($customer->email).'</a>' : '<span class="not-provided">Not provided</span>' !!}</td>
                                    <th>Phone</th>
                                    <td>{!! $customer->phone ? '<a href="tel:'.e($customer->phone).'">'.e($customer->phone).'</a>' : '<span class="not-provided">Not provided</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td colspan="3">{{ $customer->address ?: '<span class="not-provided">Not provided</span>' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Personal Details -->
                    <div class="section">
                        <h5 class="section-title">Personal Details</h5>
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Title</th>
                                    <td>{!! $customer->title ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                    <th>Name</th>
                                    <td>{!! $customer->name ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{!! $customer->gender ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                    <th>Date of Birth</th>
                                    <td>
                                        @if($customer->date_of_birth)
                                            {{ \Carbon\Carbon::parse($customer->date_of_birth)->format('Y-m-d') }}
                                        @else
                                            <span class="not-provided">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Marital Status</th>
                                    <td>{!! $customer->marital_status ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                  
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td colspan="3">
                                        {!! $customer->phone ? '<a href="tel:'.e($customer->phone).'">'.e($customer->phone).'</a>' : '<span class="not-provided">Not provided</span>' !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td colspan="3">
                                        {!! $customer->email ? '<a href="mailto:'.e($customer->email).'">'.e($customer->email).'</a>' : '<span class="not-provided">Not provided</span>' !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td colspan="3">
                                        {!! $customer->address ?: '<span class="not-provided">Not provided</span>' !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Customer Management Info -->
                    <div class="section">
                        <h5 class="section-title">Customer Management Info</h5>
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Customer Category</th>
                                    <td>{!! $customer->customer_category ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                    <th>Customer Value</th>
                                    <td>{{ CurrencyHelper::format($customer->value ?: 0) }}</td>
                                </tr>
                                <tr>
                                    <th>Mode of Communication</th>
                                    <td>{!! $customer->mode_of_communication ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                    <th>Channel</th>
                                    <td>{!! $customer->channel ?: '<span class="not-provided">Not provided</span>' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.delete-customer').on('click', function() {
            const customerId = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/company/customers/${customerId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Customer has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.href = '{{ route("company.customers.index") }}';
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the customer.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });

       // Make CurrencyHelper available globally
       window.CurrencyHelper = {
            format: function(amount) {
                return '{{ CurrencyHelper::symbol() }}' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    };
</script>
@endsection
