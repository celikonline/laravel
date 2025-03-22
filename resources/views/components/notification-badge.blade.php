@props(['count'])

@if($count > 0)
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ $count }}
        <span class="visually-hidden">okunmamış bildirim</span>
    </span>
@endif 