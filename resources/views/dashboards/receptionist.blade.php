{{-- MediNexa Receptionist & Front-Desk Dashboard --}}

<!-- Welcome Banner -->
<div class="mn-card" style="background:linear-gradient(135deg, #0f172a, #0369a1);color:#ffffff;border:none;margin-top:10px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <span class="mn-badge mn-badge-sky" style="background:rgba(255,255,255,0.2);color:#ffffff;">Front-Desk Roster</span>
                <span style="font-size:12px;color:#e0f2fe;">Clinic Date: {{ date('l, d M Y') }}</span>
            </div>
            <h2 style="font-size:24px;font-weight:800;color:#ffffff;margin:0 0 6px;">
                Front-Desk Reception & Triage
            </h2>
            <p style="font-size:14px;color:#e0f2fe;margin:0;">
                Rapid walk-in patient registration, doctor schedule coordination, bed allocation, and clinic queue management.
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/patients') }}" class="mn-btn mn-btn-teal mn-btn-sm">
                <i class="fa-solid fa-user-plus"></i> Register Patient
            </a>
            <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="border-color:rgba(255,255,255,0.4);color:#ffffff !important;">
                <i class="fa-solid fa-calendar-plus"></i> Book Appointment
            </a>
            <a href="{{ url('/admissions') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="border-color:rgba(255,255,255,0.4);color:#ffffff !important;">
                <i class="fa-solid fa-bed"></i> Bed Admission
            </a>
        </div>
    </div>
</div>

<!-- Reception Metrics Grid -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card teal">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $totalPatients }}</div>
                <div class="mn-stat-label">Registered Patients</div>
            </div>
            <div class="mn-stat-icon-wrap teal"><i class="fa-solid fa-hospital-user"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $totalAppointments }}</div>
                <div class="mn-stat-label">Total Appointments</div>
            </div>
            <div class="mn-stat-icon-wrap"><i class="fa-solid fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card amber">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $pendingAppointments }}</div>
                <div class="mn-stat-label">Pending / Waiting Queue</div>
            </div>
            <div class="mn-stat-icon-wrap amber"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card emerald">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ $freeBeds }}</div>
                <div class="mn-stat-label">Available Ward Beds</div>
            </div>
            <div class="mn-stat-icon-wrap emerald"><i class="fa-solid fa-bed"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Daily Queue & Check-In Management -->
    <div class="col-md-8">
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title">
                    <i class="fa-solid fa-list-check" style="color:#0284c7;"></i> Daily Consultation Queue & Check-In
                </h3>
                <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm">
                    <i class="fa-solid fa-calendar"></i> Appointment Roster
                </a>
            </div>

            @if($dailyQueue->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Date / Slot</th>
                                <th>Patient ID</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyQueue as $apt)
                                <tr>
                                    <td>
                                        <strong>{{ $apt->date ?: 'Today' }}</strong>
                                        <div style="font-size:12px;color:#64748b;">{{ $apt->time_slot ?: 'Assigned Slot' }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:700;color:#0f172a;">Patient #{{ $apt->patient }}</div>
                                    </td>
                                    <td>Doctor #{{ $apt->doctor }}</td>
                                    <td>
                                        @if(strtolower($apt->status) === 'confirmed')
                                            <span class="mn-badge mn-badge-emerald"><i class="fa-solid fa-circle-check"></i> Confirmed</span>
                                        @elseif(strtolower($apt->status) === 'pending')
                                            <span class="mn-badge mn-badge-amber"><i class="fa-solid fa-clock"></i> Waiting</span>
                                        @elseif(strtolower($apt->status) === 'completed')
                                            <span class="mn-badge mn-badge-sky"><i class="fa-solid fa-check-double"></i> Completed</span>
                                        @else
                                            <span class="mn-badge mn-badge-teal">{{ ucfirst($apt->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm">
                                            Update
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-solid fa-calendar-day"></i>
                    <h5>No active appointments in the current queue</h5>
                    <p>Click below to register or schedule a new patient arrival.</p>
                    <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-teal mn-btn-sm">
                        <i class="fa-solid fa-plus"></i> Book Consultation Slot
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Walk-in Patient Flow -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-bolt" style="color:#f59e0b;"></i> Front-Desk Operations & Intake</h3>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;">
                    <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Walk-in Emergency</h4>
                    <p style="font-size:12.5px;color:#64748b;margin-bottom:12px;">Only patient name is mandatory. Age is computed from DOB automatically.</p>
                    <a href="{{ url('/patients') }}" class="mn-btn mn-btn-teal mn-btn-sm" style="width:100%;">
                        <i class="fa-solid fa-user-plus"></i> Quick Register
                    </a>
                </div>
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;">
                    <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Ward Bed Admission</h4>
                    <p style="font-size:12.5px;color:#64748b;margin-bottom:12px;">One patient per bed. Bed census is linked directly to active admissions.</p>
                    <a href="{{ url('/admissions') }}" class="mn-btn mn-btn-primary mn-btn-sm" style="width:100%;">
                        <i class="fa-solid fa-bed"></i> Admit Inpatient
                    </a>
                </div>
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;">
                    <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Global Records Search</h4>
                    <p style="font-size:12.5px;color:#64748b;margin-bottom:12px;">Search patients and doctors across names, phone numbers, and IDs.</p>
                    <a href="{{ url('/search') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="width:100%;">
                        <i class="fa-solid fa-magnifying-glass"></i> Global Search
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Doctor Duty Schedules & Ward Bed Summary -->
    <div class="col-md-4">
        <!-- Available Doctors Roster -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h4 class="mn-card-title"><i class="fa-solid fa-user-doctor" style="color:#0d9488;"></i> Doctors on Roster</h4>
                <a href="{{ url('/doctors') }}" class="mn-btn mn-btn-outline mn-btn-sm">Directory</a>
            </div>

            @if($availableDoctors->isNotEmpty())
                <div style="font-size:13px;">
                    @foreach($availableDoctors as $doc)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                            <div>
                                <div style="font-weight:700;color:#0f172a;">{{ $doc->name }}</div>
                                <div style="font-size:12px;color:#0d9488;font-weight:600;">{{ $doc->department ?: 'General Medicine' }}</div>
                            </div>
                            <span class="mn-badge mn-badge-teal">Active</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="color:#64748b;font-size:13px;padding:10px 0;">
                    No doctors currently listed. Add doctors via Doctor Register.
                </div>
            @endif
        </div>

        <!-- Inpatient Ward Availability -->
        <div class="mn-card">
            <div class="mn-card-header">
                <h4 class="mn-card-title"><i class="fa-solid fa-bed-pulse" style="color:#10b981;"></i> Inpatient Bed Availability</h4>
                <a href="{{ url('/beds') }}" class="mn-btn mn-btn-outline mn-btn-sm">Beds</a>
            </div>
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
                    <span style="color:#64748b;">Current Occupancy</span>
                    <strong>{{ $occupancy }}% ({{ $occupiedBeds }} / {{ $totalBeds }})</strong>
                </div>
                <div style="height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                    <div style="width:{{ $occupancy }}%;height:100%;background:#0d9488;"></div>
                </div>
            </div>
            <div style="background:#f0fdfa;border:1px solid #ccfbf1;border-radius:8px;padding:12px;font-size:13px;color:#0f766e;">
                <i class="fa-solid fa-circle-check"></i> <strong>{{ $freeBeds }} beds</strong> ready for immediate inpatient admission.
            </div>
        </div>
    </div>
</div>
