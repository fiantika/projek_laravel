@include('kasir.layouts.header')
@include('kasir.layouts.sidebar_keuangan')
@include('kasir.layouts.content', ['content' => $content ?? null])
@include('kasir.layouts.footer')
