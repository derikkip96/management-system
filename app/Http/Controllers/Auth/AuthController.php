<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Storage;
use Avatar;
use App\Notifications\SignupActivate;
use App\Notifications\SignupActivated;
use App\User;
use Mail;
use DB;

class AuthController extends Controller
{
    /**
     * Create user deactivate and send notification to activate account user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $initialPass = str_random(8);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($initialPass),
            'user_type' => $request->user_type,
            'department' => $request->department,
            'nat_id' => $request->nat_id,
            'NSSF' => $request->NSSF,
            'NHIF' => $request->NHIF,
            'KRA_Pin' => $request->KRA_Pin
            // 'activation_token' => str_random(60)
        ]);

        $user->save();


        $data['name'] = $initialPass;
        $data['to'] = $request->email;
        // $data['message']=$initialPass;
        $data['subject'] = "Password";
        $data['template'] = "email.password";
        $data['sender'] = "test@us.com";
        $data['sendername'] = "Test Name";
        // $this->sendEmail($data);


        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/' . $user->id . '/avatar.png', (string)$avatar);


        Mail::send(['text' => 'email.password'], $data, function ($message) {
            $message->to($request->email, 'Password')->subject
            ('Password');
            $message->from('xyz@gmail.com', 'Test Name');
        });


        return response()->json([
            'message' => __('auth.signup_success')
        ], 201);
    }

    public function sendEmail($data)
    {

        Mail::send($data['template'], $data, function ($message) use ($data) {
            $message->to($data['to'], $data['name'])->subject($data['subject']);
            $message->from($data['sender'], $data['sendername']);
        });
        echo "HTML Email Sent. Check your inbox.";
    }
    /**
     * Confirm your account user (Activate)
     *
     * @param  [type] $token
     * @return [string] message
     * @return [obj] user
     */
    // public function signupActivate($token)
    // {
    //     $user = User::where('activation_token', $token)->first();
    //
    //     if (!$user) {
    //         return response()->json([
    //             'message' => __('auth.token_invalid')
    //         ], 404);
    //     }
    //
    //     $user->active = true;
    //     $user->activation_token = '';
    //     $user->save();
    //
    //     $user->notify(new SignupActivated($user));
    //
    //     return $user;
    // }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        /*
         * Authenticate User
         */
        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
        if (Auth::attempt($credentials)) {
            /*
             * If email and password is correct
             */
            if (Auth::user()->active) {
                /*
                 * Active User
                 */
                $tokenResult = Auth::user()->createToken('Personal Access Token');
                if ($request->remember_me) {
                    /*
                     * Save Token
                     */
                    $tokenResult->token->expires_at = Carbon::now()->addWeeks(1)->save();
                }

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'active' => true,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'user_data' => Auth::user(),
                    'error' => 'false'
                ], 200);

            } else {
                /*
                 * Inactive User
                 */
                return response()->json([
                    'active' => 0,
                    'payload' => [
                        'user' => Auth::user()->user_type
                    ],
                    "message" => "Create new password",
                    "error" => false
                ], 200);
            }
        } else {
            /*
             * In-correct email or password
             */
            return response()->json(["message" => "Email or password is incorrect",
                "error" => true]);
        }

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => __('auth.logout_success')
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public
    function user(Request $request)
    {
        return response()->json($request->user());
    }

    public
    function new_password(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $u = DB::table('users')
            ->where('email', $email)
            ->update([
                'active' => 1,
                'password' => bcrypt($password)
            ]);
        // return $u;
        return $this->prepareResult(1, $u, [], "Password Changed Successfully", "False");
    }

    private
    function prepareResult($status, $data, $errors, $msg, $err)
    {
        if ($errors == null) {
            return ['active' => $status, 'payload' => $data, 'message' => $msg, 'error' => $err];
        } else {
            return ['active' => $status, 'message' => $msg, 'errors' => $errors, 'error' => 'True'];
        }
    }
}
