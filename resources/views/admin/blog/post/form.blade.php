@extends('global.main')
@section('title',"Create New Blog Post")
@section('description',"Create New Blog Post")
@section('innerTitle',"New Blog Post")
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
    <form role="form" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="@if(isset($post->id)){{ $post->id }}@endif">
        <input type="hidden" name="storeType" id="storeType" value="@if(isset($post->id)){{'edit'}}@else{{'create'}}@endif">
        <div class="form-group">
            <label>Blog Title</label>
            <input class="form-control" name="postTitle" autofocus value="{{ old('postTitle', $post->title) }}"  placeholder="Blog Title" type="text">
            @if ($errors->has('postTitle'))
                <label for="name-error" class="error"
                       style="display: inline-block;">{{ $errors->first('postTitle') }}</label>
            @endif
        </div>
        <div class="form-group">
            <label>Blog Excerpt (This will be the title on facebook)</label>
            <textarea class="form-control" placeholder="Little Description of the Post" style="height: 80px;" name="postExcerpt">{{ old('postExcerpt', $post->excerpt) }}</textarea>
        </div>
        <div class="form-group">
            <label>Blog Content</label>
            <textarea id="summernote" name="postContent">{{ old('postContent', $post->content_html) }}</textarea>
        </div>
        
         <div class="form-group">
            <label>Facebook Page</label>
             <select name="facebook_page" class="form-control">
                    <option value="">Select Facebook Page</option>
                    @if(isset($pages))
                        @foreach($pages as $page)
                           <option value="{{ $page['access_token'] }}#####{{ $page['id'] }}">{{ $page['name'] }}</option> 
                        @endforeach
                    @endif
             </select>
        </div>
        <div class="form-group">
            <label>URL slug (Optional)</label>
            <input type="text" name="postSlug" class="form-control" placeholder="Url Slug" value="{{ old('postSlug', $post->slug) }}" />
        </div>

        <div class="form-group">
            <label>Featured Image</label>
            @if(isset($post->image))
                <br/><br/>
             <img src="{{ asset('post_image/'.$post->image) }}" width="15%" class="img-responsive"><br/>
            @endif
            <input type="file" name="postImage" class="form-control" placeholder="Url Slug"/>
        </div>

        <div class="form-group">
            <label>Post Status</label>
            <select name="postStatus" class="form-control">
                @if(!isset($post->id))
                <option></option>
                @endif
                <option value="PUBLISHED"@if(old('postStatus', $post->status) == 'PUBLISHED'){!! 'selected="selected"' !!}@endif>PUBLISHED</option>
                <option value="DRAFT"@if(old('postStatus', $post->status) == 'DRAFT'){!! 'selected="selected"' !!}@endif>DRAFT</option>
                <option value="PENDING"@if(old('postStatus', $post->status) == 'PENDING'){!! 'selected="selected"' !!}@endif>PENDING</option>
            </select>
        </div>

        <div class="form-group">
            <label>Post Category</label>
            <select name="postCategory" class="form-control js-example-placeholder-single" style="width: 100%">
                @if(!isset($post->id))
                    <option></option>
                @endif
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}"@if($id == old('postCategory', $post->category)){!! 'selected="selected"' !!}@endif>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Post Tag</label>
            <select id="postTag" class="form-control js-example-basic-select2" name="postTag[]" class="js-example-tags" multiple="multiple" style="width: 100%">
                @if(!isset($post->id))
                    <option></option>
                @endif
                @foreach($tags as $id => $name)
                    <option value="{{ $id }}"@if(in_array($id, old('postTag', $post_tag))){!! 'selected="selected"' !!}@endif>{{ $name }}</option>
                @endforeach
            </select>
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
                height: 300,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: true                  // set focus to editable area after initializing summernote
            });

        });
    </script>
@endsection
