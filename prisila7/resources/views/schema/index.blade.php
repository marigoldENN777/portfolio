@extends('layouts.app')

@section('title', 'Data Tables')

@section('content')
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-8">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- Header -->
                <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Data Tables</h1>

                        @if (empty($tables))
                            <p class="mt-1 text-sm text-slate-600">No tables found in the database.</p>
                        @else
                            <p class="mt-1 text-sm text-slate-600">
                                {{ count($tables) }} table{{ count($tables) === 1 ? '' : 's' }} available
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('schema.create') }}"
                           class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            + Add Table
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    @if (empty($tables))
                        <p class="text-sm text-slate-700">
                            You don't have any tables yet. Create your first one to start managing data.
                        </p>
                    @else
                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
  <tr>
    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
      Table name
    </th>
    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
      Actions
    </th>
  </tr>
</thead>

                                <tbody class="divide-y divide-slate-200 bg-white">
  @foreach ($tables as $table)
    <tr class="hover:bg-slate-50">
      <td class="px-4 py-3 text-sm font-medium text-slate-900">
        {{ $table }}
      </td>

      <td class="px-4 py-3 text-sm text-right">
        <a href="{{ route('schema.edit.table', ['table' => $table]) }}"
           class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
          Edit
        </a>
      </td>
    </tr>
  @endforeach
</tbody>

                            </table>
                        </div>

                        <div class="mt-4 text-sm text-slate-600">
                            Managing <strong>{{ count($tables) }}</strong> table{{ count($tables) === 1 ? '' : 's' }}
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection
