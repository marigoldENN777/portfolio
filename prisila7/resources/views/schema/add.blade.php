@extends('layouts.app')

@section('title', 'Add Table')

@section('content')
<div class="min-h-screen bg-slate-50">
  <div class="mx-auto max-w-6xl px-4 py-8">

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

      <!-- Header -->
      <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <h1 class="text-xl font-semibold text-slate-900">Add Table</h1>
          <p class="mt-1 text-sm text-slate-600">Create a new database table.</p>
        </div>

        <div class="flex items-center gap-2">
          <a href="{{ route('schema.index') }}"
             class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Back
          </a>
        </div>
      </div>

      <div class="p-6">
        @if (!empty($customErrors))
  <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
    <div class="font-semibold mb-2">Please fix the following:</div>
    <ul class="list-disc pl-5 space-y-1">
      @foreach ($customErrors as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (!empty($success))
  <div
    id="flash-message"
    class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 transition-opacity duration-500"
  >
    {{ $success }}
  </div>
@endif




        <form method="POST" action="{{ route('schema.store') }}" class="space-y-6">
          @csrf

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label class="block text-sm font-semibold text-slate-800">Table name</label>
              <p class="mt-1 text-xs text-slate-500">Use lowercase + underscores (example: <code>products</code>).</p>

              <input
                type="text"
                name="table_name"
                value="{{ old('table_name', old('name')) }}"
                required
                class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                placeholder="e.g. products"
              />

              {{-- Send a duplicate key for controllers that expect "name" --}}
              <input type="hidden" name="name" value="{{ old('table_name', old('name')) }}">
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-800">Primary key</label>
              <p class="mt-1 text-xs text-slate-500">Optional (default: <code>id</code>).</p>

              <input
                type="text"
                name="primary_key"
                value="{{ old('primary_key', 'id') }}"
                class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                placeholder="id"
              />
            </div>
          </div>

          <div class="flex items-center gap-3">
            <button
              type="submit"
              class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
            >
              Create table
            </button>

            <a
              href="{{ route('schema.index') }}"
              class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
              Cancel
            </a>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  // keep hidden "name" in sync if user edits table_name
  document.addEventListener('input', (e) => {
    if (e.target && e.target.name === 'table_name') {
      const hidden = document.querySelector('input[name="name"]');
      if (hidden) hidden.value = e.target.value;
    }
  });
</script>
@endsection
