@include('admin.header')
@include('admin.sidebar')

<!-- /. NAV SIDE  -->
<div id="page-wrapper">
<div id="page-inner">
<div class="row">
    <div class="col-md-12">
        <h2>{{$page_title}} </h2>
    </div>
    <div class="container-fluid col-lg-12">
        <h4>Are you sure you want to delete this post?</h4>
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
                    <input disabled type="text" value="{{$row->title}}" class="form-control" id="title" name="title" placeholder="Title" ><br>
                </div>
            </div>

            <div class="form-group row">
                <label for="file" class="col-sm-2 col-form-label">Image</label>
                <div class="col-sm-10">
                    <img style="width:200px;" src="{{url('uploads/' . $row->image)}}" alt="">
                </div>

            <!-- <br style="clear: both;"> -->
            <input type="submit" class="btn btn-danger" value="Delete">
            <a href="{{url('admin/posts')}}">
                <input type="button" class="btn btn-success" style="float: right;" value="Back">
            </a>
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