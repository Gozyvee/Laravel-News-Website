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
                        <label for="title" class="col-sm-2 col-form-label">Category Name</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{old('category')}}" class="form-control" id="category" name="category" placeholder="Category Name" autofocus><br>
                        </div>
                    </div>
                  
                    <!-- <br style="clear: both;"> -->
                    <input type="submit" class="btn btn-primary" value="Add">
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
