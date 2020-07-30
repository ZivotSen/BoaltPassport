@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Show Registered Clients by the logged user</div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Secret</th>
                                <th scope="col">Redirect</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(isset($clients) && $clients)
                                    @foreach($clients as $client)
                                        <tr>
                                            <th scope="row">{{ $client->user_id }}</th>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->secret }}</td>
                                            <td>{{ $client->redirect }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
