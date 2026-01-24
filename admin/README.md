# Kalpoink Admin CRM
# Comprehensive Admin CRM System for managing leads, content, projects, and more.

## Features

- **Dashboard**: Overview of leads, blog posts, projects with statistics
- **Leads Management**: Track and manage contact form submissions
- **Blog Management**: Create, edit, and publish blog posts
- **Projects/Portfolio**: Manage case studies and portfolio items
- **Services Management**: Maintain your service offerings
- **Team Management**: Manage team member profiles
- **User Management**: Multiple admin users with role-based access
- **Activity Logging**: Track all admin actions
- **Settings**: Configure site settings, contact info, social links

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.3+
- Apache with mod_rewrite (XAMPP recommended)

### Steps

1. Make sure XAMPP/Apache and MySQL are running

2. Navigate to the installer:
   ```
   http://localhost/kalpoink/admin/install/
   ```

3. Click "Install Database" to create the database and tables

4. Login with default credentials:
   - **Username**: admin
   - **Password**: admin123

5. **Important**: Delete the `/admin/install/` folder after installation

6. Change the default password from Profile settings

## Access Admin Panel

```
http://localhost/kalpoink/admin/
```

## User Roles

| Role | Permissions |
|------|-------------|
| Admin | Full access to all features |
| Editor | Manage content (blogs, projects, services, team) |
| Viewer | Read-only dashboard access |

## Directory Structure

```
admin/
├── assets/
│   ├── css/admin.css
│   └── js/admin.js
├── config/
│   ├── auth.php
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── install/
│   ├── index.php
│   └── setup.sql
├── index.php (Dashboard)
├── login.php
├── logout.php
├── leads.php
├── blogs.php
├── projects.php
├── services.php
├── team.php
├── users.php
├── settings.php
├── activity.php
└── profile.php
```

## Security Features

- Password hashing with bcrypt
- CSRF token protection
- SQL injection prevention with prepared statements
- XSS protection with output escaping
- Session-based authentication
- Activity logging

## Configuration

Edit `admin/config/database.php` to change database settings:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'kalpoink_crm');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## Contact Form Integration

The contact form on the website automatically saves submissions to the CRM as leads once the database is installed.

## Support

For issues or questions, contact: kalpoinc@gmail.com
