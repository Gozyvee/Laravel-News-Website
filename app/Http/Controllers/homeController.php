<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Image, MyPage};

class homeController extends Controller
{
//
public function index(Request $req)
{
    $limit = 12;
    $page = $req->input('page') ? (int)$req->input('page') : 1;
    $offset = ($page - 1) * $limit;

    $page_class = new MyPage();
    $links = $page_class->make_links($req->fullUrlWithQuery(['page' => $page]), $page, 1);

    if (null != $req->input('find')) {
        //search by post title
        $query = "select posts.*, categories.category from posts join categories on posts.category_id = categories.id
    where title like :title limit $limit offset $offset ";
        $title = "%" . $req->input('find') . "%";
        $rows = DB::select($query, ['title' => $title]);
    } elseif (null != $req->input('cat')) {
        //search by category
        $query = "select posts.*, categories.category from posts join categories on posts.category_id = categories.id
    where category_id = :id limit $limit offset $offset ";
        $id =  $req->input('cat');
        $rows = DB::select($query, ['id' => $id]);}
    else {

        $query = "select posts.*, categories.category from posts join categories on posts.category_id = categories.id limit $limit offset $offset ";
        $rows = DB::select($query);
    }

    $img = new Image();


    foreach ($rows as $key => $row) {
        $rows[$key]->image = $img->get_thumb_post('uploads/' . $row->image);
    }
    $category_query = "select * from categories order by id desc";
    $categories = DB::select($category_query);

    $data['rows'] = $rows;
    $data['links'] = $links;
    $data['page_title'] = 'Home';
    $data['categories'] = $categories;
    return view('index', $data);
}
public function save(Request $req)
{
    $validate = $req->validate([
        'key' => 'required|string',
        'key' => 'required|image'
    ]);
    return view('index');
}
}
