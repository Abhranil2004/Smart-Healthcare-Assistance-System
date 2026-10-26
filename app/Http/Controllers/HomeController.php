<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Bed;
use App\Models\Service;
use Illuminate\Support\Facades\Schema;

class HomeController extends BaseController
{
    /**
     * MediNexa Public Homepage.
     * Accessible without authentication.
     */
    public function index(Request $request)
    {
        if ($request->has('login')) {
            return $this->login($request);
        }

        $institute = Institute::first();
        if (!$institute) {
            $institute = new Institute;
            $institute->name = "MediNexa Healthcare";
        }

        $departments = Schema::hasTable('department') ? Department::all() : collect();
        $doctorsCount = Schema::hasTable('doctor') ? Doctor::count() : 0;
        $patientsCount = Schema::hasTable('patient') ? Patient::count() : 0;
        $bedsCount = Schema::hasTable('bed') ? Bed::count() : 0;
        $services = Schema::hasTable('service') ? Service::take(6)->get() : collect();

        return view('welcome', compact('institute', 'departments', 'doctorsCount', 'patientsCount', 'bedsCount', 'services'));
    }

    /**
     * Dedicated MediNexa Login Screen.
     */
    public function login(Request $request)
    {
        $error = \Session::get('error');
        $institute = Institute::first();
        if (!$institute) {
            $institute = new Institute;
            $institute->name = "MediNexa Healthcare";
        }

        return view('login', compact('error', 'institute'));
    }
}
