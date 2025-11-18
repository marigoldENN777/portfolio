from flask import Flask, render_template, request
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

app = Flask(__name__)

@app.route('/')
def form():
    return render_template('order_form.html')

@app.route('/send', methods=['POST'])
def send():
    name = request.form['name']
    email = request.form['email']
    product = request.form['product']

    sender_email = "nevenjosipovic5@gmail.com"
    password = "fvmhvwuipseackzc"

    message = MIMEMultipart("alternative")
    message["Subject"] = f"🛍️ Thank you, {name}!"
    message["From"] = sender_email
    message["To"] = email

    text = f"Hi {name},\n\nThanks for ordering {product}!\nWe'll ship it soon.\n- Neven’s Art Studio"

    html = f"""\
    <html>
      <body style="font-family: Arial, sans-serif; background-color: #f2f2f2; padding: 20px;">
        <div style="max-width: 500px; margin: auto; background: white; border-radius: 10px; padding: 20px;">
          <h2 style="color: #6a1b9a;">Hi {name} 🌸</h2>
          <p style="font-size: 16px; color: #333;">
            Thank you for purchasing <b>{product}</b> from Neven’s Art Studio.
          </p>
          <p style="font-size: 14px; color: #888;">With appreciation,<br>Neven 🎨</p>
        </div>
      </body>
    </html>
    """

    message.attach(MIMEText(text, "plain"))
    message.attach(MIMEText(html, "html"))

    with smtplib.SMTP_SSL("smtp.gmail.com", 465) as server:
        server.login(sender_email, password)
        server.send_message(message)

    return f"<h3>Email sent successfully to {name} ({email}) for {product}!</h3>"

if __name__ == '__main__':
    app.run(debug=True)





