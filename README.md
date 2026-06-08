# INFO 4345 Web Application Security Semester 2, 2025/2026

## Group Project 2: Web Application Security Enhancement Report

---

### Group Members

| Name | Matric No. | 
| :--- | :--- | 
| NURFATIEHAH NABILAH BINTI ABDUL NAJID | 2317546 | 
| FADHILAH BINTI ABD MUN'EM | 2313560 |
| AIZATUL MAZNI BINTI MAZLAN | 2316248 |

GROUP : NeoTech
LECTURER NAME : DR. NAJHAN BIN MUHAMAD IBRAHIM

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

### **a. Input Validation & Data Sanitization Framework**

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

### **b. Authentication**

##### 1. Technical Framework Overview
The authentication perimeter of the application serves as the primary barrier protecting user identities and financial ledgers. To safeguard tenant parameters against unauthorized entry, defensive rate limiting and strict credential policies are applied to stop automated attack engines before they can interact with background resource strings.

#### 2. Vulnerability 1: Missing Account Lockout Mechanism (CWE-307)
* **Vulnerability Name:** Online Brute-Force & Credential Guessing Exposure
* **Technical Identifier:** CWE-307 (Improper Restriction of Excessive Authentication Attempts) / OWASP A07:2021-Identification and Authentication Failures
* **Risk Rating:** High

###### A. Description & Testing Proof
During authentication security testing, the login portal was subjected to an online credential-guessing simulation. A valid email address was targeted, and incorrect password payloads were submitted 20 consecutive times. The application backend originally failed to restrict or delay submittal traffic, continually rendering standard validation alerts without penalizing the source session.

<img width="1892" height="876" alt="Screenshot 2026-06-07 114255" src="https://github.com/user-attachments/assets/e33cca4d-84f0-4ade-8cd9-c03e8a504a32" />


###### B. Security Risk Impact
Without an automated lockout policy, the authentication gateway is completely exposed to rapid, automated online brute-force and dictionary attacks. A threat actor can systematically exhaust thousands of password variations using basic credential-stuffing tools until a valid authentication string matches, leading to complete account takeover (ATO).

###### C. Where the Code Was Updated
* **File Directory Path:** `app/Filament/Pages/Auth/Login.php`
* **Target Schema Section:** 1. `public function authenticate()` (The entrance intercept wall) 
  2. `protected function loginProcess()` (The backend verification handler)

###### D. Source Code Modifications
The original code checked credential authenticity strings immediately, allowing threat actors to repeat invalid connection loops instantly. The mitigation puts a security wall at the very top of the function to kill the process instantly if a session lock threshold is currently active.

**Before Code (Vulnerable):**

<img width="396" height="534" alt="Auth_flaw1_bfr01" src="https://github.com/user-attachments/assets/53a1c858-f09e-480a-adbc-983eddddbe36" />
<img width="418" height="390" alt="Auth_flaw2_bfr02" src="https://github.com/user-attachments/assets/f4d088c6-2421-4279-904b-2bfc24d71dbe" />

**After Code (Mitigated & Hardened):**

<img width="751" height="721" alt="Auth_flaw1_after01" src="https://github.com/user-attachments/assets/c70ea9c3-c421-46c4-a912-1c74f1277382" />
<img width="579" height="460" alt="Auth_flaw1_after02" src="https://github.com/user-attachments/assets/4b7d59aa-97c2-4080-ac5d-d28a403d9e78" />

###### E. Summary & Mitigation Result
In simple terms, the original login page allowed anyone to guess passwords indefinitely without any penalties. 

By chaining the entrance interception block (`authenticate`) together with an active backend tracking system (`loginProcess`), we built a comprehensive defense system that implements several security best practices:
* **Defense-in-Depth:** Placing verification checks across both the entry layer and the backend processing routine ensures that swift front-end automated bypass clicks cannot slide past our filters.
* **Server Resource Protection:** By blocking requests early when a lockout is active, our server halts processing instantly without checking passwords or opening database connections. This saves server processor power and stops automated password-guessing tools from exhausting CPU resources.
* **Safe Error Messaging:** The error interface safely alerts standard users exactly how much penalty time is remaining, while using neutral text that avoids leaking any sensitive backend architectural hints to an attacker.

<img width="1899" height="852" alt="Auth_flaw1_afterTest01" src="https://github.com/user-attachments/assets/97aff3d8-16a7-4a85-8351-dc066fb6e317" />
<img width="1870" height="865" alt="Auth_flaw1_afterTest02" src="https://github.com/user-attachments/assets/bb21ff22-3d53-4126-b3d9-0306425f545e" />

-----
#### 3. Vulnerability 2: Lack of Password Complexity Enforcements (CWE-521)
* **Vulnerability Name:** Weak Password Policy and Low Entropy Acceptance
* **Technical Identifier:** CWE-521 (Weak Password Requirements) / OWASP A07:2021-Identification and Authentication Failures
* **Risk Rating:** Medium

###### A. Description & Testing Proof
During registration gateway configuration testing, the signup endpoint was evaluated for password entropy and strength verification requirements. An account was successfully registered using the purely sequential numeric payload `12345678`. The backend validator processed and saved the record without issuing warnings or blocking submission, confirming that the system originally only checked for a basic minimum character length but entirely lacked rules enforcing alphanumeric diversity.

<img width="1624" height="845" alt="Auth_flaw2_bftTest01" src="https://github.com/user-attachments/assets/5ee229a5-c158-4586-b31c-2432de470fc9" />
<img width="1872" height="921" alt="Auth_flaw2_bfrTest02" src="https://github.com/user-attachments/assets/32aa851b-acde-414e-87af-2f917ed9c948" />

###### B. Security Risk Impact
Allowing users to establish weak, easily guessable, or purely numeric passwords drastically lowers the cryptographic entropy of authentication credentials. It leaves the application highly susceptible to offline dictionary attacks, credential stuffing, and automated guessing utilities, as threat actors can easily predict standard sequential patterns and compromise user accounts, leading to total account takeover.

###### C. Where the Code Was Updated
* **File Directory Path:** `app/Filament/Auth/Register.php`
* **Target Section:** Inside the field structural component array, specifically upgrading the validation chain layout inside the `getPasswordFormComponent()` method.

###### D. Source Code Modifications
In the original implementation, the system relied on generic validation settings. The mitigation replaces the default handler with an explicit rule validation chain that mandates multi-set character diversity to increase entry complexity.

**Before Code (Vulnerable):**

<img width="680" height="231" alt="Auth_flaw2_bfrCode" src="https://github.com/user-attachments/assets/2fe00012-45a1-4ece-a680-1d316bce3339" />

**After Code (Mitigated & Hardened):**

<img width="752" height="374" alt="Auth_flaw2_afterCode" src="https://github.com/user-attachments/assets/aab95a13-6112-4773-b9de-84579546ea68" />

###### E. Summary & Mitigation Result
In simple terms, the original signup form let anyone create accounts using incredibly weak, sequential passwords like "12345678", leaving user accounts highly vulnerable to automated guessing scripts.

By rewrote the validation rules to require true character diversity, we built a robust front-gate defense implementing key security principles:
* **Entropy Expansion:** Forcing a combination of uppercase letters, lowercase letters, numbers, and symbols vastly expands password complexity, making dictionary tools completely ineffective.
* **Proactive Input Shielding:** Weak credential patterns are blocked instantly at the registration phase, ensuring low-entropy hashes can never be written to the database.
* **Granular Validation UI:** The user interface now handles input verification dynamically, throwing targeted error prompts until all security criteria are fully satisfied.

<img width="1839" height="918" alt="Auth_flaw2_afterTest01" src="https://github.com/user-attachments/assets/a9261c3d-d64a-45e1-a3c5-2dbd5ce1399b" />
<img width="1700" height="892" alt="Auth_flaw2_afterTest02" src="https://github.com/user-attachments/assets/04ab29f9-77c6-4575-b1bc-3e0d1f573087" />
<img width="1690" height="859" alt="Auth_flaw2_afterTest03" src="https://github.com/user-attachments/assets/c0bb0b7e-8e59-41c5-8154-2829e8481c80" />



----
### **c. Authorization**
##### 1. Technical Framework Overview
The Invoice Management System implements Role-Based Access Control (RBAC) to ensure that users can only perform actions according to their assigned responsibilities.

Three user roles are available within the system:

* Superadmin – Full access to all system modules and records.
* Admin – Allowed to manage customers, invoices, recurring invoices, and payments.
* Customer – Allowed to view invoices, recurring invoices, and payment information, but prohibited from creating, modifying, or deleting records. Customers are also restricted from accessing the Customers management page.

The authorization mechanism was implemented in the resource files responsible for managing Customers, Invoices, Recurring Invoices, and Payments.


##### 2. Authorization Architecture
##### Before Enhancement:
All authenticated users could access and perform actions on system resources without proper role restrictions. This created a risk where unauthorized users could modify or delete business records.

<img width="1720" height="178" alt="Screenshot 2026-06-08 144123" src="https://github.com/user-attachments/assets/00c5278a-304a-45fe-a39b-ea29a4a9c8c6" />

<img width="1717" height="473" alt="Screenshot 2026-06-08 144134" src="https://github.com/user-attachments/assets/6ee10e42-1097-4cdd-89c6-05d71f09526c" />

<img width="1712" height="465" alt="Screenshot 2026-06-08 144142" src="https://github.com/user-attachments/assets/8d27803a-8d6f-4b72-b21f-95a26ef1fde8" />

<img width="1713" height="552" alt="Screenshot 2026-06-08 144157" src="https://github.com/user-attachments/assets/79ed2c16-0780-4306-8ea2-176e2c54bbd2" />

<img width="1707" height="477" alt="Screenshot 2026-06-08 144150" src="https://github.com/user-attachments/assets/cabb64d9-14d6-4c64-9e98-7d55169f3283" />


##### After Enhancement:
Role verification is performed before allowing access to system functions. The application checks the user's role before displaying pages or permitting operations such as creating, editing, or deleting records. Only authorized users are granted access to sensitive management functions.

<img width="1205" height="143" alt="Screenshot 2026-06-08 144350" src="https://github.com/user-attachments/assets/bd985063-d412-4985-8f34-8e460d9e6aff" />

<img width="1227" height="353" alt="Screenshot 2026-06-08 144357" src="https://github.com/user-attachments/assets/58568d00-7fa4-4c08-9411-ab2dbb418525" />

<img width="1195" height="310" alt="Screenshot 2026-06-08 144403" src="https://github.com/user-attachments/assets/70a6a48d-82c1-45c0-a784-94e0cbd4a5bd" />

<img width="1206" height="357" alt="Screenshot 2026-06-08 144409" src="https://github.com/user-attachments/assets/ad258547-07e1-4277-9d42-3dad8ba0f00a" />



##### 3. Vulnerability: Improper Authorization Control
* **Vulnerability Name:** Broken Access Control (CWE-862)
* **Risk Rating:** High
  
###### A. Description & Testing Proof
Initially, the application lacked sufficient authorization controls on several resources. Any authenticated user could potentially access management functions intended only for administrators.

For example, a Customer account could potentially access invoice management pages and modify invoice information if no authorization checks were applied. This violates the principle of least privilege and increases the risk of unauthorized modification of business records.


###### B. Security Risk Impact
Insufficient authorization controls may allow attackers or unauthorized users to:

* Modify invoice information
* Create unauthorized payment records
* Delete customer records
* Access sensitive business data
* Disrupt normal business operations

Such actions could compromise data integrity, financial records, and system reliability.


###### C. Where the Code Was Updated
 **File Directory Path:** 
* `app/Filament/Resources/CustomerResource.php`
* `app/Filament/Resources/InvoiceResource.php`
* `app/Filament/Resources/RecurringInvoiceResource.php`
* `app/Filament/Resources/PaymentResource.php`

**Target Schema Section:** Authorization methods added within each Resource class.

###### D. Source Code Modifications
**I. InvoiceResource.php** 

* Before Code (Vulnerable)

<img width="715" height="163" alt="Screenshot 2026-06-08 144811" src="https://github.com/user-attachments/assets/b8d566d6-bc46-47af-abcf-1a129d06df6b" />

* After Code (Mitigated and Hardened)
<img width="713" height="487" alt="Screenshot 2026-06-08 144834" src="https://github.com/user-attachments/assets/00a88f32-4440-43a5-84f2-700f83bbe70d" />
<img width="717" height="398" alt="Screenshot 2026-06-08 144931" src="https://github.com/user-attachments/assets/c66e42d2-3f7c-4ea7-9170-d5f46af7c836" />


**II. RecurringInvoiceResource.php** 

* Before Code (Vulnerable)
  
<img width="717" height="102" alt="Screenshot 2026-06-08 145012" src="https://github.com/user-attachments/assets/f7838672-8264-4a1b-8338-507a427ccae0" />

* After Code (Mitigated and Hardened)

<img width="717" height="502" alt="Screenshot 2026-06-08 145108" src="https://github.com/user-attachments/assets/7e502a09-9ff8-4aaf-bc7d-b2bb0e0ba399" />
<img width="680" height="626" alt="Screenshot 2026-06-08 150030" src="https://github.com/user-attachments/assets/923f4373-7b83-4980-a053-1640169e92ed" />



**III. PaymentResource.php** 
* Before Code (Vulnerable)
<img width="713" height="77" alt="Screenshot 2026-06-08 145233" src="https://github.com/user-attachments/assets/604aacd7-c0f7-4c0a-8f67-bd3b54c1cbae" />

* After Code (Mitigated and Hardened)
<img width="720" height="496" alt="Screenshot 2026-06-08 145307" src="https://github.com/user-attachments/assets/ed3e74a6-295d-4091-bafc-da077dce3922" />

<img width="713" height="576" alt="Screenshot 2026-06-08 145324" src="https://github.com/user-attachments/assets/9345644f-06ac-490a-8760-758d279b454d" />


**IV. CustomerResource.php** 

*Before Code (Vulnerable)
<img width="717" height="82" alt="Screenshot 2026-06-08 145357" src="https://github.com/user-attachments/assets/9c82ad48-81da-4441-82a2-caccc359e5a4" />


*After Code (Mitigated and Hardened)

<img width="715" height="538" alt="Screenshot 2026-06-08 145428" src="https://github.com/user-attachments/assets/c13cb011-1fae-4947-bea8-aed3755a53a3" />

<img width="720" height="266" alt="Screenshot 2026-06-08 145448" src="https://github.com/user-attachments/assets/41f13337-afb8-4001-8f01-e16e16ee5053" />


###### E. Summary & Mitigation Result
In summary, the original application lacked sufficient authorization controls, increasing the possibility of unauthorized access to administrative functions.

The enhancement introduces role-based access control by verifying user roles before allowing access to system operations. Customers are limited to viewing information, while administrative actions such as creating, editing, and deleting records are restricted to Admin and Superadmin accounts only.

This implementation follows the Principle of Least Privilege and significantly reduces the risk of unauthorized data manipulation within the system.


----
### **d. Database Security Principles**

##### 1. Technical Framework Overview

The system implements database-layer protection against SQL Injection attacks by utilizing **Laravel Eloquent ORM** as the primary database interaction framework.

Instead of manually constructing SQL statements, the application performs database operations through Eloquent query methods, which automatically generate parameterized queries and securely bind user-supplied values before execution.

The SQL Injection security review focused on invoice-related database operations, particularly functions responsible for retrieving and generating invoice records.

##### 2. Database Security Architecture

**Before Security Review** 
The application was already developed using Laravel Eloquent ORM through Filament Resource components and Eloquent Models. Database queries were executed through ORM methods rather than raw SQL statements.

**After Security Review** 
A security review was conducted to verify that database interactions continue to use Eloquent ORM and parameterized queries. Additional validation rules were also implemented at the application layer to ensure that only valid data reaches the database.

This approach provides multiple layers of protection:
* Eloquent ORM Query Layer 
* Database Query Binding Layer

Together, these controls help prevent SQL Injection attempts from reaching the database engine.


#### 3. Vulnerability: SQL Injection (CWE-89)

* **Vulnerability Name:** SQL Injection
* **Risk Rating:** High

###### A. Description & Testing Review

SQL Injection occurs when user-controlled input is inserted directly into SQL statements without proper parameterization or sanitization.

If raw SQL statements are used improperly, attackers may attempt to manipulate database queries by injecting malicious SQL payloads into application inputs.

During the security assessment, database-related functionality was reviewed to verify that queries were executed through Laravel Eloquent ORM rather than dynamically concatenated SQL strings.
The review confirmed that invoice-related database operations utilize Eloquent methods, which automatically apply parameterized query bindings.


###### B. Security Risk Impact

If SQL Injection vulnerabilities exist, attackers may be able to:

* Access confidential customer information
* Modify invoice records
* Delete business data
* Bypass application restrictions
* Execute unauthorized database commands

Such attacks may compromise the confidentiality, integrity, and availability of information stored within the system.

###### C. Where the Code Was Reviewed

* **File Directory Path:** `app/Models/Invoice.php`
* **Target Function:**
  `static function generate_invoice_number($tenant_id)
{
    return Invoice::where('team_id', $tenant_id)
        ->orderBy('id', 'desc')
        ->first()?->id + 1;
}
`

###### D. Source Code Review

The reviewed function retrieves invoice information using Eloquent ORM methods rather than constructing raw SQL queries.

**Current Secure Implementation**

<img width="936" height="145" alt="Screenshot 2026-06-08 150359" src="https://github.com/user-attachments/assets/99d01c67-cb0f-4f17-aed4-8493a82ce665" />


The query uses:

* `Invoice::where()`
* `orderBy()`
* `first()`

These Eloquent methods automatically generate parameterized SQL queries and securely bind the `$tenant_id` value to the database query.

As a result, user input cannot alter the structure of the SQL command.

No source code modification was required because secure database access practices were already implemented through Laravel Eloquent ORM and Filament's database abstraction layer.


###### E. Summary & Mitigation Result

In summary, the Invoice Management System already incorporates SQL Injection protection through Laravel Eloquent ORM, which automatically executes parameterized database queries.

Unlike traditional applications that manually concatenate user input into SQL statements, the current implementation relies on ORM-generated queries that securely separate SQL commands from user-supplied data.

The security enhancement activity therefore focused on reviewing and verifying that database operations continue to use Eloquent methods rather than unsafe raw SQL statements. This ensures that user-supplied values are securely bound to database queries and cannot be interpreted as executable SQL commands.

By relying on Eloquent ORM together with application-level input validation, the system significantly reduces the risk of SQL Injection attacks and strengthens the overall security of the database layer.


----
### **e. XSS and CSRF Prevention**
##### 1. Technical Framework Overview

The application implements multiple layers of protection against client-side attacks that target user sessions and browser execution environments. These controls are designed to prevent malicious scripts from being stored within invoice records and to protect authenticated users from unauthorized request forgery attacks.

The security framework focuses on:

- Input sanitization of user-generated content before database storage.
- Automatic Cross-Site Request Forgery (CSRF) token validation through Laravel middleware.
- Additional confirmation controls for sensitive actions involving record deletion.

##### 2. Vulnerability 1: Stored Cross-Site Scripting (CWE-79)
- Vulnerability Name: Improper Input Sanitization in Invoice Notes Field
- Technical Identifier: CWE-79 (Improper Neutralization of Input During Web Page Generation)
- Risk Rating: Medium / High
  
###### A. Description & Testing Proof

During security testing, the Invoice Notes field accepted arbitrary HTML and JavaScript content without sanitization. The following payload was entered into the notes field:

<script>alert('XSS-Test')</script>

The application successfully stored the payload inside the database. If rendered without sanitization, the script could execute whenever an administrator or user views the invoice.

###### B. Security Risk Impact

Stored XSS creates a persistent attack vector because malicious code remains stored in the database. Attackers may exploit this weakness to:

- Steal session cookies and authentication tokens.
- Hijack administrator accounts.
- Redirect users to malicious websites.
- Manipulate invoice content displayed to users.
  
###### C. Where the Code Was Updated
- File Directory Path: app/Filament/Resources/InvoiceResource.php
- Target Schema Section: Invoice Notes Field (Textarea::make('notes'))

###### D. Source Code Modifications

Before Code (Vulnerable):
<img width="633" height="91" alt="Screenshot 2026-06-08 040157" src="https://github.com/user-attachments/assets/0b4bc374-f61e-40dc-b8a3-95776b28b7e2" />

After Code (Mitigated & Hardened):
<img width="748" height="119" alt="Screenshot 2026-06-08 040255" src="https://github.com/user-attachments/assets/f05ca582-a94c-40c9-8553-9c03fbb2e015" />

###### E. Summary & Mitigation Result

The implemented solution removes all HTML and JavaScript tags before data is stored in the database. The strip_tags() function ensures that malicious scripts cannot persist within invoice records, significantly reducing the risk of Stored Cross-Site Scripting attacks.

##### 3. Vulnerability 2: Cross-Site Request Forgery (CSRF)
- Vulnerability Name: Unauthorized Request Execution Through Forged Requests
- Technical Identifier: CWE-352 (Cross-Site Request Forgery)
- Risk Rating: Medium

###### A. Description & Testing Proof

Cross-Site Request Forgery occurs when an authenticated user is tricked into submitting unintended requests to the web application. Attackers commonly use hidden forms or malicious links to force users into performing actions without their knowledge.

###### B. Security Risk Impact

Without CSRF protection, attackers could:

- Modify invoice records.
- Delete important business data.
- Create unauthorized transactions.
- Alter user account information.

###### C. Existing Protection Mechanism

The application utilizes Laravel's built-in CSRF protection middleware:

App\Http\Middleware\VerifyCsrfToken

This middleware automatically generates and validates unique CSRF tokens for all protected requests.

###### D. Additional Security Enhancement

Sensitive delete operations were configured to require user confirmation before execution.

Example:
<img width="499" height="63" alt="image" src="https://github.com/user-attachments/assets/076cf428-0362-49fa-8b5e-4e413838b5b4" />
<img width="759" height="174" alt="image" src="https://github.com/user-attachments/assets/175ed4c4-63bd-43ea-8a74-08d23c242ec7" />

###### E. Summary & Mitigation Result

Laravel automatically validates every incoming request against a valid CSRF token. Requests that fail validation are immediately rejected. Combined with confirmation prompts, this significantly reduces the likelihood of unauthorized or accidental destructive actions.

----
#### f. File Security Principles
##### 1. Technical Framework Overview

The application implements file upload security controls to ensure that uploaded files cannot be abused as a mechanism for malware distribution, server compromise, or unauthorized file execution.

The security framework focuses on:

- Restricting accepted file formats.
- Limiting file upload size.
- Preventing filename manipulation attacks.
- Protecting sensitive application files from public exposure.

##### 2. Vulnerability 1: Unrestricted File Upload
- Vulnerability Name: Improper Restriction of Uploaded File Types
- Technical Identifier: CWE-434 (Unrestricted Upload of File with Dangerous Type)
- Risk Rating: High

###### A. Description & Testing Proof

The application originally accepted uploaded files with minimal restrictions. Attackers could potentially attempt to upload malicious files disguised as legitimate content.

###### B. Security Risk Impact

If unrestricted file uploads are permitted, attackers may:

- Upload malicious executable files.
- Distribute malware through uploaded content.
- Attempt remote code execution attacks.
- Consume excessive server storage resources.

###### C. Where the Code Was Updated
- File Directory Path: app/Filament/Resources/ItemResource.php
- Target Schema Section: Product Image Upload Component

###### D. Source Code Modifications

Before Code (Vulnerable):
<img width="630" height="168" alt="Screenshot 2026-06-08 035833" src="https://github.com/user-attachments/assets/e24b1b52-d918-44c9-93f9-399a561c3e0d" />

After Code (Mitigated & Hardened):
<img width="659" height="308" alt="Screenshot 2026-06-08 035501" src="https://github.com/user-attachments/assets/7e9b533b-3c0d-4106-b613-d9b697808366" />


###### E. Summary & Mitigation Result

The enhanced implementation only accepts JPEG and PNG image formats, limits uploads to 2 MB, and automatically generates safe filenames. These controls significantly reduce the risk of malicious file uploads, storage abuse, and filename-based attacks.

##### 3. Protection of Sensitive Files and Directories

The application also protects critical system resources from unauthorized exposure. Sensitive files and directories include:

- .env
- vendor/
- storage/
- composer.json

These resources contain application secrets, framework dependencies, and internal configuration data that should never be directly accessible by end users.

By restricting access to these resources and limiting file upload capabilities, the overall security posture of the application is significantly improved.
