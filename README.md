# 🚀 Task Laravel - Secure Auth & MFA

Welcome to the **Task Laravel** project! This is a modern, high-security web application built with Laravel 12. Its main goal is to show how we can keep user accounts safe using **Multi-Factor Authentication (MFA)**.

---

## 💡 The Concept

Imagine you have a secret diary. To keep it safe, you don't just use a lock; you use a lock **and** a secret handshake. 

This project does exactly that for web apps! It's a playground for implementing advanced security features where users can choose how they want to verify their identity:
- **TOTP**: Using apps like Google Authenticator.
- **Email**: Getting a code in your inbox.
- **SMS**: Getting a code on your phone.

---

## 🛡️ Laravel Fortify (Explained for a Kid!)

Imagine you are building a **Secret Clubhouse**. 🏰

To build your clubhouse, you need two things:
1. **The Rules**: Who can come in? How do they sign up? What happens if they forget their password?
2. **The Decorations**: What color is the door? Is the "Login" button blue or sparkly?

He handles all the boring "behind-the-scenes" security stuff so that we can focus on making the clubhouse look awesome!

> [!TIP]
> For a deep dive into the technical logic and why we chose this architecture, check out [FORTIFY_LOGIC.md](./FORTIFY_LOGIC.md).

---

## 🛠️ The Toolbox (Our Tech Stack)

Here are the cool tools we used to build this:

| Tool | What is it? | Why do we use it? |
| :--- | :--- | :--- |
| **Laravel 12** | The Framework | The "skeleton" or "engine" of the whole app. It organizes everything. |
| **PHP 8.4** | The Language | The actual code that tells the computer what to do. |
| **Fortify** | Auth Backend | The security guard. It handles logins, signups, and 2FA logic. |
| **Tailwind CSS v4** | Styling | The "paintbrush." It makes the app look modern and beautiful. |
| **Pest** | Testing | The "detective." It runs tests to make sure there are no bugs. |
| **Sail** | Docker Dev Env | The "shipping container." It makes sure the app runs the same on every computer. |
| **Pint** | Code Formatter | The "cleaner." It automatically fixes messy code to keep it neat. |
| **Vite** | Frontend Asset Tool | The "delivery truck." It prepares all the CSS and JS for the browser quickly. |
| **Boost** | Developer Tools | A special helper that gives us extra commands to work faster. |

---

## 📖 Glossary of Terms

- **Authentication (Auth)**: Proving you are who you say you are (Logging in).
- **MFA / 2FA**: "Multi-Factor Authentication." Using more than one way to prove your identity (Password + Code).
- **Middleware**: A "filter" that checks things before you reach a page (e.g., "Are you logged in?").
- **Migration**: A "blueprint" for the database. It tells the computer how to store your data.
- **Eloquent**: A way for Laravel to talk to the database easily, almost like talking to a person.
- **OTP**: "One-Time Password." A secret code that only works once and disappears!

---

## 🚀 Getting Started

To run this project locally, follow these steps:

1. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database**:
   ```bash
   php artisan migrate
   ```

4. **Launch**:
   ```bash
   composer run dev
   ```

---

Built with ❤️ using **Laravel 12**.
