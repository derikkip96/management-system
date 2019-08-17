<?php

namespace App\Http\Controllers;

use App\Department;
use App\Type;
use Illuminate\Http\Request;
use App\User;
use App\LeaveApplication;
use App\Visitors;
use DB;
use Auth;
use Avatar;
use Illuminate\Support\Facades\Storage;
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function sidebar()
    {
        return view('sidebar');
    }
    public function navbar()
    {
        return view('navbar');
    }

    public function employees()
    {
      $employee = User::all();
      return view('employees', compact('employee'));
    }
    public function add_member()
    {
      $employee = User::all();
      $type = Type::all();
      $department = Department::all();
      return view('member_signup', compact('employee','type','department'));
    }
    public function manage_members()
    {
      $employee = User::all();
      return view('manage_members', compact('employee'));
    }
    public function member_edit($id)
    {
      $employee = User::where('id', $id)->first();
      $type = Type::all();
      $department = Department::all();
      return view('member_edit', compact('employee','type','department'));
    }
    public function edit_member(Request $request, $id)
    {

        User::where('id', $id)->update([
              'name' => $request->name,
              'email' => $request->email,
//              'department' => $request->department,
              'nat_id' => $request->nat_id,
              'NSSF' => $request->NSSF,
              'NHIF' => $request->NHIF,
              'KRA_Pin' => $request->KRA_Pin,
              'type_id' => $request->type
      ]);

      return redirect()->route('manage-members');
    }
    public function delete_member($id){
      $member = User::where('id',$id)->delete();
      return redirect()->back();
    }
    public function communication()
    {
      $employee = User::all();
      return view('communication', compact('employee'));
    }
    public function apply_leave()
    {
      $employee = User::all();
      return view('applyleave', compact('employee'));
    }
    public function leave_history()
    {
    //   $leave = LeaveApplication::all();
        $leave = DB::table('leave_application')
                    ->join('users', 'users.id', '=', 'leave_application.user_id')
                    ->where('users.id', Auth::user()->id)
                    ->select('leave_application.*','users.name as name')
                    ->get();
        return view('leave_history', compact('leave'));
    }
    public function visitors()
    {
      $visitors = DB::table('visitors')
                    ->join('users', 'users.id', '=', 'visitors.employee_id')
                    ->select('visitors.*','users.name as employee')
                    ->get();
      return view('visitors', compact('visitors'));
    }
    public function profile()
    {
      $employee = User::all();
      return view('profile', compact('employee'));
    }
    public function account()
    {
      $employee = User::all();
      return view('account', compact('employee'));
    }
    public function add_employee(Request $request){

      $initialPass = str_random(8);
    // getting department of a relationship
      $department = new Department();
      $department->id = $request->department;
    //getting type data of arelationship
      $type = new Type();
      $type->id = $request->type;
      $user = new User([
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($initialPass),
          'department_id' => $request->department,
          'nat_id' => $request->nat_id,
          'NSSF' => $request->NSSF,
          'NHIF' => $request->NHIF,
          'KRA_Pin' => $request->KRA_Pin,
          'type_id' => $request->type
      ]);
      $department->user()->save($user);
      $user->department()->associate($department);
//      trying to save type
        $type->user()->save($user);
        $user->type()->associate($type);

      $user->save();

      $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
      Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);

//      Mail::send(['text'=>'email.password'], $data, function($message) {
//         $message->to($request->email, 'Password')->subject
//            ('Password');
//         $message->from('xyz@gmail.com','Test Name');
//      });

      return redirect()->back();
    }

    public function updateStatus(Request $request, $id)
    {
        $employee = User::find($id);

        $employee->status = $request->status;

        $employee->save();

        return redirect()->back();

    }
    public function applied_leave(Request $request){

        $id = Auth::user()->id;

        // $nr_work_day = $this->calculate_days($id, $request->startDate, $request->endDate);
        $beginday=date($request->startDate);
        $lastday=date($request->endDate);

        // $nr_work_days = $this->getWorkingDays($beginday,$lastday);
        $begin=strtotime($beginday);
        $end=strtotime($lastday);
        // print_r($startDate);
        if($begin>$end){
          echo "startdate is in the future! <br />";
          return 0;
        }else{
          $no_days=0;
          $weekends=0;
          while($begin<=$end){
            $no_days++; // no of days in the given interval
            $what_day=date("N",$begin);
            if($what_day>5) { // 6 and 7 are weekend days
                  $weekends++;
            };
            $begin+=86400; // +1 day
          };

          $working_days=$no_days-$weekends;
        }
        // $nr_work_days = (int)$nr_work_day;
        if ($working_days > 0) {

            $leave = new LeaveApplication([
              'userId'      => Auth::user()->id,
              'type'        => $request->type,
              'startDate'   => $request->startDate,
              'endDate'     => $request->endDate,
              'reliever'    => $request->reliever,
              'leave_days'  => $working_days

          ]);

          $leave->save();
          return redirect()->back();
        } else {
          echo "Invalid application";
        }

      }

      public function leave_approval(){

        $user_type = Auth::user()->type_id;
        $us = Auth::user()->type()->get();
//        dd($us);
        $department = Auth::user()->department;
        // echo $department;
        // die();
        if ($user_type == 'EMPLOYEE') {
              $leave = DB::table('leave_application')
                    ->join('users', 'users.id', '=', 'leave_application.user_d')
                    ->where('reliever', '=', Auth::user()->id)
                    ->select('leave_application.*','users.name as name')
                    ->orderBy('leave_application.created_at')
                    ->get();
            return view('leave_approval', compact('leave'));
        } elseif ($user_type == 'HOD') {
              $leave = DB::table('leave_application')
                    ->join('users', 'users.id', '=', 'leave_application.user_id')
                    ->where('users.department', '=', $department)
                    ->select('leave_application.*','users.name as name')
                    ->orderBy('leave_application.created_at')
                    ->get();
            return view('leave_approval', compact('leave'));
        }elseif ($user_type == 'HR') {
              $leave = DB::table('leave_application')
                    ->join('users', 'users.id', '=', 'leave_application.user_id')
                    ->select('leave_application.*','users.name as name')
                    ->orderBy('leave_application.created_at')
                    ->get();
            return view('leave_approval', compact('leave'));
        }elseif ($user_type == 'MD') {
              $leave = DB::table('leave_application')
                    ->join('users', 'users.id', '=', 'leave_application.user_id')
                    ->select('leave_application.*','users.name as name')
                    ->orderBy('leave_application.created_at')
                    ->get();
            return view('leave_approval', compact('leave'));
        }


//        die();
            $leave = DB::table('leave_application')
                    ->join('users', 'users.id', '=', 'leave_application.user_id')
                    ->where('reliever', '=', Auth::user()->id)
                    ->select('leave_application.*','users.name as name')
                    ->orderBy('leave_application.created_at')
                    ->get();
            return view('leave_approval', compact('leave'));
      }

      public function leaveApproval(Request $request)
      {

         $userId  = Auth::user()->id;
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
             return redirect()->back();
           } else {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['reliever_approval' => 'denied']);
               return redirect()->back();
           }
         } else if ($title == 'HOD') {
           if ($option == 1) {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['HOD' => 'accepted']);
             return redirect()->back();
           } else {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['HOD' => 'denied']);
               return redirect()->back();
           }
         } else if ($title == 'HR') {
           if ($option == 1) {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['HR' => 'accepted']);
             return redirect()->back();
           } else {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['HR' => 'denied']);
               return redirect()->back();
           }
         } else if ($title == 'MD') {
           if ($option == 1) {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['MD' => 'accepted']);
             return redirect()->back();
           } else {
             $u = DB::table('leave_application')
               ->where('userId', $employeeId)
               ->where('created_at', $dateRequested)
               ->where('reliever', $userId)
               ->update(['MD' => 'denied']);
               return redirect()->back();
           }
         } else {
           echo "choice not available";
         }
      }
}
