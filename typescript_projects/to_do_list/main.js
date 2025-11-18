var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var tasks = [];
var idCounter = 1;
function addTask(text) {
    var newTask = {
        id: idCounter++,
        text: text,
        done: false
    };
    tasks.push(newTask);
    renderTasks();
}
function toggleTask(id) {
    tasks = tasks.map(function (t) {
        return t.id === id ? __assign(__assign({}, t), { done: !t.done }) : t;
    });
    renderTasks();
}
function deleteTask(id) {
    tasks = tasks.filter(function (t) { return t.id !== id; });
    renderTasks();
}
function renderTasks() {
    var list = document.getElementById("task-list");
    list.innerHTML = "";
    var _loop_1 = function (task) {
        var li = document.createElement("li");
        li.textContent = task.text + (task.done ? " ✓" : "");
        li.onclick = function () { return toggleTask(task.id); };
        var del = document.createElement("button");
        del.textContent = "X";
        del.onclick = function (e) {
            e.stopPropagation();
            deleteTask(task.id);
        };
        li.appendChild(del);
        list.appendChild(li);
    };
    for (var _i = 0, tasks_1 = tasks; _i < tasks_1.length; _i++) {
        var task = tasks_1[_i];
        _loop_1(task);
    }
}
// Button event
document.getElementById("add-btn").onclick = function () {
    var input = document.getElementById("task-input");
    if (input.value.trim() !== "") {
        addTask(input.value.trim());
        input.value = "";
    }
};
