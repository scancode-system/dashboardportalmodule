@extends('dashboard::layouts.master')

@section('content')

@alert_success()
{{ Form::open(['route' => 'dashboardportal.export.auto']) }}
<div class="form-group">
	{{ Form::label('auto', 'Automatico') }}
	<div class="input-group">
		<span class="input-group-prepend">
			{{ Form::button('<i class="fa fa-trash-o"></i>', ['class' => 'btn btn-danger', 'type' => 'submit', 'id' => 'btn_token', 'name' => 'action', 'value' => 'clear']) }}
		</span>
		{{ Form::time('auto', $auto, ['class' => 'form-control']) }}
		<span class="input-group-append">
			{{ Form::button('<i class="fa fa-save"></i>', ['class' => 'btn btn-primary', 'type' => 'submit', 'name' => 'action', 'value' => 'save']) }}
		</span>
	</div>
</div>
{{ Form::close() }}

{{ Form::open(['route' => 'dashboardportal.export.start', 'id' => 'form_token']) }}
<div class="form-group">
	{{ Form::label('token', 'Manual') }}
	<div class="input-group">
		{{ Form::text('token', $token, ['class' => 'form-control', 'placeholder' => 'Token (Exportação)', 'id' => 'token', 'disabled' => 'disabled']) }}
		<span class="input-group-append">
			{{ Form::button('<i class="fa fa-refresh"></i>', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btn_token', 'disabled' => 'disabled']) }}
		</span>
	</div>
</div>
{{ Form::close() }}
<div id="container_progress"></div>		
@endsection


@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="{{ route('dashboard') }}">Dashboard</a>
</li>
<li class="breadcrumb-item">
	<a href="{{ route('dashboardportal.index') }}">Portal Scancode</a>
</li>
<li class="breadcrumb-item">
	Portal Exportação (Atualização) de Dados
</li>
@endsection


@push('scripts')
<script>

	$.get('{{ route('dashboardportal.export.check') }}', function( data ) {
		if(!data.status){
			$("#token").prop("disabled", false);
			$("#btn_token").prop("disabled", false);
		} else {
			var interval = setInterval(function(){
				$("#container_progress").load('{{ route('dashboardportal.export.progress') }}');
			}, 1000);
		}
	});

	$("#form_token").submit(function(e){
		e.preventDefault();

		$("#token").prop("disabled", true);
		$("#btn_token").prop("disabled", true);

		var interval = setInterval(function(){
			$("#container_progress").load('{{ route('dashboardportal.export.progress') }}');
		}, 1000);

		$.ajax({url: '{{ route('dashboardportal.export.start') }}',
			type: 'post',
			data: {token:$('#token').val()},
			headers: {'X-CSRF-Token': "{{ csrf_token() }}"}
		}).always(function(data) {
			clearInterval(interval);
			$("#container_progress").load('{{ route('dashboardportal.export.progress') }}');
			
			$("#token").prop("disabled", false);
			$("#btn_token").prop("disabled", false);
			$("#token").val('');
		});
	});

</script>
@endpush



