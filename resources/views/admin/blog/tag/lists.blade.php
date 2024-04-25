@extends('global.main')
@section('title',"Tag List")
@section('description',"Tag List")
@section('innerTitle',"List Tag")
@section('breadcrumb',"")

@section('extra_css_files')
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
@endsection

@section('panel_controls')
    <a href="#" id="create" class="btn btn-sm btn-success add" data-toggle="modal" data-target="#id01" data-link="{{ route('tag.store') }}">Add New</a>
@endsection

@section('content')
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="data-rows">
                @foreach($tags as $tag)
                    <tr id="{{ $tag->id }}_tr">
                        <td data-name="name">{{ $tag->name }}</td>
                        <td>
                            <a id="edit" data-toggle="modal" data-target="#id01" class="btn btn-primary btn-sm" href="#" data-link="{{ route('tag.edit',$tag->id) }}">Edit</a>
                            <a id="delete" data-toggle="modal" data-target="#id01" class="btn btn-danger btn-sm" href="#" data-link="{{ route('tag.delete',$tag->id) }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="id01" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-head">#</h5>
                    <button type="button" class="close do-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-body" class="modal-body">

                </div>
                <div id="modal-footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>

@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{  asset('bower_components/datatables-tabletools/js/dataTables.tableTools.js') }}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-colvis/js/dataTables.colVis.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-scroller/js/dataTables.scroller.js') }}"></script>

    <!--init data tables-->
    <script src="{{ asset('js/init-datatables.js') }}"></script>

    <script type="text/javascript">
        $(document).on("click", "a", function(){
            var modalType = $(this).attr('id');
            if(modalType==='create' || modalType==='edit' || modalType==='delete'){
                var dataLink = $(this).attr('data-link');
                var dataName = $(this).parent().parent().find('td[data-name="name"]').text();
                $('.modal-area').css('display', 'block');
                $('#modal-head').text(modalType.toUpperCase()+ " TAG");
                if(modalType==='create'){
                    $('#modal-body').html('<div class="form-group"> <label>Name</label><input class="modal-input-name form-control"  value="" autofocus="autofocus" required="required" /></div>');
                    $('#modal-footer').html('<a id="do-save" class=" btn btn-sm btn-success" data-url="'+ dataLink +'">CREATE</a><a class="btn btn-sm  btn-cancel do-close" href="#">CANCEL</a>');
                }else if(modalType==='edit'){
                    $('#modal-body').html('<div class="form-group"> <label>Name</label><input class="modal-input-name form-control" value="' + dataName + '" autofocus="autofocus" required="required" /></div>');
                    $('#modal-footer').html('<a id="do-save" class="btn btn-sm btn-success" data-url="'+ dataLink +'">SAVE</a><a class="btn btn-sm btn-danger do-close" href="#">CANCEL</a>');
                }else if(modalType==='delete'){
                    $('#modal-body').html('<p>Are you sure want to delete [<span class="delete-message">' + dataName +'</span>] ?</p>');
                    $('#modal-footer').html('<a class="btn btn-sm btn-danger" href="'+ dataLink +'">DELETE</a><a class=" btn btn-sm btn-danger do-close" href="#">CANCEL</a>');
                }else{
                    alert('error..');
                }
            }
        });
        $(document).on("click", ".do-close", function(){
            $('#modal-head').text('');
            $('#modal-body').html('');
            $('#modal-footer').html('');
        });
        $(document).on("click", "#do-save", function(){
            var ajaxUrl = $(this).attr('data-url');
            var ajaxData = { 'name': $('.modal-input-name').val() };
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: ajaxData,
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf_token"]').attr('content');
                    if (token) {
                        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                success: function(data){
                    if(data['type']==='create'){
                        $('.data-rows').prepend(
                            '<tr id="'+ data['id'] +'_tr">'+
                            '<td class="data-cell" data-name="name">'+ data['name'] +'</td>'+
                            '<td class="data-cell data-cell-action">'+
                            '<a id="edit" data-toggle="modal" data-target="#id01" class="btn btn-sm btn-primary" data-link="{{ asset("tag") }}/'+ data['id'] +' /edit">Edit</a> '+
                            '<a id="delete" data-toggle="modal" data-target="#id01" class="btn btn-sm btn-danger" data-link="{{ asset("tag") }}/'+ data['id'] +' /delete">Delete</a>'+
                            '</td>'+
                            '</tr>'
                        );
                        $('.do-close').trigger('click')
                    }
                    if(data['type']==='edit'){
                        $('#'+data['id']+'_tr').find('td[data-name="name"]').text(data['name']);
                        $('.do-close').trigger('click');
                    }
                    if(data['type']==='error'){
                        console.log(data['message']);
                        alert(data['message']);
                        $('.do-close').trigger('click');
                    }
                },
                error: function(data){
                    var errors = data.responseJSON;
                    console.log(errors);
                    alert(errors);
                }
            })
        });
        </script>
@endsection
