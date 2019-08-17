<?php

namespace App\Http\Controllers;

use App\LeaveApplication;
use Illuminate\Http\Request;


class RelieverController extends Controller
{
  public function relieverRequests(Request $request)
    {
        $requests = LeaveApplication::where('reliever', auth('api')->user()->id)->orderBy('id', 'desc')->get();

        return response()->json([
            'requests' => $requests,
            'message' => 'Reliever requests',
            'error' => false
        ]);
    }
}
