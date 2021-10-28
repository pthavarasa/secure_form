# secure_form

Secure online form in PHP

### Usage

- Bootstap custom style template for style
- CDN as jsdelivr.net for css
- logo from pixabay.com
- MySQL as database

### Test

```
Email : test@email.com
Password : ET27:h<m-V/L:
```

- Strong password required!

### Database

Create database

```sql
CREATE DATABASE secure_form;
```

Create table

```sql
CREATE TABLE credential(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_email VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    creation timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modification timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### version

```
Apache : 2.4.27
PHP : 5.6.31
MySQL : 5.7.19
PhpMyAdmin : 4.7.4
```

Note : i used obsolete version, DO NOT DO THAT!!!

### Security Measurement I Took for prevent :

XSS (Cross Site Scripting)

- sanitizing user input

SQL Injection

- prepared SQL query
- specify data type

Cross Site Request Forgery (CSRF)

- usage of token to process form

Man in the middle attack

- HTTPS
- i use always https://certbot.eff.org/ (FREE and Auto renewal)
- Note : Verify your SSL configuration

DDos

- i use CloudFlare (they provide free ssl certificat too)

Some common advice :

- use prepared SQL statements
- never trust external Data
- update your PHP version regularly
- always validate user input
- limit directory access
- recapcha & mail verification to avoid spam
- if you use md5 don't forget to add suger

### if Feedback : Always_WELCOME
