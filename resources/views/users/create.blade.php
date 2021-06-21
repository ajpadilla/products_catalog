@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <div class="">
        <h2 class="">Laravel Ajax jquery Validation</h2>
        <form id="user-form">
            <div class="">
                <input type="text" name="first_name" class="" placeholder="Enter First Name" id="first_name">
                <span id="first_name-error"></span>
            </div>
            <div class="">
                <input type="text" name="last_name" class="" placeholder="Enter Last name" id="last_name">
                <span id="last_name-error"></span>
            </div>
            <div class="">
                <input type="email" name="email" class="" placeholder="Enter Email" id="email">
                <span id="email-error"></span>
            </div>
            <div class="">
                <input type="text" name="phone" class="" placeholder="Enter Phone" id="phone">
                <span id="phone-error"></span>
            </div>
            <div class="">
                <input type="text" name="birthday" class="" placeholder="Enter Birthday (Y-m-d)" id="birthday">
                <span id="birthday-error"></span>
            </div>

            <div class="">
                <!-- The second value will be selected initially -->
                <select name="identification_type_id" id="identification_type_id">
                    @foreach($identificationTypes as $identificationType)
                    <option value="{{$identificationType->id}}">{{$identificationType->name}}</option>
                    @endforeach
                </select>
                <span id="identification_type_id-error"></span>
            </div>

            <div class="">
                <button class="btn btn-success" id="submit">Submit</button>
            </div>
            <div class="">
                <b><span class="text-success" id="success-message"> </span><b>
            </div>
        </form>
        <button type="button" onclick="window.location='{{ url("/users") }}'">Regresar</button>
    </div>
@endsection

@section('script')
    @parent

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'API-KEY-LAIKA': '0123456789'
            }
        });

        $('#user-form').on('submit', function(event){
            event.preventDefault();
            $('#name-error').text('');
            $('#description-error').text('');

            $.ajax({
                url: "/api/v1/users",
                type: "POST",
                data:{
                    identification_type_id: $('#identification_type_id').val(),
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    birthday: $('#birthday').val()
                },
                success:function(response){
                    console.log(response);
                    if (response) {
                        $('#success-message').text(response.data.message);
                        $("#user-form")[0].reset();
                    }
                },
                error: function(response) {
                    $('#identification_type_id-error').text(response.responseJSON.errors.identification_type_id);
                    $('#first_name-error').text(response.responseJSON.errors.first_name);
                    $('#last_name-error').text(response.responseJSON.errors.last_name);
                    $('#email-error').text(response.responseJSON.errors.email);
                    $('#phone-error').text(response.responseJSON.errors.phone);
                    $('#birthday-error').text(response.responseJSON.errors.birthday);
                }
            });
        });
    </script>
@show
