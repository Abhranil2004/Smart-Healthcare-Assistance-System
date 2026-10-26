<?php

/**
 * MediNexa Dashboard Controller.
 *
 * Provides operational, clinical, and role-based metrics across
 * Administrator, Doctor, Receptionist, and Patient dashboards.
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Lab;
use App\Models\Payment;
use App\Models\MedicalHistory;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $error   = \Session::get('error');
        $success = \Session::get('success');

        // Current user & role detection
        $user = Auth::user();
        $rawGroup = strtolower(trim($user->group ?? 'admin'));
        
        // Normalize role key
        if (str_contains($rawGroup, 'doctor')) {
            $userRole = 'doctor';
        } elseif (str_contains($rawGroup, 'patient')) {
            $userRole = 'patient';
        } elseif (str_contains($rawGroup, 'reception')) {
            $userRole = 'receptionist';
        } elseif (str_contains($rawGroup, 'nurse')) {
            $userRole = 'nurse';
        } else {
            $userRole = 'admin';
        }

        // View mode: allow admin to switch views via ?view=patient / ?view=doctor / ?view=receptionist
        $requestedView = strtolower(trim($request->get('view', '')));
        if ($userRole === 'admin' && in_array($requestedView, ['admin', 'doctor', 'patient', 'receptionist'])) {
            $activeView = $requestedView;
        } else {
            $activeView = $userRole;
        }

        // ---- headline counts -------------------------------------------------
        $totalPatients   = $this->count('patient');
        $totalDoctors    = $this->count('doctor');
        $totalNurses     = $this->count('nurse');
        $totalStaff      = $totalDoctors + $totalNurses
                         + $this->count('receptionist')
                         + $this->count('pharmacist')
                         + $this->count('laboratorist')
                         + $this->count('accountant');

        // ---- beds ------------------------------------------------------------
        $totalBeds    = $this->count('bed');
        $occupiedBeds = $this->count('alloted_bed');
        $freeBeds     = max($totalBeds - $occupiedBeds, 0);
        $occupancy    = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;

        // ---- appointments ----------------------------------------------------
        $totalAppointments   = $this->count('appointment');
        $pendingAppointments = $this->countWhere('appointment', 'status', 'pending');

        // ---- clinical activity ----------------------------------------------
        $totalPrescriptions = $this->count('prescription');
        $totalLabTests      = $this->count('lab');
        $totalReports       = $this->count('diagnostic_report');
        $totalMedicines     = $this->count('medicine');

        // ---- money -----------------------------------------------------------
        $income   = $this->sum('payment', 'amount_received');
        $expenses = $this->sum('expense', 'amount');
        $balance  = $income - $expenses;

        // ---- recent activity -------------------------------------------------
        $recentPatients     = $this->latest('patient', ['id', 'name', 'phone', 'sex', 'age', 'add_date']);
        $recentAppointments = $this->latest('appointment', ['id', 'patient', 'doctor', 'date', 'time_slot', 'status']);

        // ---- Patient-specific context ----------------------------------------
        $patient = null;
        $patientAppointments = collect();
        $patientPrescriptions = collect();
        $patientLabs = collect();
        $patientInvoices = collect();
        $patientHistory = collect();

        if ($activeView === 'patient' || $userRole === 'patient') {
            if (Schema::hasTable('patient')) {
                $patient = Patient::where('ion_user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();

                // If admin previewing patient view, fallback to first patient for realistic preview
                if (!$patient && $userRole === 'admin') {
                    $patient = Patient::first();
                }

                if ($patient) {
                    if (Schema::hasTable('appointment')) {
                        $patientAppointments = Appointment::where('patient', $patient->id)->orderByDesc('id')->take(6)->get();
                    }
                    if (Schema::hasTable('prescription')) {
                        $patientPrescriptions = Prescription::where('patient', $patient->id)->orderByDesc('id')->take(6)->get();
                    }
                    if (Schema::hasTable('lab')) {
                        $patientLabs = Lab::where('patient', $patient->id)->orderByDesc('id')->take(6)->get();
                    }
                    if (Schema::hasTable('payment')) {
                        $patientInvoices = Payment::where('patient', $patient->id)->orderByDesc('id')->take(6)->get();
                    }
                    if (Schema::hasTable('medical_history')) {
                        $patientHistory = MedicalHistory::where('patient_id', $patient->id)->orderByDesc('id')->take(6)->get();
                    }
                }
            }
        }

        // ---- Doctor-specific context -----------------------------------------
        $doctor = null;
        $doctorAppointments = collect();
        if ($activeView === 'doctor' || $userRole === 'doctor') {
            if (Schema::hasTable('doctor')) {
                $doctor = Doctor::where('ion_user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();

                if (!$doctor && $userRole === 'admin') {
                    $doctor = Doctor::first();
                }

                if (Schema::hasTable('appointment')) {
                    $doctorAppointments = Appointment::when($doctor, function ($q) use ($doctor) {
                        return $q->where('doctor', $doctor->id)->orWhere('doctor', $doctor->name);
                    })->orderByDesc('id')->take(10)->get();
                }
            }
        }

        // ---- Receptionist-specific context -----------------------------------
        $dailyQueue = collect();
        $availableDoctors = collect();
        if ($activeView === 'receptionist' || $userRole === 'receptionist' || $userRole === 'admin') {
            if (Schema::hasTable('appointment')) {
                $dailyQueue = Appointment::orderByDesc('id')->take(10)->get();
            }
            if (Schema::hasTable('doctor')) {
                $availableDoctors = Doctor::select('id', 'name', 'department', 'phone')->take(6)->get();
            }
        }

        return view('dashboard', compact(
            'error', 'success',
            'userRole', 'activeView',
            'totalPatients', 'totalDoctors', 'totalNurses', 'totalStaff',
            'totalBeds', 'occupiedBeds', 'freeBeds', 'occupancy',
            'totalAppointments', 'pendingAppointments',
            'totalPrescriptions', 'totalLabTests', 'totalReports', 'totalMedicines',
            'income', 'expenses', 'balance',
            'recentPatients', 'recentAppointments',
            'patient', 'patientAppointments', 'patientPrescriptions', 'patientLabs', 'patientInvoices', 'patientHistory',
            'doctor', 'doctorAppointments',
            'dailyQueue', 'availableDoctors'
        ));
    }

    // ---- helpers -------------------------------------------------------------

    private function count(string $table): int
    {
        return Schema::hasTable($table) ? (int) DB::table($table)->count() : 0;
    }

    private function countWhere(string $table, string $column, string $value): int
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return 0;
        }

        return (int) DB::table($table)->whereRaw('LOWER(' . $column . ') = ?', [$value])->count();
    }

    private function sum(string $table, string $column): float
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return 0.0;
        }

        return (float) DB::table($table)->sum(DB::raw('CAST(' . $column . ' AS DECIMAL(15,2))'));
    }

    private function latest(string $table, array $columns, int $limit = 5)
    {
        if (! Schema::hasTable($table)) {
            return collect();
        }

        $present = array_values(array_filter($columns, fn ($c) => Schema::hasColumn($table, $c)));
        if (! $present) {
            return collect();
        }

        return DB::table($table)->select($present)->orderByDesc('id')->limit($limit)->get();
    }
}
