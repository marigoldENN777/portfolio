# Copilot Instructions for AI Agents

## Project Overview
This workspace is a multi-project portfolio containing web apps, forms, animations, and a PHP-based booking system. Each subfolder is a self-contained demo or app, with minimal cross-dependencies.

## Key Components
- **Root HTML files**: Quick demos and landing pages (`index.html`, etc.)
- **contact_form, signup_form, subscription_form, product_slider, typewriter, remove_background, shoe_repair, timer**: Each is a standalone HTML/CSS/JS web app. Scripts are in the same folder as their HTML.
- **online_booking_dev**: A PHP/MySQL booking system. Key structure:
  - `public/`: User/admin-facing pages
  - `controllers/`: Handles booking, login, status updates
  - `includes/`: Shared PHP (header, mail)
  - `config/db.php`: Database connection
  - `vendor/`: Composer dependencies (notably PHPMailer)
- **python_projects**: Standalone Python scripts and small apps. Each subfolder is independent.
- **typescript_projects**: TypeScript demos, each with its own `tsconfig.json` and build output.

## Developer Workflows
- **Web apps**: Edit HTML/CSS/JS directly. No build step required.
- **TypeScript**: Run `tsc` in each TypeScript subfolder to build `.ts` to `.js`.
- **PHP app**: Use Composer for dependency management (`composer install` in `online_booking_dev`).
- **Python**: Scripts are run directly; no global environment or requirements file.

## Patterns & Conventions
- Each demo/app is isolated; avoid cross-folder imports.
- For PHP, use `includes/` for shared code and `controllers/` for logic.
- Database config is always in `config/db.php`.
- PHPMailer is used for email in booking system (`includes/send_mail.php`).
- No global build or test system; workflows are per-app.

## Integration Points
- **PHPMailer**: Used for sending emails in booking system. See `vendor/phpmailer/` and `includes/send_mail.php`.
- **Composer**: Only used in `online_booking_dev`.
- **No frontend frameworks**: All web apps use vanilla JS/TS.

## Examples
- To add a new booking feature, update `controllers/HandleBooking.php` and relevant `public/` pages.
- To add a new TypeScript demo, create a new folder in `typescript_projects/` with its own `tsconfig.json`.
- To update shared PHP logic, edit files in `includes/`.

## References
- See `README.md` for a minimal project description.
- See `online_booking_dev/vendor/phpmailer/README.md` for PHPMailer usage.

---
**For AI agents:**
- Always check the relevant subfolder for context before making changes.
- Respect the isolation of each demo/app.
- For PHP, follow the controller/include/public pattern in `online_booking_dev`.
- For TypeScript, build per-folder, not globally.
- Avoid introducing cross-folder dependencies.
