<template>
  <div class="rounded-xl border border-zinc-200 bg-white p-4">
    <div class="text-xs text-zinc-500">{{ label }}</div>
    <div class="mt-1 text-lg font-semibold">{{ value }}</div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: [String, Number],
});
</script>