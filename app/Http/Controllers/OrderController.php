<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Http\Traits\Notify;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use Notify;

    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "All Orders";
        $orders = Order::with('service', 'user')->has('service')->paginate(config('basic..paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    /*
     * search
     */
    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $orders = Order::with('service', 'users')
            ->when(isset($search['order_id']), function ($query) use ($search) {
                return $query->where('id', 'LIKE', "%{$search['order_id']}%");
            })
            ->when(isset($search['service']), function ($query) use ($search) {
                return $query->whereHas('service', function ($q) use ($search) {
                    return $q->where('service_title', 'LIKE', "%{$search['service']}%");
                });
            })
            ->when(isset($search['user']), function ($query) use ($search) {
                return $query->whereHas('users', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user']}%");
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', 'LIKE', "%{$search['status']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));

        $page_title = "Search Orders";
        return view('admin.pages.order.search', compact('orders', 'page_title'));
    }

    public function awaiting(Request $request, $name = 'awaiting')
    {
        $page_title = "Awaiting Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function pending(Request $request, $name = 'pending')
    {
        $page_title = "Pending Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function processing(Request $request, $name = 'processing')
    {
        $page_title = "Processing Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function progress(Request $request, $name = 'progress')
    {
        $page_title = "Progress Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function completed(Request $request, $name = 'completed')
    {
        $page_title = "Completed Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function partial(Request $request, $name = 'partial')
    {
        $page_title = "Partial Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function canceled(Request $request, $name = 'canceled')
    {
        $page_title = "Canceled Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    public function refunded(Request $request, $name = 'refunded')
    {
        $page_title = "Refunded Orders";
        $orders = Order::with('service', 'users')->where('status', $name)->paginate(config('basic.paginate'));
        return view('admin.pages.order.show', compact('orders', 'page_title'));
    }

    /*
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order, $id)
    {
        $order = Order::with('users', 'service', 'service.category', 'service.provider')->find($id);
        return view('admin.pages.order.edit', compact('order'));
    }

    /*
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order, $id)
    {
        $this->validate($request, [
        
            'remains' => 'nullable|numeric|not_in:0',
            'start_counter' => 'nullable|numeric|not_in:0',
            'status' => 'required|in:awaiting,pending,processing,progress,completed,partial,canceled,refunded',
            'refill_status' => 'sometimes|in:awaiting,pending,processing,progress,completed,partial,canceled,refunded',
            'reason' => 'nullable|max:10000'
        ], [
            'reason.max' => 'The note field should be maximum 10000 characters.'
        ]);

        $order = Order::with('user','service')->find($id);
        $order->start_counter = $request['start_counter'] == '' ? null : $request['start_counter'];
        $order->api_order_id = $request->api_order_id;
        $order->link = $request['link'];
        $order->remains = $request['remains'] == '' ? null : $request['remains'];
        $order->status = $request['status'];

        if (isset($order->refilled_at) && $request->refill_status) {
            $order->refill_status = $request['refill_status'];
        }

        $order->reason = $request['reason'];
        $order->save();

        $msg = [
            'description'=>$order->service->service_title.' order is '.$order->status,
            'title'=>'Order Update',
            'image'=>''
        ];
        Helpers::send_push_notif_to_device($order->user->cm_firebase_token, $msg);

        // $this->sendMailSms($order->users, 'ORDER_UPDATE', [
        //     'order_id' => $order->id,
        //     'start_counter' => $order->start_counter,
        //     'link' => $order->link,
        //     'remains' => $order->remains,
        //     'order_status' => $order->status
        // ]);
        return back()->with('success', 'successfully updated');
    }


    /*
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        $order->delete();
        return back()->with('success', 'Successfully Deleted');
    }

    public function statusChange(Request $request)
    {
        $req = $request->all();
        $order = Order::with('user','service')->find($request->id);
        $order->status = $req['statusChange'];
        $order->save();
        
        if($order->currency =='USD'){
            
        if ($order->status == 'refunded' && $order->remains != 0) {
            $perOrder = $order->price / $order->quantity;
            $getBackAmo = $order->remains * $perOrder;

            $user = $order->user;
            $user->balance += $getBackAmo;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->trx_type = '+';
            $transaction->amount = $getBackAmo;
            $transaction->remarks = 'Refunded order on #'.$order->id;
            $transaction->trx_id = strRandom();
            $transaction->charge = 0;
            $transaction->save();
        }
        if ($order->status == 'canceled') {
            $getBackAmo = $order->price;

            $user = $order->user;
            $user->balance += $getBackAmo;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->trx_type = '+';
            $transaction->amount = $getBackAmo;
            $transaction->remarks = 'Canceled order on #'.$order->id;
            $transaction->trx_id = strRandom();
            $transaction->charge = 0;
            $transaction->save();
        }
        
        }
        else{
                if ($order->status == 'refunded' && $order->remains != 0) {
            $perOrder = $order->price / $order->quantity;
            $getBackAmo = $order->remains * $perOrder;

            $user = $order->user;
            $user->lbalance += $getBackAmo;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->trx_type = '+';
            $transaction->currency = "LBP";
            $transaction->amount = $getBackAmo;
            $transaction->remarks = 'Refunded order on #'.$order->id;
            $transaction->trx_id = strRandom();
            $transaction->charge = 0;
            $transaction->save();
        }
        if ($order->status == 'canceled') {
            $getBackAmo = $order->price;

            $user = $order->user;
            $user->lbalance += $getBackAmo;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->trx_type = '+';
             $transaction->currency = "LBP";
            $transaction->amount = $getBackAmo;
            $transaction->remarks = 'Canceled order on #'.$order->id;
            $transaction->trx_id = strRandom();
            $transaction->charge = 0;
            $transaction->save();
        }
        }

        $msg = [
            'description'=>$order->service->service_title.' order is '.$order->status,
            'title'=>'Order Update',
            'image'=>''
        ];
        Helpers::send_push_notif_to_device($order->user->cm_firebase_token, $msg);
        return back()->with('success', 'Successfully Updated');
    }


    public function getuser(Request $request)
    {
        $user = User::where('name', 'LIKE', "%{$request->user}%")->get()->pluck('name');
        return response()->json($user);
    }


    /*
     * user drop search
     */
    public function getusersearch(Request $request)
    {
        $user = User::where('name', 'LIKE', "%{$request->user_name}%")->get()->pluck('name');
        return response()->json($user);
    }

    /*
     * user search
     */
    public function getTrxUserSearch(Request $request)
    {
        $users = User::where('name', 'LIKE', "%{$request->data}%")->get()->pluck('name');
        return response()->json($users);
    }

    /*
     * TRX
     */
    public function gettrxidsearch(Request $request)
    {
        $transaction = Transaction::where('trx_id', 'LIKE', "%{$request->trxid}%")->get()->pluck('trx_id');
        return response()->json($transaction);
    }

    public function transaction()
    {
        $transaction = Transaction::with('user')->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
       // dd($transaction);
       // Log::info($transaction);
        return view('admin.pages.transaction.index', compact('transaction'));
    }

    /*
     * transaction search
     */
    public function transactionSearch(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $transaction = Transaction::with('user')->orderBy('id', 'DESC')
            ->when($search['transaction_id'], function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when($search['user_name'], function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when($search['remark'], function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transaction = $transaction->appends($search);

        return view('admin.pages.transaction.index', compact('transaction'));
    }
}
