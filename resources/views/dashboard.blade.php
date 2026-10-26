{{--
  MediNexa — Unified Role-Based Dashboard
  AI-Powered Healthcare & Hospital Management Platform
--}}
@extends('layouts.master')

@section("style")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/medinexa-theme.css') }}">
<style>
  .mn-role-tab-group {
    display: inline-flex;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    padding: 4px;
    margin-bottom: 18px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    gap: 4px;
  }
  .mn-role-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    border-radius: 20px;
    text-decoration: none !important;
    transition: all 0.2s ease;
  }
  .mn-role-tab:hover {
    color: #0f172a;
    background: #f1f5f9;
  }
  .mn-role-tab.active {
    background: #0f172a;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
  }
  .mn-role-tab.active.teal {
    background: #0d9488;
  }
  .mn-role-tab.active.blue {
    background: #0284c7;
  }
  .mn-role-tab.active.amber {
    background: #f59e0b;
  }
</style>
@endsection

@section('content')
<div class="row" style="margin-top:14px;">
  <div class="col-md-12">
    @if($error)<div class="alert alert-danger" style="border-radius:8px;">{{ $error }}</div>@endif
    @if($success)<div class="alert alert-success" style="border-radius:8px;">{{ $success }}</div>@endif

    {{-- Administrator Role View Switcher for Evaluators & Superusers --}}
    @if(isset($userRole) && $userRole === 'admin')
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;margin-bottom:10px;">
        <div class="mn-role-tab-group">
          <a href="{{ url('/dashboard?view=admin') }}" class="mn-role-tab {{ ($activeView ?? 'admin') === 'admin' ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Administrator Overview
          </a>
          <a href="{{ url('/dashboard?view=doctor') }}" class="mn-role-tab teal {{ ($activeView ?? '') === 'doctor' ? 'active' : '' }}">
            <i class="fa-solid fa-user-doctor"></i> Doctor Clinical View
          </a>
          <a href="{{ url('/dashboard?view=receptionist') }}" class="mn-role-tab blue {{ ($activeView ?? '') === 'receptionist' ? 'active' : '' }}">
            <i class="fa-solid fa-bell-concierge"></i> Receptionist View
          </a>
          <a href="{{ url('/dashboard?view=patient') }}" class="mn-role-tab amber {{ ($activeView ?? '') === 'patient' ? 'active' : '' }}">
            <i class="fa-solid fa-hospital-user"></i> Patient Portal View
          </a>
        </div>
        <span class="mn-badge mn-badge-teal" style="margin-bottom:18px;">
          <i class="fa-solid fa-user-shield"></i> Viewing as: {{ ucfirst($activeView ?? 'Admin') }}
        </span>
      </div>
    @endif
  </div>
</div>

{{-- Render Dynamic Role Dashboard --}}
@if(($activeView ?? 'admin') === 'patient')
  @include('dashboards.patient')
@elseif(($activeView ?? 'admin') === 'doctor')
  @include('dashboards.doctor')
@elseif(($activeView ?? 'admin') === 'receptionist')
  @include('dashboards.receptionist')
@else
  @include('dashboards.admin')
@endif

@endsection
