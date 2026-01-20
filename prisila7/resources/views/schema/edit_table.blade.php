@extends('layouts.app')

@section('title', 'Edit Table')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
       @if (session('success'))
  <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
    {{ session('success') }}
  </div>
@endif

@if (!empty(session('customErrors') ?? []))
  <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
    <ul class="list-disc pl-5">
      @foreach (session('customErrors') as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif


        <h1 class="text-xl font-semibold text-slate-900">Edit table</h1>
        <a href="{{ route('schema.data.add', ['table' => $table]) }}"
   class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-200">
  + Add data
</a>
<a href="{{ route('schema.table.columns.add', ['table' => $table]) }}"
   class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
   + Add columns
</a>





        <p class="mt-2 text-slate-700">
            Table: <span class="font-mono">{{ $table }}</span>
        </p>

        <div class="mt-6">
    <h2 class="text-base font-semibold text-slate-900">Rows</h2>
    <p class="mt-1 text-sm text-slate-600">Showing up to {{ $rows->count() }} rows.</p>

    @if ($rows->isEmpty())
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            No rows yet.
        </div>
    @else
        <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            @foreach ($columns as $col)
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    {{ $col }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach ($rows as $row)
                            <tr class="hover:bg-slate-50">
                                @foreach ($columns as $col)
                                    @php
                                        $val = $row->$col ?? '';
                                        $val = is_scalar($val) ? (string)$val : json_encode($val);
                                    @endphp

                                    <td class="px-4 py-3 text-sm text-slate-900 align-top">
                                        <div class="max-w-[420px] truncate" title="{{ $val }}">
                                            {{ $val }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>


        <div class="mt-4">
            <a href="{{ route('schema.index') }}"
               class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                ← Back
            </a>
        </div>
    </div>
</div>
@endsection
