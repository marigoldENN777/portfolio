@extends('layouts.app') {{-- change to layouts.app if that’s your base --}}

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <h1 class="text-2xl font-semibold mb-6">Add New Table</h1>

    <form method="POST" action="{{ route('schema.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block font-medium mb-1">Table name</label>
            <input
                type="text"
                name="table_name"
                required
                class="w-full border rounded px-3 py-2"
                placeholder="e.g. products"
            >
        </div>

        <div>
            <label class="block font-medium mb-1">Primary key</label>
            <input
                type="text"
                name="primary_key"
                value="id"
                class="w-full border rounded px-3 py-2"
            >
        </div>

        <div class="flex gap-3">
            <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded"
            >
                Create table
            </button>

            <a
                href="{{ route('schema.index') }}"
                class="px-4 py-2 border rounded"
            >
                Cancel
            </a>
        </div>
    </form>

</div>
@endsection
