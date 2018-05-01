@extends('layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group form-inline">
                <input class="form-control col-9 input-file" type="file">
                <button id="upload-file" class="btn btn-info col-3">Upload File</button>
            </div>

            <div class="form-group">
                <button id="get-file-data" hidden class="form-control btn btn-info">Get File Data</button>
            </div>
            <div id="file-data-block"></div>
            <div class="form-group">
                <button id="get-updated-file" hidden class="form-control btn btn-info">Download Updated File</button>
            </div>
            <div hidden id="error-block" class="alert-warning form-control"></div>
        </div>
    </div>
    <script>
        $('#upload-file').on('click', function (event) {
            var url = '<?=route('uploadfile')?>';
            var data = new FormData();
            data.append('handling_file', $('input.input-file')[0].files[0]);
            data.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (result) {
                if (result.file_id) {
                    $('#get-file-data').data('file-id', result.file_id);
                    $('#get-file-data').attr('hidden', false);
                    $('#get-updated-file').data('file-id', result.file_id);
                    $('#get-updated-file').attr('hidden', false);
                    $('#upload-file').attr('disabled', true);
                    $('#error-block').attr('hidden', true);
                }
            }).fail(function (result) {
                if (JSON.parse(result.responseText).message) {
                    $('#error-block').attr('hidden', false);
                    $('#error-block').html(JSON.parse(result.responseText).message);
                }
            });
        });

        $('#get-file-data').on('click', function (event) {
            var url = 'taskone/getfiledata/' + $(this).data('file-id');
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (result) {
                if (result) {
                    $('#file-data-block').html(result);
                }
            });
        });

        $('#get-updated-file').on('click', function (event) {
            location.href = 'taskone/downloadfile/' + $(this).data('file-id');
        });


    </script>
@endsection