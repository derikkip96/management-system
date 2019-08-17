<?php

namespace App\Http\Controllers\Bootsrap;

use App\Department;
use App\Type;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class BootstrapController extends Controller
{


    public function index()
    {
        $this
            ->createDepartments();
        $this->createUserTypes();
        $this->createUsers();

        return "success";
    }

    public function createDepartments()
    {
        Department::create([
            'name' => 'IT'
        ]);
        Department::create([
            'name' => 'Finance'
        ]);
        Department::create([
            'name' => 'HR'
        ]);
    }

    public function createUserTypes(){
        Type::create([
            'name'  =>  'Employee',
        ]);
        Type::create([
            'name'  =>  'HOD',
        ]);
        Type::create([
            'name'  =>  'HR',
        ]);
        Type::create([
            'name'  =>  'MD',
        ]);
    }

    public function createUsers()
    {
        User::create([
            'email' => 'emp@ellixar.com',
            'password' => Hash::make('123456'),
            'nat_id' => '31234156657',
            'name' => 'Emp IT',
            'NSSF' => '26345156',
            'KRA_Pin' => '65189078',
            'avatar' => 'https://randomuser.me/api/portraits/women/26.jpg',
            'active' => 1,
            'department_id' => 1,
            'type_id' => 1,
            'NHIF' => '1542134',
        ]);
        User::create([
            'email' => 'hod@ellixar.com',
            'password' => Hash::make('123456'),
            'nat_id' => '312325456657',
            'name' => 'HOD IT',
            'NSSF' => '26345256',
            'KRA_Pin' => '65892078',
            'avatar' => 'https://randomuser.me/api/portraits/women/26.jpg',
            'active' => 1,
            'department_id' => 1,
            'type_id' => 2,
            'NHIF' => '1542234',
        ]);
        User::create([
            'email' => 'emp2@ellixar.com',
            'password' => Hash::make('123456'),
            'nat_id' => '3123456657',
            'name' => 'Emp Finance',
            'NSSF' => '26345356',
            'KRA_Pin' => '65893078',
            'avatar' => 'https://randomuser.me/api/portraits/women/26.jpg',
            'active' => 1,
            'department_id' => 2,
            'type_id' => 1,
            'NHIF' => '1543234',
        ]);
        User::create([
            'email' => 'hod2@ellixar.com',
            'password' => Hash::make('123456'),
            'nat_id' => '3122334546657',
            'name' => 'HOD Finance',
            'NSSF' => '26345456',
            'KRA_Pin' => '65849078',
            'avatar' => 'https://randomuser.me/api/portraits/women/26.jpg',
            'active' => 1,
            'department_id' => 2,
            'type_id' => 2,
            'NHIF' => '1544234',
        ]);
        User::create([
            'email' => 'hr@ellixar.com',
            'password' => Hash::make('123456'),
            'nat_id' => '3121334546657',
            'name' => 'HR',
            'NSSF' => '26345456',
            'KRA_Pin' => '65849078',
            'avatar' => 'https://randomuser.me/api/portraits/women/26.jpg',
            'active' => 1,
            'department_id' => 3,
            'type_id' => 3,
            'NHIF' => '1544234',
        ]);
        User::create([
            'email' => 'md@ellixar.com',
            'password' => Hash::make('123456'),
            'nat_id' => '313234546657',
            'name' => 'MD',
            'NSSF' => '26345456',
            'KRA_Pin' => '65849078',
            'avatar' => 'https://randomuser.me/api/portraits/women/26.jpg',
            'active' => 1,

            'type_id' => 4,
            'NHIF' => '1544234',
        ]);


    }
}
