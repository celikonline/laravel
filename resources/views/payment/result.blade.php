@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ödeme Sonucu</div>

                <div class="card-body text-center">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
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