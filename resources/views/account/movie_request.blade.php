@extends('account.shared.main')
@section('breadcrumb',"")
@section('content')

    <div class="dashboard-container">
        <br/>
        <h5>Movie / Series Request</h5>
        <div class="row">
            <div class="col-sm-6">
                @if(session('success'))
                    {!! alert_success(session('success')) !!}
                @elseif(session('error'))
                    {!! alert_error(session('error')) !!}
                @endif
                <form action="" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Movie / Series Title</label>
                        <input type="text" value="{{ old('title') }}" class="form-control" name="title" id="name" placeholder="Title">
                        @if ($errors->has('title'))
                            <label for="name-error" class="error"
                                   style="display: inline-block;">{{ $errors->first('title') }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="email">Select Request Type</label>
                        <select class="form-control" name="request_type">
                            <option>Select Type</option>
                            <option value="MOVIE">Movie (Request for a Movie)</option>
                            <option value="SERIES">Series (Request for a Series)</option>
                        </select>
                        @if ($errors->has('request_type'))
                            <label for="name-error" class="error"
                                   style="display: inline-block;">{{ $errors->first('request_type') }}</label>
                        @endif
                    </div>
                    <br/>
                    <div class="form-group">
                    <button class="btn btn-sm btn-green-pro btn-block" type="submit">Request Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
