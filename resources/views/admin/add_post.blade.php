@include('admin.header')
<link href="{{url('summernote/summernote-lite.min.css')}}" rel="stylesheet" />

@include('admin.sidebar')

<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>{{$page_title}} </h2>
            </div>
            <div class="container-fluid col-lg-12">
                <form method="post" enctype="multipart/form-data">
                    @csrf
                    @if($errors->all())
                    <div class="alert alert-danger text-center">
                        @foreach($errors->all() as $error)
                        {{$error}}<br>
                        @endforeach
                    </div>
                    @endif
                    <div class="form-group row">
                        <label for="title" class="col-sm-2 col-form-label">Post Title</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{old('title')}}" class="form-control" id="title" name="title" placeholder="Title" autofocus><br>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="file" class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                            <input id="file" type="file" class="form-control" name="file">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="category_id" class="col-sm-2 col-form-label">Post Category</label>
                        <div class="col-sm-10">
                            <select id="category_id" name="category_id" class="form-control">
                                <option>--Select a category</option>

                                @foreach($categories as $category)

                                <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- <br style="clear: both;"> -->
                    <label for="summernote">Post Content</label><br>
                    <textarea name="content" id="summernote" cols="30" rows="10">{{old('content')}}</textarea>
                    <input type="submit" class="btn btn-primary" value="Post">
                </form>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr />

        <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
</div>
@include('admin.footer')
<script src="{{url('summernote/summernote-lite.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            codeviewIframeFilter: true
        });
    });
</script>