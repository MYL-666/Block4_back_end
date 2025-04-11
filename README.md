# Block4_back_end
# 🏫 School Management System - St. Alphonsus Primary

A full-stack web-based management system built for St. Alphonsus Primary School to manage students, teachers, parents, classes, books, salaries, and in-system communication.  
Developed using **PHP**, **MySQL**, **HTML/CSS/JavaScript**, and enhanced with modern UI libraries.

---
## 🔐 Demo Accounts (For Testing Purposes)

Use the following pre-defined login credentials to access the system with different roles:

| Role     | Email                        | Password |
|----------|------------------------------|----------|
| Admin    | myl666@gmail.com             | 123456   |
| Teacher  | emmalumi@gamil.com           | 222222   |
| Student  | johnsmith@gmail.com          | 111111   |
| Parent   | john@gamil.com               | 123123   |

> 💡 These accounts are for demo/testing only. Passwords are stored securely in the system backend.


## 🚨 CAUTION

> ⚠️ **IMPORTANT**:  
To insert or update books in the library, make sure to set up your **Google Books API key** first.  
This API is used in [`config/backValidation.php`](config/backValidation.php) to fetch book cover images from Google.  
If no API is provided, the system will raise an error. (See Figure 24 in documentation)

## 📌 Features

- 🧑‍🏫 Role-based login system (Student, Teacher, Parent, Admin)
- 🧒 Student/Parent/Teacher data management with validation
- 📚 Library system with Google API book cover integration
- 💬 In-app chat board (Message + Announcement by admin only)
- 📈 Data visualization via Chart.js
- 🎨 Responsive front-end using SweetAlert, Swiper.js, Iconfont
- ✅ Secure session handling, form validation, referential integrity
- 
---

## ⚙️ Technologies & Resources Used

1. [SweetAlert2](https://sweetalert2.github.io/) – modern alert popups  
2. [Chart.js](https://www.chartjs.org/docs/latest/samples/bar/stacked-groups.html) – for visualizing data  
3. [Swiper.js](https://swiperjs.com/demos#navigation) – carousel / slider on mobile  
4. [Iconfont.cn](https://www.iconfont.cn/) – free icon system  
5. [Pinterest](https://au.pinterest.com/) – front-end layout inspiration  
6. 🧠 PHP + MySQL + HTML/CSS + JavaScript  
7. [GitHub Actions](https://github.com/features/actions) – CI via php.yml

---
## 📁 Project Structure
project-root/ ├── api/ # Backend API handlers (insert, delete, fetch, session, etc.) │ ├── borrowBook.php │ ├── chartDrawing.php │ ├── checkBind.php │ ├── delete.php │ ├── Edit.php │ ├── fetch.delete.php │ ├── fetch.edit.php │ ├── fetch.insert.php │ ├── fetchUserInfo.php │ ├── getSessionUser.php │ ├── insert.php │ ├── loginV.php │ ├── logout.php │ ├── profile.doughnut.php │ ├── registerV.php │ └── returnBook.php │ ├── config/ # Database connection, backend logic, HTML helpers │ ├── backValidation.php # Google Books API handling │ ├── db.php # Database connection │ └── htmlContent.php │ ├── public/ │ ├── css/ # All front-end stylesheets │ │ ├── chat.css │ │ ├── header&footer.css │ │ ├── index.css │ │ ├── login.css │ │ ├── slidebar.css │ │ ├── table.form.css │ │ └── user.css │ │ │ ├── icons/ # Iconfont files │ │ ├── iconfont.css │ │ ├── iconfont.ttf │ │ ├── iconfont.woff │ │ └── iconfont.woff2 │ │ │ ├── img/ # Image assets │ │ ├── admin.jpg │ │ ├── badge.png │ │ ├── dividingLine.png │ │ ├── library.png │ │ ├── no-cover.png │ │ ├── parent.jpeg │ │ ├── student.jpg │ │ ├── teacher.jpeg │ │ └── teachers.jpg │ │ │ ├── js/ # JavaScript validation logic │ │ └── frontValidation.js │ ├── user/ │ └── common/ # Role-based user modules (HTML+PHP) │ ├── chat.php │ ├── classes.php │ ├── footer.php │ ├── head.php │ ├── header.php │ ├── index.php │ ├── library.php │ ├── login.php │ ├── parents.php │ ├── salaries.php │ ├── slidebar.php │ ├── students.php │ ├── table.php │ └── teachers.php


---





