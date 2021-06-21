@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <button type="button" onclick="window.location='{{ url("identificationTypes/create") }}'">Crear</button>
    <table style="width:100%">
        <tr>
            <th style="text-align:left">Name</th>
            <th style="text-align:left">Description</th>
            <th style="text-align:left">Actions</th>
        </tr>
        @forelse ($identificationTypes as $identificationType)
            <tr>
                <td>{{$identificationType->name}}</td>
                <td>{{$identificationType->description}}</td>
                <td>
                    <a href="{{route('identificationTypes.edit', $identificationType->id)}}" class="">Editar</a>
                    <a href="" class="delete" attr-id="{{$identificationType->id}}">Eliminar</a>
                </td>
            </tr>
        @empty
            <p>No Identification Types</p>
        @endforelse
    </table>
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

        $('.delete').on('click', function(event){
            event.preventDefault();
            const id = $(this).attr("attr-id");
            if (confirm('Are you sure?')) {
                // Post the form
                $.ajax({
                    url: "/api/v1/identificationTypes/"+id,
                    type: "DELETE",
                    data:{
                        name: $('#name').val(),
                        description: $('#description').val()
                    },
                    success:function(response){
                        console.log(response);
                        window.location = "/identificationTypes";
                    },
                    error: function(response) {
                    }
                });
            }
        });
    </script>
@show
