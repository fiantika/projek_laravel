<div class="container-fluid">
@if (auth()->check())
    <div class="alert alert-success">Halo {{ auth()->user()->name }} Selamat datang di halaman admin!!</div>
@else
    <div class="alert alert-warning">Halo Guest, silakan login terlebih dahulu!</div>
@endif
</div>