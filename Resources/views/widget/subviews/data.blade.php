<div class="brand-card-body">
    <div>
        <div class="text-value">{{ $widget['new'] }}</div>
        <div class="text-uppercase text-muted small">Novo</div>
    </div>
    <div>
        <div class="text-value">{{ $widget['updated'] }}</div>
        <div class="text-uppercase text-muted small">Atualizado</div>
    </div>
    <div>
        <div class="text-value">{{ $widget['failures'] }}</div>
        <div class="text-uppercase text-muted small">Falha</div>
    </div>
    @if($widget['completed'] == 100)
    <div>
        <a href="{{ route('dashboardportal.report.failures', $widget['name']) }}" class="text-muted text-decoration-none">
            <i class="fa fa-file-text-o fa-lg  fa-2x mb-2"></i><br>
            Ver Falhas
        </a>
    </div>
    @endif
</div>