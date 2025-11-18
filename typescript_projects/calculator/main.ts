function getNumber(id: string): number {
    const value = (document.getElementById(id) as HTMLInputElement).value;
    return Number(value);
}

function setResult(value: number | string) {
    const resultElement = document.getElementById("result")!;
    resultElement.textContent = "Result: " + value;
}

function add(a: number, b: number): number {
    return a + b;
}

function subtract(a: number, b: number): number {
    return a - b;
}

function multiply(a: number, b: number): number {
    return a * b;
}

function divide(a: number, b: number): number | string {
    if (b === 0) return "Cannot divide by zero";
    return a / b;
}

// Event Listeners
(document.getElementById("add") as HTMLButtonElement).onclick = () => {
    setResult(add(getNumber("a"), getNumber("b")));
};

(document.getElementById("sub") as HTMLButtonElement).onclick = () => {
    setResult(subtract(getNumber("a"), getNumber("b")));
};

(document.getElementById("mul") as HTMLButtonElement).onclick = () => {
    setResult(multiply(getNumber("a"), getNumber("b")));
};

(document.getElementById("div") as HTMLButtonElement).onclick = () => {
    setResult(divide(getNumber("a"), getNumber("b")));
};
