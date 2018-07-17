@extends('layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-10">
            <form method="POST" name="form-registration" onsubmit="return false">
                <div class="form-group">
                    <label>Name
                        <input class="form-control" required name="name">
                    </label>
                    <label>Email
                        <input class="form-control" required name="email" type="email">
                    </label>
                    <label>Phone
                        <input class="form-control" required name="phone">
                    </label>
                    <label>Avatar
                        <input class="form-control" required name="avatar" type="file"
                               accept="image/x-png,image/gif,image/jpeg,image/bmp">
                    </label>
                    <label>Password
                        <input class="form-control" required name="password" type="password">
                    </label>
                    <button class="form-control btn btn-primary" id="user-submit">Submit</button>
                </div>
            </form>
            <div hidden id="error-block" class="alert-warning form-control"></div>

            <div class="form-group">
                <button id="get-users" data-last-page="" data-next-page="" class="form-control btn btn-info">Get Users
                </button>
                <div class="loader align-items-center" hidden></div>
            </div>
            <div>
                <table>
                    <thead>
                    <tr>
                        <td>Avatar</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                    </tr>
                    </thead>
                    <tbody id="users-data">

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <script>
        $('#get-users').on('click', function (event) {
            var url;
            $button = $(this);
            if ($button.data('last-page') && $button.data('next-page')) {
                url = '<?=route('userslist')?>' + '?page=' + $button.data('next-page');
            } else {
                url = '<?=route('userslist')?>';
            }

            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (result) {
                if (result.data) {
                    $('#users-data').html('');
                    $(result.data).each(function (key, user) {
                        $('#users-data').append(
                            $('<tr />').append(
                                $('<td />').append(
                                    $('<img />').attr('src', user.avatar)
                                )
                            ).append(
                                $('<td />').html(user.name)
                            ).append(
                                $('<td />').html(user.email)
                            ).append(
                                $('<td />').html(user.phone)
                            )
                        );
                    });

                    if (result.current_page === result.last_page) {
                        $('#get-users').attr('disabled', 'disabled')
                    } else {
                        $button.data('last-page', result.last_page);
                        $button.data('next-page', result.current_page + 1);
                    }
                }
            }).fail(function (result) {
                if (JSON.parse(result.responseText).message) {
                    $('#error-block').attr('hidden', false);
                    if (JSON.parse(result.responseText).errors) {
                        $('#error-block').html(JSON.parse(result.responseText).errors);
                    } else {
                        $('#error-block').html(JSON.parse(result.responseText).message);
                    }
                }
            });
        });

        $('form[name="form-registration"]').on('submit', function (event) {
            var url = '{{ route('usercreate') }}';
            var formData = new FormData();
            formData.append('name',$('input[name="name"]').val());
            formData.append('email',$('input[name="email"]').val());
            formData.append('phone',$('input[name="phone"]').val());
            formData.append('password',$('input[name="password"]').val());
            formData.append('avatar',$('input[name="avatar"]')[0].files[0]);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (result) {
                $('form[name="form-registration"] input').removeClass('invalid');
            }).fail(function (result) {
                if (JSON.parse(result.responseText).message) {
                    $('#error-block').attr('hidden', false);
                    if (JSON.parse(result.responseText).errors) {
                        for(var key in JSON.parse(result.responseText).errors) {
                            $('form[name="form-registration"] input[name="'+key+'"]').addClass('invalid');
                        }

                        $('#error-block').html(JSON.parse(result.responseText).message);
                    } else {
                        $('#error-block').html(JSON.parse(result.responseText).message);
                    }
                }
            });
        });
    </script>
@endsection