@include('admin.layouts.header')
@include('admin.layouts.sidebar')
@include('admin.layouts.content', ['content' => $content ?? null])

@include('admin.layouts.footer')