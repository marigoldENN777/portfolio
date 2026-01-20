import './bootstrap';

console.log('Vite is working ✅');

let formIndex = 0;

function add_field() {
    const container = document.getElementById('tables_container');
    if (!container) return;

    const subDiv = document.createElement('div');
    subDiv.className = "rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm space-y-3";


    const tableInput = document.createElement('input');
    tableInput.placeholder = "Table name";
    tableInput.name = `table[${formIndex}]`;
    tableInput.className =
        "w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200";
    subDiv.appendChild(tableInput);

    // 1) Try .counter-button (your PRISILA input)
    // 2) Fallback to #tables_count (your current Blade input)
    const counterEl =
        document.querySelector('.counter-button') ||
        document.getElementById('tables_count');

    let count = parseInt(counterEl?.value ?? '1', 10);
    if (Number.isNaN(count) || count < 1) count = 1;

    while (count > 0) {
        const fieldInput = document.createElement('input');
        fieldInput.placeholder = "Field name";
        fieldInput.name = `fields[${formIndex}][]`;
        fieldInput.className =
            "w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200";
        subDiv.appendChild(fieldInput);
        count--;
    }

    container.appendChild(subDiv);
    formIndex++;
}

document.addEventListener('DOMContentLoaded', () => {
    const addTablesBtn = document.getElementById('add_tables');
    if (!addTablesBtn) return;

    addTablesBtn.addEventListener('click', add_field);
});


document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flash-message');
    if (!flash) return;

    setTimeout(() => {
        flash.classList.add('opacity-0');
    }, 3000); // start fade after 3s

    setTimeout(() => {
        flash.remove();
    }, 3500); // remove after fade
});