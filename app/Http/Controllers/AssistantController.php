<?php

namespace App\Http\Controllers;

use App\Services\HealthcareAssistantService;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssistantController extends BaseController
{
    protected HealthcareAssistantService $assistantService;

    public function __construct(HealthcareAssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
    }

    /**
     * Display the MediNexa Healthcare Assistant standalone or embedded interface.
     */
    public function index()
    {
        $departments = Department::select('name', 'description')->get();
        $doctors = Doctor::select('id', 'name', 'department', 'profile')->get();

        return view('assistant.index', compact('departments', 'doctors'));
    }

    /**
     * Handle symptom analysis query via POST request.
     */
    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symptoms' => 'required|string|min:3|max:1000',
            'duration' => 'nullable|string|max:100',
        ]);

        $result = $this->assistantService->analyze(
            $validated['symptoms'],
            $validated['duration'] ?? null
        );

        return response()->json($result);
    }
}
