@extends('dashboard::layouts.master')

@section('content')

{{ Form::open(['route' => 'dashboardportal.token', 'method' => 'get', 'id' => 'form_token']) }}
<div class="form-group">
	<div class="input-group">
		{{ Form::text('token', null, ['class' => 'form-control', 'placeholder' => 'Insira o token aqui', 'id' => 'token']) }}
		<span class="input-group-append">
			{{ Form::button('<i class="fa fa-key"></i>', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btn_token']) }}
		</span>
	</div>
</div>
{{ Form::close() }}
<div id="container_import"></div>

@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="{{ route('dashboard') }}">Dashboard</a>
</li>
<li class="breadcrumb-item">
	Portal Sincronização
</li>
@endsection


@push('scripts')
<script>
	$("#form_token").submit(function(e){
		e.preventDefault();

		$("#token").prop("disabled", true);
		$("#btn_token").prop("disabled", true);

		var interval = setInterval(function(){
			$("#container_import").load('{{ route('dashboardportal.update') }}');
		}, 2000);

		$.ajax({url: '{{ route('dashboardportal.token') }}',
			type: 'post',
			data: {token:$('#token').val()},
			headers: {'X-CSRF-Token': "{{ csrf_token() }}"}
		}).always(function(data) {
			clearInterval(interval);
			$("#token").prop("disabled", false);
			$("#btn_token").prop("disabled", false);
		});

	});
</script>
@endpush



