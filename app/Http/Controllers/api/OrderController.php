<?php

namespace App\Http\Controllers\api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\CPU\ImageManager;
use App\Jobs\ProcessOrder;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Providers\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;




class OrderController extends Controller
{

    public function get_orders(Request $request)
    {
        //LOG::info($request);
        $limit = $request["limit"];
        $date = $request['date'];

        $offset = $request["offset"];
        $paginator = Order::query();

        $paginator->with(['service']);




        if ($date) {
           $paginator= $paginator->whereDate('created_at', '=', Carbon::parse($date)->toDateString()
        );
        }

        $paginator =$paginator->latest()->paginate($limit, ['*'], 'page', $offset);

        /*$paginator->count();*/
        $orders = [
            'total_size' => $paginator->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'orders' => $paginator->items()
        ];
    //Log::info(json_encode($paginator->items()));

        return response()->json($orders, 200);
    }

    public function place_order(Request $request)
    {

    
        // $validator = Validator::make($request->all(), [
        //     'style_id' => 'required',

        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        // }





        $order = [
            'user_id' => 1,
            'category_id'=>1,
            'service_id'=>1,
            'status'=>'pending',
            'price'=>1.5,

            'updated_at' => now(),
        ];

        $order_ref=Order::create($order);



        return response()->json(['message' => Helpers::translate('successfully created!')], 200);
    }
}
