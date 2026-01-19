import os
import smtplib
from smtplib import SMTPAuthenticationError, SMTPException

@app.route('/send', methods=['POST'])
def send():
    name = request.form['name']
    email = request.form['email']
    product = request.form['product']

    sender_email = os.getenv("SMTP_USER")
    password = os.getenv("SMTP_PASS")

    message = MIMEMultipart("alternative")
    message["Subject"] = f"Thank you, {name}!"
    message["From"] = sender_email or ""
    message["To"] = email

    text = f"Hi {name},\n\nThanks for ordering {product}!\nWe'll ship it soon.\n- Neven’s Art Studio"
    html = f"""\
    <html><body>
      <div style="font-family: Arial, sans-serif;">
        <h2>Hi {name} 🌸</h2>
        <p>Thank you for purchasing <b>{product}</b> from Neven’s Art Studio.</p>
        <p>With appreciation,<br>Neven 🎨</p>
      </div>
    </body></html>
    """

    message.attach(MIMEText(text, "plain"))
    message.attach(MIMEText(html, "html"))

    try:
        if not sender_email or not password:
            raise RuntimeError("Missing SMTP credentials. Set SMTP_USER and SMTP_PASS on the server.")

        with smtplib.SMTP_SSL("smtp.gmail.com", 465, timeout=20) as server:
            server.login(sender_email, password)
            server.send_message(message)

        flash(f"Email sent to {email} for {product}!", "success")

    except SMTPAuthenticationError:
        flash("SMTP login failed. Use a Gmail App Password and verify SMTP_USER/SMTP_PASS.", "error")
    except SMTPException as e:
        flash(f"SMTP error while sending: {e}", "error")
    except Exception as e:
        flash(f"Error: {e}", "error")

    return redirect("/email_sender/")
