@extends('layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background-color: #3d9f9b;">
    <div class="card shadow-sm" style="max-width: 500px; border: 2px solid #ccc; border-radius: 12px;">
        <div class="card-body text-center px-5 py-5">
            <p class="mb-4 fw-semibold" style="font-size: 1.1rem; line-height: 1.6;">
                You will now begin receiving all updates and notifications through your registered email address.
            </p>
            
            <a href="{{ route('client.welcome') }}" class="btn px-5 py-2 fw-medium" style="background-color: #a7f0ba; border-radius: 25px; color: #000;">
                Confirm
            </a>
        </div>
    </div>
</div>
@endsection
