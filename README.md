# INFO 4345 Web Application Security
## Group Project 2: Web Application Security Enhancement Report

---

### Group Members

| Name | Matric No. | 
| :--- | :--- | 
| NURFATIEHAH NABILAH BINTI ABDUL NAJID | 2317546 | 

---

### Title of the Web Application
* **Application Title:** Invoice Sensei

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

-----

#### a. Input Validation
**Vulnerability 1 :  Improper Input Validation (CWE-20)**
* **Vulnerability Name:** Range & Numeric Boundary Check Failure
* **Technical Identifier:** CWE-20 (Improper Input Validation) / OWASP A03:2021-Injection
* **Risk Rating:** High

##### 2. Description in Simple English
The web application forgets to check if a user is entering a valid, positive number. It blindly trusts whatever number the user types into the Quantity or Price boxes rather than validating the logical boundaries of the input payload.

##### 3. How It Was Proved (Vulnerability Testing)
During testing, a negative value of `-38` was typed into the headphones quantity input field. Instead of blocking the request, the web application accepted the value, multiplied it by the baseline price, and calculated a final negative invoice subtotal and total of `-RM1,900.00`. The system then successfully committed this broken state into the persistent database layer.
  
<img width="1662" height="844" alt="IV_flaw1_01" src="https://github.com/user-attachments/assets/5473287f-e1b2-40f1-b7a5-ff9818edb4d7" />

##### 4. Why It Is a Security Risk
Leaving data ranges unvalidated allows malicious users to perform parameter tampering. By creating a "negative bill," an attacker could trick an automated corporate accounting ledger system into giving them free money, issuing unauthorized automatic refunds, or wiping away a real, valid debt they owe a business.

##### 5. Where the Code Was Updated
The security fix was implemented inside the backend configuration file managing invoice resource layouts:
* **File Directory Path:** `app/Filament/Resources/InvoiceResource.php`
* **Target Schema Section:** Inside the Form Schema array, specifically inside the `TextInput` component definition handling the line-item quantity metadata (`pivot.quantity`).

##### 6. Source Code Modifications

###### Before Code (Vulnerable)
The original form definition trusted client-side inputs implicitly. It checked that the data type was an integer, but completely ignored the value range boundaries, letting negative integers pass directly into the mathematical calculation sequences:
<img width="543" height="151" alt="IV_flaw1_03" src="https://github.com/user-attachments/assets/169a599e-3959-4183-9b33-237970fd2391" />

###### After Code (Mitigated & Hardened)
The mitigation implements a robust server-side boundary range defense mechanism. By chaining the minValue(1) validator method onto the input structure, the system establishes a definitive backend boundary that automatically drops form validation payloads violating logical business rules before they hit database memory structures:
<img width="743" height="208" alt="IV_flaw1_04" src="https://github.com/user-attachments/assets/16743c02-a497-4295-a609-348a64c9f79b" />

##### 7. Report Summary & Explanation
In short, the original web app had no safety boundaries, letting users type a negative quantity (like `-38`) and create a negative bill of `-RM1,900.00` in the database. 

By adding `minValue(1)`, we built a solid backend shield. Now, even if a hacker uses sneaky tools to bypass the browser screen, our server instantly catches the bad input, stops the math calculation, and forces the user to enter a real, positive number.
<img width="1825" height="879" alt="IV_flaw1_02" src="https://github.com/user-attachments/assets/bfa855fc-f405-405a-98d2-58d12076f263" />

-------
**Vulnerability 2 : Improper Neutralization of Input During Web Page Generation (CWE-79)**

##### 1. Flaw Overview: Bad Text Filtering / Code Injection (Stored XSS)
* **Vulnerability Name:** Lacking Input Sanitization (Stored Cross-Site Scripting)
* **Technical Identifier:** CWE-79 (Improper Neutralization of Input During Web Page Generation) / OWASP A03:2021-Injection
* **Risk Rating:** Medium / High

##### 2. Description in Simple English
The web application allows users to type raw website programming code (such as HTML and JavaScript tags) into text areas like the Notes box. The application then saves that code directly into the database without cleaning it up.

##### 3. How It Was Proved (Vulnerability Testing)
During testing, a malicious script tag (`<h1>Vulnerable Note</h1><script>alert('XSS-Test')</script>`) was typed into the Invoice Notes box. The web application saved the invoice perfectly into the database without stripping any of the code away.
<img width="1534" height="825" alt="IV_flaw2_01" src="https://github.com/user-attachments/assets/03c11dbf-191b-4d9c-887f-aad833ec4cd5" />

##### 4. Why It Is a Security Risk
Because the dangerous code is now living inside your database, whenever an administrator opens that invoice to view it or tries to print it out, the hidden script will automatically run inside their browser. This can allow hackers to secretly steal administrator passwords, hijack user sessions, or change the look of the website.

##### 5. Where the Code Was Updated
The security fix was implemented inside the invoice backend resource management layout file:
* **File Directory Path:** `app/Filament/Resources/InvoiceResource.php`
* **Target Schema Section:** Inside the Form Schema array, specifically inside the `Textarea` component definition managing the invoice comment section (`notes`).

##### 6. Source Code Modifications

###### Before Code (Vulnerable)
The application accepted free-form string entries inside the text area directly, saving everything—including active scripts and raw HTML layouts—straight to your persistent database:
----

#### b. Authentication
Following authentication security best practices, the application gateway was hardened using two defense mechanisms:
1. **Brute-Force Protection via Rate Limiting:** A structural login rate limiter was introduced into the authentication attempt thread. If an automated script triggers consecutive failed authentication requests, a session-locked penalty wall (`locked_until`) activates. This halts the authentication flow early, preserving server resources and blocking automated dictionary lists before the resource-intensive password hashing check (`Hash::check`) runs.
2. **Enforcement of High-Entropy Passwords (CWE-521):** The registration schema was upgraded to enforce a strict password complexity validation rule chain. The framework rejects weak or sequential strings, requiring all new accounts to contain a minimum of 8 characters consisting of an uppercase letter, a lowercase letter, a number, and a special keyboard symbol.

#### c. Authorization


#### d. XSS and CSRF Prevention

#### e. Database Security Principles
