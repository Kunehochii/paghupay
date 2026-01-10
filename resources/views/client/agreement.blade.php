@extends('layouts.app')

@section('title', 'Statement of Confidentiality')

@push('styles')
<style>
    body {
        background-color: #4db6ac !important;
    }
    
    .agreement-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    
    .agreement-card {
        background: white;
        border-radius: 8px;
        max-width: 700px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    
    .agreement-header {
        padding: 30px 40px 20px;
    }
    
    .agreement-title {
        color: #2c7a7b;
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0;
    }
    
    .agreement-subtitle {
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
    
    .agreement-body {
        padding: 0 40px;
        max-height: 350px;
        overflow-y: auto;
    }
    
    .agreement-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .agreement-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .agreement-body::-webkit-scrollbar-thumb {
        background: #4db6ac;
        border-radius: 3px;
    }
    
    .agreement-intro {
        color: #1a202c;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    
    .agreement-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .agreement-list li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 20px;
        line-height: 1.7;
        color: #1a202c;
    }
    
    .agreement-list li::before {
        content: attr(data-number);
        position: absolute;
        left: 0;
        font-weight: 700;
        color: #1a202c;
    }
    
    .agreement-footer {
        padding: 30px 40px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    .btn-cancel {
        background: transparent;
        border: none;
        color: #4a5568;
        font-weight: 500;
        padding: 10px 25px;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .btn-cancel:hover {
        color: #1a202c;
    }
    
    .btn-agree {
        background-color: #4db6ac;
        border: none;
        color: white;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-agree:hover {
        background-color: #3d9d94;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(77, 182, 172, 0.4);
    }
</style>
@endpush

@section('content')
<div class="agreement-container">
    <div class="agreement-card">
        <div class="agreement-header">
            <h1 class="agreement-title">STATEMENT OF CONFIDENTIALITY</h1>
        </div>
        
        <div class="agreement-body">
            <h2 class="agreement-subtitle">Your Agreement</h2>
            
            <p class="agreement-intro">
                Students often raise questions about the privacy of what is discussed in counselling.
                All TUPV Guidance Staff adhere to very strict confidentiality standards.
            </p>
            
            <ol class="agreement-list">
                <li data-number="1.">
                    Any information or counselling records that you provide are strictly confidential, except in life threatening situations, cases of suspected child or elder abuse, or when release is otherwise required by law.
                </li>
                <li data-number="2.">
                    In order to provide the best services possible, your counsellor may consult with other counselors in the Guidance Services Office.
                </li>
                <li data-number="3.">
                    Information about the counselling will not appear in your academic record.
                </li>
                <li data-number="4.">
                    In order to protect your right to confidentiality, your written authorization is required if you want us to provide information about your counselling to another person or agency. If you have any question, you may ask your intake counsellor.
                </li>
            </ol>
            
            <p class="agreement-intro mt-3">
                Please click "I Agree" below to indicate that you have read and agree to the above statement regarding records, confidentiality and services.
            </p>
        </div>
        
        <div class="agreement-footer">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-cancel">Cancel</button>
            </form>
            
            <form method="POST" action="{{ route('client.agreement.accept') }}">
                @csrf
                <button type="submit" class="btn-agree">I Agree</button>
            </form>
        </div>
    </div>
</div>
@endsection
