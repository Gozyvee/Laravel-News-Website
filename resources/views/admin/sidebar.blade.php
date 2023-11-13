<!-- /. NAV TOP  -->
<nav class="navbar-default navbar-side" role="navigation">
<div class="sidebar-collapse">
    <ul class="nav" id="main-menu">


        <li class="<?= $page_title == 'Dashboard' ? 'active-link':'';?>">
            <a href="{{url('admin')}}"><i class="fa fa-desktop "></i>Dashboard</a>
        </li>


        <li class="<?= $page_title == 'Posts' ? 'active-link':'';?>">
            <a href="{{url('admin/posts')}}"><i class="fa fa-table "></i>Posts</a>
        </li>
        <li class="<?= $page_title == 'Categories' ? 'active-link':'';?>">
            <a href="{{url('admin/categories')}}"><i class="fa fa-edit "></i>Categories</a>
        </li>



        <li class="<?= $page_title == 'Users' ? 'active-link':'';?>">
            <a href="{{url('admin/users')}}"><i class="fa fa-user "></i>Users</a>
        </li>
         </ul>
</div>

</nav>