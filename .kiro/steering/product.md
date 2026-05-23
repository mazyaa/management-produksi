# Product Overview

**Mitsuba** is an internal production monitoring web application for PT Mitsuba Indonesia, Press-3 Department. It digitizes the daily production reporting and verification workflow.

## Core Domain

- **Produksi Harian** — operators log daily production records per shift, machine, and part, capturing good quantity, NG (non-good/defect) quantity, and notes.
- **NG Tracking** — each production record can have multiple NG detail entries linked to categorized defect types (`KategoriNg`) with severity levels (low, medium, high, critical).
- **Verification Workflow** — records follow a status lifecycle: `draft → submitted → verified / rejected → revised → submitted`. Leaders and admins verify or reject submitted records.
- **Laporan (Reports)** — date-range filtered production reports with totals, accessible to leaders, assistant managers, and admins.
- **Master Data** — admin-managed reference data: shifts, machines (mesin), parts, and NG categories.

## User Roles

| Role | Key Permissions |
|---|---|
| `operator` | Create/edit/delete own draft records, submit for verification |
| `leader` | View all records, verify or reject submitted records, view reports |
| `assistant_manager` | View all records and reports (read-heavy) |
| `admin` | Full access including master data and user management |

## Language

The UI and domain terminology are in **Bahasa Indonesia**. Variable names, database columns, and route names follow Indonesian domain language (e.g., `produksi`, `mesin`, `shift`, `catatan`, `tanggal_produksi`).
