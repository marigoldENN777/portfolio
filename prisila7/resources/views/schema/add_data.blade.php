@extends('layouts.app')

@section('title', 'Add Data')

@section('content')
<div class="min-h-screen bg-slate-50">
  <div class="mx-auto max-w-4xl px-4 py-8">

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="flex items-start justify-between gap-4 border-b border-slate-200 p-6">
        <div>
          <h1 class="text-xl font-semibold text-slate-900">Add data</h1>
          <p class="mt-1 text-sm text-slate-600">
            Insert a new row into: <span class="font-mono">{{ $table }}</span>
          </p>
        </div>

        <a href="{{ route('schema.edit.table', ['table' => $table]) }}"
           class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
          ← Back
        </a>
      </div>

      <div class="p-6">

        @if (!empty(session('customErrors') ?? []))
          <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
              @foreach (session('customErrors') as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="post" action="{{ route('schema.data.store', ['table' => $table]) }}" class="space-y-4">
          @csrf

          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($editableColumns as $col)
              <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">{{ $col }}</label>
                <input
                  type="text"
                  name="row[{{ $col }}]"
                  value="{{ old('row.' . $col) }}"
                  class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
              </div>
            @endforeach
          </div>

          <div class="flex items-center gap-2 pt-2">
            <button type="submit"
              class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-200">
              Save row
            </button>

            <a href="{{ route('schema.edit.table', ['table' => $table]) }}"
              class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
              Cancel
            </a>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection
