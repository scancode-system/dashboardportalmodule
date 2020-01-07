<div class="alert alert-info" role="alert">
	{{ $alert }}
</div>

@foreach($widgets as $widget)
@include('importwidget::index', ['module' => $widget['module'], 'method' => $widget['method']])
@endforeach