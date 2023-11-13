<?php

namespace App\Http\Controllers;

use App\Models\{Category, Post, User, Image, MyPage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
//
public function index(Request $req)
{
return view('admin.admin', ['page_title' => 'Dashboard']);
}
public function posts(Request $req, $type = null, $id = null)
{
switch ($type) {
case 'add':
    if ($req->isMethod('POST')) {
        $post = new Post();

        $validated = $req->validate([

            'title' => 'required|string',
            'file' => 'required|image',
            'content' => 'required'
        ]);

        $folder = "uploads/";
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }


        //remove images front content
        preg_match_all('/<img[^>]+>/', $req->input('content'), $matches);
        $new_content = $req->input('content');

        $image_class = new Image();
        if (is_array($matches) && count($matches) > 0) {
            foreach ($matches[0] as $match) {
                preg_match('/src="([^"]+)"/i', $match, $matches2);
                $parts = explode(",", $matches2[0]);
                $filename = $folder . "base_64" . $image_class->generate_filename(50) . ".jpg";

                $new_content = str_replace($parts[0] . "," . $parts[1], 'src="' . $filename . '"', $new_content);
                file_put_contents($filename, base64_decode($parts[1]));
            }
        }

        $path = $req->file('file')->store('/', ['disk' => 'my_disk']);

        $data['title'] = $req->input('title');
        $data['category_id'] = $req->input('category_id');
        $data['image'] = $path;
        $data['content'] = $new_content;
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['slag'] = $post->str_to_url($data['title']);

        $post->insert($data);
        return redirect('admin/posts');
    }
    $query = "select * from categories order by id desc";
    $categories = DB::select($query);
    return view(
        'admin.add_post',
        [
            'page_title' => 'New Post',
            'categories' => $categories,
        ]
    );
    break;
case 'edit':
    # code...
    $post = new Post();
    if ($req->method() == 'POST') {

        $validated = $req->validate([

            'title' => 'required|string',
            'file' => 'image',
            'content' => 'required'
        ]);

        if ($req->file('file')) {
            $oldrow = $post->find($id);
            if (file_exists('uploads/' . $oldrow->image)) {
                unlink('uploads/' . $oldrow->image);
            }
            $path = $req->file('file')->store('/', ['disk' => 'my_disk']);
            $data['image'] = $path;
        }

        $data['title'] = $req->input('title');
        $data['category_id'] = $req->input('category_id');
        $data['content'] = $req->input('content');
        $data['updated_at'] = date("Y-m-d H:i:s");

        $post->where('id', $id)->update($data);
        return redirect('admin/posts/edit/' . $id);
    }

    $row = $post->find($id);

    $category_query = "select * from categories order by id desc";
    $categories = DB::select($category_query);

    return view('admin.edit_post', [
        'page_title' => ' Edit Post',
        'row' => $row,
        'categories' => $categories,
    ]);
    break;
case 'delete':
    # code...
    $post = new Post();
    $row = $post->find($id);
    $category = $row->category()->first();

    if ($req->method() == 'POST') {
        $imageName = $row->image; // Get the image name from the database record
    
        // Check if the 'uploads/' directory exists and is a directory
        if (is_dir('uploads/')) {
            // Open the 'uploads/' directory
            if ($dh = opendir('uploads/')) {
                while (($file = readdir($dh)) !== false) {
                    // Check if the file name matches the image name
                    if ($file == $imageName) {
                        // Delete the matching file
                        unlink('uploads/' . $file);
                    }
                }
                closedir($dh); // Close the directory
            }
        }
    
        // After deleting all matching files, you can proceed to delete the database record
        $row->delete();
    
        return redirect('admin/posts');
    }
    
    return view('admin.delete_post', [
        'page_title' => ' Delete Post',
        'row' => $row,
        'category' => $category,
    ]);
    break;

default:
    # code...
    $limit = 10;
    $page = $req->input('page') ? (int)$req->input('page') : 1;
    $offset = ($page - 1) * $limit;

    $page_class = new MyPage();
    $links = $page_class->make_links($req->fullUrlWithQuery(['page' => $page]), $page, 1);

    $query = "select posts.*, categories.category from posts join categories on posts.category_id = categories.id limit $limit offset $offset";
    $rows = DB::select($query);

    $img = new Image();
    foreach ($rows as $key => $row) {
        $rows[$key]->image = $img->get_thumb('uploads/' . $row->image);
    }

    $data['rows'] = $rows;
    $data['links'] = $links;
    $data['page_title'] = 'Posts';
    return view('admin.posts', $data);
    break;
}
}
public function categories(Request $req, $type = null, $id = null)
{
switch ($type) {
case 'add':
    if ($req->method() == 'POST') {
        $category = new Category();

        $validated = $req->validate([

            'category' => 'required|string',

        ]);

        $data['category'] = $req->input('category');
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");

        $category->insert($data);
        return redirect('admin/categories');
    }
    return view('admin.add_category', ['page_title' => 'New Category']);
    break;
case 'edit':
    # code...
    $category = new Category();
    if ($req->method() == 'POST') {

        $validated = $req->validate([
            'category' => 'required|string'
        ]);

        $data['category'] = $req->input('category');
        $data['updated_at'] = date("Y-m-d H:i:s");

        $category->where('id', $id)->update($data);
        return redirect('admin/categories/edit/'. $id);
    }

    $row = $category->find($id);

    return view('admin.edit_category', [
        'page_title' => ' Edit Category',
        'row' => $row
    ]);
    break;
case 'delete':
    # code...
    $category = new Category();
    $row = $category->find($id);

    if ($req->method() == 'POST') {
        $row->delete();
        return redirect('admin/categories');
    }

    return view('admin.delete_category', [
        'page_title' => ' Delete Category',
        'row' => $row,
    ]);
    break;

default:
    # code...
    $limit = 7;
    $page = $req->input('page') ? (int)$req->input('page') : 1;
    $offset = ($page - 1) * $limit;

    $page_class = new MyPage();
    $links = $page_class->make_links($req->fullUrlWithQuery(['page' => $page]), $page, 1);

    $query = "select * from categories order by id desc limit $limit offset $offset";
    $rows = DB::select($query);
    $data['rows'] = $rows;
    $data['links'] = $links;
    $data['page_title'] = 'Categories';
    return view('admin.categories', $data);
    break;
}
}
public function users(Request $req, $type = null, $id = null)
{
switch ($type) {
case 'edit':
    # code...
    $user = new User();
    if ($req->method() == 'POST') {

        $validated = $req->validate([

            'name' => 'string',
            'email' => 'email'
        ]);

        $data['name'] = $req->input('name');
        $data['email'] = $req->input('email');

        if (!empty($req->input('password'))) {
            $data['password'] = $req->input('password');
        }

        $data['updated_at'] = date("Y-m-d H:i:s");
        $user->where('id', $id)->update($data);
        return redirect('admin/users');
    }

    $row = $user->find($id);

    return view('admin.edit_user', [
        'page_title' => ' Edit User',
        'row' => $row
    ]);
    break;
case 'delete':
    # code...
    $user = new User();
    $row = $user->find($id);

    if ($req->method() == 'POST') {
        if ($row->id != 1) {
            $row->delete();
        }
        return redirect('/admin/users');
    }

    return view('admin.delete_user', [
        'page_title' => ' Delete User',
        'row' => $row,
    ]);
    break;

default:
    # code...
    $limit = 12;
    $page = $req->input('page') ? (int)$req->input('page') : 1;
    $offset = ($page - 1) * $limit;

    $page_class = new MyPage();
    $links = $page_class->make_links($req->fullUrlWithQuery(['page' => $page]), $page, 1);

    $query = "select * from users order by id desc limit $limit offset $offset";
    $rows = DB::select($query);
    $data['rows'] = $rows;
    $data['page_title'] = 'Users';
    $data['links'] = $links;
    return view('admin.users', $data);
    break;
}
}
public function save(Request $req)
{
$validate = $req->validate([
'key' => 'required|string',
'key' => 'required|image'
]);
return view('view');
}
}
