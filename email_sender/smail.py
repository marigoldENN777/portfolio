import os
import smtplib
from smtplib import SMTPAuthenticationError, SMTPException
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

from flask import Flask, render_template, request, redirect, flash

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
    message["From"] = sender_email or ""
    message["To"] = email

    text = f"""Hi {name},

Thank you for your order.

Product:
{product}

– Neven’s Art Studio
"""

    html = f"""\
<!doctype html>
<html lang="en">
  <body style="margin:0;padding:0;background:#f6f6f6;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f6f6f6;">
      <tr>
        <td align="center" style="padding:32px 16px;">
          <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;width:100%;">
            <tr>
              <td style="text-align:center;padding-bottom:14px;">
                <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#777;">
                  Neven’s Studio
                </div>
              </td>
            </tr>

            <tr>
              <td style="background:#ffffff;border:1px solid #e7e7e7;border-radius:16px;padding:28px 24px;text-align:center;">
                <div style="font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:700;color:#111;">
                  Thank you for your order
                </div>

                <div style="margin-top:16px;font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#111;">
                  Hi {name},
                </div>

                <div style="margin-top:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#111;">
                  We received your order for:
                </div>

                <div style="margin-top:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:700;color:#111;">
                  {product}
                </div>

                <div style="margin-top:22px;">
                  <a href="https://neven-portfolio.com"
                     style="display:inline-block;padding:10px 18px;
                            font-family:Arial,Helvetica,sans-serif;
                            font-size:14px;font-weight:600;
                            color:#ffffff;background:#111;
                            border-radius:8px;text-decoration:none;">
                    View details
                  </a>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
"""

    message.attach(MIMEText(text, "plain", "utf-8"))
    message.attach(MIMEText(html, "html", "utf-8"))

    try:
        if not sender_email or not password:
            raise RuntimeError("Missing SMTP credentials. Set SMTP_USER and SMTP_PASS on the server.")

        with smtplib.SMTP_SSL("smtp.gmail.com", 465, timeout=20) as server:
            server.login(sender_email, password)
            server.send_message(message)

        flash(f"Email sent to {email} for {product}!", "success")

    except SMTPAuthenticationError:
        flash("SMTP login failed. Verify SMTP_USER/SMTP_PASS (Gmail App Password).", "error")
    except SMTPException as e:
        flash(f"SMTP error while sending: {e}", "error")
    except Exception as e:
        flash(f"Error: {e}", "error")

    return redirect("/email_sender/")
