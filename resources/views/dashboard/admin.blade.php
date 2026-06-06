@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center">
            <i data-lucide="school" class="h-6 w-6 text-blue-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Sekolah</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_schools'] }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center">
            <i data-lucide="users" class="h-6 w-6 text-indigo-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Guru</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_gurus'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
            <i data-lucide="calendar-clock" class="h-6 w-6 text-emerald-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Periode Aktif</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['active_periods'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="h-12 w-12 rounded-full bg-purple-50 flex items-center justify-center">
            <i data-lucide="file-check-2" class="h-6 w-6 text-purple-600"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500">Total Evaluasi</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_evaluations'] }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="text-lg font-medium text-slate-900">Sekolah Terbaru</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3">NPSN</th>
                    <th class="px-6 py-3">Nama Sekolah</th>
                    <th class="px-6 py-3">Kepala Sekolah</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSchools as $school)
                <tr class="bg-white border-b border-slate-50 hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $school->npsn }}</td>
                    <td class="px-6 py-4">{{ $school->nama }}</td>
                    <td class="px-6 py-4">{{ $school->kepala_sekolah }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $school->status == 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                            {{ ucfirst($school->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
