var users = [];
function addUser(name, age) {
    var newUser = {
        id: Date.now(), // simple unique ID
        name: name,
        age: age
    };
    users.push(newUser);
    console.log("User added:", newUser);
    return newUser;
}
function getUsers() {
    console.log("All users:", users);
    return users;
}
function findUser(id) {
    var user = users.find(function (u) { return u.id === id; });
    console.log("Found user:", user);
    return user;
}
function removeUser(id) {
    users = users.filter(function (u) { return u.id !== id; });
    console.log("User removed:", id);
}

console.log("Functions are: addUser, getUsers, findUser, and removeUser")
