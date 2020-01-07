@if(array_key_exists('extension', $errors))
<div class="alert alert-danger alert-dismissible fade show mt-3 mb-0 py-2" role="alert">
    <strong>Arquivo incompatível!</strong> Para importar utilize um arquivo <strong>XLSX</strong> (Excel).  
    <button type="button" class="close py-2" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@elseif(array_key_exists('file', $errors))
<div class="alert alert-danger alert-dismissible fade show mt-3 mb-0 py-2" role="alert">
    <strong>Arquivo comrrompido!</strong> Para importar utilize um arquivo salvo em <strong>EXCEL 2007 - 2019</strong>.  
    <button type="button" class="close py-2" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@else
<div class="alert alert-danger mt-3 mb-0 py-2  alert-dismissible fade show" role="alert">
    <strong>Cabeçalho incompleto!</strong> Segue abaixo a lista das colunas faltantes.
    <ul class="ldist-group pl-3 mt-2 mb-0">
        @foreach($errors as $heading)
        <li class="lisst-group-item">{{ $heading }}</li>
        @endforeach
    </ul>
    <button type="button" class="close py-2" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
