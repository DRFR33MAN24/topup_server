<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Style;
use App\Models\Tag;
use Illuminate\Http\Request;


class StyleController extends Controller
{

    public function get_styles(Request $request)
    {
        $limit = $request["limit"];
        $tag = $request['tag'];
        $offset = $request["offset"];
        if ($tag) {
            $paginator = Style::with(['rating', 'tags'])->whereRelation('tags','tag','=',$tag)->latest()->paginate($limit, ['*'], 'page', $offset);
            
        } else {
            $paginator = Style::with(['rating', 'tags'])->latest()->paginate($limit, ['*'], 'page', $offset);
        }
        

        /*$paginator->count();*/
        $styles = [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'styles' => $paginator->items()
        ];

        return response()->json($styles, 200);
    }

    public function get_styles_tags(Request $request){
        $tags =[
            'tags'=>Tag::all()
        ];
        return response()->json($tags,200);
    }
}
