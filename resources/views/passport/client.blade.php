@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">New Client Registration</div>

                    <form action="{{ route('passport.clients.store') }}" method="POST">
                        <div class="card-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="redirect">Redirect</label>
                                <input type="text" class="form-control" id="redirect" name="redirect" placeholder="Enter URL">
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('show_client') }}" class="btn btn-primary">View Clients</a>
                            <button type="submit" class="btn btn-success ml-2">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
