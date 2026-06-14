@extends('layouts.app')
@section('title', 'Grafik Kinerja Guru')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Dashboard Analitik Kinerja</h2>
    <p class="text-sm text-slate-500 mt-1">Periode Aktif: {{ $activePeriod->nama ?? 'Belum ada periode aktif' }}</p>
</div>

<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900">Filter Grafik Kinerja</h3>
        </div>
        
        <form action="{{ route('reports.grafik') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            @if(auth()->user()->isAdmin())
            <div class="flex-1 w-full max-w-sm">
                <label for="school_id" class="block text-xs font-medium text-slate-500 mb-1">Pilih Sekolah (Unit Kerja)</label>
                <select name="school_id" id="school_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border bg-white">
                    <option value="">-- Menampilkan Semua Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                            {{ $school->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex-1 w-full max-w-sm">
                <label for="evaluation_period_id" class="block text-xs font-medium text-slate-500 mb-1">Pilih Periode Evaluasi</label>
                <select name="evaluation_period_id" id="evaluation_period_id" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border bg-white">
                    <option value="">-- Periode Aktif --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                            {{ $period->nama }} {{ $period->status == 'aktif' ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm inline-flex items-center">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i> Terapkan
                </button>
                @if($schoolId || request()->filled('evaluation_period_id'))
                    <a href="{{ route('reports.grafik') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors shadow-sm inline-flex items-center">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Status Distribution (Pie Chart) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
            <i data-lucide="pie-chart" class="w-5 h-5 mr-2 text-indigo-500"></i>
            Status Evaluasi Guru
        </h3>
        <div class="flex-1 relative min-h-[300px] w-full flex items-center justify-center">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Trend Chart (Line Chart) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:col-span-2 flex flex-col">
        <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
            <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-emerald-500"></i>
            Tren Rata-rata Kinerja per Periode
        </h3>
        <div class="flex-1 relative min-h-[300px] w-full">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

<!-- Indicator Level Distribution (Stacked Bar Chart) -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
    <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
        <i data-lucide="bar-chart" class="w-5 h-5 mr-2 text-blue-500"></i>
        Sebaran Level Capaian per Indikator (Periode Aktif)
    </h3>
    <div class="relative min-h-[400px] w-full">
        <canvas id="indicatorChart"></canvas>
    </div>
</div>

<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data from Controller
    const statusData = @json($chartData['status']);
    const trendData = @json($chartData['trend']);
    const indicatorData = @json($chartData['indicators']);

    // Common Options
    Chart.defaults.font.family = "'Inter', 'sans-serif'";
    Chart.defaults.color = '#64748b';

    // 1. Status Pie Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: statusData.labels,
            datasets: [{
                data: statusData.data,
                backgroundColor: [
                    '#94a3b8', // Draft (slate)
                    '#f59e0b', // In Progress (amber)
                    '#3b82f6', // Completed (blue)
                    '#10b981'  // Approved (emerald)
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                    }
                }
            },
            cutout: '65%'
        }
    });

    // 2. Trend Line Chart
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    
    // Gradient for line chart
    let gradientTrend = ctxTrend.createLinearGradient(0, 0, 0, 300);
    gradientTrend.addColorStop(0, 'rgba(79, 70, 229, 0.2)');   // indigo-600
    gradientTrend.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [{
                label: 'Rata-rata Skor Kinerja',
                data: trendData.data,
                borderColor: '#4f46e5', // indigo-600
                backgroundColor: gradientTrend,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3 // smooth curve
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false,
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    displayColors: false,
                }
            }
        }
    });

    // 3. Indicator Level Distribution (Stacked Bar Chart)
    const ctxIndicator = document.getElementById('indicatorChart').getContext('2d');
    new Chart(ctxIndicator, {
        type: 'bar',
        data: {
            labels: indicatorData.labels,
            datasets: [
                {
                    label: 'Level 1',
                    data: indicatorData.level1,
                    backgroundColor: '#ef4444', // red-500
                    borderRadius: 4,
                },
                {
                    label: 'Level 2',
                    data: indicatorData.level2,
                    backgroundColor: '#f59e0b', // amber-500
                    borderRadius: 4,
                },
                {
                    label: 'Level 3',
                    data: indicatorData.level3,
                    backgroundColor: '#3b82f6', // blue-500
                    borderRadius: 4,
                },
                {
                    label: 'Level 4',
                    data: indicatorData.level4,
                    backgroundColor: '#10b981', // emerald-500
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false,
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    stacked: true,
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });
});
</script>
@endsection
