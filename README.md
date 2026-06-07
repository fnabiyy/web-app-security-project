# INFO 4345 Web Application Security
## Group Project 2: Web Application Security Enhancement Report

---

### Group Members

| Name | Matric No. | 
| :--- | :--- | 
| NURFATIEHAH NABILAH BINTI ABDUL NAJID | 2317546 | 

---

### Title of the Web Application
* **Application Title:** Automated Secure Invoice and Payment Management System

---

### Introduction of Web Application
For today's fast-paced business environment, freelancers, small businesses, and service providers require efficient instruments for managing billing and payment processes. To this end, we recommend designing a Simple Invoice System, a built-in web-based application that can simplify the creation, sending, and following of invoices.

The system will provide users with fundamental invoicing features through a simplified interface. Users will be able to securely log in or sign up, manage their clients, create and edit invoices, and track the payment status. Compared to more advanced platforms, this app is confined to the basics of invoicing and is ideal for users who need a basic but reliable billing facility.

This project borrows ideas from open-source options like Invoice Ninja but limit the scope to keep things simple, easy to use, and efficient. The proposed system will serve as a working solution for individuals and small organizations that want to improve the accuracy of billing and save their time.

---

### Objective of the Enhancements
The core objectives of the security engineering modifications implemented in this project are:
1. **To Fortify Authentication Mechanisms:** Eliminate brute-force exposure lines by introducing adaptive request-throttling walls.
2. **To Enforce Strict Access Controls:** Implement structural input complexity rules to safeguard credential boundaries from low-entropy exploitation.
3. **To Establish Data Sanitization Standards:** Protect system operators and clients from malicious data injection vectors (such as cross-site scripting) targeting structural invoice metadata fields.
4. **To Align with Industry Benchmarks:** Elevate the application’s overall architecture closer to the OWASP Top 10 framework compliance guidelines.

---

### Web Application Security Enhancements

#### a. Input Validation
* **Vulnerability 1 :  Improper Input Validation** User Registration Parameters (`name`, `email`, `password`, `password_confirmation`), Invoice Record Modals, and Payment Transaction Fields (`amount`, `transaction_reference`).
* **Client-Side Validation:** Handled dynamically via Filament’s real-time input masks and browser-level reactive constraints. Text inputs explicitly restrict maximum string limits (`maxLength(255)`), enforce specific HTML5 input types (e.g., `type="email"`), and utilize structural field locking (`required()`) to catch empty form payloads before transmission.
* **Server-Side Validation:** Enforced rigorously using Laravel’s backend validation layer during lifecycle hooks. Even if an attacker uses browser inspection tools to bypass client constraints, the server catches bad inputs. For example, transaction inputs are bound to numeric filters (`numeric()`) and minimum mathematical boundaries (`rule('min:0.01')`) to completely block parameter tampering, negative values, or zero-value exploitation.

#### ii. Authentication
Following authentication security best practices, the application gateway was hardened using two defense mechanisms:
1. **Brute-Force Protection via Rate Limiting:** A structural login rate limiter was introduced into the authentication attempt thread. If an automated script triggers consecutive failed authentication requests, a session-locked penalty wall (`locked_until`) activates. This halts the authentication flow early, preserving server resources and blocking automated dictionary lists before the resource-intensive password hashing check (`Hash::check`) runs.
2. **Enforcement of High-Entropy Passwords (CWE-521):** The registration schema was upgraded to enforce a strict password complexity validation rule chain. The framework rejects weak or sequential strings, requiring all new accounts to contain a minimum of 8 characters consisting of an uppercase letter, a lowercase letter, a number, and a special keyboard symbol.

#### iii. Authorization
* **Implementation Methods:** Role-Based Access Control (RBAC) and Route Protection.
* **Details:** Fine-grained authorization is implemented by binding resources directly to Filament resource policies. Standard users or payment operators are structurally isolated from higher-tier administrative controllers. Access to secure application routes is verified at the framework kernel level using Laravel Middlewares, which inspect session tokens to ensure users cannot manipulate URLs (IDOR attacks) to view or modify invoices belonging to other entities.

#### iv. XSS and CSRF Prevention
* **Stored XSS (Cross-Site Scripting) Prevention:** To stop attackers from saving malicious JavaScript payloads (such as `<h1>Vulnerable Note</h1><script>alert('XSS-Test')</script>`) into database fields like invoice notes or payment references, data is sanitized during the backend form dehydration phase using PHP's native `strip_tags()` filter. Furthermore, presentation columns discard risky render parameters (avoiding `->html()`) in favor of default Blade safe-escaping (`{{ $variable }}`), converting special characters into harmless string entities (e.g., `&lt;script&gt;`).
* **CSRF (Cross-Site Request Forgery) Prevention:** Every data mutation request (`POST`, `PUT`, `DELETE`) generated by the application is protected by a cryptographically secure, session-linked CSRF token token generated by Laravel's `VerifyCsrfToken` middleware. Incoming state changes are instantly dropped if the payload token fails a validation match against the active user session token, nullifying session riding attacks.

#### v. Database Security Principles
* **Prevention of SQL Injection (SQLi):** The application completely eliminates raw database queries by routing all database operations through Laravel's Eloquent Object-Relational Mapper (ORM) and the underlying PDO (PHP Data Objects) layer. Eloquent inherently utilizes strict **PDO Parameter Binding and Prepared Statements**. This forces the SQL engine to treat incoming user inputs strictly as parameters (literal values) rather than executable database syntax commands, making injection impossible.
* **Server and Connection Hardening:**
  * Sensitive database credentials (passwords, usernames, host addresses) are completely decoupled from the source code and isolated inside a protected local environment file (`.env`).
  * The production `.env` configuration file is explicitly registered inside the application's root `.gitignore` file, guaranteeing that private infrastructure keys are never pushed to public version control systems.
