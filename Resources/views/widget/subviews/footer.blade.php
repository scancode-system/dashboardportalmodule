<div class="card-footer d-flex">
	<div class="progress-group mb-0 flex-fill">
		<div class="progress bg-secondary w-100">
			<div class="progress-bar" role="progressbar" style="width: {{ $widget['completed']  }}%;" aria-valuenow="{{ $widget['completed']  }}" aria-valuemin="0" aria-valuemax="100">{{ $widget['completed']  }}%</div>
		</div>
	</div>
</div>