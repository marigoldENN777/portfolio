<script setup>
import { computed } from "vue"

const props = defineProps({
    t: { type: Object, required: true },
    projectColumns: { type: Array, required: true },

    mode: { type: String, default: "active" }, // "active" | "done"

    // UI state from parent
    selectedTaskId: { type: [String, Number, null], default: null },
    editingTitleId: { type: [String, Number, null], default: null },
    editingTimeTaskId: { type: [String, Number, null], default: null },
    timeInput: { type: String, default: "" },
    liveSeconds: { type: Function, required: true },
    formatSeconds: { type: Function, required: true },
})

const emit = defineEmits([
    "select",
    "remove",
    "startEditTitle",
    "stopEditTitle",
    "autoGrow",
    "startEditTime",
    "saveEditTime",
    "cancelEditTime",
    "startTimer",
    "stopTimer",
    "timeInputChange",
    "changeStatus",
])

const isDone = computed(() => props.mode === "done")
const isDoing = computed(() => props.t.status === "Doing")

const rowClass = computed(() => {
  if (isDone.value) {
    return "bg-green-200 hover:bg-green-300 transition cursor-pointer"
  }

  if (isDoing.value) {
    return "bg-orange-200 hover:bg-orange-300 transition cursor-pointer"
  }

  return "hover:bg-zinc-300 transition cursor-pointer"
})

const handleClass = computed(() => (isDone.value ? "drag-handle-done" : "drag-handle"))

function truncateTitle(s, max = 30) {
  const str = (s ?? "").trim()
  if (!str) return "New task"
  return str.length > max ? str.slice(0, max) + "…" : str
}
</script>
<template>
    <tr :key="t.id" :data-task="t.id" @click="emit('select', t.id)" :class="rowClass">
        <!-- TITLE + drag handle -->
        <td class="px-6 py-4 overflow-hidden">
  <div class="flex items-center gap-3">
    <span
      :class="[
        handleClass,
        'cursor-grab select-none rounded-md border border-zinc-200 bg-white px-2 py-1 text-xs text-zinc-600 hover:bg-zinc-50'
      ]"
      title="Drag to reorder"
      @click.stop
    >
      ⇅
    </span>

    <!-- IMPORTANT: min-w-0 + truncate on a BLOCK inside a shrinking flex item -->
    <div
      v-if="editingTitleId !== t.id"
      class="flex-1 min-w-0"
      @click.stop="emit('startEditTitle', t)"
      :title="t.title"
    >
      <span
        :class="[
          isDone ? 'line-through text-zinc-500' : '',
          'block truncate text-sm'
        ]"
      >
        {{ truncateTitle(t.title, 30) }}
      </span>
    </div>

    <textarea
      v-else
      v-model="t.title"
      rows="1"
      class="flex-1 min-w-0 resize-none rounded-lg border border-zinc-200 px-3 py-2 text-sm leading-5
             focus:outline-none focus:ring-2 focus:ring-zinc-300 max-h-24 overflow-y-auto"
      @click.stop
      @input="emit('autoGrow', $event)"
      @blur="emit('stopEditTitle')"
      @keydown.enter.exact.prevent="emit('stopEditTitle')"
    />
  </div>
</td>

    <!-- STATUS -->
    <td class="px-6 py-4" @click.stop>
      <select
  :value="t.status"
  @change="emit('changeStatus', { task: t, status: $event.target.value })"
  class="h-7 rounded-md border border-zinc-200 px-2 text-xs focus:outline-none focus:ring-1 focus:ring-zinc-300"
  @mousedown.stop
  @click.stop
>
  <option>Todo</option>
  <option>Doing</option>
  <option>Done</option>
</select>
    </td>

    <!-- PRIORITY -->
    <td class="px-6 py-4" @click.stop>
      <select
        v-model="t.priority"
        class="h-7 rounded-md border border-zinc-200 px-2 text-xs focus:outline-none focus:ring-1 focus:ring-zinc-300"
        @mousedown.stop
        @click.stop
      >
        <option>Low</option>
        <option>Medium</option>
        <option>High</option>
      </select>
    </td>

    <!-- CUSTOM COLS -->
    <td v-for="c in projectColumns" :key="c.id" class="px-6 py-4" @click.stop>
      <input
        v-model="t.fields[c.key]"
        class="h-9 w-44 rounded-lg border border-zinc-200 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-300"
        placeholder="—"
        @mousedown.stop
        @click.stop
      />
    </td>
    <!-- TIME -->
<td class="px-6 py-4 font-mono" @click.stop>
  <!-- DONE: just show time -->
  <span v-if="isDone" class="line-through text-zinc-500">
    {{ formatSeconds(liveSeconds(t)) }}
  </span>

  <!-- ACTIVE: view -->
  <button
    v-else-if="editingTimeTaskId !== t.id"
    class="w-full text-left hover:underline"
    @click.stop="emit('startEditTime', t)"
    title="Click to edit time"
  >
    {{ formatSeconds(liveSeconds(t)) }}
  </button>

  <!-- ACTIVE: edit -->
  <input
    v-else
    :value="timeInput"
    @input="emit('timeInputChange', $event.target.value)"
    class="h-8 w-32 rounded-lg border border-zinc-200 px-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-zinc-300"
    placeholder="hh:mm:ss"
    @click.stop
    @keydown.enter.prevent="emit('saveEditTime', t)"
    @keydown.esc.prevent="emit('cancelEditTime')"
    @blur="emit('saveEditTime', t)"
  />
</td>

    <!-- ACTIONS -->
    <td class="px-6 py-4" @click.stop>
  <div class="flex gap-2 justify-end">
    <template v-if="!isDone">
      <button
        v-if="!t.timerStartedAt"
        @click.stop="emit('startTimer', t)"
        class="rounded-lg bg-zinc-900 px-3 py-1.5 text-xs text-white hover:bg-zinc-800"
      >
        Start
      </button>

      <button
        v-else
        @click.stop="emit('stopTimer', t)"
        class="rounded-lg bg-zinc-600 px-3 py-1.5 text-xs text-white hover:bg-zinc-700"
      >
        Stop
      </button>
    </template>

    <!-- placeholder keeps the same spacing/width as Start/Stop -->
    <span v-else class="inline-block w-[56px]"></span>

    <button
      @click.stop="emit('remove', t.id)"
      class="rounded-lg border border-red-200 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50"
    >
      Delete
    </button>
  </div>
</td>
  </tr>
</template>