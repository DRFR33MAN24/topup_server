<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CategoryController extends Controller
{

    public function get_categories(Request $request)
    {
        $limit = $request["limit"];
        $tag = $request['tag'];
        $offset = $request["offset"];
        if ($tag) {
            $paginator = Category::with([ 'tags','services'])->whereRelation('tags','tag','=',$tag)->latest()->paginate($limit, ['*'], 'page', $offset);
            
        } else {
            $paginator = Category::with([ 'tags','services'])->latest()->paginate($limit, ['*'], 'page', $offset);
        }
        

        /*$paginator->count();*/
        $categories = [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'categories' => $paginator->items()
        ];
      // Log::info(json_encode($categories));

        return response()->json($categories, 200);
    }

    public function get_categories_tags(Request $request){
        $tags =[
            'tags'=>Tag::all()
        ];
        return response()->json($tags,200);
    }
}