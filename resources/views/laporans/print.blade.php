<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Produksi Harian - PT Mitsuba Indonesia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }
        .header { text-align: center; padding: 16px 0 12px; border-bottom: 2px solid #1e293b; margin-bottom: 16px; }
        .header h1 { font-size: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .header h2 { font-size: 12px; font-weight: 600; margin-top: 2px; color: #475569; }
        .header p { font-size: 10px; color: #64748b; margin-top: 4px; }
        .meta { display: flex; gap: 32px; margin-bottom: 14px; padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; }
        .meta-item { }
        .meta-item .label { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px; }
        .meta-item .value { font-size: 11px; font-weight: 700; color: #1e293b; margin-top: 1px; }
        .summary { display: flex; gap: 16px; margin-bottom: 14px; }
        .summary-card { flex: 1; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 4px; border-left: 3px solid #4f46e5; }
        .summary-card.green { border-left-color: #10b981; }
        .summary-card.red { border-left-color: #ef4444; }
        .summary-card.amber { border-left-color: #f97316; }
        .summary-card .s-label { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #94a3b8; }
        .summary-card .s-value { font-size: 16px; font-weight: 800; color: #1e293b; margin-top: 2px; }
        .summary-card .s-sub { font-size: 9px; color: #64748b; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #f1f5f9; }
        th { padding: 7px 8px; text-align: left; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; border-bottom: 1px solid #cbd5e1; }
        th.right, td.right { text-align: right; }
        td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; font-size: 10px; color: #334155; }
        tr:nth-child(even) td { background: #f8fafc; }
        tfoot td { font-weight: 800; background: #f1f5f9; border-top: 2px solid #cbd5e1; font-size: 10px; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 20px; font-size: 9px; font-weight: 700; }
        .badge-verified { background: #d1fae5; color: #065f46; }
        .badge-submitted { background: #dbeafe; color: #1e40af; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-draft { background: #f1f5f9; color: #475569; }
        .badge-revised { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; }
        .sign-box { text-align: center; width: 160px; }
        .sign-box .sign-label { font-size: 10px; font-weight: 600; color: #475569; }
        .sign-box .sign-line { margin-top: 48px; border-top: 1px solid #1e293b; padding-top: 4px; font-size: 10px; font-weight: 700; }
        .print-info { font-size: 9px; color: #94a3b8; text-align: center; margin-top: 12px; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Print Button (hidden on print) -->
    <div class="no-print" style="padding: 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; gap: 8px; align-items: center;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;">
            Cetak
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div style="padding: 20px 24px;">
        <!-- Header -->
        <div class="header">
            <h1>PT Mitsuba Indonesia — Press-3 Department</h1>
            <h2>Laporan Produksi Harian</h2>
            <p>Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</p>
        </div>

        <!-- Meta Info -->
        <div class="meta">
            <div class="meta-item">
                <div class="label">Periode</div>
                <div class="value">{{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}</div>
            </div>
            <div class="meta-item">
                <div class="label">Total Records</div>
                <div class="value">{{ $allRecords->count() }} data</div>
            </div>
            <div class="meta-item">
                <div class="label">Dicetak Oleh</div>
                <div class="value">{{ auth()->user()->nama }}</div>
            </div>
            <div class="meta-item">
                <div class="label">Tanggal Cetak</div>
                <div class="value">{{ now()->format('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-card">
                <div class="s-label">Total Target</div>
                <div class="s-value">{{ number_format($totalTarget) }}</div>
                <div class="s-sub">pcs</div>
            </div>
            <div class="summary-card green">
                <div class="s-label">Total Good (OK)</div>
                <div class="s-value">{{ number_format($totalGood) }}</div>
                <div class="s-sub">Achievement: {{ $totalTarget > 0 ? round(($totalGood / $totalTarget) * 100, 1) : 0 }}%</div>
            </div>
            <div class="summary-card red">
                <div class="s-label">Total NG</div>
                <div class="s-value">{{ number_format($totalNg) }}</div>
                <div class="s-sub">Defect rate: {{ ($totalGood + $totalNg) > 0 ? round(($totalNg / ($totalGood + $totalNg)) * 100, 1) : 0 }}%</div>
            </div>
            <div class="summary-card amber">
                <div class="s-label">Achievement Rate</div>
                <div class="s-value">{{ $totalTarget > 0 ? round(($totalGood / $totalTarget) * 100, 1) : 0 }}%</div>
                <div class="s-sub">dari total target</div>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Mesin</th>
                    <th>Part</th>
                    <th>Operator</th>
                    <th class="right">Target</th>
                    <th class="right">Good</th>
                    <th class="right">NG</th>
                    <th class="right">Ach%</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allRecords as $i => $produksi)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $produksi->tanggal_produksi->format('d/m/Y') }}</td>
                        <td>{{ $produksi->shift->nama_shift }}</td>
                        <td>{{ $produksi->mesin->kode_mesin }}</td>
                        <td>{{ $produksi->part->nomor_part }}</td>
                        <td>{{ $produksi->operator->nama }}</td>
                        <td class="right">{{ number_format($produksi->target_qty) }}</td>
                        <td class="right">{{ number_format($produksi->good_qty) }}</td>
                        <td class="right">{{ number_format($produksi->total_ng_qty) }}</td>
                        <td class="right">{{ $produksi->achievement_rate }}%</td>
                        <td>
                            <span class="badge badge-{{ $produksi->status->value }}">{{ $produksi->status->label() }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">TOTAL</td>
                    <td class="right">{{ number_format($totalTarget) }}</td>
                    <td class="right">{{ number_format($totalGood) }}</td>
                    <td class="right">{{ number_format($totalNg) }}</td>
                    <td class="right">{{ $totalTarget > 0 ? round(($totalGood / $totalTarget) * 100, 1) : 0 }}%</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Signature -->
        <div class="footer">
            <div class="sign-box">
                <div class="sign-label">Dibuat Oleh,</div>
                <div class="sign-line">{{ auth()->user()->nama }}</div>
            </div>
            <div class="sign-box">
                <div class="sign-label">Diketahui Oleh,</div>
                <div class="sign-line">Leader / Supervisor</div>
            </div>
            <div class="sign-box">
                <div class="sign-label">Disetujui Oleh,</div>
                <div class="sign-line">Assistant Manager</div>
            </div>
        </div>

        <div class="print-info">
            Dokumen ini dicetak secara otomatis oleh Sistem Informasi Produksi PT Mitsuba Indonesia Press-3 pada {{ now()->format('d M Y H:i') }} WIB
        </div>
    </div>
</body>
</html>
