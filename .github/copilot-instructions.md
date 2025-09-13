# AI Agent Instructions for app_karyawan-server

## Project Overview
This is a Laravel-based employee management system with extensive HR functionality including attendance, leaves, documents, and organizational structure management.

## Key Architecture Points

### Authentication System
- Multi-guard authentication system with web and admin guards
- User roles managed through `spatie/laravel-permission` package
- Core auth configuration in `config/auth.php`

### Module Structure
- Controllers organized by feature in `app/Http/Controllers/`
- Key modules include:
  - Absen (Attendance): `AbsenController.php`, `AbsenUserController.php`
  - Cuti (Leave): `CutiController.php`, `CutiUserController.php` 
  - Dokumen (Documents): `DokumenController.php`
  - Organization: `DivisionController.php`, `DepartemenController.php`

### API Integration
- REST API routes in `routes/api.php`
- Additional HRD-specific routes in `routes/api_hrd.php`
- Uses Laravel Sanctum for API authentication

### Dependencies & Extensions
- PWA support through `ladumor/laravel-pwa`
- PDF generation using `barryvdh/laravel-dompdf`
- Excel handling with `maatwebsite/excel`
- ZKTeco integration via `maliklibs/zkteco`
- DataTables integration using `yajra/laravel-datatables-oracle`

## Development Conventions

### Route Organization
- Web routes in `routes/web.php` grouped by feature
- API routes separated into `api.php` and `api_hrd.php`
- Consistent route naming following Laravel conventions

### Authentication & Authorization
- Use multi-guard system for different user types
- Permissions managed through Spatie roles and permissions
- Always use middleware groups for protected routes

### Data Handling
- Use Laravel Excel for spreadsheet operations
- Use Intervention Image for image processing
- Implement DataTables for large data displays

## Common Tasks
1. Adding new employee features:
   - Add controller in `app/Http/Controllers`
   - Define routes in appropriate route file
   - Create views in `resources/views`

2. Modifying user access:
   - Update permissions in roles system
   - Modify auth guards in `config/auth.php`
   - Update middleware assignments in routes

3. Integrating with biometric devices:
   - Use ZKTeco library for device communication
   - Handle in `FingerController.php`