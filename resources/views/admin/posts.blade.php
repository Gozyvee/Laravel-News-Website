@include('admin.header')
@include('admin.sidebar')
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>{{$page_title}} </h2>
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif


            </div>
            <table class="table table-striped table-hover">

                <form action="{{route('admin.select')}}" method="post">
                    @csrf
                    <input style="margin-left: 10px;" type="submit" name="clone" class="btn btn-success" value="Clone">
                    <a href="{{url('admin/posts/add')}}">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Add Post</button>
                    </a>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><input id="selectAllBoxes" type="checkbox"></th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Featured image</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($rows)
                            @foreach($rows as $row)
                            <tr>
                                <td>

                                    <input value="{{$row->id}}" class="CheckBoxes" type="checkbox" name="check[]">

                                </td>
                </form>


                                <td>{{$row->title}}</td>
                                <td>{{$row->category}}</td>
                                <td><img src="{{url($row->image)}}" style="width:150px;" alt="image"></td>
                                <td>{{date("jS M, Y", strtotime($row->created_at))}}</td>

                                <td>
                                    <a href="{{url('admin/posts/edit/'.$row->id)}}">
                                        <button class="btn-sm btn btn-success">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </a><br><br>
                                    <a href="{{url('admin/posts/delete/'.$row->id)}}">
                                        <button class="btn-sm btn btn-warning">
                                            <i class="fa fa-times"></i> Delete
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                @include('pagination')
        </div>
        <!-- /. ROW  -->
        <hr />

        <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var selectAllBoxes = document.getElementById("selectAllBoxes");
        var checkBoxes = document.querySelectorAll(".CheckBoxes");

        selectAllBoxes.addEventListener("click", function() {
            for (var i = 0; i < checkBoxes.length; i++) {
                checkBoxes[i].checked = this.checked;
            }
        });
    });
</script>
@include('admin.footer')