interface Task {
    id: number;
    text: string;
    done: boolean;
}

let tasks: Task[] = [];
let idCounter = 1;

function addTask(text: string) {
    const newTask: Task = {
        id: idCounter++,
        text,
        done: false
    };

    tasks.push(newTask);
    renderTasks();
}

function toggleTask(id: number) {
    tasks = tasks.map(t =>
        t.id === id ? { ...t, done: !t.done } : t
    );
    renderTasks();
}

function deleteTask(id: number) {
    tasks = tasks.filter(t => t.id !== id);
    renderTasks();
}

function renderTasks() {
    const list = document.getElementById("task-list") as HTMLUListElement;
    list.innerHTML = "";

    for (const task of tasks) {
        const li = document.createElement("li");
        li.textContent = task.text + (task.done ? " ✓" : "");

        li.onclick = () => toggleTask(task.id);

        const del = document.createElement("button");
        del.textContent = "X";
        del.onclick = (e) => {
            e.stopPropagation();
            deleteTask(task.id);
        };

        li.appendChild(del);
        list.appendChild(li);
    }
}

// Button event
(document.getElementById("add-btn") as HTMLButtonElement).onclick = () => {
    const input = document.getElementById("task-input") as HTMLInputElement;
    if (input.value.trim() !== "") {
        addTask(input.value.trim());
        input.value = "";
    }
};
