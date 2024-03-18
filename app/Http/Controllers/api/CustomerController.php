<?php

namespace App\Http\Controllers\api;


use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use function App\CPU\translate;
class CustomerController extends Controller
{
   
    public function info(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ], [
            'f_name.required' => Helpers::translate('First name is required!'),
            'l_name.required' => Helpers::translate('Last name is required!'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->has('image')) {
            $imageName = ImageManager::update('profile/', $request->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = $request->user()->image;
        }

 

        $userDetails = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imageName,
       
            'updated_at' => now(),
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);

        return response()->json(['message' => Helpers::translate('successfully updated!')], 200);
    }

    public function account_delete(Request $request, $id)
    {
        if($request->user()->id == $id)
        {
            $user = User::find($id);

            ImageManager::delete('/profile/' . $user['image']);

            $user->delete();
           return response()->json(['message' => Helpers::translate('Your_account_deleted_successfully!!')],200);

        }else{
            return response()->json(['message' =>'access_denied!!'],403);
        }
    }

}