<?php 

namespace App\Models;

class MyPage
{

	public function make_links($url, $curpage, $butno = 2)
	{
        $curpage = $curpage ? (int)$curpage : 1; 
        $start =  $curpage - $butno;
        $end =  $curpage + $butno;
        if($start < 1){$start = 1;}
        
        $buttons = [];
        $buttons[] = [
            'First',
            preg_replace('/page=[0-9]+/', 'page=1', $url),
              0];

        $num = $curpage + 1;
        for ($i=$start; $i <=$end ; $i++) { 
            # code...
            $myurl = preg_replace('/page=[0-9]+/', 'page='.$i, $url);
            $active = 0; 
            if($i == $curpage){$active = 1;}
            $buttons[] = [$i, $myurl, $active];
            $num = $i;
           
        }
        $buttons[] = [
            'Next',
            preg_replace('/page=[0-9]+/', 'page='.($num + $butno), $url),
            0
        ];
        
        return $buttons;
    }
    public function getPaginatedData($req, $page_class, $limit) {
       
        $page = $req->input('page', 1);
        $offset = ($page - 1) * $limit;

        $links = $page_class->make_links($req->fullUrlWithQuery(['page' => $page]), $page, 1);

    
        return [
            'offset' => $offset,
            'links' => $links,
        ];
    }
}