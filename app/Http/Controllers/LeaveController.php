<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\LeaveApplication;
use App\Holidays;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;
use Illuminate\Support\Carbon;

class LeaveController extends Controller
{
    public function applyLeave(Request $request)
    {
        $usersId = auth('api')->user('id');
        $id = $usersId['id'];
        // $nr_work_day = $this->calculate_days($id, $request->startDate, $request->endDate);
        $beginday = Carbon::parse($request->startDate);
        $lastday = Carbon::parse($request->endDate);

        dd("here");
        // $nr_work_days = $this->getWorkingDays($beginday,$lastday);
        $begin = strtotime($beginday);
        $end = strtotime($lastday);
        // print_r($startDate);
        if ($begin > $end) {
            echo "startdate is in the future! <br />";
            return 0;
        } else {
            $no_days = 0;
            $weekends = 0;
            while ($begin <= $end) {
                $no_days++; // no of days in the given interval
                $what_day = date("N", $begin);
                if ($what_day > 5) { // 6 and 7 are weekend days
                    $weekends++;
                };
                $begin += 86400; // +1 day
            };

            $working_days = $no_days - $weekends;
        }
        // $nr_work_days = (int)$nr_work_day;
        if ($working_days > 0) {

            $leave = Auth::user()->leave()->create([
                'type' => $request->type,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'reliever' => $request->reliever,
                'leave_days' => $working_days
            ]);
//            dd($leave);

            return $this->prepareResult(1, $leave, [], "Success");
        } else {
            echo "Invalid application";
        }
        return "Invalid application";
    }

    public function leaveHistory()
    {
        $usersId = auth('api')->user('id');
        $id = $usersId['id'];
        $history = LeaveApplication::where('id', $id)->get();
        return $history;
    }

    public function employees_departments(Request $request)
    {
        $user = auth('api')->user();
        $relievers = User::where('department_id', $user->department_id)->where('id', '!=', $user->id)
            ->select(['name', 'id'])->get();

        return response()->json([
            'relievers' => $relievers,
            "messages" => "Department Relievers",
            'error' => false
        ],200);
    }

    public function employees()
    {
        $employees = User::where('active', 1)->get();

        return $this->prepareResult(1, $employees, [], "Success");
    }

    private function prepareResult($status, $data, $errors, $msg)
    {
        if ($errors == null) {
            return ['status' => $status, 'payload' => $data, 'message' => $msg];
        } else {
            return ['status' => $status, 'message' => $msg, 'errors' => $errors];
        }
    }

    // calculate days
    public function calculate_days($id, $start, $end)
    {

        $beginday = date($start);
        $lastday = date($end);

        $nr_work_days = $this->getWorkingDays($beginday, $lastday);

        return $nr_work_days;
    }

    public function getWorkingDays($startDate, $endDate)
    {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);
        // print_r($startDate);
        if ($begin > $end) {
            echo "startdate is in the future! <br />";
            return 0;
        } else {
            $no_days = 0;
            $weekends = 0;
            while ($begin <= $end) {
                $no_days++; // no of days in the given interval
                $what_day = date("N", $begin);
                if ($what_day > 5) { // 6 and 7 are weekend days
                    $weekends++;
                };
                $begin += 86400; // +1 day
            };

            $working_days = $no_days - $weekends;

            // echo $working_days;

            // $holiday_dates = DB::table('holidays')
            //                 ->select('holiday_date')
            //                 ->get();
            // foreach ($holiday_dates as $value) {
            //   $holidays = array();
            //   $y = date("Y");
            //   $values = "2019/".$value->holiday_date;
            //   // $values = $y."/".$value->holiday_date;
            //   $r = array_push($holidays, $values);
            //   // print_r($value);
            //   //Subtract the holidays
            //   foreach($holidays as $holiday){
            //     // print_r($holiday);
            //     $time_stamp=strtotime($holiday);
            //     // print_r($time_stamp);
            //     //If the holiday doesn't fall in weekend
            //     if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            //         $working_days--;
            //         // print_r ($working_days);
            //                 return $working_days;
            //   }
            // }

        }
    }

    public function mail()
    {
        $data['name'] = "Test";
        $data['to'] = "mageto.denis@gmail.com";
        $data['message'] = "abcdefg";
        $data['subject'] = "Password";
        $data['template'] = "email.password";
        $data['sender'] = "test@us.com";
        $data['sendername'] = "Test Name";
        $this->sendEmail($data);
    }

    public function sendEmail($data)
    {


        // Mail::send($data['template'],$data, function($message) use ($data){
        //    $message->to($data['to'],$data['name'])->subject($data['subject']);
        //    $message->from($data['sender'],$data['sendername']);
        // });
        // echo "HTML Email Sent. Check your inbox.";

        $data = array('name' => "Virat Gandhi");

        Mail::send(['text' => 'email.password'], $data, function ($message) {
            $message->to('mageto.denis@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
        echo "Basic Email Sent. Check your inbox.";

    }

    public function relieverApprove(Request $request)
    {
        $validate = Validator::make($request->only('reliever_reason'), [
            'reliever_reason' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => true,
                "errors" => $validate->getMessageBag(),
                "message" => "Please give a reason"
            ]);
        }
        $user = auth('api')->user();
        $application = LeaveApplication::where('id', $request->get('leaveID'))->where('reliever', $user->id)->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID, Permission denied",
                "error" => true

            ], 404);
        }

        /*
         * Accept Request
         */
        $application->update([
            'releiver_approval' => 'accepted'
        ]);

        $application->reason()->updateOrCreate([
            'reliever' => $request->get('reliever_reason')
        ]);

        return response()->json([
            'message' => "Leave request accepted",
            "leave" => $application,
            "error" => false
        ]);

    }

    public function relieverReject(Request $request)
    {
        $validate = Validator::make($request->only('reliever_reason'), [
            'reliever_reason' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => true,
                "errors" => $validate->getMessageBag(),
                "message" => "Please give a reason"
            ]);
        }
        $user = auth('api')->user();
        $application = LeaveApplication::where('id', $request->get('leaveID'))->where('reliever', $user->id)->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }

        /*
         * Accept Request
         */
        $application->update([
            'reliever_approval' => 'rejected'
        ]);
        $application->reason()->updateOrCreate([
            'reliever' => $request->get('reliever_reason')
        ]);

        return response()->json([
            'message' => "Leave request rejected",
            "leave" => $application,
            "error" => false
        ]);
    }

    public function hodApprove(Request $request)
    {
        $user = auth('api')->user();
        $application = LeaveApplication::where('id', $request->get('leaveID'))->first();


        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }


            /*
        * Accept Request
        */



        $application->update([
            'HOD' => 'accepted'
        ]);

        $application->reason()->updateOrCreate([
            'reliever'  =>  '..',
            'hod' => $request->get('..', 'reliever_reason')
        ]);

        return response()->json([
            'message' => "Leave request accepted",
            "leave" => $application,
            "error" => false
        ]);

    }

    public function hodReject(Request $request)
    {
        $user = auth('api')->user();
        $application = LeaveApplication::where('id', $request->get('leaveID'))->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }

        /*
         * Reject Request
         */
        $application->update([
            'HOD' => 'rejected'
        ]);

        $application->reason()->updateOrCreate([
            'reliever'  =>  $application->reason->reliever || '..',
            'hod' => $request->get('', 'reliever_reason')
        ]);

        return response()->json([
            'message' => "Leave request accepted",
            "leave" => $application,
            "error" => false
        ]);

    }

    /*
     * HR APPROVE & REJECT
     */

    public function hrApprove(Request $request)
    {

        $application = LeaveApplication::where('id', $request->get('leaveID'))->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }


        $application->update([
            'HR' => 'approved'
        ]);

        $application->reason()->updateOrCreate([
            'reliever'  =>  $application->reason->reliever || '..',
            'hr' => $request->get('', 'hr')
        ]);
        return response()->json([
            'message' => "Leave request approved",
            "leave" => $application,
            "error" => false
        ]);

    }

    public function hrReject(Request $request)
    {

        $application = LeaveApplication::where('id', $request->get('leaveID'))->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }


        $application->update([
            'HR' => 'rejected'
        ]);

        $application->reason()->updateOrCreate([
            'reliever'  =>  $application->reason->reliever || '..',
            'hr' => $request->get('', 'hr')
        ]);
        return response()->json([
            'message' => "Leave request rejected",
            "leave" => $application,
            "error" => false
        ]);

    }

    public function mdApprove(Request $request)
    {

        $application = LeaveApplication::where('id', $request->get('leaveID'))->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }


        $application->update([
            'MD' => 'approved'
        ]);

        $application->reason()->updateOrCreate([
            'reliever'  =>  $application->reason->reliever || '..',
            'md' => $request->get('', 'md')
        ]);
        return response()->json([
            'message' => "Leave request approved",
            "leave" => $application,
            "error" => false
        ]);

    }

    public function mdReject(Request $request)
    {

        $application = LeaveApplication::where('id', $request->get('leaveID'))->first();

        if ($application == null) {
            return response()->json([
                "message" => "No leave with that ID",
                "error" => true

            ], 404);
        }


        $application->update([
            // 'reliever'  =>  '..',
            'MD' => 'rejected'
        ]);

        $application->reason()->updateOrCreate([
            'reliever'  =>  $application->reason->reliever || '..',
            'md' => $request->get('', 'md')
        ]);
        return response()->json([
            'message' => "Leave request rejected",
            "leave" => $application,
            "error" => false
        ]);

    }

    public function leaveApproval()
    {
        $usersId = auth('api')->user('id');
        $userId = $usersId['id'];
        $user_type = $usersId['user_type'];
        $department = $usersId['department'];
        // echo $department;
        // die();
        if ($user_type == 'EMPLOYEE') {
            $leave = DB::table('leave_application')
                ->join('users', 'users.id', '=', 'leave_application.userId')
                ->where('reliever', '=', $userId)
                ->select('leave_application.*', 'users.name as name')
                ->orderBy('leave_application.created_at')
                ->get();
            return $this->prepareResult(1, $leave, [], "Success");
        } elseif ($user_type == 'HOD') {
            $leave = DB::table('leave_application')
                ->join('users', 'users.id', '=', 'leave_application.userId')
                ->where('users.department', '=', $department)
                ->select('leave_application.*', 'users.name as name')
                ->orderBy('leave_application.created_at')
                ->get();
            return $this->prepareResult(1, $leave, [], "Success");
        } elseif ($user_type == 'HR') {
            $leave = DB::table('leave_application')
                ->join('users', 'users.id', '=', 'leave_application.userId')
                ->select('leave_application.*', 'users.name as name')
                ->orderBy('leave_application.created_at')
                ->get();
            return $this->prepareResult(1, $leave, [], "Success");
        } elseif ($user_type == 'MD') {
            $leave = DB::table('leave_application')
                ->join('users', 'users.id', '=', 'leave_application.userId')
                ->select('leave_application.*', 'users.name as name')
                ->orderBy('leave_application.created_at')
                ->get();
            return $this->prepareResult(1, $leave, [], "Success");
        }


        die();
        $leave = DB::table('leave_application')
            ->join('users', 'users.id', '=', 'leave_application.userId')
            ->where('reliever', '=', Auth::user()->id)
            ->select('leave_application.*', 'users.name as name')
            ->orderBy('leave_application.created_at')
            ->get();
        return $this->prepareResult(1, $leave, [], "Success");
    }

    public function leave_approval(Request $request)
    {
        $usersId = auth('api')->user('id');
        $userId = $usersId['id'];
        $employeeId = $request->employeeId;
        $dateRequested = $request->dateRequested;
        $option = $request->option;

        // user type for logged in user
        $title = User::where('id', $userId)->value('user_type');

        if ($title == 'EMPLOYEE') {
            if ($option == 1) {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    // ->get();
                    ->update(['reliever_approval' => 'accepted']);
                return $u;
            } else {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['reliever_approval' => 'denied']);
                return $u;
            }
        } else if ($title == 'HOD') {
            if ($option == 1) {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['HOD' => 'accepted']);
                return $u;
            } else {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['HOD' => 'denied']);
                return $u;
            }
        } else if ($title == 'HR') {
            if ($option == 1) {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['HR' => 'accepted']);
                return $u;
            } else {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['HR' => 'denied']);
                return $u;
            }
        } else if ($title == 'MD') {
            if ($option == 1) {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['MD' => 'accepted']);
                return $u;
            } else {
                $u = DB::table('leave_application')
                    ->where('userId', $employeeId)
                    ->where('created_at', $dateRequested)
                    ->where('reliever', $userId)
                    ->update(['MD' => 'denied']);
                return $u;
            }
        } else {
            echo "choice not available";
        }


        //  $getRelieverId = DB::table('oauth_access_tokens')
        //               ->where('id', $token)
        //               ->value('id');

        //  $check = DB::table('leave_application')
        //      ->where('reliever', $getRelieverId)
        //      ->where('userId', $employeeId)
        //      ->where('created_at', $dateRequested)
        //      ->value("reliever_approval");

        //  if ($check == 'pending') {
        //     echo "Reliever"
        //  } else {
        //    # code...
        //  }


        // return $u;
        //  return $this->prepareResult(1, $u, [],"Approved Successfully", "False");
    }
//  private function prepareResult($status, $data, $errors, $msg, $err)
//  {
//      if ($errors == null) {
//          return ['status' => $status,'payload'=> $data,'message' => $msg, 'error' => $err];
//      } else {
//          return ['status' => $status, 'message' => $msg,'errors' => $errors, 'error' => 'True'];
//      }
//  }
}
