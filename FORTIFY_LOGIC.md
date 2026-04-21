    # 🛡️ Laravel Fortify: Logic & Architecture

This document explains the technical logic behind how authentication and Multi-Factor Authentication (MFA) are handled in this project.

---

## 🧠 The Authentication Pipeline

When a user attempts to log in, Laravel Fortify processes the request through a **Pipeline**. Think of this as a conveyor belt where different "workers" (Action classes) perform specific tasks.

### 1. The Standard Pipeline
By default, Fortify runs these steps:
- **Rate Limiting**: Checks if the user is making too many requests.
- **Credential Validation**: Checks if the email and password match.
- **Session Preparation**: Prepares the user's session.

### 2. Custom Interception
On this task I  modified the pipeline in `FortifyServiceProvider.php` to include a custom step: `RedirectToOtpChallenge`.

```php
Fortify::authenticateThrough(function (Request $request) {
    return array_filter([
        config('fortify.limiters.login') ? \Laravel\Fortify\Actions\EnsureLoginIsNotThrottled::class : null,
        \Laravel\Fortify\Actions\PrepareAuthenticatedSession::class,
        \Laravel\Fortify\Actions\AttemptToAuthenticate::class,
        \App\Actions\Otp\RedirectToOtpChallenge::class, // <--- Custom Logic
    ]);
});
```

---

## ⚙️ How the MFA Logic Works

The `RedirectToOtpChallenge` action acts as a "Security Gate" that triggers immediately after a successful password check:

1.  **Detection**: It checks if the user has MFA enabled (`two_factor_secret`).
2.  **Immediate Logout**: If MFA is active, it logs the user out immediately. This ensures the user doesn't have a valid session yet.
3.  **Session Stashing**: It stores the user's ID in a temporary session variable (`login.id`).
4.  **Redirection**: It sends the user to the `/auth/otp-challenge` page.
5.  **Verification**: The user enters their code. Once verified, the application manually logs them in fully.

---

## 🏆 Why This Approach?

### 1. Headless Architecture
Fortify is **headless**, meaning it provides the "Brain" (logic) without the "Face" (UI). 
- **Benefit**: We have total control over the design while relying on Laravel's battle-tested security logic.

### 2. Zero-Trust Security
By logging the user out *before* the MFA challenge, we ensure that no private data can be accessed until the second factor is verified. Even if a password is stolen, the "door" remains locked.

### 3. Modular & Maintainable
Because each step is an "Action" class (like `CreateNewUser.php`), the code is:
- **Easy to read**: Each file does exactly one thing.
- **Easy to test**: I can test the MFA logic without running the whole login flow.
- **Flexible**: I can easily add new MFA methods (like SMS or Biometrics) by just adding a new worker to the pipeline.

---

## 📖 Key Files
- `config/fortify.php`: Main configuration for auth features.
- `app/Providers/FortifyServiceProvider.php`: Where we define the "conveyor belt" (pipeline).
- `app/Actions/Otp/RedirectToOtpChallenge.php`: The custom gatekeeper for MFA.
- `app/Http/Controllers/Auth/OtpChallengeController.php`: Handles the logic for checking the secret codes.
