<script setup>
import draggable from "vuedraggable"

const props = defineProps({
    modelValue: { type: Array, required: true }, // customColumns array
    showTimer: { type: Boolean, default: true },
})

const emit = defineEmits(["update:modelValue"])
</script>
<template>
    <thead class="bg-zinc-900 text-white">
        <draggable :modelValue="modelValue" @update:modelValue="emit('update:modelValue', $event)" item-key="id" tag="tr" class="bg-zinc-900 text-white">
            <template #header>
                <th class="px-6 py-4 text-left font-semibold">Task</th>
                <th class="px-6 py-4 text-left font-semibold">Status</th>
                <th class="px-6 py-4 text-left font-semibold">Priority</th>
            </template>
            <template #item="{ element: c }">
                <th class="px-6 py-4 text-left font-semibold cursor-move select-none">
                    {{ c.label }}
                </th>
            </template>
            <template #footer>
                <th class="px-6 py-4 text-left font-semibold">Time</th>
                <th class="px-6 py-4 text-left font-semibold">Timer</th>
            </template>
        </draggable>
    </thead>
</template>