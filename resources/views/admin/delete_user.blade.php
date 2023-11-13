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
        <?php if($row): ?>
        <?php if($row->id == 1): ?>
            <h4>Action denied!!</h4>
        <?php else: ?>
        <h4>Are you sure you want to delete this User?</h4>
        <form method="post">
            @csrf
            @if($errors->all())
            <div class="alert alert-danger text-center">
                @foreach($errors->all() as $error)
                    {{$error}}<br>
                @endforeach
            </div>
            @endif
            <div class="form-group row">
                <label for="category" class="col-sm-2 col-form-label">User Name</label>
                <div class="col-sm-10">
                    <input disabled type="text" value="{{$row->name}}" class="form-control" id="name" name="name" placeholder="category" ><br>
                </div>
               
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input disabled type="text" value="{{$row->email}}" class="form-control" id="email" name="email" placeholder="email" ><br>
                </div>
            </div>

            <!-- <br style="clear: both;"> -->
            <input type="submit" class="btn btn-danger" value="Delete">
            <a href="{{url('admin/users')}}">
                <input type="button" class="btn btn-success" style="float: right;" value="Back">
            </a>
        </form>
        <?php endif; ?>
        <?php else: ?>
            <div>Sorry, we could not find your requested data in our records</div>
            <a href="{{url('admin/users')}}">
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