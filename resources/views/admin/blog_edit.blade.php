@extends('admin.layout')
@section('main')
    <div class="editormd" id="editormd">
        <textarea style="display:none;">### Hello Editor.md !</textarea>
    </div>
    <script>
        $(function() {
            var editor = editormd({
                id   : "editormd",
                path : "{{ URL('ext/meditor/lib/') }}/",
                width : "80%",
                height : 777,
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "{{ URL('admin/imgUpload') }}",
            });

        });
    </script>
@endsection