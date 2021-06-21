@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <button type="button" onclick="window.location='{{ url("users/create") }}'">Crear</button>
    <table style="width:100%">
        <tr>
            <th style="text-align:left">Identification Type</th>
            <th style="text-align:left">First Name</th>
            <th style="text-align:left">Last Name</th>
            <th style="text-align:left">email</th>
            <th style="text-align:left">Birthday</th>
        </tr>
        @forelse ($users as $user)
            <tr>
                <td>{{$user->identification_types_name}}</td>
                <td>{{$user->first_name}}</td>
                <td>{{$user->last_name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->birthday}}</td>
                <td>
                    <a href="{{route('users.edit', $user->id)}}" class="">Editar</a>
                    <a href="" class="delete" attr-id="{{$user->id}}">Eliminar</a>
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
                    url: "/api/v1/users/"+id,
                    type: "DELETE",
                    data:{
                    },
                    success:function(response){
                        console.log(response);
                        window.location = "/users";
                    },
                    error: function(response) {
                    }
                });
            }
        });
    </script>
@show
