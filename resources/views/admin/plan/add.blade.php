@extends('global.main')
@section('title',$page_title)
@section('description',$page_title)
@section('innerTitle',$page_title)
@section('breadcrumb',"")

@section('extra_css_files')
    <link rel="stylesheet" href="{{ asset('bower_components/summernote/dist/summernote.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    @if(session('success'))
        {!! alert_success(session('success')) !!}
    @elseif(session('error'))
        {!! alert_error(session('error')) !!}
    @endif

    <form role="form" action="{{ route("plan.process") }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <input type="hidden" name="id" id="id" value="@if(isset($plan->id)){{ $plan->id }}@endif">
        <input type="hidden" name="storeType" id="storeType" value="@if(isset($plan->id)){{'edit'}}@else{{'create'}}@endif">
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" name="name"  value="{{ old('name', $plan->name) }}"  placeholder="Plan Name" type="text">
        </div>

        <div class="form-group">
            <label>Plan Type</label>
            <select name="type" class="form-control">
                @if(!isset($plan->id))
                    <option>Select One</option>
                @endif
                    <option value="HOURLY" @if(old('type', $plan->type) == 'HOURLY'){!! 'selected="selected"' !!} @endif>HOURLY</option>
                <option value="DAILY" @if(old('type', $plan->type) == 'DAILY'){!! 'selected="selected"' !!} @endif>DAILY</option>
                <option value="WEEKLY" @if(old('type', $plan->type) == 'WEEKLY'){!! 'selected="selected"' !!} @endif>WEEKLY</option>
                <option value="MONTHLY" @if(old('type', $plan->type) == 'MONTHLY'){!! 'selected="selected"' !!} @endif>MONTHLY</option>
                <option value="YEARLY" @if(old('type', $plan->type) == 'YEARLY'){!! 'selected="selected"' !!} @endif>YEARLY</option>
            </select>
        </div>

        <div class="form-group">
            <label>Show Plan in Homepage and registration(Max of 3 Plan)</label>
            <select name="show_homepage" class="form-control">
                <option value="0" @if(old('show_homepage', $plan->show_homepage) == '0'){!! 'selected="selected"' !!} @endif>NO</option>
                <option value="1" @if(old('show_homepage', $plan->show_homepage) == '1'){!! 'selected="selected"' !!} @endif>YES</option>
            </select>
        </div>

        <div class="form-group">
            <label>No of Hours/Day/Week/Year</label>
            <input class="form-control" name="no_of_type"  value="{{ old('amount', $plan->no_of_type) }}"  placeholder="No of Hours/Day/Week/Year" type="number">
        </div>

        <div class="form-group">
            <label>Amount</label>
            <input class="form-control" name="amount"  value="{{ old('amount', $plan->amount) }}"  placeholder="Amount" type="text">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="ACTIVE" @if(old('type', $plan->status) == 'ACTIVE'){!! 'selected="selected"' !!} @endif>ACTIVE</option>
                <option value="INACTIVE" @if(old('type', $plan->status) == 'INACTIVE'){!! 'selected="selected"' !!} @endif>INACTIVE</option>
            </select>
        </div>

        <div class="form-group">
            <label>Mobile Description</label>
            <textarea class="form-control"  name="description">{{ old('description', $plan->description) }}</textarea>
        </div>


        <div class="form-group">
            <label>Website Description</label>
            <textarea class="form-control" id="summernote"  name="postContent">{{ old('web_description', $plan->web_description) }}</textarea>
        </div>

        <button type="submit"  id="save" class="btn btn-success btn-lg">SAVE</button>
    </form>

@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/summernote/dist/summernote.js') }}"></script>
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 260,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: false                  // set focus to editable area after initializing summernote
            });

        });
    </script>
@endsection
