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
        <?php if($row): ?>
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
                <label for="category" class="col-sm-2 col-form-label">Category Name</label>
                <div class="col-sm-10">
                    <input type="text" value="{{$row->category}}" class="form-control" id="category" name="category" placeholder="category" autofocus><br>
                </div>
            </div>

            <!-- <br style="clear: both;"> -->
            <input type="submit" class="btn btn-primary" value="Save">
            <a href="{{url('admin/categories')}}">
                <input type="button" class="btn btn-success" style="float: right;" value="Back">
            </a>
        </form>
        <?php else: ?>
            <div>Sorry, we could not find your request in our records</div>
            <a href="{{url('admin/categories')}}">
                <input type="button" class="btn btn-success" style="float: right;" value="Back">
            </a>
        <?php endif; ?>
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
