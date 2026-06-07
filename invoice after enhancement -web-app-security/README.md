
---

# 🎓 INFO 3305 — Section 02  
### 🌐 Web Application Development  
**👨‍🏫 Instructor:** Mohd Khairul Azmi bin Hassan  
**👥 Group:** C  
**📌 Project Title:** *Invoice Sensei*

---

## 👩‍💻 Group Members

| 🧑‍🤝‍🧑 Name                                           | 🆔 Student ID |
|------------------------------------------------------|---------------|
| Nabilah binti Ahmad Nordin                           | 2225498       |
| Alin Farhain binti Abdul Rajat @ Abdul Razak         | 2224210       |
| Amysha Qistina binti Amerolazuam                     | 2225998       |
| Fadhilah binti Abd Mun'em                            | 2313560       |
| Amna Syuhada binti Mohamad Aminudin                  | 2311986       |

---

## Introduction

For today's fast-paced business environment, freelancers, small businesses, and service providers require efficient instruments for managing billing and payment processes. To this end, we recommend designing a Simple Invoice System, a built-in web-based application that can simplify the creation, sending, and following of invoices.

The system will provide users with fundamental invoicing features through a simplified interface. Users will be able to securely log in or sign up, manage their clients, create and edit invoices, and track the payment status. Compared to more advanced platforms, this app is confined to the basics of invoicing and is ideal for users who need a basic but reliable billing facility.

This project borrows ideas from open-source options like Invoice Ninja but limit the scope to keep things simple, easy to use, and efficient. The proposed system will serve as a working solution for individuals and small organizations that want to improve the accuracy of billing and save their time.

##  Objectives

The main objectives of this web application is to:

- ✅ **User-Friendly Interface**  
  Provide an intuitive platform for managing invoices and payments made by clients.

- ⚙️ **Efficiency through Automation**  
  Increase efficiency by automating billing tasks and minimizing the risk of manual errors.

- 🧾 **Empower Small Organizations**  
  Equip small businesses with tools to effectively create, track, and manage invoices.

- 📚 **Centralized Records**  
  Centralize customer and payment records in one place for easy tracking and reporting.

- 🌱 **Sustainable Invoicing**  
  Promote sustainability by reducing paper usage through digital invoicing solutions.


## Core Features

| **Feature**       | **Description** |
|-------------------|-----------------|
| **Login / Signup** | Secure user authentication and registration. |
| **Dashboard** | Quick view of total invoices, received payments, and outstanding balances. Includes recent activity feed or invoice status indicators (paid, unpaid, overdue). |
| **Clients** | Add, edit, and delete client records. View client-specific invoice/payment history. Store contact information and business details. |
| **Invoices** | Create new invoices with invoice number, date, due date, and itemized services. Edit or delete existing invoices. Includes status indicators: Draft, Sent, Paid, Overdue. Option to download or email PDF invoices. |
| **Payments** | Record full or partial payments against specific invoices. View list of all payments made and pending. Auto-adjust invoice status based on payment received. |
| **Profile / Settings** | Edit user profile information. Manage basic account settings. Logout functionality. |

## Additional Functionality

| **Feature**                | **Description** |
|----------------------------|-----------------|
| **Custom Branding**        | Let users upload their company logo and customize invoice colors and footer messages. |
| **Multiple Currency Support** | Useful for freelancers or businesses working with international clients. |
| **Recurring Invoices**     | Send invoices automatically on a regular schedule. |
| **Display Mode**           | Improve readability for some individuals with visual impairments. |


## ERD Diagram
![webapp drawio (2)](https://github.com/user-attachments/assets/cca9d2bf-b5ff-429d-99b0-7fc984f9a06f)


This ERD represents the data structure of a Simple Invoice System designed for freelancers and small businesses. The system includes six main entities: User, Client, Invoice, InvoiceItem, Payment, and RecurringInvoice. Each user can manage multiple clients and generate invoices for them. Invoices can have multiple items and receive multiple payments. Recurring invoices allow users to automate billing on a schedule. The relationships are primarily one-to-many, ensuring a clear and efficient data model.


## Sequence Diagram
![Sequence_DiagramV3](https://github.com/user-attachments/assets/206cd3de-7a9e-4a54-8f90-78aea90918e7)

Summary:

Navigation: Active tab is Payment with access to Dashboard, Clients, Invoices.

Actions: Add Payment, Import, filter by status, search.

Table Columns: Status, Number, Client, Amount, Invoice Number, Date, Type, Transaction Ref.

Functions: View, filter, and manage payments with pagination and bulk actions.

## Mockup Prototype

1. Sign up

![2  Sign Up](https://github.com/user-attachments/assets/6f5ca372-b40a-420b-9aff-27d4db348095)


2. Login

![1  Login](https://github.com/user-attachments/assets/6ccf1725-f148-4159-a9e8-119c2bd84018)


3. Profile 

![6 Profile](https://github.com/user-attachments/assets/ecf88bf5-b732-46b6-859b-cbd494a494b7)


4. Dashboard

![4. Dashboard](https://raw.githubusercontent.com/amnasyuhada/invoice-web-app/main/4.Dashboard.png)

5. Client

![5  Clients](https://raw.githubusercontent.com/amnasyuhada/invoice-web-app/main/prototypeClients.png)

## 6.1. Invoice

![8  Invoice](https://github.com/user-attachments/assets/47ccfb8f-6e0f-43da-9729-04867ef88893)


## 6.2. Recurring Invoice

![9  Recurring Invoices](https://github.com/user-attachments/assets/6998a5b0-577c-4e04-9a44-990119c0327c)


## 7. Payment
![7 Payment](https://github.com/user-attachments/assets/e00c1fae-3d37-4e57-ba66-a52725af7b50)


## 6. Link youtube : https://youtube.com/shorts/b37x3u3bXmc?feature=shared

## Captured Screens with Descriptions
1. in .env file change the http/localhost to 127.0.0.1.8000 and run php artisan storage:link
2. Enabling Extension=intl in php.ini
   To allow image manipulation and file handling (like resizing logos), the intl extension in PHP must be enabled.
3. Enabling ZIP Extension in php.ini
   To allow image manipulation and file handling (like resizing logos), the zip extension in PHP must be enabled.
4. Installing Intervention Image (v3) via Composer
   To allow image processing like resizing, I used the Intervention Image package.
   composer require intervention/image

## 1. Login : 
Allows authorized users to securely access the admin panel by entering their credentials. It serves as the entry point for administrators to manage the system, ensuring only authenticated users can access backend features and sensitive data.

![image](https://github.com/user-attachments/assets/d1bfbf6f-f710-4ca6-9cee-b959c7ea53d9)

## 2. Sign Up : 
Sign Up lets new users create an account by filling out a registration form (e.g., name, email, password, etc.), allowing access to your app.

![image](https://github.com/user-attachments/assets/35b4a7bb-1778-486d-aeb3-9bebc1319987)

## 3. User Profile : 
The User Profile Page serves as a centralized interface where authenticated users can view and manage their personal information such as name, email, and profile photo. It enables users to update their details, change their password, and optionally upload a profile picture or adjust personal preferences. This page plays a crucial role in providing self-service account management, making it especially valuable in applications that involve user accounts, team memberships, or administrative roles.

![UserProfile](https://github.com/user-attachments/assets/d5d99cc4-dac8-4cef-b37f-7ed38acb7237)
![Screenshot 2025-06-12 034307](https://github.com/user-attachments/assets/0ba03f91-c410-4f1d-9290-839c33d752d3)

## 4. Dashboard : 
Provides summarized view of invoice management. It includes total revenue, invoices sent, 
pending invoices and overdue invoices. This page also displays the payment trends line graph 
and several tables showing recent invoices, recent payment and recurring invoices.

![1. Dashboard](https://raw.githubusercontent.com/amnasyuhada/invoice-web-app/main/Dashboard(Web).png)


## 5. Customer :
Customer page of the Invoice Sensei system, displaying a searchable and sortable table of customer records. Key details such as customer name, email, balance, amount paid, last login, date created, date created, updated and deleted are listed. The interface includes options to add a new customer or perform additional actions.

![5  Clients](https://raw.githubusercontent.com/amnasyuhada/invoice-web-app/main/Customer.png)
![image](https://github.com/user-attachments/assets/25c5b2c9-5dfd-4d33-91a0-e92e687ab39b)
![image](https://github.com/user-attachments/assets/24b60872-e5f8-4211-8db9-782286c09712)
![image](https://github.com/user-attachments/assets/177303e6-2df9-44aa-96fc-d06f4587222f)
![image](https://github.com/user-attachments/assets/3ca14fe1-98a9-462d-b736-84fc1761b1c7)


## 6.1 Invoice :
The system provides comprehensive features for managing both items and invoices. In the **Invoice page**, users can create and edit invoices by selecting customers, adding items, and adjusting quantities. The page offers sorting options for invoice details, such as the status, date, invoice number, and timestamps for creation and updates. This allows users to easily track and manage invoice history. In the **Item management page**, users can create new items by adding product details like name, description, price, and weight. It also includes the option to upload product images and attach files. Users can review the item list and sort it by product name or other information, making it easy to navigate and find specific products. These features, combined with sorting and review options, help streamline the management of inventory and invoices.

![image](https://github.com/user-attachments/assets/d2589231-1474-4aaf-82ee-3085a5158c12)
![image](https://github.com/user-attachments/assets/5917accc-f22d-4e2e-9ccf-e2a30212f608)
![image](https://github.com/user-attachments/assets/b0f3d996-e659-49e3-aa3d-7123bfe90459)
![image](https://github.com/user-attachments/assets/aa938215-39f0-40ab-914c-bfd6a1ce1b9b)
![image](https://github.com/user-attachments/assets/65b186ca-2f11-4516-9d84-8cbf080e7e21)
![image](https://github.com/user-attachments/assets/3ea620a5-1147-4356-9477-38eb81851faa)
![image](https://github.com/user-attachments/assets/c93c908d-3584-4c34-8a57-af51e4284112)
![image](https://github.com/user-attachments/assets/0698796c-bb76-4e39-9cfb-831497052ff3)
![image](https://github.com/user-attachments/assets/15ca6e7d-0556-45d6-b141-3356fd1b9189)
![image](https://github.com/user-attachments/assets/886e09a5-be37-4214-9f73-470b82588424)
![image](https://github.com/user-attachments/assets/a86c67ae-763f-4491-ab26-19e2e45d56b6)
![image](https://github.com/user-attachments/assets/049996df-a560-40a8-9ed4-685acdc48aba)
![image](https://github.com/user-attachments/assets/fc99b912-1230-4c1c-9a30-867168ea55c1)
![image](https://github.com/user-attachments/assets/0d194617-a646-4f27-9090-f9e0605cea7c)


## 6.2 Recurring Invoice :
The RecurringInvoice feature automates the generation of invoices on a regular schedule (e.g., weekly, monthly). Instead of manually creating the same invoice repeatedly, users can set up a template that auto-generates invoices at set intervals. More importantly highlights the status such as sent, refund, overdue, or pending. Customer also can check their total amount referring to their frequency and limit.

![image](https://github.com/user-attachments/assets/5450cba3-f92b-4c6a-a284-361bcce87fe2)
![image](https://github.com/user-attachments/assets/8cda46b8-82b3-4260-9438-c5ed2f0b7516)
![image](https://github.com/user-attachments/assets/87587c22-15f4-42d7-bcde-e1fdcbbc2db7)
![image](https://github.com/user-attachments/assets/14c0e3ff-ad8c-4492-9ace-bc72aaba190d)
![image](https://github.com/user-attachments/assets/9aa7377f-b2a2-494e-aad2-7d580351209f)
![image](https://github.com/user-attachments/assets/bf57b3b4-41a5-4cb8-aab1-e5723867609f)
![image](https://github.com/user-attachments/assets/5e07f6f5-0d96-414c-95f2-42552b4c73e0)
![image](https://github.com/user-attachments/assets/783cc71b-5c0f-412e-a743-e4fc4d15c0bf)
![image](https://github.com/user-attachments/assets/d6d25efd-94a5-4744-963d-725ad2dffb6a)
![image](https://github.com/user-attachments/assets/8c0095d8-bf4c-4a1e-b4f0-647c863e2d75)
![image](https://github.com/user-attachments/assets/c6e5f84c-1190-4b6b-9cc8-6714198cecb4)
![image](https://github.com/user-attachments/assets/4aad37ec-46e0-48f2-b1d8-beb3af84b800)
![image](https://github.com/user-attachments/assets/166df06e-6714-4353-8e83-9ed372cf1c14)
![image](https://github.com/user-attachments/assets/9ebedbbf-4346-4f44-bec9-d97d60a940af)


## 7. Payment:
The payment page allows users to record new customer payments by entering details such as the date, payment number, amount, payment method, and notes. It ensures accurate tracking of transactions for each customer in the system. 

![image](https://github.com/user-attachments/assets/7ba81baa-6f74-40c5-938b-f3297ab47265)
![image](https://github.com/user-attachments/assets/8a80a395-ac6a-4f17-824c-39a378c7a4a6)
![image](https://github.com/user-attachments/assets/4f1a0225-7ce4-44e5-998d-a5851a98a75f)
![image](https://github.com/user-attachments/assets/9dc185fa-d8ea-4ad1-85b9-e8f1f123f661)
---

## 7. Additional (Maintainance & Display Mode (Bright Mode & Dark Mode) :

![image](https://github.com/user-attachments/assets/3abd4bbc-12c4-47e9-a3b2-75e243993f4a)
![image](https://github.com/user-attachments/assets/4af1357a-b10c-4f40-88e4-9bde43086a07)
![image](https://github.com/user-attachments/assets/28d93dde-a960-4bd2-bd0d-ce2a12f7892e)
![image](https://github.com/user-attachments/assets/6432104d-d347-4366-9731-3f1f061bbf5e)
![image](https://github.com/user-attachments/assets/c49ff418-a111-4bb7-a01c-e5e7bdf70720)


# Challenge/difficulties to develop the application

### **1. Implementing User Authentication and Role Management**

* Setting up secure login and registration using Filament’s authentication.
* Ensuring access control per user so one user cannot view another user’s invoices, clients, or payments.
* Managing password reset and email verification features securely.
  

### **2. Multi-Tenancy / Team-based Data Separation**

* Implementing team-specific data access (i.e. each user or team sees only their own clients/invoices).
* Making sure `team_id` is linked correctly in all tables (e.g. invoices, payments, clients).
* Filtering database queries so that users don’t accidentally see or edit others’ data.
  

### **3. Complex Database Relationships**

* Designing and maintaining relationships between Users, Clients, Invoices, Invoice Items, Payments, and Recurring Invoices.
* Ensuring data integrity (e.g. a payment must belong to a valid invoice and client).
* Handling cascading actions like deleting a client and ensuring related invoices/payments are handled correctly.
* Creating fakers for all data.
  
### **4. Invoice Logic and Automation**

* Automatically calculating totals and taxes from invoice items.
* Updating invoice status (e.g. from “Sent” to “Paid” or “Overdue”) based on payment status.
* Handling partial payments and adjusting balances dynamically.
* Adding items for the invoice type for user to kick in the items data for easy checking. 
  

### **5. Recurring Invoices Functionality**

* Setting up a system to schedule and auto-generate invoices at regular intervals.
* Ensuring proper linking between recurring templates and the actual generated invoices.
* Updating invoice status (e.g. from “Sent” to “Paid” or “Overdue”) based on payment status.
* Handling different recurrence intervals (weekly, monthly, etc.).
* Adding items for the invoice type for user to kick in the items data for easy checking. 


### **6. Currency and Branding Customization**

* Allowing users to select different currencies and ensure amounts are formatted correctly.
* Enabling logo uploads and dynamic invoice color customization.
* Ensuring branding settings apply consistently across invoice PDFs and UI.
  

### **7. Dashboard & Data Visualization**

* Displaying dynamic charts and KPIs (e.g. total revenue, invoice count).
* Aggregating and filtering invoice/payment data efficiently.
* Ensuring dashboard loads fast despite large datasets.
  

### **8. Responsive UI and Accessibility**

* Designing interfaces that work well on both desktop and mobile.
* Implementing a display mode for visual impairments.
* Customizing Filament’s default look to match your branding needs.
  

### **9. Testing and Debugging**

* Testing complex form submissions with Livewire and Filament.
* Debugging unexpected issues like validation failures, form not updating, or relationship errors.
* Testing soft delete and restore functionality for customers and invoices.
  

### **10. PDF Generation and File Downloads**

* Implementing download/export of invoice as PDF with proper layout.
* Handling file formatting issues across browsers.
* Ensuring downloaded files include correct branding and data.
  

### **11. Search, Filter, and Pagination**

* Implementing advanced search and filter features for clients, invoices, and payments.
* Handling filtered queries efficiently without breaking pagination or table actions.
* Making sure search works across all related fields (e.g. client name in invoice table).

