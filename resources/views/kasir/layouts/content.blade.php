@include('sweetalert::alert')
<div class="card-body">
    @if (isset($content) && View::exists($content))
        @include($content)
    @endif
</div>