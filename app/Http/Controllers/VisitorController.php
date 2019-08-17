<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Visitors;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function new_visitor(Request $request)
    {
        // $profile_data = $request->all();
        // $fName = $profile_data['fname'];
        //
        // $file = $request->file('avatar');
        // $file->move('avatar', time().'-'.$fName.'-'.$file->getClientOriginalName());
        // $name = time().'-'.$fName.'-'.$file->getClientOriginalName();

        $visitor = new Visitors([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'nat_id' => $request->nat_id,
            'employee_id' => $request->employee_id
            // 'avatar'       => $name
        ]);

        $visitor->save();

        return $visitor;
    }

    public function get_visitor(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nat_id' => 'required' 
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => "Please fill all fields",
                'error' => true,
                "errors" => $validate->getMessageBag()
            ]);
        }

        $visitor = Visitors::where('nat_id', $request->get('nat_id'))->first();

        if ($visitor == null) {
            return response()->json([
                "message" => 'Visitor not found',
                'error' => true,
            ], 404);
        }


        return response()->json([
            "message" => 'Visitor',
            'error' => false,
            "visitor" => $visitor
        ]);
    }

    public function uploadVisitorImage(Request $request){
        //Do validation

        $name = uniqid() . '.' . $request->file('file')->getClientOriginalExtension();

        $visitor    =Visitors::find($request->get('id'));

        //If visitor is null

        $request->file('file')->move(public_path(avatarUploads()) , $name);
        /*
         * Resize and sve banner File
         */
//        Image::make(public_path(productUploads() . $name))
//            ->resize(400 , NULL , function ($constraint) {
//                $constraint
//                    ->aspectRatio()
//                    ->upsize();
//            })
//            ->text('Sembe.' , 0 , 200)
//            ->save(public_path(bannerResized() . $name));
        /*
         * Delete Original Image File
         */
//        deleteFile(public_path(productUploads() . $name));
        /*
         * Save Banner Image
         */

        /*
         * Chnege to the Resized path
         */
        $visitor->update([
            'avatar'    =>  $name
        ]);
    }
}
