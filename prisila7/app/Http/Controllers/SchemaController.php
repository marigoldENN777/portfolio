<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class SchemaController extends Controller
{
    //
    public function index()
    {
        // MySQL/MariaDB: list tables in the current database
        $dbName = DB::getDatabaseName();
        $rows = DB::select('SHOW TABLES');

        // The column name returned by SHOW TABLES is dynamic: "Tables_in_<db>"
        $key = 'Tables_in_' . $dbName;

        $tables = array_map(fn($row) => $row->$key, $rows);

        // (optional) sort like CMS lists usually do
        sort($tables);

        return view('schema.index', compact('tables'));
    }

    public function create()
    {
        return view('schema.add');
    }


public function storeTable(Request $request)
{
    $pattern = '/^[a-z0-9_]+$/';

    $tableName  = trim((string) $request->input('table_name', ''));
    $primaryKey = trim((string) $request->input('primary_key', 'id')); // optional

    $errors = [];

    // Validate table name
    if ($tableName === '' || !preg_match($pattern, $tableName)) {
        $errors[] = "Table name '{$tableName}' is invalid. Only lowercase letters, numbers, and underscores are allowed.";
    }

    // Validate primary key (optional)
    if ($primaryKey !== '' && !preg_match($pattern, $primaryKey)) {
        $errors[] = "Primary key '{$primaryKey}' is invalid. Only lowercase letters, numbers, and underscores are allowed.";
    }

    if (!empty($errors)) {
        return back()->with('customErrors', $errors)->withInput();
    }

    // Check if exists
    if (Schema::hasTable($tableName)) {
        return back()->with('success', "Table `{$tableName}` already exists.")->withInput();
    }

    try {
        Schema::create($tableName, function (Blueprint $table) use ($primaryKey) {
            // Default Laravel style primary key
            if ($primaryKey === '' || $primaryKey === 'id') {
                $table->bigIncrements('id');
            } else {
                $table->bigIncrements($primaryKey);
            }

            $table->timestamps(); // created_at + updated_at
        });

    } catch (\Throwable $e) {
        return back()
            ->with('customErrors', ['SQL Error: ' . $e->getMessage()])
            ->withInput();
    }

    return back()->with('success', "✅ Table `{$tableName}` created successfully.");
}



    public function store(Request $request)
    {
        $request->validate([
            'table_name'  => ['required', 'string', 'regex:/^[a-z][a-z0-9_]*$/'],
            'primary_key' => ['nullable', 'string'],
        ]);

        $tableName  = Str::snake($request->table_name);
        $primaryKey = $request->primary_key ?: 'id';

        if (Schema::hasTable($tableName)) {
            return back()
                ->withErrors(['table_name' => 'Table already exists.'])
                ->withInput();
        }

        Schema::create($tableName, function (Blueprint $table) use ($primaryKey) {
            $table->bigIncrements($primaryKey);
            $table->timestamps();
        });

        return redirect()
            ->route('schema.index')
            ->with('success', "Table '{$tableName}' created successfully.");
    }


public function editTable(string $table)
{
    if (!Schema::hasTable($table)) {
        abort(404);
    }

    $columns = Schema::getColumnListing($table);

    // Load rows (latest first if you have id)
    $query = DB::table($table);
    if (in_array('id', $columns, true)) {
        $query->orderByDesc('id');
    }

    $rows = $query->limit(50)->get();

    return view('schema.edit_table', compact('table', 'columns', 'rows'));
}

public function dropTable(string $table)
{
    $pattern = '/^[a-z0-9_]+$/';

    $table = trim($table);

    if ($table === '' || !preg_match($pattern, $table)) {
        return back()->with('customErrors', ["Invalid table name '{$table}'."])->withInput();
    }

    if (!Schema::hasTable($table)) {
        return back()->with('customErrors', ["Table '{$table}' does not exist."])->withInput();
    }

    try {
        Schema::dropIfExists($table);
    } catch (\Throwable $e) {
        return back()->with('customErrors', ['SQL Error: ' . $e->getMessage()])->withInput();
    }

    return back()->with('success', "🗑️ Table `{$table}` dropped successfully.");
}


public function addTableData(string $table)
{
    if (!Schema::hasTable($table)) abort(404);

    $columns = Schema::getColumnListing($table);

    // We usually don't want users manually entering id/created_at
    $editableColumns = array_values(array_filter($columns, fn($c) => !in_array($c, ['id', 'created_at'], true)));

    return view('schema.add_data', compact('table', 'editableColumns'));
}

public function storeTableData(Request $request, string $table)
{
    if (!Schema::hasTable($table)) {
        abort(404);
    }

    // Columns in the table
    $columns = Schema::getColumnListing($table);

    // Do not allow writing these manually
    $editableColumns = array_values(array_filter(
        $columns,
        fn ($c) => !in_array($c, ['id', 'created_at'], true)
    ));

    // Build insert data only from allowed columns
    $data = [];
    foreach ($editableColumns as $col) {
        $val = $request->input("row.$col", null);

        if (is_string($val)) {
            $val = trim($val);
        }

        // store null for empty strings (keeps DB cleaner)
        $data[$col] = ($val === '') ? null : $val;
    }

    try {
        DB::table($table)->insert($data);
    } catch (\Throwable $e) {
        return back()
            ->with('customErrors', ['Insert error: ' . $e->getMessage()])
            ->withInput();
    }

    return redirect()
        ->route('schema.edit.table', ['table' => $table])
        ->with('success', 'Row added successfully.');
}


public function showAddColumns(string $table)
{
    if (!Schema::hasTable($table)) abort(404);

    $existingColumns = Schema::getColumnListing($table);

    return view('schema.add_columns', compact('table', 'existingColumns'));
}

public function addColumns(Request $request)
{
    $table = (string) $request->input('table', '');

    if ($table === '' || !Schema::hasTable($table)) {
        return back()->with('customErrors', ['Table not found.']);
    }

    $pattern = '/^[a-z0-9_]+$/';

    $columns = $request->input('columns', []);
    $columns = is_array($columns) ? $columns : [];

    // Clean + dedupe + validate
    $clean = [];
    $errors = [];

    foreach ($columns as $col) {
        $col = trim((string) $col);
        if ($col === '') continue;

        if (!preg_match($pattern, $col)) {
            $errors[] = "Invalid column name '{$col}'. Only lowercase letters, numbers, and underscores are allowed.";
            continue;
        }

        $clean[$col] = true;
    }

    $clean = array_keys($clean);

    if (!empty($errors)) {
        return back()->with('customErrors', $errors)->withInput();
    }

    if (count($clean) === 0) {
        return back()->with('customErrors', ['Please enter at least one valid column name.'])->withInput();
    }

    $existing = Schema::getColumnListing($table);
    $toAdd = array_values(array_filter($clean, fn($c) => !in_array($c, $existing, true)));

    if (count($toAdd) === 0) {
        return back()->with('customErrors', ['All provided columns already exist.'])->withInput();
    }

    try {
        Schema::table($table, function (Blueprint $t) use ($toAdd) {
            foreach ($toAdd as $col) {
                $t->string($col, 255); // VARCHAR(255) NOT NULL
            }
        });
    } catch (\Throwable $e) {
        return back()->with('customErrors', ['SQL Error: ' . $e->getMessage()])->withInput();
    }

    $existingColumns = Schema::getColumnListing($table);

    return view('schema.add_columns', [
        'table' => $table,
        'existingColumns' => $existingColumns,
        'success' => 'Columns added: ' . implode(', ', $toAdd),
    ]);

}

public function removeColumn(Request $request)
{
    $table = (string) $request->input('table', '');
    $column = (string) $request->input('column', '');

    if ($table === '' || $column === '') {
        return back()->with('customErrors', ['Missing table or column.']);
    }

    if (!Schema::hasTable($table)) {
        return back()->with('customErrors', ['Table not found.']);
    }

    // Safety: block dropping protected columns
    if (in_array($column, ['id', 'created_at'], true)) {
        return back()->with('customErrors', ["You can't drop the '{$column}' column."]);
    }

    // Validate identifier (same rule you use elsewhere)
    if (!preg_match('/^[a-z0-9_]+$/', $column) || !preg_match('/^[a-z0-9_]+$/', $table)) {
        return back()->with('customErrors', ['Invalid table or column name.']);
    }

    // Must exist
    $existing = Schema::getColumnListing($table);
    if (!in_array($column, $existing, true)) {
        return back()->with('customErrors', ["Column '{$column}' does not exist."]);
    }

    try {
        Schema::table($table, function (Blueprint $t) use ($column) {
            $t->dropColumn($column);
        });
    } catch (\Throwable $e) {
        return back()->with('customErrors', ['SQL Error: ' . $e->getMessage()]);
    }

    // Stay on the same page and show success (same pattern you used)
    $existingColumns = Schema::getColumnListing($table);

    return view('schema.add_columns', [
        'table' => $table,
        'existingColumns' => $existingColumns,
        'success' => "Column dropped: {$column}",
    ]);
}


}
