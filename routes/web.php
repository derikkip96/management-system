<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('bootstrap', 'Bootsrap\BootstrapController@index');

Route::get('/', function () {
    return view('auth/login');
});

// Route::get('/', function () {
//     return view('sidebar');
// });

Route::get('/sidebar', 'HomeController@sidebar');
Route::get('/sidebar', 'HomeController@navbar');

Route::get('/logout', 'Auth\LoginController@logout');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/add-member', 'HomeController@add_member')->name('add-member');
Route::post('/add-employee', 'HomeController@add_employee')->name('add-employee');
Route::get('/manage-members', 'HomeController@manage_members')->name('manage-members');
Route::post('/update_status/{id}','HomeController@updateStatus')->name('update-status');
Route::post('/edit-member{id}', 'HomeController@edit_member')->name('edit-member');
Route::delete('/delete-member/{id}', 'HomeController@delete_member');

Route::get('/member-edit/{id}', 'HomeController@member_edit');
Route::get('/communication', 'HomeController@communication')->name('communication');
Route::get('/apply-leave', 'HomeController@apply_leave')->name('apply-leave');
Route::post('/applied-leave', 'HomeController@applied_leave');
Route::get('/leave-history', 'HomeController@leave_history')->name('leave-history');
Route::get('/visitors', 'HomeController@visitors')->name('visitors');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/account', 'HomeController@account')->name('account');

Route::get('/employees', 'HomeController@employees')->name('employees');
Route::post('/applyLeave', 'LeaveController@applyLeave')->middleware('auth');
Route::get('/department_employees', 'LeaveController@employees_departments')->middleware('auth');
Route::get('/employees', 'LeaveController@employees');
Route::get('/leaveHistory/{id}', 'LeaveController@leaveHistory');
// Route::get('/calculateLeave/{id}', 'LeaveController@calculate_days');
Route::get('/send/email', 'LeaveController@mail');

// Leave Approval
Route::post('/leaveApproval', 'HomeController@leaveApproval')->middleware('auth');
Route::get('/leave-approval', 'HomeController@leave_approval')->middleware('auth');

// visitors
Route::post('/visitor', 'VisitorController@new_visitor');
Route::get('/visitorList', 'VisitorController@get_visitor');
