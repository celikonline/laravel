@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ödeme Sonucu</div>

                <div class="card-body text-center">
                    @if($status === 'success')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h4>Ödeme Başarılı</h4>
                            <p>{{ $message }}</p>
                            @if(isset($package))
                                <p>Paket No: {{ $package->contract_number }}</p>
                                <p>Tutar: {{ number_format($package->price, 2) }} TL</p>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle fa-3x mb-3"></i>
                            <h4>Ödeme Başarısız</h4>
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('packages.index') }}" class="btn btn-primary">
                            Paketlere Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 