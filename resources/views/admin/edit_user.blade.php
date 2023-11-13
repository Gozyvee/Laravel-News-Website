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
                <label for="name" class="col-sm-2 col-form-label">User Name</label>
                <div class="col-sm-10">
                    <input type="text" value="{{$row->name}}" class="form-control" id="name" name="name" placeholder="Name" autofocus><br>
                </div>
               
                <label for="Email" class="col-sm-2 col-form-label">User Email</label>
                <div class="col-sm-10">
                    <input type="email" value="{{$row->email}}" class="form-control" id="email" name="email" placeholder="Email" autofocus><br>
                </div>
               
                <label for="password" class="col-sm-2 col-form-label">User Password</label>
                <div class="col-sm-10">
                    <input type="text" value="" class="form-control" id="password" name="password" placeholder="Password" autofocus><br>
                    <small>Leave password empty if you don't want to change it</small>
                </div>
            </div>

            <!-- <br style="clear: both;"> -->
            <input type="submit" class="btn btn-primary" value="Save">
            <a href="{{url('admin/users')}}">
                <input type="button" class="btn btn-success" style="float: right;" value="Back">
            </a>
        </form>
        <?php else: ?>
            <div>Sorry, we could not find your request in our records</div>
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
