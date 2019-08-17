<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

require __DIR__ . '/auth/auth.php';
require __DIR__ . '/auth/passwordReset.php';

Route::post('/applyLeave', 'LeaveController@applyLeave')->middleware('auth:api');
Route::get('/department_employees', 'LeaveController@employees_departments')->middleware('auth:api');
Route::get('/employees', 'LeaveController@employees');
Route::get('/leaveHistory/{id}', 'LeaveController@leaveHistory');
// Route::get('/calculateLeave/{id}', 'LeaveController@calculate_days');
Route::get('/send/email', 'LeaveController@mail');

// Leave Approval
Route::post('/leaveApproval', 'LeaveController@leave_approval')->middleware('auth:api');
Route::get('/leave-approval', 'HomeController@leaveApproval')->middleware('auth:api');

Route::post('/reliever-approve', 'LeaveController@relieverApprove')->middleware('auth:api');
Route::post('/reliever-reject', 'LeaveController@relieverReject')->middleware('auth:api');

// visitors
Route::post('/visitor', 'VisitorController@new_visitor');
Route::post('/visitorList', 'VisitorController@get_visitor');

//hod
Route::post('/hod-approve', 'LeaveController@hodApprove')->middleware('auth:api');
Route::post('/hod-reject', 'LeaveController@hodReject')->middleware('auth:api');

Route::post('/hr-approve', 'LeaveController@hrApprove')->middleware('auth:api');
Route::post('/hr-reject', 'LeaveController@hrReject')->middleware('auth:api');

Route::post('/md-approve', 'LeaveController@mdApprove')->middleware('auth:api');
Route::post('/md-reject', 'LeaveController@mdReject')->middleware('auth:api');



