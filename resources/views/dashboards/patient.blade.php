{{-- MediNexa Patient Portal Dashboard --}}

<!-- Welcome Banner -->
<div class="mn-card" style="background:linear-gradient(135deg, #0f172a, #1e293b);color:#ffffff;border:none;margin-top:10px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <span class="mn-badge mn-badge-teal" style="background:rgba(204,251,241,0.2);color:#2dd4bf;">Patient Healthcare Portal</span>
                <span style="font-size:12px;color:#94a3b8;">ID: {{ $patient->patient_id ?? 'PAT-' . Auth::id() }}</span>
            </div>
            <h2 style="font-size:24px;font-weight:800;color:#ffffff;margin:0 0 6px;">
                Welcome back, {{ $patient->name ?? Auth::user()->firstname . ' ' . Auth::user()->lastname }}
            </h2>
            <p style="font-size:14px;color:#cbd5e1;margin:0;">
                Access your consultation schedule, digital prescriptions, lab reports, and AI symptom assistant.
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-teal mn-btn-sm">
                <i class="fa-solid fa-calendar-plus"></i> Book Consultation
            </a>
            <a href="#patientAssistant" class="mn-btn mn-btn-primary mn-btn-sm">
                <i class="fa-solid fa-sparkles"></i> AI Symptom Check
            </a>
        </div>
    </div>
</div>

<!-- Patient Summary Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card teal">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $patientAppointments->count() }}</div>
                <div class="mn-stat-label">Appointments</div>
            </div>
            <div class="mn-stat-icon-wrap teal"><i class="fa-solid fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $patientPrescriptions->count() }}</div>
                <div class="mn-stat-label">Prescriptions</div>
            </div>
            <div class="mn-stat-icon-wrap"><i class="fa-solid fa-prescription"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card emerald">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $patientLabs->count() }}</div>
                <div class="mn-stat-label">Diagnostic Labs</div>
            </div>
            <div class="mn-stat-icon-wrap emerald"><i class="fa-solid fa-flask"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card amber">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $patientInvoices->count() }}</div>
                <div class="mn-stat-label">Invoices on Record</div>
            </div>
            <div class="mn-stat-icon-wrap amber"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Upcoming Appointments & Clinical Records -->
    <div class="col-md-8">
        <!-- Upcoming Appointments -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-calendar-day" style="color:#0284c7;"></i> Consultation Schedule</h3>
                <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm">View Calendar</a>
            </div>

            @if($patientAppointments->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Consultant Doctor</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patientAppointments as $apt)
                                <tr>
                                    <td>
                                        <strong>{{ $apt->date ?: 'Scheduled Date' }}</strong>
                                        <div style="font-size:12px;color:#64748b;">{{ $apt->time_slot ?: 'Clinic Hours' }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;color:#0f172a;">Doctor #{{ $apt->doctor }}</div>
                                    </td>
                                    <td>
                                        @if(strtolower($apt->status) === 'confirmed')
                                            <span class="mn-badge mn-badge-emerald">Confirmed</span>
                                        @elseif(strtolower($apt->status) === 'pending')
                                            <span class="mn-badge mn-badge-amber">Pending</span>
                                        @elseif(strtolower($apt->status) === 'cancelled')
                                            <span class="mn-badge mn-badge-rose">Cancelled</span>
                                        @else
                                            <span class="mn-badge mn-badge-sky">{{ ucfirst($apt->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm" title="View details">
                                            <i class="fa-regular fa-eye"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <h5>No upcoming appointments scheduled</h5>
                    <p>Schedule a visit with any hospital physician in just a few clicks.</p>
                    <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-teal mn-btn-sm">
                        <i class="fa-solid fa-calendar-plus"></i> Book Consultation
                    </a>
                </div>
            @endif
        </div>

        <!-- Prescriptions & Medicines -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-pills" style="color:#0d9488;"></i> Digital Prescriptions</h3>
                <a href="{{ url('/prescriptions') }}" class="mn-btn mn-btn-outline mn-btn-sm">View All</a>
            </div>

            @if($patientPrescriptions->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Prescribed Notes</th>
                                <th>Doctor</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patientPrescriptions as $rx)
                                <tr>
                                    <td>{{ $rx->date ?: 'Recent' }}</td>
                                    <td>
                                        <div style="font-weight:600;color:#0f172a;">{{ $rx->symptom ?: 'Clinical Rx' }}</div>
                                        <div style="font-size:12px;color:#64748b;">{{ Str::limit($rx->medicine ?? '', 50) }}</div>
                                    </td>
                                    <td>{{ $rx->doctor ? 'Dr. ' . $rx->doctor : 'Consultant' }}</td>
                                    <td>
                                        <a href="{{ url('/prescriptions') }}" class="mn-btn mn-btn-outline mn-btn-sm">
                                            <i class="fa-solid fa-file-medical"></i> View Rx
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-solid fa-prescription-bottle-medical"></i>
                    <h5>No active prescriptions on file</h5>
                    <p>Prescriptions authored by your physicians will appear here with dosage instructions.</p>
                </div>
            @endif
        </div>

        <!-- Laboratory Results -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-flask-vial" style="color:#7c3aed;"></i> Diagnostic Laboratory Reports</h3>
                <a href="{{ url('/lab') }}" class="mn-btn mn-btn-outline mn-btn-sm">All Lab Orders</a>
            </div>

            @if($patientLabs->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Request Date</th>
                                <th>Doctor</th>
                                <th>Test Information</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patientLabs as $lab)
                                <tr>
                                    <td>{{ $lab->date ?: 'Recent' }}</td>
                                    <td>{{ $lab->doctor ? 'Dr. ' . $lab->doctor : 'Attending Physician' }}</td>
                                    <td>{{ Str::limit($lab->report ?? 'Diagnostic Test Request', 45) }}</td>
                                    <td><span class="mn-badge mn-badge-teal">Processed</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-solid fa-flask"></i>
                    <h5>No laboratory tests on file</h5>
                    <p>Diagnostic test requests and lab results will appear here with reference ranges.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: AI Assistant & Medical History Summary -->
    <div class="col-md-4">
        <!-- Interactive AI Assistant Box for Patient -->
        <div class="mn-card" id="patientAssistant" style="border-top:4px solid #0d9488;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#ccfbf1;color:#0d9488;display:flex;align-items:center;justify-content:center;font-size:16px;">
                    <i class="fa-solid fa-sparkles"></i>
                </div>
                <div>
                    <h4 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Healthcare Assistant</h4>
                    <span style="font-size:11px;color:#0d9488;font-weight:600;text-transform:uppercase;">AI Symptom Checker</span>
                </div>
            </div>

            <p style="font-size:13px;color:#64748b;margin-bottom:14px;">
                Feeling unwell? Enter your symptoms to receive instant triage recommendations and specialty guidance.
            </p>

            <form id="patientWidgetForm">
                <textarea 
                    id="patSymptomsInput" 
                    class="mn-assistant-textarea" 
                    placeholder="Describe what symptoms you are experiencing..." 
                    style="min-height:75px;margin-bottom:10px;font-size:13px;" 
                    required></textarea>
                
                <button type="submit" id="btnPatAnalyze" class="mn-btn mn-btn-teal mn-btn-sm" style="width:100%;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Analyze Symptoms
                </button>
            </form>

            <div id="patResultBox" style="display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-top:14px;font-size:13px;">
                <div id="patUrgencyTag" style="margin-bottom:6px;"></div>
                <div id="patSpecialtyTag" style="font-weight:700;color:#0f172a;margin-bottom:6px;"></div>
                <div id="patRationaleText" style="color:#475569;font-size:12px;margin-bottom:8px;"></div>
                <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-primary mn-btn-sm" style="width:100%;font-size:12px;">
                    <i class="fa-regular fa-calendar-plus"></i> Book Consultation
                </a>
                <div style="font-size:10.5px;color:#94a3b8;margin-top:8px;font-style:italic;">
                    Preliminary AI triage. Not a medical diagnosis.
                </div>
            </div>
        </div>

        <!-- Medical History Overview -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h4 class="mn-card-title"><i class="fa-solid fa-heart-pulse" style="color:#e11d48;"></i> Medical Summary</h4>
                <a href="{{ url('/history') }}" class="mn-btn mn-btn-outline mn-btn-sm">Details</a>
            </div>

            @if($patientHistory->isNotEmpty())
                <div style="font-size:13px;">
                    @foreach($patientHistory as $h)
                        <div style="padding:10px 0;border-bottom:1px solid #f1f5f9;">
                            <div style="font-weight:700;color:#0f172a;">{{ $h->title ?? 'Medical Entry' }}</div>
                            <div style="color:#64748b;font-size:12px;margin-top:2px;">{{ Str::limit($h->description ?? '', 70) }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding:10px 0;color:#64748b;font-size:13px;">
                    <p style="margin-bottom:8px;">No allergies, chronic conditions, or surgeries registered yet.</p>
                    <a href="{{ url('/history') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="width:100%;">
                        <i class="fa-solid fa-plus"></i> Add Medical History
                    </a>
                </div>
            @endif
        </div>

        <!-- Billing & Invoices Overview -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h4 class="mn-card-title"><i class="fa-solid fa-receipt" style="color:#0284c7;"></i> Invoices & Billing</h4>
                <a href="{{ url('/invoices') }}" class="mn-btn mn-btn-outline mn-btn-sm">History</a>
            </div>

            @if($patientInvoices->isNotEmpty())
                @foreach($patientInvoices as $inv)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                        <div>
                            <div style="font-weight:600;color:#0f172a;">Invoice #{{ $inv->id }}</div>
                            <div style="font-size:11.5px;color:#64748b;">{{ $inv->date ?: 'Recent' }}</div>
                        </div>
                        <span class="mn-badge mn-badge-emerald">Recorded</span>
                    </div>
                @endforeach
            @else
                <div style="color:#64748b;font-size:13px;padding:8px 0;">
                    No pending hospital billing balances on record.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.getElementById('patientWidgetForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('patSymptomsInput').value.trim();
    const btn = document.getElementById('btnPatAnalyze');
    const box = document.getElementById('patResultBox');
    if (!input) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Analyzing...';

    fetch('{{ url("/assistant/analyze") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ symptoms: input })
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Analyze Symptoms';
        if (d.success) {
            box.style.display = 'block';
            document.getElementById('patUrgencyTag').innerHTML = `<span class="mn-badge ${d.urgency_level === 'Emergency' ? 'mn-badge-rose' : (d.urgency_level === 'Urgent' ? 'mn-badge-amber' : 'mn-badge-emerald')}">Urgency: ${d.urgency_level}</span>`;
            document.getElementById('patSpecialtyTag').innerText = 'Recommended: ' + d.recommended_specialty;
            document.getElementById('patRationaleText').innerText = d.clinical_rationale;
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Analyze Symptoms';
    });
});
</script>
