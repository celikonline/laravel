@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Araç Modelleri</h5>
                    <a href="{{ route('vehicle-models.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni Model
                    </a>
                </div>

                <div class="card-body">
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

                    <!-- Filtre Formu -->
                    <form action="{{ route('vehicle-models.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="brand_id" class="form-control">
                                        <option value="">Tüm Markalar</option>
                                        @foreach($brands as $id => $name)
                                            <option value="{{ $id }}" {{ request('brand_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Model adı ara..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">Tüm Durumlar</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pasif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrele
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('vehicle-models.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Sıfırla
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marka</th>
                                    <th>Model Adı</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($models as $model)
                                    <tr>
                                        <td>{{ $model->id }}</td>
                                        <td>{{ $model->brand->name }}</td>
                                        <td>{{ $model->name }}</td>
                                        <td>
                                            @if($model->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Pasif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vehicle-models.edit', $model) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('vehicle-models.destroy', $model) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu modeli silmek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Henüz araç modeli eklenmemiş.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $models->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 