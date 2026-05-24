<x-app-layout>
    @section('title', 'Dashboard Monitoring')
    @section('page-title', 'Dashboard Monitoring Produksi')

    <!-- Dashboard stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 items-center justify-center">
        <!-- Good Qty -->
        <x-stat-card
            title="Produk OK Hari Ini"
            value="{{ number_format($totalGoodToday) }} pcs"
            color="success"
            subtitle="Produk harian terkumpul"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- NG Qty -->
        <x-stat-card
            title="Produk NG Hari Ini"
            value="{{ number_format($totalNgToday) }} pcs"
            color="danger"
            subtitle="Produk cacat (NG) terkumpul"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- Submitted (pending verification) -->
        <x-stat-card
            title="Menunggu Verifikasi"
            value="{{ $todaySubmitted }}"
            color="warning"
            subtitle="{{ $todaySubmitted > 0 ? 'Segera diverifikasi Leader' : 'Tidak ada antrian' }}"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- Verified Today -->
        <x-stat-card
            title="Terverifikasi"
            value="{{ $todayVerified }}"
            color="success"
            subtitle="{{ $todayVerified > 0 ? 'Data sudah diverifikasi' : 'Belum ada verifikasi' }}"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>
        </x-stat-card>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Produksi per Shift -->
        <x-card title="Statistik Produksi per Shift (Hari Ini)">
            <div class="h-64 relative">
                <canvas id="shiftChart"></canvas>
            </div>
        </x-card>

        <!-- Produksi per Mesin -->
        <x-card title="Produk OK per Mesin (Hari Ini)">
            <div class="h-64 relative">
                <canvas id="mesinChart"></canvas>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- NG by Categories chart -->
        <div class="lg:col-span-2">
            <x-card title="Kategori Non-Good (NG)">
                @if(count($ngCategoryLabels) > 0)
                    <div class="h-64 relative flex items-center justify-center">
                        <canvas id="ngChart"></canvas>
                    </div>
                @else
                    <x-empty-state
                        title="Tidak Ada Data NG"
                        message="Selamat! Tidak ada produk cacat (NG) yang tercatat hari ini."
                    />
                @endif
            </x-card>
        </div>

        <!-- Verification Distribution -->
        <div class="lg:col-span-1">
            <x-card title="Distribusi Status (Hari Ini)">
                <div class="h-64 relative flex items-center justify-center">
                    <canvas id="verificationChart"></canvas>
                </div>
            </x-card>
        </div>

        <!-- Recent Activities -->
        <div class="lg:col-span-3">
            <x-card title="Aktivitas Produksi Terakhir">
                @if($recentActivities->count() > 0)
                    <div class="divide-y divide-slate-100 overflow-hidden">
                        @foreach($recentActivities as $activity)
                            <div class="py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors px-1">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800">{{ $activity->tanggal_produksi->format('d M Y') }}</span>
                                        <span class="text-xs font-bold text-slate-400">•</span>
                                        <span class="text-xs font-semibold text-slate-550">{{ $activity->shift->nama_shift }}</span>
                                        <span class="text-xs font-bold text-slate-400">•</span>
                                        <span class="text-xs font-bold text-primary-600">{{ $activity->mesin->kode_mesin }}</span>
                                    </div>
                                    <span class="block text-xs font-medium text-slate-500">
                                        Part: {{ $activity->part->nomor_part }} ({{ $activity->part->nama_part }})
                                    </span>
                                    <span class="block text-[10px] font-semibold text-slate-400">
                                        Operator: {{ $activity->operator->nama }}
                                    </span>
                                </div>

                                <div class="text-right flex items-center gap-3">
                                    <div class="space-y-0.5">
                                        <span class="block text-xs font-bold text-slate-800 leading-none">{{ number_format($activity->good_qty) }} <span class="text-[10px] text-slate-450 font-semibold">OK</span></span>
                                        @if($activity->total_ng_qty > 0)
                                            <span class="block text-[10px] font-bold text-red-500 leading-none">{{ number_format($activity->total_ng_qty) }} NG</span>
                                        @endif
                                    </div>
                                    <x-badge :type="$activity->status->value" />

                                    <a href="{{ route('produksis.index') }}" class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-transparent hover:border-slate-200/50 hover:bg-white hover:shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state
                        title="Belum Ada Aktivitas"
                        message="Belum ada data pencatatan produksi yang dimasukkan."
                    />
                @endif
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Shift Chart (Bar Chart)
            const ctxShift = document.getElementById('shiftChart').getContext('2d');
            new Chart(ctxShift, {
                type: 'bar',
                data: {
                    labels: @json($shiftLabels),
                    datasets: [
                        {
                            label: 'Produk OK (Good)',
                            data: @json($shiftGoodData),
                            backgroundColor: '#10b981',
                            borderRadius: 8,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Produk NG',
                            data: @json($shiftNgData),
                            backgroundColor: '#ef4444',
                            borderRadius: 8,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Inter', weight: '600', size: 11 }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { family: 'Inter', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 10, weight: '650' } }
                        }
                    }
                }
            });

            // 2. Mesin Chart (Bar Chart)
            const ctxMesin = document.getElementById('mesinChart').getContext('2d');
            new Chart(ctxMesin, {
                type: 'bar',
                data: {
                    labels: @json($mesinLabels),
                    datasets: [{
                        label: 'Good Qty',
                        data: @json($mesinGoodData),
                        backgroundColor: '#4f46e5',
                        borderRadius: 8,
                        barPercentage: 0.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { family: 'Inter', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 10, weight: '650' } }
                        }
                    }
                }
            });

            // 3. Verification Status Chart (Doughnut Chart)
            const ctxVerif = document.getElementById('verificationChart');
            if (ctxVerif) {
                new Chart(ctxVerif.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Verified', 'Submitted', 'Rejected', 'Draft'],
                        datasets: [{
                            data: [{{ $todayVerified }}, {{ $todaySubmitted }}, {{ $todayRejected }}, {{ $todayDraft }}],
                            backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#94a3b8'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: { family: 'Inter', weight: '600', size: 10 }
                                }
                            }
                        }
                    }
                });
            }

            // 4. NG Chart (Doughnut Chart)
            const ctxNg = document.getElementById('ngChart');
            if (ctxNg) {
                new Chart(ctxNg.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($ngCategoryLabels),
                        datasets: [{
                            data: @json($ngCategoryData),
                            backgroundColor: [
                                '#ef4444', '#f97316', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: { family: 'Inter', weight: '600', size: 10 }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
