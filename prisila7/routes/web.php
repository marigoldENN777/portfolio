<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchemaController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| Schema (Tables + Columns)
|--------------------------------------------------------------------------
*/
Route::group([], function () {
// List tables (your list_tables.php equivalent)
    Route::get('/', fn() => redirect('/schema'));

    Route::get('/schema', [SchemaController::class, 'index'])->name('schema.index');
    Route::post('/schema', [SchemaController::class, 'storeTable'])->name('schema.store');

    // Add table UI + submit
    Route::get('/schema/add', [SchemaController::class, 'create'])->name('schema.create');
    Route::post('/schema/create', [SchemaController::class, 'store'])->name('schema.store');

    // Edit table structure (columns)
    // no functionality yet
    Route::get('/schema/{table}/edit', [SchemaController::class, 'editTable'])->name('schema.edit.table');
    // Add data (rows)
    Route::get('/schema/{table}/data/add', [SchemaController::class, 'addTableData'])->name('schema.data.add');
    Route::post('/schema/{table}/data/add', [SchemaController::class, 'storeTableData'])->name('schema.data.store');



    // Drop table
    Route::post('/schema/drop', [SchemaController::class, 'drop'])->name('schema.drop');

    // Remove / add columns
    Route::post('/schema/remove-column', [SchemaController::class, 'removeColumn'])->name('schema.removeColumn');
    Route::get(
        '/schema/{table}/columns/add',
        [SchemaController::class, 'showAddColumns']
    )->name('schema.table.columns.add');
    Route::post('/schema/add-columns', [SchemaController::class, 'addColumns'])->name('schema.addColumns');

});

/*
|--------------------------------------------------------------------------
| Table Data (Rows)
|--------------------------------------------------------------------------
*/

// List tables (your /tables old route)
// Route::get('/tables', [DataController::class, 'listTables'])->name('tables.index');

// Show table data
// Route::get('/table_data/{table}', [DataController::class, 'showTableData'])->name('table.data');

// Add row UI + submit
// Route::get('/table_add/{table}', [DataController::class, 'createRow'])->name('table.row.create');
// Route::post('/table_add/{table}', [DataController::class, 'storeRow'])->name('table.row.store');

// Edit row UI + submit
// Route::get('/table_edit/{table}/{id}', [DataController::class, 'editRow'])->name('table.row.edit');
// Route::post('/table_edit/{table}/{id}', [DataController::class, 'updateRow'])->name('table.row.update');

// Delete single row
// Route::get('/table_delete/{table}/{id}', [DataController::class, 'deleteRow'])->name('table.row.delete');

// Bulk delete rows
// Route::post('/table_delete_bulk/{table}', [DataController::class, 'bulkDelete'])->name('table.row.bulkDelete');


/*
|--------------------------------------------------------------------------
| Table Filter (Enable / Disable tables on frontend)
|--------------------------------------------------------------------------
*/
// Route::get('/tables/filter', [DataController::class, 'showTableFilterForm'])->name('tables.filter');
// Route::post('/tables/filter', [DataController::class, 'updateTableFilter'])->name('tables.filter.update');


/*
|--------------------------------------------------------------------------
| CSV Import
|--------------------------------------------------------------------------
*/
// Route::get('/table_import/{table}', [SchemaController::class, 'showImportForm'])->name('table.import.form');
// Route::post('/table_import/{table}', [SchemaController::class, 'handleImportCsv'])->name('table.import.handle');


/*
|--------------------------------------------------------------------------
| Storefront (optional)
|--------------------------------------------------------------------------
*/
// Route::get('/store', [StoreController::class, 'index'])->name('store.index');
