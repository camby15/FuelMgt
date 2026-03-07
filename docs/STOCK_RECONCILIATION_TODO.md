# Station Stock Reconciliation – Implementation Todo List

Use this checklist to build the Stock Reconciliation feature end-to-end. Order: **Migration → Model → Controller → Routes → Connect to Blade**. Use multi-guard auth (same pattern as `StockController` / `DispatchController`). Place **Model** in `App\Models\FuelManagement\StockManagement` and **Controller** in `App\Http\Controllers\FuelManagement\StockManagement`.

**Access:** Only **Station Managers** may access this section. Apply role/middleware so that non–station-manager users cannot reach reconciliation routes or see the sidebar link.

---

## Understanding the page (`stockRecon.blade.php`)

- **Purpose:** Compare physical dipping with system balances; capture daily reconciliation entries per tank per station.
- **Sections:**
  1. **Header** – Title, subtitle, and **station name** (e.g. "Your Station" or selected station).
  2. **Summary cards** – Opening Stock, Sales, Variance (values in L; can be derived from reconciliation data or stock balances).
  3. **Daily Reconciliation Entry form** – One row per tank per day: date, tank, closing stock, opening stock, add stock, total stock (Opening + Add), sales, dipping (mm), variance, notes. Submit saves a reconciliation record.
  4. **Stock Reconciliation Ledger** – Table of saved entries with filters (search, tank, date), Export CSV, Export PDF modals.
- **Form fields (to persist):** `recon_date`, `tank`, `closing_stock`, `opening_stock`, `add_stock`, `total_stock`, `sales_volume`, `dipping_reading`, `variance`, `notes`.
- **Tanks:** Blade uses fixed options (e.g. "PMS Tank 1", "AGO Tank 1"). Store as string; optionally later add a `tanks` table per station.

---

## 1. Migration

- [ ] **1.1** Create migration: `database/migrations/YYYY_MM_DD_HHMMSS_create_stock_reconciliations_table.php`
- [ ] **1.2** Table `stock_reconciliations` with columns:
  - `id` (bigIncrements)
  - `company_id` (foreignId → company_profiles, cascade)
  - `station_id` (foreignId → fuel_stations, cascade)
  - `recon_date` (date)
  - `tank` (string, 100) — e.g. "PMS Tank 1", "AGO Tank 2"
  - `closing_stock` (decimal 12,2) — previous day closing (L)
  - `opening_stock` (decimal 12,2) — opening (L)
  - `add_stock` (decimal 12,2) — added stock (L)
  - `total_stock` (decimal 12,2) — opening + add (L)
  - `sales_volume` (decimal 12,2) — sales (L)
  - `dipping_reading` (decimal 10,2) — dipping in mm
  - `variance` (decimal 12,2) — variance (L)
  - `notes` (text, nullable)
  - `created_by`, `updated_by` (foreignId → users, set null), nullable
  - `timestamps`, `softDeletes`
- [ ] **1.3** Indexes: `(company_id, station_id)`, `(company_id, recon_date)`, `(station_id, recon_date, tank)` for filtering.
- [ ] **1.4** Run `php artisan migrate` and confirm table exists.

---

## 2. Model

- [ ] **2.1** Create `app/Models/FuelManagement/StockManagement/StockReconciliation.php`.
- [ ] **2.2** Use `HasFactory`, `SoftDeletes`; set `$fillable` and `$casts` for all fields above (dates, decimals).
- [ ] **2.3** Relationships: `company()` → CompanyProfile, `station()` → FuelStation, `creator()`, `updater()` → User.
- [ ] **2.4** Scopes: `scopeForCompany($query, $companyId)`, `scopeForStation($query, $stationId)`, `scopeForDateRange($query, $start, $end)`, `scopeForTank($query, $tank)`, `scopeOrderByDate($query, $direction)`.
- [ ] **2.5** Optional: accessor for `variance_display` or use cast; ensure decimals formatted in blade.

---

## 3. Controller

- [ ] **3.1** Create `app/Http/Controllers/FuelManagement/StockManagement/ReconciliationController.php` (or `StockReconciliationController` — keep naming consistent with route).
- [ ] **3.2** Use same auth pattern as `StockController` / `DispatchController`:
  - `isAuthenticated()`, `resolveCompanyId()`, `getAuthenticatedUserId()`.
- [ ] **3.3** **index**: Require auth + company. Accept optional `station_id` from request. Load reconciliations with `station`, filtered by company (and station_id if provided), ordered by `recon_date` desc. Load stations list for company (for station selector if you add one, or to resolve station name). Compute or pass **summary** values for the selected station/context (e.g. latest opening, sum of sales for period, latest variance) for the summary cards. Pass `reconciliations`, `stations`, `stationId`, `stationName`, `companyId`, and any summary vars to view `company.FuelManagement.stockRecon`.
- [ ] **3.4** **store**: Validate: `recon_date` (required, date), `tank` (required, string, max 100), `closing_stock` (required, numeric, min 0), `opening_stock` (required, numeric, min 0), `add_stock` (required, numeric, min 0), `total_stock` (required, numeric, min 0), `sales_volume` (required, numeric, min 0), `dipping_reading` (required, numeric, min 0), `variance` (required, numeric), `notes` (nullable, string), `station_id` (required, exists in fuel_stations, belongs to company). Create `StockReconciliation` with `company_id` from `resolveCompanyId()`, `created_by` from `getAuthenticatedUserId()`. Redirect to index (with same `station_id` if applicable) and success message.
- [ ] **3.5** **destroy** (optional): Soft delete; company scoped; redirect to index.
- [ ] **3.6** Use DB::transaction for store; log errors and redirect back with input and error message on exception.

---

## 4. Routes

- [ ] **4.0** Restrict reconciliation routes to **Station Manager** role only (middleware or route group that allows station managers; deny others with 403 or redirect). Ensure sidebar link is hidden for non–station-manager users.
- [ ] **4.1** In `routes/web.php`, inside the same `company` + `fuel-management` prefix group, add:
  - `Route::resource('reconciliations', ReconciliationController::class)->except(['create', 'edit']);`
  - Or name resource `stock-reconciliations` and controller `StockReconciliationController` if you prefer; ensure route names are consistent (e.g. `company.fuel.reconciliations.index`, `company.fuel.reconciliations.store`).
- [ ] **4.2** Confirm route names and that they do not conflict with existing routes.

---

## 5. Connect to Blade

- [ ] **5.1** **Sidebar**: In `resources/views/layouts/shared/left-sidebar.blade.php`, change the “Stock Reconciliation” link from `route('any', 'company/FuelManagement/stockRecon')` to `route('company.fuel.reconciliations.index')` (or the actual route name you chose).
- [ ] **5.2** **RoutingController**: In `app/Http/Controllers/RoutingController.php`, add a redirect for the catch-all when `$first === 'company' && $second === 'FuelManagement' && $third === 'stockRecon'` to `redirect()->route('company.fuel.reconciliations.index')`.
- [ ] **5.3** **User profile menu**: Update `data-route` for “Stock Reconciliation” to use the new index route.
- [ ] **5.4** **stockRecon.blade.php**:
  - Set form `action="{{ route('company.fuel.reconciliations.store') }}"` and keep `method="POST"`, `@csrf`.
  - Form field names to match controller: `recon_date`, `tank`, `closing_stock`, `opening_stock`, `add_stock`, `total_stock`, `sales_volume`, `dipping_reading`, `variance`, `notes`, `station_id`. Add a hidden or select for `station_id` if the page is station-scoped (or use current station from controller).
  - **Station selector:** If you support multiple stations, add a dropdown (or use query param) and pass `$stations` and `$stationId`; set `$stationName` from selected station or “All stations” / “Your Station”.
  - Replace the seed ledger row with a `@forelse($reconciliations ?? [] as $index => $recon)` loop: one row per record (date, tank, opening, add, total, sales, closing, dipping, variance, notes). Remove or replace the `data-empty-state` row with an `@empty` block: “No reconciliation records captured yet.”
  - Summary cards: Bind `data-role="summary-opening-value"`, `summary-sales-value`, `summary-variance-value` to values passed from controller (e.g. from latest recon or computed aggregates).
- [ ] **5.5** **Tank dropdown:** Keep options in blade (or pass `$tankOptions` from controller). Use same options in form and in ledger filters / CSV/PDF modals.
- [ ] **5.6** **JS**: Do not prevent form submit; let it POST to store and redirect so the list is server-rendered. Keep client-side logic for: total stock calculation (opening + add), ledger filters (search, tank, date), modal open/close, CSV/PDF export (build from table or request filtered data from a new endpoint if needed). Fix any ID typo (e.g. `addedStockInput` vs `addStock`) so Total Stock calculation works.
- [ ] **5.7** **Export CSV/PDF:** Either generate client-side from visible table rows, or add controller methods (e.g. `exportCsv`, `exportPdf`) that accept tank/date filters and return file download; then wire modals to those endpoints.
- [ ] **5.8** Use `@push('javascript')` (not `@push('scripts')`) if the layout only stacks `javascript`, so all scripts run.

---

## 6. Optional / Polish

- [ ] **6.1** Session flash for success/error using SweetAlert (same as Dispatch Stock / Stock Received) for uniformity.
- [ ] **6.2** Optional: Unique constraint on `(company_id, station_id, recon_date, tank)` to prevent duplicate entry per tank per day; handle validation error in UI.
- [ ] **6.3** Optional: “Opening Stock” / “Closing Stock” prefill from previous day’s reconciliation for the same tank (e.g. via JS or an API that returns last recon for tank).

---

## Reference

- **Auth pattern:** `StockController` / `DispatchController` in `App\Http\Controllers\FuelManagement\StockManagement`.
- **View:** `resources/views/company/FuelManagement/stockRecon.blade.php`.
- **Similar flow:** Stock (Receiving), Dispatch Stock — same folder structure and multi-guard auth.

Say when to start and we can implement step by step (Migration → Model → Controller → Routes → Blade).
