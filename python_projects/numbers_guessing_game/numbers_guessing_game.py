import random

print("🎯 Welcome to the Number Guessing Game!")
print("I'm thinking of a number between 1 and 100...")

# 1️⃣ Choose a random number
secret_number = random.randint(1, 100)
attempts = 0

while True:
    # 2️⃣ Get user's guess
    guess = input("Enter your guess: ")

    # 3️⃣ Validate input
    if not guess.isdigit():
        print("Please enter a valid number.")
        continue

    guess = int(guess)
    attempts += 1

    # 4️⃣ Compare the guess with the secret number
    if guess < secret_number:
        print("Too low! Try again.")
    elif guess > secret_number:
        print("Too high! Try again.")
    else:
        print(f"🎉 Correct! The number was {secret_number}.")
        print(f"You guessed it in {attempts} attempts.")
        break
