<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MediNexaRebrandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic department and doctor for realistic testing
        Department::create([
            'name' => 'Cardiology',
            'description' => 'Heart and circulation.',
            'x' => '',
            'y' => '',
        ]);

        Doctor::create([
            'name' => 'Dr. Sarah Jenkins',
            'department' => 'Cardiology',
            'profile' => 'Consultant Cardiologist',
            'phone' => '03001234567',
            'email' => 'sarah@example.com',
            'address' => 'Hospital Suite 4B',
            'x' => '',
            'y' => '',
            'ion_user_id' => 2,
            'procedure_id' => 1,
        ]);
    }

    public function test_public_homepage_renders_with_smart_healthcare_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Smart Healthcare');
        $response->assertSee('Assistance System');
        $response->assertSee('Smarter Healthcare');
        $response->assertSee('Starts Here.');
        $response->assertSee('Healthcare Assistant');
    }

    public function test_dedicated_login_screen_renders_smart_healthcare_interface(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Smart Healthcare');
        $response->assertSee('Assistance System');
        $response->assertSee('Sign in to your account');
        $response->assertSee('Demonstration Credentials');
    }

    public function test_ai_healthcare_assistant_analyze_endpoint_returns_triage_guidance(): void
    {
        $response = $this->postJson('/assistant/analyze', [
            'symptoms' => 'I have crushing chest pain radiating to my left arm',
            'duration' => 'Started today',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_emergency' => true,
                'urgency_level' => 'Emergency',
                'recommended_specialty' => 'Cardiology',
            ]);

        $this->assertNotNull($response->json('emergency_warning'));
        $this->assertNotEmpty($response->json('recommended_doctors'));
    }

    public function test_ai_healthcare_assistant_handles_routine_symptoms(): void
    {
        $response = $this->postJson('/assistant/analyze', [
            'symptoms' => 'I have a mild runny nose and sneezing',
            'duration' => '2-3 days',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_emergency' => false,
                'urgency_level' => 'Routine',
            ]);
    }

    public function test_role_based_dashboard_views_accessible_to_authenticated_admin(): void
    {
        $admin = User::create([
            'firstname' => 'Hospital',
            'lastname' => 'Admin',
            'login' => 'admin_test',
            'email' => 'admin_test@example.com',
            'group' => 'Admin',
            'desc' => 'Admin test',
            'password' => Hash::make('secret'),
        ]);

        // 1. Executive Admin View
        $this->actingAs($admin)
            ->get('/dashboard?view=admin')
            ->assertStatus(200)
            ->assertSee('Hospital Operations');

        // 2. Doctor Clinical View Preview
        $this->actingAs($admin)
            ->get('/dashboard?view=doctor')
            ->assertStatus(200)
            ->assertSee('Doctor Clinical Portal');

        // 3. Receptionist View Preview
        $this->actingAs($admin)
            ->get('/dashboard?view=receptionist')
            ->assertStatus(200)
            ->assertSee('Front-Desk Reception');

        // 4. Patient View Preview
        $this->actingAs($admin)
            ->get('/dashboard?view=patient')
            ->assertStatus(200)
            ->assertSee('Patient Healthcare Portal');
    }

    public function test_doctor_login_renders_doctor_dashboard(): void
    {
        $doctorUser = User::create([
            'firstname' => 'Dr. Robert',
            'lastname' => 'Smith',
            'login' => 'dr_robert',
            'email' => 'robert@example.com',
            'group' => 'Doctor',
            'desc' => 'Doctor test',
            'password' => Hash::make('secret'),
        ]);

        $this->actingAs($doctorUser)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Doctor Clinical Portal');
    }

    public function test_patient_login_renders_patient_dashboard(): void
    {
        $patientUser = User::create([
            'firstname' => 'Alice',
            'lastname' => 'Wonderland',
            'login' => 'alice_pat',
            'email' => 'alice@example.com',
            'group' => 'Patient',
            'desc' => 'Patient test',
            'password' => Hash::make('secret'),
        ]);

        $this->actingAs($patientUser)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Patient Healthcare Portal');
    }

    public function test_assistant_analyze_requires_valid_input(): void
    {
        $response = $this->postJson('/assistant/analyze', [
            'symptoms' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['symptoms']);
    }
}
