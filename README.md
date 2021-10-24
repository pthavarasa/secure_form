# secure_form

Secure online form in PHP

### Usage

- Bootstap custom style template for style
- CDN as jsdelivr.net for css
- logo from pixabay.com
- MySQL as database

### Database

Create database

```sql
CREATE DATABASE secure_form;
```

Create table

```sql
CREATE TABLE credential(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_email VARCHAR(20) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    creation timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modification timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```
