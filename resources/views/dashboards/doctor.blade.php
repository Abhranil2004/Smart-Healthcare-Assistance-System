{{-- MediNexa Doctor Clinical Portal Dashboard --}}

<!-- Welcome Banner -->
<div class="mn-card" style="background:linear-gradient(135deg, #0f172a, #0d9488);color:#ffffff;border:none;margin-top:10px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <span class="mn-badge mn-badge-teal" style="background:rgba(255,255,255,0.2);color:#ffffff;">Doctor Clinical Portal</span>
                <span style="font-size:12px;color:#ccfbf1;">Department: {{ $doctor->department ?? 'Consultant Physician' }}</span>
            </div>
            <h2 style="font-size:24px;font-weight:800;color:#ffffff;margin:0 0 6px;">
                Good day, {{ $doctor->name ?? Auth::user()->firstname . ' ' . Auth::user()->lastname }}
            </h2>
            <p style="font-size:14px;color:#f0fdfa;margin:0;">
                Clinical queue overview, active patient consultations, electronic medical records, and digital prescribing.
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/prescriptions') }}" class="mn-btn mn-btn-primary mn-btn-sm" style="background:#0284c7;">
                <i class="fa-solid fa-file-prescription"></i> Create Prescription
            </a>
            <a href="{{ url('/lab') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="border-color:rgba(255,255,255,0.4);color:#ffffff !important;">
                <i class="fa-solid fa-flask-vial"></i> Order Lab Test
            </a>
            <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="border-color:rgba(255,255,255,0.4);color:#ffffff !important;">
                <i class="fa-solid fa-calendar"></i> Day View
            </a>
        </div>
    </div>
</div>

<!-- Doctor Metrics Grid -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card teal">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $doctorAppointments->count() }}</div>
                <div class="mn-stat-label">Assigned Appointments</div>
            </div>
            <div class="mn-stat-icon-wrap teal"><i class="fa-solid fa-stethoscope"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card amber">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $doctorAppointments->where('status', 'pending')->count() }}</div>
                <div class="mn-stat-label">Waiting in Queue</div>
            </div>
            <div class="mn-stat-icon-wrap amber"><i class="fa-solid fa-user-clock"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card emerald">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $totalPrescriptions }}</div>
                <div class="mn-stat-label">Prescriptions Issued</div>
            </div>
            <div class="mn-stat-icon-wrap emerald"><i class="fa-solid fa-pills"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $totalLabTests }}</div>
                <div class="mn-stat-label">Diagnostic Orders</div>
            </div>
            <div class="mn-stat-icon-wrap"><i class="fa-solid fa-flask"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Patient Consultation Queue -->
    <div class="col-md-8">
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title">
                    <i class="fa-solid fa-users-line" style="color:#0d9488;"></i> Consultation Queue & Patient Appointments
                </h3>
                <span class="mn-badge mn-badge-teal">Live Roster</span>
            </div>

            @if($doctorAppointments->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Slot / Time</th>
                                <th>Patient Name</th>
                                <th>Pre-Consultation / Symptoms</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctorAppointments as $apt)
                                <tr>
                                    <td>
                                        <strong>{{ $apt->date ?: 'Scheduled' }}</strong>
                                        <div style="font-size:12px;color:#64748b;">{{ $apt->time_slot ?: 'Assigned Slot' }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:700;color:#0f172a;">Patient #{{ $apt->patient }}</div>
                                    </td>
                                    <td>
                                        <span style="font-size:13px;color:#475569;">
                                            {{ Str::limit($apt->symptom ?? 'Standard outpatient clinical review', 40) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(strtolower($apt->status) === 'confirmed')
                                            <span class="mn-badge mn-badge-emerald">Confirmed</span>
                                        @elseif(strtolower($apt->status) === 'pending')
                                            <span class="mn-badge mn-badge-amber">Awaiting</span>
                                        @elseif(strtolower($apt->status) === 'completed')
                                            <span class="mn-badge mn-badge-sky">Completed</span>
                                        @else
                                            <span class="mn-badge mn-badge-teal">{{ ucfirst($apt->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;">
                                            <a href="{{ url('/prescriptions') }}" class="mn-btn mn-btn-teal mn-btn-sm" title="Prescribe">
                                                <i class="fa-solid fa-prescription"></i> Rx
                                            </a>
                                            <a href="{{ url('/history') }}" class="mn-btn mn-btn-outline mn-btn-sm" title="Medical History">
                                                <i class="fa-solid fa-notes-medical"></i> History
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <h5>No patients currently queued</h5>
                    <p>When reception books consultations for your clinic, they appear directly in this queue.</p>
                </div>
            @endif
        </div>

        <!-- Recent Prescriptions by Clinic -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-file-medical" style="color:#0284c7;"></i> Prescriptions & Diagnostics Quick Links</h3>
                <a href="{{ url('/prescriptions') }}" class="mn-btn mn-btn-outline mn-btn-sm">Prescription Register</a>
            </div>
            <p style="font-size:13.5px;color:#64748b;margin-bottom:16px;">
                Medications are stored as distinct child table rows (`prescription_medicine`) with snapshot drug names, preventing discrepancies with pharmacy stock.
            </p>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="{{ url('/prescriptions') }}" class="mn-btn mn-btn-teal mn-btn-sm">
                    <i class="fa-solid fa-list-check"></i> View Prescriptions
                </a>
                <a href="{{ url('/lab') }}" class="mn-btn mn-btn-outline mn-btn-sm">
                    <i class="fa-solid fa-vial-circle-check"></i> Review Diagnostic Reports
                </a>
                <a href="{{ url('/history') }}" class="mn-btn mn-btn-outline mn-btn-sm">
                    <i class="fa-solid fa-folder-open"></i> Patient Allergy & Chronic History
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Pre-Consultation AI Triage Lookup & Bed Status -->
    <div class="col-md-4">
        <!-- AI Symptom Triage Lookup for Doctors -->
        <div class="mn-card" style="border-top:4px solid #0284c7;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:16px;">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div>
                    <h4 style="font-size:16px;font-weight:700;color:#0f172a;margin:0;">Clinical Triage Assistant</h4>
                    <span style="font-size:11px;color:#0284c7;font-weight:600;text-transform:uppercase;">Pre-Consultation Aide</span>
                </div>
            </div>

            <p style="font-size:13px;color:#64748b;margin-bottom:12px;">
                Input patient reported symptoms to review urgency flags and clinical department classification.
            </p>

            <form id="docAssistantForm">
                <textarea 
                    id="docSymptomsInput" 
                    class="mn-assistant-textarea" 
                    placeholder="Enter patient reported symptoms..." 
                    style="min-height:70px;margin-bottom:10px;font-size:13px;" 
                    required></textarea>
                
                <button type="submit" id="btnDocAnalyze" class="mn-btn mn-btn-primary mn-btn-sm" style="width:100%;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Evaluate Clinical Triage
                </button>
            </form>

            <div id="docResultBox" style="display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-top:14px;font-size:13px;">
                <div id="docUrgencyTag" style="margin-bottom:6px;"></div>
                <div id="docSpecialtyTag" style="font-weight:700;color:#0f172a;margin-bottom:4px;"></div>
                <div id="docRationaleText" style="color:#475569;font-size:12px;line-height:1.4;"></div>
                <div style="font-size:10.5px;color:#94a3b8;margin-top:8px;border-top:1px solid #e2e8f0;padding-top:6px;">
                    * AI guidance is probabilistic. Clinical judgment by physician remains authoritative.
                </div>
            </div>
        </div>

        <!-- Inpatient Bed Census -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h4 class="mn-card-title"><i class="fa-solid fa-bed-pulse" style="color:#10b981;"></i> Ward Bed Census</h4>
                <a href="{{ url('/beds') }}" class="mn-btn mn-btn-outline mn-btn-sm">Ward Board</a>
            </div>
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
                    <span style="color:#64748b;">Current Inpatient Occupancy</span>
                    <strong>{{ $occupancy }}% ({{ $occupiedBeds }} of {{ $totalBeds }})</strong>
                </div>
                <div style="height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                    <div style="width:{{ $occupancy }}%;height:100%;background:#0d9488;"></div>
                </div>
            </div>
            <div style="font-size:12.5px;color:#475569;display:flex;justify-content:space-between;">
                <span><i class="fa-solid fa-circle" style="color:#10b981;font-size:9px;"></i> {{ $freeBeds }} Free Beds</span>
                <span><i class="fa-solid fa-circle" style="color:#ef4444;font-size:9px;"></i> {{ $occupiedBeds }} Occupied</span>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('docAssistantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('docSymptomsInput').value.trim();
    const btn = document.getElementById('btnDocAnalyze');
    const box = document.getElementById('docResultBox');
    if (!input) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Evaluating...';

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
        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Evaluate Clinical Triage';
        if (d.success) {
            box.style.display = 'block';
            document.getElementById('docUrgencyTag').innerHTML = `<span class="mn-badge ${d.urgency_level === 'Emergency' ? 'mn-badge-rose' : (d.urgency_level === 'Urgent' ? 'mn-badge-amber' : 'mn-badge-emerald')}">Urgency: ${d.urgency_level}</span>`;
            document.getElementById('docSpecialtyTag').innerText = 'Specialty Class: ' + d.recommended_specialty;
            document.getElementById('docRationaleText').innerText = d.clinical_rationale;
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Evaluate Clinical Triage';
    });
});
</script>
