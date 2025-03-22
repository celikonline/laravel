@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Bildirimler</span>
                    @if($notifications->where('is_read', false)->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                Tümünü Okundu İşaretle
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="text-center text-muted">
                            Henüz bildiriminiz bulunmamaktadır.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'active' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h5 class="mb-1">{{ $notification->title }}</h5>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if(!$notification->is_read)
                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Okundu İşaretle
                                                    </button>
                                                </form>
                                            @endif
                                            @if($notification->link)
                                                <a href="{{ $notification->link }}" class="btn btn-sm btn-info">
                                                    Görüntüle
                                                </a>
                                            @endif
                                        </div>
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu bildirimi silmek istediğinizden emin misiniz?')">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 