<?php

namespace App\Http\Controllers;

use App\Models\{Category, Post, User, Image, MyPage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
                $post = new Post();
                if ($req->isMethod('POST')) {

                    $req->validate([

                        'title' => 'required|string',
                        'file' => 'required|image',
                        'content' => 'required'
                    ]);

                    $path = $req->file('file')->store('/', ['disk' => 'my_disk']);

                    $post->title = $req->input('title');
                    $post->category_id = $req->input('category_id');
                    $post->content = $post->replaceImagesInContent($req->input('content'));
                    $post->slag = $post->str_to_url($post->title);

                    $post->created_at = date("Y-m-d H:i:s");
                    $post->updated_at = date("Y-m-d H:i:s");


                    if ($req->hasFile('file')) {
                        $path = $req->file('file')->store('/', ['disk' => 'my_disk']);
                        $post->image = $path;
                    }
                    $post->save();

                    return redirect('admin/posts');
                }
                $categories = Category::orderBy('id', 'desc')->get();

                return view('admin.add_post', [
                    'page_title' => 'New Post',
                    'categories' => $categories,
                ]);
                break;
            case 'edit':
                $post = Post::find($id);

                if ($req->isMethod('POST')) {

                    $validated = $req->validate([

                        'title' => 'required|string',
                        'file' => 'image',
                        'content' => 'required'
                    ]);

                    $post->title = $req->input('title');
                    $post->category_id = $req->input('category_id');
                    $post->content =  $post->editPostAndRemoveUnusedImages($req->input('content'), $post->content);
                    $post->updated_at = gmdate('Y-m-d\TH:i:s\Z');
                    $post->slag = $post->str_to_url($post->title);

                    if ($req->hasFile('file')) {
                        // Delete the old image
                        Storage::disk('my_disk')->delete($post->image);
                        $path = $req->file('file')->store('/', ['disk' => 'my_disk']);
                        $post->image = $path;
                    }

                    $post->save();
                    return redirect('admin/posts/edit/' . $id);
                }

                $categories = Category::orderBy('id', 'desc')->get();
                return view('admin.edit_post', [
                    'page_title' => ' Edit Post',
                    'row' => $post,
                    'categories' => $categories,
                ]);

                break;

            case 'delete':
                $post = Post::find($id);

                if (!$post) {
                    // Handle the case where the post with the given ID is not found
                    return redirect('admin/posts')->with('error', 'Post not found');
                } elseif ($req->isMethod('POST')) {
                    $post->deletePostAndFiles($post);
                    $post->delete();
                    return redirect('admin/posts')->with('success', 'Post and associated files deleted successfully');
                }


                return view('admin.delete_post', [
                    'page_title' => ' Delete Post',
                    'row' => $post
                ]);
                break;

            default:
                # code...
                $page_class = new MyPage();
                $limit = 5;
                $offsetAndLinks = $page_class->getPaginatedData($req, $page_class, $limit);

                $posts = DB::table('posts')
                    ->join('categories', 'posts.category_id', '=', 'categories.id')
                    ->select('posts.*', 'categories.category')
                    ->skip($offsetAndLinks['offset'])
                    ->take($limit)
                    ->get();

                $posts->each(function ($post) {
                    $post->image = (new Image())->get_thumb('uploads/' . $post->image);
                });

                if ($req->input('clone')) {
                    $postIds = $req->input('check');
                    
                    if (!empty($postIds)) {
                        $selectedIds = [];
                        foreach ($postIds as $ids) {
                            // Append each ID to the $selectedIds array
                            $selectedIds[] = $ids;
                            $originalPost = Post::find($ids);
                        
                            if ($originalPost) {
                                $clonedPost = $originalPost->replicate();
                                $clonedPost->save();
                            }
                        }
                                        
                    } else {
                        return redirect()->route('admin.select')->with('error', 'No checkboxes selected.');
                    }
                }
                
                return view('admin.posts', [
                    'rows' => $posts,
                    'links' =>  $offsetAndLinks['links'],
                    'page_title' => 'Posts',


                ]);

                break;
        }
    }
    public function categories(Request $req, $type = null, $id = null)
    {
        switch ($type) {
            case 'add':
                if ($req->isMethod('POST')) {
                    $category = new Category();

                    $req->validate([
                        'category' => 'required|string',
                    ]);

                    $category->category = $req->input('category');
                    $category->created_at = date("Y-m-d H:i:s");
                    $category->updated_at = date("Y-m-d H:i:s");


                    $category->save();
                    return redirect('admin/categories');
                }
                return view('admin.add_category', ['page_title' => 'New Category']);
                break;
            case 'edit':
                # code...
                $category = Category::find($id);
                if ($req->isMethod('POST')) {

                    $req->validate([
                        'category' => 'required|string'
                    ]);

                    $category->category = $req->input('category');
                    $category->created_at = date("Y-m-d H:i:s");

                    $category->save();
                    return redirect('admin/categories/edit/' . $id);
                }

                return view('admin.edit_category', [
                    'page_title' => ' Edit Category',
                    'row' => $category
                ]);
                break;
            case 'delete':
                # code...
                $category = Category::find($id);

                if ($req->isMethod('POST')) {
                    $category->delete();
                    return redirect('admin/categories');
                }

                return view('admin.delete_category', [
                    'page_title' => ' Delete Category',
                    'row' => $category,
                ]);
                break;

            default:
                # code...
                $page_class = new MyPage();
                $limit = 12;
                $offsetAndLinks = $page_class->getPaginatedData($req, $page_class, $limit);

                $categories = DB::table('categories')
                    ->select('*')
                    ->orderBy('id', 'desc')
                    ->skip($offsetAndLinks['offset'])
                    ->take($limit)
                    ->get();


                return view('admin.categories', [
                    'rows' => $categories,
                    'links' => $offsetAndLinks['links'],
                    'page_title' => 'Categories',
                ]);
                break;
        }
    }
    public function users(Request $req, $type = null, $id = null)
    {
        switch ($type) {
            case 'edit':
                # code...
                $user = User::find($id);
                if ($req->isMethod('POST')) {

                    $req->validate([
                        'name' => 'string',
                        'email' => 'email'
                    ]);

                    $user->name = $req->input('name');
                    $user->email = $req->input('email');

                    if (!empty($req->input('password'))) {
                        $user->password = $req->input('password');
                    }

                    $user->updated_at = date("Y-m-d H:i:s");
                    $user->save();
                    return redirect('admin/users');
                }

                return view('admin.edit_user', [
                    'page_title' => ' Edit User',
                    'row' => $user
                ]);
                break;
            case 'delete':
                # code...
                $user = User::find($id);


                if ($req->isMethod('POST')) {
                    if ($user->id != 1) {
                        $user->delete();
                    }
                    return redirect('/admin/users');
                }

                return view('admin.delete_user', [
                    'page_title' => ' Delete User',
                    'row' => $user,
                ]);
                break;

            default:
                # code...
                $page_class = new MyPage();
                $limit = 12;
                $offsetAndLinks = $page_class->getPaginatedData($req, $page_class, $limit);

                $users = DB::table('users')
                    ->select('*')
                    ->orderBy('id', 'desc')
                    ->skip($offsetAndLinks['offset'])
                    ->take($limit)
                    ->get();

                return view('admin.users', [
                    'rows' => $users,
                    'links' => $offsetAndLinks['links'],
                    'page_title' => 'Users',
                ]);
                break;
        }
    }
}
