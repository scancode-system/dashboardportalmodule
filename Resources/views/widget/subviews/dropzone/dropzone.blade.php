<div id="layout" class="d-none">
    <div class="progress-group mb-0">
        <div class="progress-group-header align-items-end">
            <div>Transferindo arquivo (<span data-dz-name></span>)</div>
            <div class="ml-auto font-weight-bold mr-2 progress-text"></div>
        </div>
        <div class="progress-group-bars">
            <div class="progress progress-xs bg-secondary">
                <div class="progress-bar bg-primary" id="progressbar-file-{{ $event_validation->id }}" role="progressbar" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
            </div>
        </div>
    </div>
</div>

{{ Html::style('modules/portal/dropzone/dropzone.css') }}
<style type="text/css">
  .card-footer .dropzone{border: none; background: none; padding: 0px; min-height: 0px;}
  .card-footer .dz-message{margin: 0px;}  

</style>



{{ Html::script('modules/portal/dropzone/dropzone.js') }}
<script>
    Dropzone.autoDiscover = false;

    var layout = document.getElementById('layout').innerHTML;

    var dropzone_import_{{ $event_validation->id }} = new Dropzone('#dropzone-import-{{ $event_validation->id }}', {
        url: '{{ route("imports.widget.upload", $event_validation->id) }}',
        params: {
            extension: "xlsx"
        },
        headers: {'X-CSRF-Token': "{{ csrf_token() }}"},
        previewTemplate: layout,
        uploadprogress: function(file, progress, bytesSent) {
            console.log(file.previewElement);
            if (file.previewElement) {
                 $('#btn-clean_{{ $event_validation->id }}').hide();
                 $('#btn-play_{{ $event_validation->id }}').hide();
                var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
                progressElement.style.width = progress + "%";
                file.previewElement.querySelector(".progress-text").textContent = progress + "%";
                if(progress == 100){
                    $("#progressbar-file-{{ $event_validation->id }}").addClass("progress-bar-striped progress-bar-animated");
                }
            }
        },
        success: function(file, response, xhr){
            var interval = setInterval(function(){
                $("#container_{{ $event_validation->id }}").load('{{ route('imports.widget.info', $event_validation->id) }}');
            }, 1000);

            $.post('{{ route('imports.widget.start', $event_validation->id) }}').always(function(data) {
                clearInterval(interval);
                $("#container_{{ $event_validation->id }}").html(data);
            });
        },
        error: function(file, response, xhr){
            console.log(response.errors);

            $(".errors_{{ $event_validation->id }}").load('{{ route('imports.widget.errors', $event_validation->id) }}', response.errors, function(){
                dropzone_import_{{ $event_validation->id }}.removeAllFiles();
            });
        }
    });
</script>