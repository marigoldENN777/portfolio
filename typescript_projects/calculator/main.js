function getNumber(id) {
    var value = document.getElementById(id).value;
    return Number(value);
}
function setResult(value) {
    var resultElement = document.getElementById("result");
    resultElement.textContent = "Result: " + value;
}
function add(a, b) {
    return a + b;
}
function subtract(a, b) {
    return a - b;
}
function multiply(a, b) {
    return a * b;
}
function divide(a, b) {
    if (b === 0)
        return "Cannot divide by zero";
    return a / b;
}
// Event Listeners
document.getElementById("add").onclick = function () {
    setResult(add(getNumber("a"), getNumber("b")));
};
document.getElementById("sub").onclick = function () {
    setResult(subtract(getNumber("a"), getNumber("b")));
};
document.getElementById("mul").onclick = function () {
    setResult(multiply(getNumber("a"), getNumber("b")));
};
document.getElementById("div").onclick = function () {
    setResult(divide(getNumber("a"), getNumber("b")));
};
