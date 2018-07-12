@extends('layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group form-inline">
                <input class="form-control col-9 input-file" type="file">
                <button id="upload-file" class="btn btn-info col-3">Upload Image</button>
            </div>

            <div class="form-group">
                <button id="get-resized-images" hidden class="form-control btn btn-info">Get Resized Images</button>
                <div class="loader align-items-center" hidden></div>
            </div>
            <div id="resized-images-block"></div>
            <div hidden id="error-block" class="alert-warning form-control"></div>
        </div>
    </div>
    <script>
        $('#upload-file').on('click', function (event) {
            var url = '<?=route('uploadimage')?>';
            var data = new FormData();
            data.append('image', $('input.input-file')[0].files[0]);
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
                if (result.image_id) {
                    $('#error-block').attr('hidden', true);
                    $('#upload-file').attr('disabled','disabled');
                    $('#get-resized-images').removeAttr('hidden');
                    $('#get-resized-images').data('image-id',result.image_id);
                }
            }).fail(function (result) {
                if (JSON.parse(result.responseText).message) {
                    $('#error-block').attr('hidden', false);
                    if(JSON.parse(result.responseText).errors) {
                        $('#error-block').html(JSON.parse(result.responseText).errors.image);
                    } else {
                        $('#error-block').html(JSON.parse(result.responseText).message);
                    }
                }
            });
        });


        $('#get-resized-images').on('click', function (event) {
            var image_id = $(this).data('image-id');
            var url = 'tasktwo/images/resize/'+image_id;
            if(image_id) {
                $thisButton = $(this);
                $(this).attr('disabled','disabled');
                $('.loader').removeAttr('hidden');

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function (result) {
                    if (result.images) {
                        $('#resized-images-block').html('');
                        $(result.images).each(function(key,value) {
                            $('#resized-images-block').append(
                                $('<img />').attr('src',value.url)
                            );
                        })
                    }
                    $('.loader').attr('hidden','hidden');
                }).fail(function (result) {
                    if (JSON.parse(result.responseText).message) {
                        $('#error-block').attr('hidden', false);
                        if(JSON.parse(result.responseText).errors) {
                            $('#error-block').html(JSON.parse(result.responseText).errors.image);
                        } else {
                            $('#error-block').html(JSON.parse(result.responseText).message);
                        }
                    }
                    $('.loader').attr('hidden','hidden');
                });
            }
        });


    </script>
@endsection