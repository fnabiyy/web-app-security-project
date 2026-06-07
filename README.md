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

#### a. Input Validation & Data Sanitization Framework

##### 1. Technical Framework Overview
The system implements a defense-in-depth validation architecture across two critical entry points within the Invoice Creation interface to ensure that invalid or malicious form payloads are dropped before interacting with database threads:
* **Item Quantity Field (`pivot.quantity`):** Regulates the number of inventory units being billed. The backend strictly filters this value to accept only logical positive whole numbers.
* **Invoice Notes Field (`notes`):** A free-form narrative box used for custom administrative annotations. This allows operators to supply contextual text metadata.

##### 2. Client-Side vs. Server-Side Architecture
* **Client-Side Validation:** Executed locally within the user's browser environment. It handles immediate typos and provides instant UI feedback to prevent incorrect form submissions early.
* **Server-Side Validation:** Handled at the application backend inside `app/Filament/Resources/InvoiceResource.php`. This serves as our absolute security boundary; even if an attacker uses interception proxy tools (like *Burp Suite*) to bypass browser screen controls, the server catches and drops the modified request before it processes.

---

#### 3. Vulnerability 1: Improper Input Validation (CWE-20)
* **Vulnerability Name:** Range & Numeric Boundary Check Failure
* **Risk Rating:** High

###### A. Description & Testing Proof
The web application originally failed to validate logical numeric boundaries, blindly trusting any number inputted into the Quantity panel. 

During boundary testing, a negative value of `-38` was typed into the headphones quantity field. The web application implicitly accepted the value, multiplied it by the baseline price, calculated a final negative invoice subtotal and total of `-RM1,900.00`, and successfully committed this broken state into the persistent database.

<img width="1662" height="844" alt="IV_flaw1_01" src="https://github.com/user-attachments/assets/5473287f-e1b2-40f1-b7a5-ff9818edb4d7" />

###### B. Security Risk Impact
Leaving data ranges unvalidated exposes the application to parameter tampering. By creating a "negative bill," an attacker could exploit this arithmetic flaw to trick automated corporate accounting systems into wiping away legitimate debts, corrupting balances, or generating unauthorized automatic monetary refunds.

###### C. Where the Code Was Updated
* **File Directory Path:** `app/Filament/Resources/InvoiceResource.php`
* **Target Schema Section:** Inside the Form Schema array, specifically modifying the `TextInput` component layout handling the `pivot.quantity` metadata.

###### D. Source Code Modifications
The original layout only verified the integer data type but lacked lower range limits. The mitigation uses rule chaining to add an explicit `minValue(1)` parameter to enforce an absolute operational floor.

**Before Code (Vulnerable):**
<img width="543" height="151" alt="IV_flaw1_03" src="https://github.com/user-attachments/assets/169a599e-3959-4183-9b33-237970fd2391" />

**After Code (Mitigated & Hardened):**
<img width="743" height="208" alt="IV_flaw1_04" src="https://github.com/user-attachments/assets/16743c02-a497-4295-a609-348a64c9f79b" />

###### E. Summary & Mitigation Result
In short, the original web app had no safety boundaries, letting users type a negative quantity (like `-38`) and create a negative bill of `-RM1,900.00` in the database. 

By adding `minValue(1)`, we built a solid backend shield. Now, even if a hacker uses sneaky tools to bypass the browser screen, our server instantly catches the bad input, stops the math calculation, and forces the user to enter a real, positive number.

<img width="1825" height="879" alt="IV_flaw1_02" src="https://github.com/user-attachments/assets/bfa855fc-f405-405a-98d2-58d12076f263" />

---

#### 4. Vulnerability 2: Stored Cross-Site Scripting (CWE-79)
* **Vulnerability Name:** Lacking Input Sanitization (Stored XSS via Notes Field)
* **Risk Rating:** Medium / High

###### A. Description & Testing Proof
The application originally permitted arbitrary website programming code (HTML and JavaScript tags) to be written into text areas without data scrubbing, saving it raw into the database. 

During testing, a malicious script tag (`<h1>Vulnerable Note</h1><script>alert('XSS-Test')</script>`) was inputted into the Invoice Notes field. The system accepted and stored the payload seamlessly without stripping the dangerous parameters away.

<img width="1534" height="825" alt="IV_flaw2_01" src="https://github.com/user-attachments/assets/03c11dbf-191b-4d9c-887f-aad833ec4cd5" />

###### B. Security Risk Impact
Because raw programming code strings live directly inside the database table, a persistent threat is created. Every single time an authorized administrator opens or attempts to print that specific invoice page, the hidden payload script will automatically execute in their browser. Attackers use Stored XSS to hijack admin sessions, steal cookies/tokens, or secretly alter the webpage design.

###### C. Where the Code Was Updated
* **File Directory Path:** `app/Filament/Resources/InvoiceResource.php`
* **Target Schema Section:** Inside the Form Schema array, modifying the `Textarea` component handling the invoice comments section (`notes`).

###### D. Source Code Modifications
The vulnerability was mitigated by applying two protective layers: a size limit barrier (`maxLength`) to stop database text-flooding denial of service attempts, and an active server-side state sanitization callback hook using PHP's native `strip_tags()` function.

**Before Code (Vulnerable):**
<img width="281" height="63" alt="IV_flaw2_03_beforeCode" src="https://github.com/user-attachments/assets/70e3ae9a-be7e-4d98-9475-640a00925c18" />

**After Code (Mitigated & Hardened):**
<img width="531" height="159" alt="IV_flaw2_04_afterCode" src="https://github.com/user-attachments/assets/60d76fb0-96f5-47fa-a606-54eb56e4191e" />

###### E. Summary & Mitigation Result
In short, the original text box let anyone save dangerous programming code right into our database tables.

To fix this, we added a maximum length rule to stop users from breaking the layout with massive blocks of text, and we used a tool called `strip_tags()`. Now, right before the note is saved to the database, our server instantly wipes away any hidden JavaScript or HTML scripts, keeping the data clean and safe for administrators to read.

<img width="1406" height="759" alt="IV_flaw2_02" src="https://github.com/user-attachments/assets/309dc8e8-aca2-4aba-9b65-47cc4532f52a" />
----

#### b. Authentication
Following authentication security best practices, the application gateway was hardened using two defense mechanisms:
1. **Brute-Force Protection via Rate Limiting:** A structural login rate limiter was introduced into the authentication attempt thread. If an automated script triggers consecutive failed authentication requests, a session-locked penalty wall (`locked_until`) activates. This halts the authentication flow early, preserving server resources and blocking automated dictionary lists before the resource-intensive password hashing check (`Hash::check`) runs.
2. **Enforcement of High-Entropy Passwords (CWE-521):** The registration schema was upgraded to enforce a strict password complexity validation rule chain. The framework rejects weak or sequential strings, requiring all new accounts to contain a minimum of 8 characters consisting of an uppercase letter, a lowercase letter, a number, and a special keyboard symbol.

----
#### c. Authorization

----
#### d. XSS and CSRF Prevention


----
#### e. Database Security Principles
