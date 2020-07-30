@extends('dashboard::layouts.master')

@section('content')

<div class="row">
	<div class="col">
		<a href="{{ route('dashboardportal.import') }}" class="card text-decoration-none">
			<div class="card-body p-0 d-flex align-items-center">
				<i class="fa fa-download bg-primary p-4 px-5 font-2xl mr-3"></i>
				<div>
					<div class="text-value-sm text-primary">Importação</div>
					<div class="text-muted text-uppercase small">Ultima Atualizãção: <span class="font-weight-bold">@datetime($setting_portal->last_import) </span></div>
				</div>
			</div>
		</a>	
	</div>
	<div class="col">
		<a href="{{ route('dashboardportal.export') }}" class="card text-decoration-none">
			<div class="card-body p-0 d-flex align-items-center">
				<i class="fa fa-cloud-upload bg-info p-4 px-5 font-2xl mr-3"></i>
				<div>
					<div class="text-value-sm text-primary">Exportação</div>
					<div class="text-muted text-uppercase small">Ultima Atualizãção: <span class="font-weight-bold">@datetime($setting_portal->last_export)</span></div>
				</div>
			</div>
		</a>	
	</div>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
	<a href="{{ route('dashboard') }}">Dashboard</a>
</li>
<li class="breadcrumb-item">
	Portal Scancode
</li>
@endsection





