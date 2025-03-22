@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Araç Modeli Düzenle</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('vehicle-models.update', $vehicleModel) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Marka</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                <option value="">Marka Seçin</option>
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}" {{ old('brand_id', $vehicleModel->brand_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Model Adı</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $vehicleModel->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $vehicleModel->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Aktif</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vehicle-models.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Geri
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 