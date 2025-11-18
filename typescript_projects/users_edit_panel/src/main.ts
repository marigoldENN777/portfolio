
interface User {
    id: number;
    name: string;
    age: number;
}

let users: User[] = [];

function addUser(name: string, age: number): User {
    const newUser: User = {
        id: Date.now(), // simple unique ID
        name,
        age
    };

    users.push(newUser);
    console.log("User added:", newUser);
    return newUser;
}

function getUsers(): User[] {
    console.log("All users:", users);
    return users;
}

function findUser(id: number): User | undefined {
    const user = users.find(u => u.id === id);
    console.log("Found user:", user);
    return user;
}

function removeUser(id: number): void {
    users = users.filter(u => u.id !== id);
    console.log("User removed:", id);
}