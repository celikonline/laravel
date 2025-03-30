@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('3D Secure Doğrulama') }}</div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Yükleniyor...</span>
                        </div>
                        <p class="mt-2">Banka doğrulama sayfasına yönlendiriliyorsunuz...</p>
                    </div>

                    <form id="threeDForm" method="POST" action="{{ $formData['action_url'] }}" class="d-none">
                        @foreach($formData['inputs'] as $name => $value)
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    console.log('3D Secure redirect page loaded');
    console.log('Form action URL:', '{{ $formData['action_url'] }}');
    
    // Sayfa yüklendiğinde otomatik olarak formu gönder
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Starting 3D Secure redirect...');
        const form = document.getElementById('threeDForm');
        console.log('3D Secure form found:', !!form);
        form.submit();
        console.log('3D Secure form submitted');
    });
</script>
@endpush
@endsection 