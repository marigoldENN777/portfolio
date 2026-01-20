@extends('layouts.app')

@section('title', 'Add Columns')

@section('content')
<div class="min-h-screen bg-slate-50">
  <div class="mx-auto max-w-3xl px-4 py-8">
    @if (!empty($success))
  <div
    id="flash-message"
    class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 transition-opacity duration-500"
  >
    {{ $success }}
  </div>
@endif


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="flex items-start justify-between gap-4 border-b border-slate-200 p-6">
        <div>
          <h1 class="text-xl font-semibold text-slate-900">Add columns</h1>
          <p class="mt-1 text-sm text-slate-600">
            Table: <span class="font-mono">{{ $table }}</span>
          </p>
        </div>

        <a href="{{ route('schema.edit.table', ['table' => $table]) }}"
   class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50">
   ← Back
</a>

      </div>
      <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4">
  <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4">
  <div class="text-sm font-semibold text-slate-900">Existing columns</div>

  <div class="mt-3 flex flex-wrap gap-3">
    @foreach ($existingColumns as $c)
      <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-2 text-xs font-mono text-slate-800 shadow-sm">
        <span>{{ $c }}</span>

        @if (!in_array($c, ['id', 'created_at'], true))
          <form method="post"
                action="{{ route('schema.removeColumn') }}"
                onsubmit="return confirm('Drop column {{ $c }}? This cannot be undone.')"
                class="inline">
            @csrf
            <input type="hidden" name="table" value="{{ $table }}">
            <input type="hidden" name="column" value="{{ $c }}">

                      <button type="submit"
                  class="ml-1 inline-flex h-5 w-5 items-center justify-center rounded-md
                         bg-red-500
                         text-slate-50
                         text-xs font-semibold leading-none
                         shadow-sm
                         hover:bg-red-600
                         focus:outline-none focus:ring-2 focus:ring-red-300"
                  title="Drop column">
            ×
          </button>

          </form>
        @endif
      </div>
    @endforeach
  </div>
</div>



      <div class="p-6">
        <form method="post" action="{{ route('schema.addColumns') }}" class="space-y-4">
          @csrf

          <input type="hidden" name="table" value="{{ $table }}">

          <div>
            <label class="block text-sm font-medium text-slate-700">
              New column names
            </label>
            <p class="mt-1 text-xs text-slate-500">
              Lowercase letters, numbers, underscores only.
            </p>

            <div id="new-columns-container" class="mt-3 space-y-2">
              <input
                type="text"
                name="columns[]"
                placeholder="column_name"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
              >
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button type="button"
              onclick="addNewColumnInput()"
              class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm hover:bg-slate-50">
              + Add another column
            </button>

            <button type="submit"
              class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
              Save columns
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
function addNewColumnInput() {
    const container = document.getElementById('new-columns-container');

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'columns[]';
    input.placeholder = 'column_name';
    input.className =
      'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200';

    container.appendChild(input);
}
</script>
@endsection
