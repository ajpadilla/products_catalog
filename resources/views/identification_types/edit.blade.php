@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <div class="">
        <h2 class="">Laravel Ajax jquery Validation</h2>
        <form id="identification-type">
            <div class="">
                <input type="text" name="name" class="" placeholder="Enter Name" id="name" value="{{$identificationType->name}}">
                <span id="name-error"></span>
            </div>
            <div class="">
                <input type="text" name="description" class="" placeholder="Enter description" id="description" value="{{$identificationType->description}}">
                <span id="description-error"></span>
            </div>
            <div class="">
                <button class="btn btn-success" id="submit">Submit</button>
            </div>
            <div class="">
                <b><span class="text-success" id="success-message"> </span><b>
            </div>
        </form>
        <button type="button" onclick="window.location='{{ url("/identificationTypes") }}'">Regresar</button>
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

        $('#identification-type').on('submit', function(event){
            event.preventDefault();
            $('#name-error').text('');
            $('#description-error').text('');

            $.ajax({
                url: "/api/v1/identificationTypes/{{$identificationType->id}}",
                type: "PATCH",
                data:{
                    name: $('#name').val(),
                    description: $('#description').val()
                },
                success:function(response){
                    console.log(response);
                    if (response) {
                        $('#success-message').text(response.data.message);
                    }
                },
                error: function(response) {
                    $('#name-error').text(response.responseJSON.errors.name);
                    $('#description-error').text(response.responseJSON.errors.description);
                }
            });
        });
    </script>
@show
