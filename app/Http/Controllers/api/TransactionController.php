<?php

namespace App\Http\Controllers\api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;




class TransactionController extends Controller
{

    public function get_transactions(Request $request)
    {
        //LOG::info($request);
        $limit = $request["limit"];
        $date = $request['date'];

        $offset = $request["offset"];
        $paginator = Transaction::all();




        if ($date) {
           $paginator= $paginator->where('category_title','LIKE',"%{$date}%");
        }

        $paginator =$paginator->latest()->paginate($limit, ['*'], 'page', $offset);

        /*$paginator->count();*/
        $transactions = [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'transactions' => $paginator->items()
        ];
       //Log::info(json_encode($paginator->items()));

        return response()->json($transactions, 200);
    }

  
}