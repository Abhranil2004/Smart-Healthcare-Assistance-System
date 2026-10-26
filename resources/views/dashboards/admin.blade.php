{{-- MediNexa Administrator & Operations Dashboard --}}

<!-- Welcome Banner -->
<div class="mn-card" style="background:linear-gradient(135deg, #0f172a, #1e293b);color:#ffffff;border:none;margin-top:10px;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <span class="mn-badge mn-badge-rose" style="background:rgba(239,68,68,0.2);color:#fca5a5;">Hospital Administrator</span>
                <span style="font-size:12px;color:#94a3b8;">System Engine: Laravel 12 · Database Safe</span>
            </div>
            <h2 style="font-size:24px;font-weight:800;color:#ffffff;margin:0 0 6px;">
                Hospital Operations & Executive Overview
            </h2>
            <p style="font-size:14px;color:#cbd5e1;margin:0;">
                Real-time clinical metrics, inpatient bed census, departmental staffing, and financial settlements.
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/reports') }}" class="mn-btn mn-btn-teal mn-btn-sm">
                <i class="fa-solid fa-chart-line"></i> Clinical Reports
            </a>
            <a href="{{ url('/users') }}" class="mn-btn mn-btn-primary mn-btn-sm">
                <i class="fa-solid fa-users-gear"></i> User Accounts
            </a>
            <a href="{{ url('/settings') }}" class="mn-btn mn-btn-outline mn-btn-sm" style="border-color:rgba(255,255,255,0.4);color:#ffffff !important;">
                <i class="fa-solid fa-sliders"></i> Settings
            </a>
        </div>
    </div>
</div>

<!-- Headline Operational Counts -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card teal">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ number_format($totalPatients) }}</div>
                <div class="mn-stat-label">Total Patients</div>
            </div>
            <div class="mn-stat-icon-wrap teal"><i class="fa-solid fa-hospital-user"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ number_format($totalAppointments) }}</div>
                <div class="mn-stat-label">Appointments Logged</div>
            </div>
            <div class="mn-stat-icon-wrap"><i class="fa-solid fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card emerald">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ number_format($totalDoctors) }}</div>
                <div class="mn-stat-label">Medical Doctors</div>
            </div>
            <div class="mn-stat-icon-wrap emerald"><i class="fa-solid fa-stethoscope"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-stat-card amber">
            <div class="mn-stat-info">
                <div class="mn-stat-value">{{ number_format($totalStaff) }}</div>
                <div class="mn-stat-label">Hospital Staff</div>
            </div>
            <div class="mn-stat-icon-wrap amber"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>
</div>

<!-- Inpatient Bed Census & Financial Performance -->
<div class="row">
    <div class="col-md-6">
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-bed-pulse" style="color:#0d9488;"></i> Inpatient Ward Bed Census</h3>
                <span class="mn-badge mn-badge-teal">{{ $occupancy }}% Occupied</span>
            </div>
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <div style="font-size:22px;font-weight:800;color:#0f172a;">
                        {{ $occupiedBeds }} <small style="font-size:14px;color:#64748b;font-weight:500;">of {{ $totalBeds }} beds in use</small>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#0d9488;">{{ $freeBeds }} free beds available</div>
                </div>
                <div style="height:10px;background:#e2e8f0;border-radius:5px;overflow:hidden;">
                    <div style="width:{{ $occupancy }}%;height:100%;background:linear-gradient(90deg, #0284c7, #0d9488);"></div>
                </div>
            </div>
            <p style="font-size:12.5px;color:#64748b;margin:0;">
                Calculated dynamically from active admissions where discharge dates are open, preventing discrepancy between the board and patient files.
            </p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-scale-balanced" style="color:#0284c7;"></i> Hospital Financial Summary</h3>
                <a href="{{ url('/accounting') }}" class="mn-btn mn-btn-outline mn-btn-sm">Ledgers</a>
            </div>
            <div class="row" style="text-align:center;">
                <div class="col-xs-4">
                    <div style="font-size:20px;font-weight:800;color:#10b981;">{{ number_format($income, 2) }}</div>
                    <div class="mn-stat-label">Revenue Received</div>
                </div>
                <div class="col-xs-4">
                    <div style="font-size:20px;font-weight:800;color:#ef4444;">{{ number_format($expenses, 2) }}</div>
                    <div class="mn-stat-label">Expenses Paid</div>
                </div>
                <div class="col-xs-4">
                    <div style="font-size:20px;font-weight:800;color:{{ $balance >= 0 ? '#10b981' : '#ef4444' }};">
                        {{ number_format($balance, 2) }}
                    </div>
                    <div class="mn-stat-label">Net Balance</div>
                </div>
            </div>
            <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9;font-size:12px;color:#64748b;">
                * Direct transactions derived from payment settlements and expense ledgers.
            </div>
        </div>
    </div>
</div>

<!-- Clinical Activity Metrics -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="mn-card" style="padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:22px;font-weight:800;color:#0f172a;">{{ number_format($totalPrescriptions) }}</div>
                    <div class="mn-stat-label">Prescriptions</div>
                </div>
                <i class="fa-solid fa-pills" style="font-size:24px;color:#cbd5e1;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-card" style="padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:22px;font-weight:800;color:#0f172a;">{{ number_format($totalLabTests) }}</div>
                    <div class="mn-stat-label">Lab Tests Ordered</div>
                </div>
                <i class="fa-solid fa-flask" style="font-size:24px;color:#cbd5e1;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-card" style="padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:22px;font-weight:800;color:#0f172a;">{{ number_format($totalReports) }}</div>
                    <div class="mn-stat-label">Diagnostic Reports</div>
                </div>
                <i class="fa-solid fa-file-medical" style="font-size:24px;color:#cbd5e1;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="mn-card" style="padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:22px;font-weight:800;color:#0f172a;">{{ number_format($totalMedicines) }}</div>
                    <div class="mn-stat-label">Pharmacy Formulary</div>
                </div>
                <i class="fa-solid fa-box-open" style="font-size:24px;color:#cbd5e1;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Hospital Activity -->
<div class="row">
    <!-- Recent Patients -->
    <div class="col-md-6">
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-user-plus" style="color:#0d9488;"></i> Recent Patients Registered</h3>
                <a href="{{ url('/patients') }}" class="mn-btn mn-btn-outline mn-btn-sm">All Patients</a>
            </div>

            @if($recentPatients->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Age / Sex</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPatients as $p)
                                <tr>
                                    <td>
                                        <a href="{{ url('/patients/' . $p->id) }}" style="font-weight:700;color:#0f172a;text-decoration:none;">
                                            {{ $p->name }}
                                        </a>
                                    </td>
                                    <td>{{ $p->phone ?: 'None on file' }}</td>
                                    <td>
                                        <span class="mn-badge mn-badge-sky">{{ $p->sex ?: 'Unspecified' }}</span>
                                        @if(isset($p->age) && $p->age)
                                            <span style="font-size:12px;color:#64748b;margin-left:4px;">{{ $p->age }} yrs</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-solid fa-users"></i>
                    <h5>No patient records found</h5>
                    <p>Register walk-ins or intake records via Patient Registration.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="col-md-6">
        <div class="mn-card">
            <div class="mn-card-header">
                <h3 class="mn-card-title"><i class="fa-solid fa-calendar-check" style="color:#0284c7;"></i> Recent Consultations</h3>
                <a href="{{ url('/appointments') }}" class="mn-btn mn-btn-outline mn-btn-sm">Full Calendar</a>
            </div>

            @if($recentAppointments->isNotEmpty())
                <div class="mn-table-wrap">
                    <table class="mn-table">
                        <thead>
                            <tr>
                                <th>Patient / Doctor</th>
                                <th>Date / Slot</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $apt)
                                <tr>
                                    <td>
                                        <div style="font-weight:700;color:#0f172a;">Patient #{{ $apt->patient }}</div>
                                        <div style="font-size:12px;color:#64748b;">Doctor #{{ $apt->doctor }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $apt->date ?: 'Scheduled' }}</div>
                                        <div style="font-size:12px;color:#64748b;">{{ $apt->time_slot ?: 'Assigned slot' }}</div>
                                    </td>
                                    <td>
                                        @if(strtolower($apt->status) === 'confirmed')
                                            <span class="mn-badge mn-badge-emerald">Confirmed</span>
                                        @elseif(strtolower($apt->status) === 'pending')
                                            <span class="mn-badge mn-badge-amber">Pending</span>
                                        @else
                                            <span class="mn-badge mn-badge-teal">{{ ucfirst($apt->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mn-empty-state">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <h5>No appointments logged</h5>
                    <p>Appointments booked by clerks or patients will be listed here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
