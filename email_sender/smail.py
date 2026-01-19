import os
import smtplib
from flask import Flask, render_template, request, redirect, flash
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

app = Flask(__name__)
app.secret_key = "super-secret-key"

@app.route("/")
def form():
    return render_template("order_form.html")

@app.route("/send", methods=["POST"])
def send():
    name = request.form["name"]
    email = request.form["email"]
    product = request.form["product"]

    sender_email = os.getenv("SMTP_USER")
    password = os.getenv("SMTP_PASS")

    message = MIMEMultipart("alternative")
    message["Subject"] = f"Thank you, {name}!"
    message["From"] = sender_email
    message["To"] = email

    text = f"Hi {name},\n\nThanks for ordering {product}!\nWe'll ship it soon.\n- Neven’s Art Studio"
    message.attach(MIMEText(text, "plain"))

    with smtplib.SMTP_SSL("smtp.gmail.com", 465) as server:
        server.login(sender_email, password)
        server.send_message(message)

    flash(f"Email sent to {email} for {product}!", "success")
    return redirect("/email_sender/")
