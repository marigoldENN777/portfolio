<template>
    <main class="min-h-screen bg-zinc-100 text-zinc-900">
        <div class="w-full px-6 py-8 print:hidden">
            <!-- HEADER -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight">Dashboard</h1>
                    <p class="text-sm text-zinc-500">Vue 3 + Tailwind</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div v-if="!renamingProject">
      <select
        v-model="activeProjectId"
        class="h-10 rounded-xl border border-zinc-400 bg-white px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-300"
      >
        <option v-for="p in projects" :key="p.id" :value="p.id">
          {{ p.name }}
        </option>
      </select>
    </div>

    <input
      v-else
      id="projectRenameInput"
      v-model="projectNameInput"
      class="h-10 rounded-xl border border-zinc-400 bg-white px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-300"
      @keydown.enter="saveRenameProject"
      @keydown.esc="cancelRenameProject"
      @blur="saveRenameProject"
    />
                    <button @click="addProject" class="h-10 rounded-xl border border-zinc-400 bg-white px-4 text-sm font-medium shadow-sm hover:bg-zinc-100">
                        + Project
                    </button>
                    <button @click="deleteProject" class="h-10 rounded-xl border border-red-200 bg-white px-4 text-sm font-medium text-red-700 shadow-sm hover:bg-red-50">
                        Delete Project
                    </button>
                    <button
                             @click="startRenameProject"
                            class="h-10 rounded-xl border border-zinc-400 bg-white px-4 text-sm font-medium shadow-sm hover:bg-zinc-100"
                            >
                            Rename Project
                    </button>
                </div>
            </header>
            <!-- SUMMARY -->
            <section class="mt-8 rounded-2xl border border-zinc-400 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold mb-4">Project Summary</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <SummaryCard label="Total Tasks" :value="summary.total" />
                    <SummaryCard label="Todo" :value="summary.todo" />
                    <SummaryCard label="Doing" :value="summary.doing" />
                    <SummaryCard label="Done" :value="summary.done" />
                    <SummaryCard label="Total Time" :value="formatSeconds(summary.totalSeconds)" />
                </div>
            </section>
            <!-- SEARCH + ACTIONS -->
            <section class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <input v-model="search" placeholder="Search tasks..." class="h-11 w-full rounded-xl border border-zinc-400 bg-white px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-300 sm:max-w-md" />
                <div class="flex gap-3">
                    <button @click="addTask" class="h-11 rounded-xl bg-zinc-900 px-5 text-sm font-medium text-white shadow-sm hover:bg-zinc-800">
                        + Task
                    </button>
                    <button @click="exportPdf" class="h-11 rounded-xl border border-zinc-200 bg-white px-5 text-sm font-medium shadow-sm hover:bg-zinc-100">
                        Export PDF
                    </button>
                    <button @click="resetData" class="h-11 rounded-xl border border-zinc-200 bg-white px-5 text-sm font-medium shadow-sm hover:bg-zinc-100">
                        Reset
                    </button>
                </div>
            </section>
            <section class="mt-4 rounded-2xl border border-zinc-300 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold text-zinc-800">Custom columns</h2>
                    <button @click="addColumn" class="h-9 rounded-xl bg-zinc-900 px-4 text-sm font-medium text-white shadow-sm hover:bg-zinc-800">
                        + Column
                    </button>
                </div>
                <div v-if="projectColumns.length === 0" class="mt-2 text-sm text-zinc-500">
                    No custom columns yet. Add one (e.g. “Est. time”, “Assignee”, “Notes”).
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <div v-for="c in projectColumns" :key="c.id" class="flex items-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm">
                        <!-- VIEW MODE -->
                        <template v-if="editingColumnId !== c.id">
                            <button class="font-medium text-zinc-800 hover:underline" @click="startRenameColumn(c)">
                                {{ c.label }}
                            </button>
                            <button @click="removeColumn(c.id)" class="ml-2 rounded-lg border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">
                                Delete
                            </button>
                        </template>
                        <!-- EDIT MODE -->
                        <template v-else>
                            <input v-model="editingColumnLabel" class="rounded-lg border border-zinc-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-300" @keydown.enter="saveRenameColumn(c)" @blur="saveRenameColumn(c)" autofocus />
                        </template>
                    </div>
                </div>
                <p class="mt-3 text-xs text-zinc-500">
                    Tip: drag column headers in the table to reorder (custom columns only).
                </p>
            </section>
            <!-- TABLE -->
            <section class="mt-6 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed text-sm">
                        <colgroup>
                            <col style="width: 35%" />
                            <col style="width: 8rem" />
                            <col style="width: 10rem" />
                            <col v-for="c in projectColumns" :key="c.id" style="width: 14rem" />
                            <col style="width: 8rem" />
                            <col style="width: 10rem" />
                        </colgroup>
                        <!-- Single header -->
                        <TaskTableHead v-model="safeProject.customColumns" :showTimer="true" />
                        <!-- ACTIVE (draggable tbody) -->
                        <draggable v-model="activeList" item-key="id" tag="tbody" class="divide-y divide-zinc-100" handle=".drag-handle" :disabled="search.trim().length > 0" filter="input,textarea,select" :preventOnFilter="false">
                            <template #item="{ element: t }">
                                <TaskRow :t="t" :projectColumns="projectColumns" mode="active" :selectedTaskId="selectedTaskId" :editingTitleId="editingTitleId" :editingTimeTaskId="editingTimeTaskId" :timeInput="timeInput" :liveSeconds="liveSeconds" :formatSeconds="formatSeconds" @timeInputChange="timeInput = $event" @select="selectedTaskId = $event" @remove="removeTask" @startEditTitle="startEditTitle" @stopEditTitle="stopEditTitle" @autoGrow="autoGrow" @startEditTime="startEditTime" @saveEditTime="saveEditTime" @cancelEditTime="cancelEditTime" @startTimer="startTimer" @stopTimer="stopTimer" @changeStatus="onChangeStatus" />
                            </template>
                            <template #footer>
                                <tr v-if="activeList.length === 0">
                                    <td :colspan="5 + projectColumns.length" class="px-6 py-8 text-center text-zinc-500">
                                        No tasks yet.
                                    </td>
                                </tr>
                            </template>
                        </draggable>
                        <!-- SEPARATOR (normal tbody) -->
                        <tbody v-if="doneList.length > 0" class="bg-zinc-600">
                            <tr>
                                <td :colspan="5 + projectColumns.length" class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-white border-y border-zinc-900">
                                    Done tasks
                                </td>
                            </tr>
                        </tbody>
                        <!-- DONE (draggable tbody) -->
                        <draggable v-model="doneList" item-key="id" tag="tbody" class="divide-y divide-zinc-100" handle=".drag-handle-done" :disabled="search.trim().length > 0" filter="input,textarea,select" :preventOnFilter="false">
                            <template #item="{ element: t }">
                                <TaskRow :t="t" :projectColumns="projectColumns" mode="done" :selectedTaskId="selectedTaskId" :editingTitleId="editingTitleId" :editingTimeTaskId="editingTimeTaskId" :timeInput="timeInput" :liveSeconds="liveSeconds" :formatSeconds="formatSeconds" @timeInputChange="timeInput = $event" @select="selectedTaskId = $event" @remove="removeTask" @startEditTitle="startEditTitle" @stopEditTitle="stopEditTitle" @autoGrow="autoGrow" @startEditTime="startEditTime" @saveEditTime="saveEditTime" @cancelEditTime="cancelEditTime" @startTimer="startTimer" @stopTimer="stopTimer" @changeStatus="onChangeStatus" />
                            </template>
                            <template #footer>
                                <tr v-if="doneList.length === 0">
                                    <td :colspan="5 + projectColumns.length" class="px-6 py-8 text-center text-zinc-500">
                                        No done tasks yet.
                                    </td>
                                </tr>
                            </template>
                        </draggable>
                    </table>
                </div>
            </section>
            <section v-if="selectedTask" class="mt-6 rounded-2xl border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-semibold">Task details</h2>
                        <p class="text-sm text-zinc-500 break-words">
                            {{ selectedTask.title }} • {{ selectedTask.status }} • {{ selectedTask.priority }}
                        </p>
                    </div>
                    <button @click="selectedTaskId = null" class="h-9 rounded-xl border border-zinc-200 bg-white px-4 text-sm font-medium shadow-sm hover:bg-zinc-100">
                        Close
                    </button>
                </div>
                <div class="mt-5">
                    <h3 class="text-sm font-semibold text-zinc-700">Comments</h3>
                    <div class="mt-3 flex gap-2">
                        <input v-model="newComment" placeholder="Write a comment..." class="h-10 flex-1 rounded-xl border border-zinc-300 bg-white px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-300" @keydown.enter="addComment" />
                        <button @click="addComment" class="h-10 rounded-xl bg-zinc-900 px-4 text-sm font-medium text-white shadow-sm hover:bg-zinc-800">
                            Add
                        </button>
                    </div>
                    <div v-if="!selectedTask.comments || selectedTask.comments.length === 0" class="mt-3 text-sm text-zinc-500">
                        No comments yet.
                    </div>
                    <ul class="mt-3 space-y-2">
                        <li v-for="c in (selectedTask.comments || [])" :key="c.id" class="rounded-xl border border-zinc-200 p-3">
                            <div class="text-sm break-words">{{ c.text }}</div>
                            <div class="mt-1 text-xs text-zinc-500">
                                {{ new Date(c.createdAt).toLocaleString() }}
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
            <!-- PRINT AREA -->
        </div>
    </main>
    <section class="hidden print:block mt-10 print-area">
        <h1 class="text-2xl font-semibold mb-2">{{ activeProject.name }} — Report</h1>
        <div class="text-sm mb-4">{{ new Date().toLocaleString() }}</div>
        <table class="min-w-full text-sm border border-zinc-300">
            <thead class="bg-zinc-100">
                <tr>
                    <th class="px-4 py-2 text-left border">Task</th>
                    <th class="px-4 py-2 text-left border">Status</th>
                    <th class="px-4 py-2 text-left border">Priority</th>
                    <th class="px-4 py-2 text-left border">Time</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="t in activeProject.tasks" :key="t.id">
                    <td class="px-4 py-2 border">{{ t.title }}</td>
                    <td class="px-4 py-2 border">{{ t.status }}</td>
                    <td class="px-4 py-2 border">{{ t.priority }}</td>
                    <td class="px-4 py-2 border font-mono">
                        {{ formatSeconds(liveSeconds(t)) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 font-semibold">
            Total: {{ formatSeconds(summary.totalSeconds) }}
        </div>
    </section>
    <div v-if="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 rounded-xl bg-zinc-900 px-4 py-2 text-sm text-white shadow-lg">
        {{ toast }}
    </div>
</template>
<script setup>
import SummaryCard from "./components/SummaryCard.vue";
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from "vue";
import draggable from "vuedraggable";
import TaskRow from "./components/tasks/TaskRow.vue";
import TaskTableHead from "./components/tasks/TaskTableHead.vue"

function uid() {
    return Math.random().toString(16).slice(2) + Date.now().toString(16);
}

const STORAGE_KEY = "dev-dashboard:v1";

const selectedTaskId = ref(null);

const selectedTask = computed(() => {
    const project = activeProject.value
    if (!project) return null
    return project.tasks.find(t => t.id === selectedTaskId.value) ?? null
})

function taskTimeText(t) {
    return formatSeconds(liveSeconds(t))
}

const activeListWithTime = computed(() =>
    activeList.value.map(t => ({ ...t, _timeText: taskTimeText(t) }))
)

const doneListWithTime = computed(() =>
    doneList.value.map(t => ({ ...t, _timeText: taskTimeText(t) }))
)

const toast = ref(null);
let toastTimer = null;

function showToast(msg) {
    toast.value = msg;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => (toast.value = null), 4000);
}

const newComment = ref("");

const editingTitleId = ref(null);

function titlePreview(title) {
    const s = (title ?? "").trim();
    if (!s) return "New task";
    return s.length <= 30 ? s : s.slice(0, 30) + "…";
}

function startEditTitle(t) {
    editingTitleId.value = t.id;

    nextTick(() => {
        // focus the textarea that just appeared (best way: query inside the row)
        const row = document.querySelector(`[data-task="${t.id}"]`);
        const el = row?.querySelector("textarea");
        if (!el) return;
        el.focus();
        el.style.height = "auto";
        el.style.height = Math.min(el.scrollHeight, 96) + "px";
    });
}

function stopEditTitle() {
    editingTitleId.value = null;
}

function autoGrow(e) {
    const el = e.target;
    el.style.height = "auto";

    const max = 96; // px (matches max-h-24)
    const next = Math.min(el.scrollHeight, max);

    el.style.height = next + "px";
    el.style.overflowY = el.scrollHeight > max ? "auto" : "hidden";
}


// store textarea DOM refs by task id
const titleAreaRefs = ref({});

function autosizeTitle(taskId) {
    const el = titleAreaRefs.value[taskId];
    if (!el) return;
    el.style.height = "auto";
    el.style.height = el.scrollHeight + "px";
}



function defaultProjects() {
    return [{
        id: "demo",
        name: "Demo Project",
        customColumns: [],
        tasks: [{
            id: uid(),
            title: "Set up Vue",
            status: "Doing",
            priority: "High",
            totalSeconds: 0,
            timerStartedAt: null,
            comments: [],
            fields: {},
        }, ],
    }, ];
}

function loadState() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return { projects: defaultProjects() };

        const data = JSON.parse(raw);
        const loadedProjects = data.projects ?? defaultProjects();

        const nowMs = Date.now();

        for (const p of loadedProjects) {
            for (const t of p.tasks || []) {
                // if timer was running when saved, add elapsed to totalSeconds
                if (t.timerStartedAt) {
                    const elapsed = Math.floor((nowMs - t.timerStartedAt) / 1000);
                    t.totalSeconds = (t.totalSeconds || 0) + Math.max(0, elapsed);
                    t.timerStartedAt = null; // stop it after settling
                } else {
                    t.timerStartedAt = null;
                }

                if (!t.comments) t.comments = [];
                if (!t.fields) t.fields = {};
            }
        }

        return { projects: loadedProjects };
    } catch {
        return { projects: defaultProjects() };
    }
}

const initial = loadState();
const projects = ref(initial.projects);
const projectColumns = computed(() => activeProject.value?.customColumns ?? []);
const activeProjectId = ref(projects.value[0]?.id || "demo");
const search = ref("");

const activeProject = computed(() =>
    projects.value.find((p) => p.id === activeProjectId.value)
);

const filteredTasks = computed(() => {
    const q = search.value.toLowerCase();
    return activeProject.value.tasks.filter((t) =>
        t.title.toLowerCase().includes(q)
    );
});


const q = computed(() => search.value.trim().toLowerCase())

const activeList = computed({
    get: () => {
        const tasks = (activeProject.value?.tasks ?? []).filter(t => t.status !== "Done")
        if (!q.value) return tasks
        return tasks.filter(t => (t.title ?? "").toLowerCase().includes(q.value))
    },
    set: (newOrder) => {
        // dragging is disabled during search anyway, but guard for safety
        if (q.value) return
        applyOrderWithinStatus("active", newOrder)
    },
})

const doneList = computed({
    get: () => {
        const tasks = (activeProject.value?.tasks ?? []).filter(t => t.status === "Done")
        if (!q.value) return tasks
        return tasks.filter(t => (t.title ?? "").toLowerCase().includes(q.value))
    },
    set: (newOrder) => {
        if (q.value) return
        applyOrderWithinStatus("done", newOrder)
    },
})

function applyOrderWithinStatus(which, newOrder) {
    const p = activeProject.value
    if (!p || !Array.isArray(p.tasks)) return

    const isInGroup = (t) => (which === "done" ? t.status === "Done" : t.status !== "Done")

    const keep = p.tasks.filter(t => !isInGroup(t)) // the other group (unchanged)
    const reordered = newOrder // new order for this group

    // Keep your “active first, done last” rule
    if (which === "done") {
        const active = keep
        p.tasks = [...active, ...reordered]
    } else {
        const done = keep
        p.tasks = [...reordered, ...done]
    }
}

const safeProject = computed(() => activeProject.value ?? { tasks: [], customColumns: [] })

const summary = computed(() => {
    const tasks = activeProject.value.tasks;
    const totalSeconds = tasks.reduce((a, t) => a + liveSeconds(t), 0);

    return {
        total: tasks.length,
        todo: tasks.filter((t) => t.status === "Todo").length,
        doing: tasks.filter((t) => t.status === "Doing").length,
        done: tasks.filter((t) => t.status === "Done").length,
        totalSeconds,
    };
});

watch(
    projects,
    () => {
        for (const p of projects.value) {
            if (!Array.isArray(p.customColumns)) p.customColumns = [];

            for (const t of p.tasks || []) {
                if (!t.fields) t.fields = {};

                for (const c of p.customColumns) {
                    if (!(c.key in t.fields)) t.fields[c.key] = "";
                }
            }
        }
    }, { deep: true, immediate: true }
);


watch(
    () => projects.value,
    (p) => localStorage.setItem(STORAGE_KEY, JSON.stringify({ projects: p })), { deep: true }
);

// Move tasks into the correct section whenever status changes
watch(
    () => (activeProject.value?.tasks ?? []).map(t => `${t.id}:${t.status}`).join("|"),
    () => {
        const p = activeProject.value
        if (!p || !Array.isArray(p.tasks)) return

        const desired = [
            ...p.tasks.filter(t => t.status !== "Done"),
            ...p.tasks.filter(t => t.status === "Done"),
        ]

        const curIds = p.tasks.map(t => t.id).join("|")
        const nextIds = desired.map(t => t.id).join("|")

        if (curIds !== nextIds) {
            p.tasks = desired
        }
    }
)

function settleAllRunningTimers() {
    const nowMs = Date.now();
    for (const p of projects.value) {
        for (const t of p.tasks || []) {
            if (t.timerStartedAt) {
                const elapsed = Math.floor((nowMs - t.timerStartedAt) / 1000);
                t.totalSeconds += Math.max(0, elapsed);
                t.timerStartedAt = null;
            }
        }
    }
}

function saveState() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify({ projects: projects.value }));
}

onMounted(() => {
    const handler = () => {
        settleAllRunningTimers();
        saveState();
    };
    window.addEventListener("beforeunload", handler);
    onUnmounted(() => window.removeEventListener("beforeunload", handler));
});

function addProject() {
    const name = prompt("Project name?");
    if (!name) return;
    const p = { id: uid(), name, tasks: [] };
    projects.value.push(p);
    activeProjectId.value = p.id;
}

function deleteProject() {
    const p = activeProject.value
    if (!p) return

    // optional: protect demo project
    if (p.id === "demo") {
        alert("Demo Project can't be deleted.")
        return
    }

    const ok = confirm(`Delete project "${p.name}"? This will delete all its tasks.`)
    if (!ok) return

    const idx = projects.value.findIndex(x => x.id === p.id)
    if (idx === -1) return

    projects.value.splice(idx, 1)

    // pick a new active project safely
    if (projects.value.length === 0) {
        const fresh = defaultProjects()
        projects.value = fresh
        activeProjectId.value = fresh[0]?.id ?? "demo"
    } else {
        const next = projects.value[Math.min(idx, projects.value.length - 1)]
        activeProjectId.value = next.id
    }

    // also close selected task panel (since tasks changed)
    selectedTaskId.value = null
}

function addTask() {
    const title = prompt("Task name?")?.trim();
    if (!title) return;

    activeProject.value.tasks.unshift({
        id: uid(),
        title,
        status: "Todo",
        priority: "Medium",
        totalSeconds: 0,
        timerStartedAt: null,
        comments: [],
        fields: {},
    });
}

function removeTask(id) {
    const task = activeProject.value.tasks.find(t => t.id === id);
    if (!task) return;

    if (!confirm(`Delete task "${task.title}"?`)) return;

    activeProject.value.tasks =
        activeProject.value.tasks.filter(t => t.id !== id);

    // if deleted task was selected, close details
    if (selectedTaskId.value === id) {
        selectedTaskId.value = null;
    }
}

const editingColumnId = ref(null);
const editingColumnLabel = ref("");

function startRenameColumn(col) {
    editingColumnId.value = col.id;
    editingColumnLabel.value = col.label;
}

function saveRenameColumn(col) {
    const newLabel = editingColumnLabel.value.trim();
    if (!newLabel) {
        editingColumnId.value = null;
        return;
    }

    col.label = newLabel;
    editingColumnId.value = null;
}

function addComment() {
    const task = selectedTask.value;
    if (!task) return;

    const text = newComment.value.trim();
    if (!text) return;

    // ensure comments exists (handles old saved data)
    if (!Array.isArray(task.comments)) task.comments = [];

    task.comments.unshift({
        id: uid(),
        text,
        createdAt: Date.now(),
    });

    newComment.value = "";
}

const now = ref(Date.now());
let tickHandle = null;

onMounted(() => {
    tickHandle = setInterval(() => {
        now.value = Date.now();
    }, 1000);
});

onUnmounted(() => {
    if (tickHandle) clearInterval(tickHandle);
});

function startTimer(t) {
    t.timerStartedAt = Date.now();
}

function stopTimer(t) {
    if (!t.timerStartedAt) return;
    t.totalSeconds += Math.floor((Date.now() - t.timerStartedAt) / 1000);
    t.timerStartedAt = null;
}

function liveSeconds(t) {
    if (!t.timerStartedAt) return t.totalSeconds;

    // referencing now.value makes it reactive and updates UI each second
    return t.totalSeconds + Math.max(0, Math.floor((now.value - t.timerStartedAt) / 1000));
}

function formatSeconds(sec) {
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    return [h, m, s].map((n) => String(n).padStart(2, "0")).join(":");
}

const editingTimeTaskId = ref(null);
const timeInput = ref("");

function startEditTime(t) {
    // if timer is running, stop it first (so edits are predictable)
    if (t.timerStartedAt) stopTimer(t);

    editingTimeTaskId.value = t.id;
    timeInput.value = formatSeconds(t.totalSeconds || 0);
}

function cancelEditTime() {
    editingTimeTaskId.value = null;
    timeInput.value = "";
}

function saveEditTime(t) {
    const seconds = parseTimeToSeconds(timeInput.value);
    if (seconds == null) {
        alert("Use hh:mm:ss or mm:ss or minutes (e.g. 90, 12:34, 01:02:03)");
        return;
    }

    t.totalSeconds = seconds;
    t.timerStartedAt = null; // ensure not running
    cancelEditTime();
}

function parseTimeToSeconds(input) {
    const s = (input || "").trim();
    if (!s) return 0;

    // allow plain minutes like "90"
    if (/^\d+$/.test(s)) return parseInt(s, 10) * 60;

    // allow mm:ss or hh:mm:ss
    const parts = s.split(":").map(p => p.trim());
    if (parts.some(p => !/^\d+$/.test(p))) return null;

    if (parts.length === 2) {
        const [m, sec] = parts.map(Number);
        if (sec >= 60) return null;
        return m * 60 + sec;
    }

    if (parts.length === 3) {
        const [h, m, sec] = parts.map(Number);
        if (m >= 60 || sec >= 60) return null;
        return h * 3600 + m * 60 + sec;
    }

    return null;
}

function exportPdf() {
    window.print();
}

function resetData() {
    if (!confirm("Reset all saved data?")) return
    localStorage.removeItem(STORAGE_KEY)

    const freshProjects = defaultProjects()
    projects.value = freshProjects

    // pick first project immediately
    activeProjectId.value = freshProjects[0]?.id ?? "demo"

    // OPTIONAL: if you really want to clear columns, do it on the project object directly
    const p0 = freshProjects[0]
    if (p0) p0.customColumns = []
}

function normalizeKey(label) {
    return label
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, "_")
        .replace(/^_+|_+$/g, "");
}

function addColumn() {
    const label = prompt("Column name?")?.trim();
    if (!label) return;

    const p = activeProject.value;
    if (!Array.isArray(p.customColumns)) p.customColumns = [];

    const keyBase = normalizeKey(label) || "col";
    let key = keyBase;
    let i = 2;

    while (p.customColumns.some((c) => c.key === key)) {
        key = `${keyBase}_${i++}`;
    }

    const col = { id: uid(), key, label, type: "text" };
    p.customColumns.unshift(col);

    for (const t of p.tasks) {
        if (!t.fields) t.fields = {};
        if (!(key in t.fields)) t.fields[key] = "";
    }
}

function removeColumn(colId) {
    const p = activeProject.value;
    const col = p.customColumns?.find((c) => c.id === colId);
    if (!col) return;

    if (!confirm(`Delete column "${col.label}"?`)) return;

    p.customColumns = p.customColumns.filter((c) => c.id !== colId);

    for (const t of p.tasks) {
        if (t.fields) delete t.fields[col.key];
    }
}

const renamingProject = ref(false)
const projectNameInput = ref("")

function startRenameProject() {
  if (!activeProject.value) return
  renamingProject.value = true
  projectNameInput.value = activeProject.value.name

  nextTick(() => {
    const el = document.getElementById("projectRenameInput")
    el?.focus()
    el?.select()
  })
}

function saveRenameProject() {
  const name = projectNameInput.value.trim()
  if (!name) {
    renamingProject.value = false
    return
  }

  activeProject.value.name = name
  renamingProject.value = false
}

function cancelRenameProject() {
  renamingProject.value = false
}

function ensureTaskFields(task) {
    if (!task) return;
    if (!task.fields) task.fields = {};
    for (const c of activeProject.value.customColumns) {
        if (!(c.key in task.fields)) task.fields[c.key] = "";
    }
}

function onChangeStatus({ task, status }) {
    const wasDone = task.status === "Done"
    const willBeDone = status === "Done"

    // update status first
    task.status = status

    // ✅ toast only when it becomes Done
    if (!wasDone && willBeDone) {
        showToast(`✅ Done: ${task.title || "Task"}`)
    }

    // If it stays on the same board, DO NOT move it
    if (wasDone === willBeDone) return

    const p = activeProject.value
    if (!p) return

    const without = p.tasks.filter(x => x.id !== task.id)
    const active = without.filter(t => t.status !== "Done")
    const done = without.filter(t => t.status === "Done")

    if (willBeDone) {
        p.tasks = [...active, task, ...done]
    } else {
        p.tasks = [task, ...active, ...done]
    }
}
</script>
<style>
@media print {

    /* Hide everything by default */
    body * {
        visibility: hidden;
    }

    /* Show only the report section and its children */
    .print-area,
    .print-area * {
        visibility: visible;
    }

    /* Put report at the top-left for clean PDF */
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>