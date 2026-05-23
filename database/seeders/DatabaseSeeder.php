<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Models\Mesin;
use App\Models\Part;
use App\Models\KategoriNg;
use App\Models\Produksi;
use App\Models\DetailNgProduksi;
use App\Models\VerifikasiProduksi;
use App\Enums\Role;
use App\Enums\StatusProduksi;
use App\Enums\Severity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@mitsuba.co.id',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
            'is_active' => true,
        ]);

        $operators = [];
        for ($i = 1; $i <= 3; $i++) {
            $operators[] = User::create([
                'nama' => 'Operator Press ' . $i,
                'username' => 'operator' . $i,
                'email' => 'operator' . $i . '@mitsuba.co.id',
                'password' => Hash::make('password'),
                'role' => Role::OPERATOR,
                'is_active' => true,
            ]);
        }

        $leader = User::create([
            'nama' => 'Leader Press-3',
            'username' => 'leader',
            'email' => 'leader@mitsuba.co.id',
            'password' => Hash::make('password'),
            'role' => Role::LEADER,
            'is_active' => true,
        ]);

        $asstmanager = User::create([
            'nama' => 'Asst. Manager Produksi',
            'username' => 'asstmanager',
            'email' => 'asstmanager@mitsuba.co.id',
            'password' => Hash::make('password'),
            'role' => Role::ASSISTANT_MANAGER,
            'is_active' => true,
        ]);

        // 2. Seed Shifts
        $shift1 = Shift::create([
            'nama_shift' => 'Shift 1',
            'jam_masuk' => '06:00:00',
            'jam_selesai' => '14:00:00',
        ]);
        $shift2 = Shift::create([
            'nama_shift' => 'Shift 2',
            'jam_masuk' => '14:00:00',
            'jam_selesai' => '22:00:00',
        ]);
        $shift3 = Shift::create([
            'nama_shift' => 'Shift 3',
            'jam_masuk' => '22:00:00',
            'jam_selesai' => '06:00:00',
        ]);

        // 3. Seed Mesins
        $mesins = [];
        $mesins[] = Mesin::create(['kode_mesin' => 'PR3-001', 'nama_mesin' => 'Press Machine 60T A', 'line' => 'Line 3A', 'is_active' => true]);
        $mesins[] = Mesin::create(['kode_mesin' => 'PR3-002', 'nama_mesin' => 'Press Machine 80T B', 'line' => 'Line 3A', 'is_active' => true]);
        $mesins[] = Mesin::create(['kode_mesin' => 'PR3-003', 'nama_mesin' => 'Press Machine 110T C', 'line' => 'Line 3B', 'is_active' => true]);
        $mesins[] = Mesin::create(['kode_mesin' => 'PR3-004', 'nama_mesin' => 'Press Machine 150T D', 'line' => 'Line 3B', 'is_active' => true]);
        $mesins[] = Mesin::create(['kode_mesin' => 'PR3-005', 'nama_mesin' => 'Press Machine 200T E', 'line' => 'Line 3C', 'is_active' => false]); // inactive for testing

        // 4. Seed Parts
        $parts = [];
        $parts[] = Part::create(['nomor_part' => 'MIT-P3-1001', 'nama_part' => 'Arm Bracket Comp', 'kategori' => 'Press Parts']);
        $parts[] = Part::create(['nomor_part' => 'MIT-P3-1002', 'nama_part' => 'Plate Lock Starter', 'kategori' => 'Starter Parts']);
        $parts[] = Part::create(['nomor_part' => 'MIT-P3-1003', 'nama_part' => 'Lever Washer Plate', 'kategori' => 'Wiper Parts']);
        $parts[] = Part::create(['nomor_part' => 'MIT-P3-1004', 'nama_part' => 'Flange Yoke Starter', 'kategori' => 'Starter Parts']);
        $parts[] = Part::create(['nomor_part' => 'MIT-P3-1005', 'nama_part' => 'Base Plate Wiper Motor', 'kategori' => 'Wiper Parts']);

        // 5. Seed Kategori NG
        $ngs = [];
        $ngs[] = KategoriNg::create(['kode_ng' => 'NG-001', 'nama_ng' => 'Burry', 'severity' => Severity::LOW]);
        $ngs[] = KategoriNg::create(['kode_ng' => 'NG-002', 'nama_ng' => 'Crack', 'severity' => Severity::CRITICAL]);
        $ngs[] = KategoriNg::create(['kode_ng' => 'NG-003', 'nama_ng' => 'Dent / Penyok', 'severity' => Severity::HIGH]);
        $ngs[] = KategoriNg::create(['kode_ng' => 'NG-004', 'nama_ng' => 'Scratch / Gores', 'severity' => Severity::LOW]);
        $ngs[] = KategoriNg::create(['kode_ng' => 'NG-005', 'nama_ng' => 'Dimension Out / Ukuran NG', 'severity' => Severity::MEDIUM]);
        $ngs[] = KategoriNg::create(['kode_ng' => 'NG-006', 'nama_ng' => 'Material Deformed', 'severity' => Severity::HIGH]);

        // 6. Seed Produksi & Detail NG & Verifikasi
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Rekord 1: Draft oleh Operator 1 (Kemarin)
        $p1 = Produksi::create([
            'tanggal_produksi' => $yesterday,
            'shift_id' => $shift1->id,
            'mesin_id' => $mesins[0]->id,
            'part_id' => $parts[0]->id,
            'operator_id' => $operators[0]->id,
            'target_qty' => 500,
            'good_qty' => 450,
            'total_ng_qty' => 15,
            'status' => StatusProduksi::DRAFT,
            'catatan' => 'Produksi shift pagi terhambat material telat masuk 30 menit.',
        ]);
        DetailNgProduksi::create(['produksi_id' => $p1->id, 'kategori_ng_id' => $ngs[0]->id, 'qty' => 10, 'catatan' => 'Burry tipis di pinggir bracket']);
        DetailNgProduksi::create(['produksi_id' => $p1->id, 'kategori_ng_id' => $ngs[3]->id, 'qty' => 5, 'catatan' => 'Gores halus gesekan antar material']);

        // Rekord 2: Submitted oleh Operator 1 (Kemarin, belum verifikasi)
        $p2 = Produksi::create([
            'tanggal_produksi' => $yesterday,
            'shift_id' => $shift2->id,
            'mesin_id' => $mesins[1]->id,
            'part_id' => $parts[1]->id,
            'operator_id' => $operators[0]->id,
            'target_qty' => 400,
            'good_qty' => 380,
            'total_ng_qty' => 8,
            'status' => StatusProduksi::SUBMITTED,
            'catatan' => 'Produksi lancar.',
        ]);
        DetailNgProduksi::create(['produksi_id' => $p2->id, 'kategori_ng_id' => $ngs[2]->id, 'qty' => 8, 'catatan' => 'Dent penyok saat handling']);

        // Rekord 3: Verified oleh Leader (Kemarin)
        $p3 = Produksi::create([
            'tanggal_produksi' => $yesterday,
            'shift_id' => $shift3->id,
            'mesin_id' => $mesins[2]->id,
            'part_id' => $parts[2]->id,
            'operator_id' => $operators[1]->id,
            'target_qty' => 600,
            'good_qty' => 590,
            'total_ng_qty' => 5,
            'status' => StatusProduksi::VERIFIED,
            'catatan' => 'Lembur 30 menit kejar target target_qty.',
        ]);
        DetailNgProduksi::create(['produksi_id' => $p3->id, 'kategori_ng_id' => $ngs[0]->id, 'qty' => 5, 'catatan' => 'Burry ringan']);
        VerifikasiProduksi::create([
            'produksi_id' => $p3->id,
            'verified_by' => $leader->id,
            'status' => 'verified',
            'catatan' => 'Kerja bagus, target tercapai.',
            'verified_at' => Carbon::now()->subHours(10),
        ]);

        // Rekord 4: Rejected oleh Leader (Kemarin)
        $p4 = Produksi::create([
            'tanggal_produksi' => $yesterday,
            'shift_id' => $shift1->id,
            'mesin_id' => $mesins[3]->id,
            'part_id' => $parts[3]->id,
            'operator_id' => $operators[2]->id,
            'target_qty' => 800,
            'good_qty' => 700,
            'total_ng_qty' => 80,
            'status' => StatusProduksi::REJECTED,
            'catatan' => 'Ada kendala setting cetakan/die.',
        ]);
        DetailNgProduksi::create(['produksi_id' => $p4->id, 'kategori_ng_id' => $ngs[1]->id, 'qty' => 50, 'catatan' => 'Die crack membuat retakan panjang']);
        DetailNgProduksi::create(['produksi_id' => $p4->id, 'kategori_ng_id' => $ngs[4]->id, 'qty' => 30, 'catatan' => 'Ukuran melebar out of specification']);
        VerifikasiProduksi::create([
            'produksi_id' => $p4->id,
            'verified_by' => $leader->id,
            'status' => 'rejected',
            'catatan' => 'Tolong perbaiki data good_qty dan NG, jumlah NG retak 50 pcs sangat kritis, apakah dies sudah di-maintenance?',
            'verified_at' => Carbon::now()->subHours(12),
        ]);

        // Rekord 5: Revised & Submitted kembali oleh Operator 2 (Hari ini)
        $p5 = Produksi::create([
            'tanggal_produksi' => $today,
            'shift_id' => $shift1->id,
            'mesin_id' => $mesins[0]->id,
            'part_id' => $parts[1]->id,
            'operator_id' => $operators[1]->id,
            'target_qty' => 450,
            'good_qty' => 440,
            'total_ng_qty' => 5,
            'status' => StatusProduksi::SUBMITTED,
            'catatan' => 'Revisi data produksi, dies sudah diperbaiki teknisi.',
        ]);
        DetailNgProduksi::create(['produksi_id' => $p5->id, 'kategori_ng_id' => $ngs[0]->id, 'qty' => 5]);

        // Rekord 6: Hari Ini - Verified (Operator 1, Shift 1)
        $p6 = Produksi::create([
            'tanggal_produksi' => $today,
            'shift_id' => $shift1->id,
            'mesin_id' => $mesins[1]->id,
            'part_id' => $parts[2]->id,
            'operator_id' => $operators[0]->id,
            'target_qty' => 500,
            'good_qty' => 495,
            'total_ng_qty' => 2,
            'status' => StatusProduksi::VERIFIED,
            'catatan' => 'Lancar, hasil bagus.',
        ]);
        DetailNgProduksi::create(['produksi_id' => $p6->id, 'kategori_ng_id' => $ngs[3]->id, 'qty' => 2]);
        VerifikasiProduksi::create([
            'produksi_id' => $p6->id,
            'verified_by' => $leader->id,
            'status' => 'verified',
            'catatan' => 'Sempurna.',
            'verified_at' => Carbon::now()->subHours(2),
        ]);

        // Rekord 7: Hari Ini - Draft (Operator 2, Shift 2)
        Produksi::create([
            'tanggal_produksi' => $today,
            'shift_id' => $shift2->id,
            'mesin_id' => $mesins[2]->id,
            'part_id' => $parts[3]->id,
            'operator_id' => $operators[1]->id,
            'target_qty' => 700,
            'good_qty' => 600,
            'total_ng_qty' => 0,
            'status' => StatusProduksi::DRAFT,
            'catatan' => 'Baru mulai berjalan lancar.',
        ]);
    }
}
