@include('operator.layouts.header')
@include('operator.layouts.sidebar')
@include('operator.layouts.content', ['content' => $content ?? null])

@include('operator.layouts.footer')