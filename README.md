# TicketApp — Twig (PHP)

This is a minimal Twig-based implementation. It renders templates server-side and uses client-side JavaScript to manage auth (localStorage) and tickets.

Requirements

- PHP 8 with Composer to install Twig.

Setup

1. Run `composer install` in the `twig` folder (this will install twig/twig).
2. Start PHP built-in server: `php -S localhost:8000 -t .` in the `twig` folder.
3. Open `http://localhost:8000` in your browser.

Notes

- Authentication uses `localStorage` key `ticketapp_session` for token simulation.
- Tickets are stored in `localStorage` under `ticketapp_tickets`.
- This is meant as a minimal example to show the same UI layout and client-side logic.

Test credentials

- Email: `test@example.com`
- Password: `password`
