# Project Structure

## Directory Layout

```
app/
├── Enums/                  # PHP 8.1+ backed enums (Role, StatusProduksi, Severity)
├── Http/
│   ├── Controllers/        # Standard resource controllers + Auth controllers
│   ├── Middleware/         # RoleMiddleware for role-based route protection
│   └── Requests/           # Form Request classes for validation (Store* / Update*)
├── Models/                 # Eloquent models
├── Policies/               # Laravel Policies for model-level authorization
├── Providers/              # AppServiceProvider
└── View/Components/        # Class-based Blade components (AppLayout, GuestLayout)

resources/
├── css/app.css             # Tailwind entry point + custom component classes
├── js/app.js               # Alpine.js bootstrap
└── views/
    ├── layouts/            # app.blade.php (authenticated), guest.blade.php
    ├── components/         # Anonymous Blade components (reusable UI)
    ├── auth/               # Breeze auth views
    ├── dashboard.blade.php
    ├── produksis/          # CRUD + verify/reject views for production records
    ├── laporans/           # Report index + print views
    ├── shifts/             # Master data CRUD
    ├── mesins/             # Master data CRUD
    ├── parts/              # Master data CRUD
    ├── kategori-ngs/       # Master data CRUD
    └── users/              # User management views

database/
├── migrations/             # Chronological schema migrations
├── factories/              # Model factories for testing/seeding
└── seeders/                # Database seeders

routes/
├── web.php                 # Main web routes
└── auth.php                # Breeze auth routes
```

## Key Architectural Patterns

### Authorization
- **Middleware** (`RoleMiddleware`) guards routes by role string: `->middleware('role:admin,leader')`
- **Policies** (`ProduksiPolicy`, `UserPolicy`) handle model-level gates; called via `$this->authorize()` in controllers
- Role checks on models use helper methods: `$user->isAdmin()`, `$user->isOperator()`, etc.

### Controllers
- Follow standard Laravel resource conventions (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)
- Non-CRUD actions added as extra methods (`submit`, `verify`, `reject`) with explicit route definitions
- Always eager-load relationships to prevent N+1 queries
- Wrap multi-step writes in `DB::beginTransaction()` / `DB::commit()` / `DB::rollBack()`

### Models
- All models declare `$fillable` explicitly — no `$guarded = []`
- Enums are cast via `$casts` using backed enum classes
- Scope methods prefixed with `scope` (e.g., `scopeActive`, `scopeByStatus`)
- Status helper methods on `Produksi`: `isDraft()`, `isSubmitted()`, `isVerified()`, etc.

### Blade Views
- Authenticated views extend `layouts/app.blade.php` using the `$slot` component pattern
- Use `@yield('title')` and `@yield('page-title')` for page titles
- Use `@stack('scripts')` / `@push('scripts')` for page-specific JS
- Reusable UI is built from anonymous components in `resources/views/components/`:
  - `<x-badge>`, `<x-stat-card>`, `<x-card>`, `<x-page-header>`
  - `<x-form-input>`, `<x-form-select>`, `<x-form-textarea>`
  - `<x-data-table>`, `<x-filter-section>`, `<x-empty-state>`
  - `<x-modal>`, `<x-confirm-delete>`
  - `<x-primary-button>`, `<x-secondary-button>`, `<x-danger-button>`

### Flash Notifications
Pass a `swal_msg` session value from any controller redirect:
```php
return redirect()->route('produksis.index')->with('swal_msg', [
    'title' => 'Berhasil!',
    'text'  => 'Pesan sukses.',
    'icon'  => 'success', // success | error | warning | info
]);
```

### Route Naming
- Resource routes: `produksis.*`, `laporans.*`, `users.*`
- Master data routes are namespaced: `master.shifts.*`, `master.mesins.*`, `master.parts.*`, `master.kategori-ngs.*`
