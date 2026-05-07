# ISMO-SkillSwap

## Project Description

ISMO-SkillSwap is a student skill-sharing platform that connects students to exchange knowledge and learn from one another.

## Project Structure

```
project/
├── index.php                 # Home page
├── about.php                 # About page
├── contact.php               # Contact page
│
├── assets/                   # Static assets
│   ├── css/                  # Stylesheets
│   │   ├── style.css         # Main styles
│   │   ├── layout.css        # Layout styles
│   │   ├── components.css    # Component styles
│   │   └── pages/            # Page-specific styles
│   │       ├── home.css
│   │       └── contact.css
│   ├── js/                   # JavaScript files
│   │   ├── main.js           # Main functionality
│   │   ├── utils.js          # Utility functions
│   │   └── pages/            # Page-specific scripts
│   │       ├── home.js
│   │       └── dashboard.js
│   ├── images/               # Images and media
│   └── fonts/                # Custom fonts
│
├── includes/                 # Reusable templates
│   ├── header.php            # HTML head and navbar
│   ├── navbar.php            # Navigation bar
│   ├── footer.php            # Footer
│   └── config.php            # Configuration
│
├── database/                 # Database files
│   ├── connection.php        # Database connection class
│   └── queries/              # SQL queries
│
├── auth/                     # Authentication
│   ├── login.php             # Login page
│   ├── register.php          # Registration page
│   └── logout.php            # Logout handler
│
├── dashboard/                # User dashboard
│   ├── index.php             # Dashboard home
│   └── settings.php          # User settings
│
└── api/                      # API endpoints
    └── users.php             # Users API
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled

## Installation

1. Clone or download the project
2. Create a MySQL database named `skill_swap`
3. Update database credentials in `includes/config.php`
4. Run the SQL scripts in `database/queries/` to create tables
5. Access the application through your web server

## Configuration

Edit `includes/config.php` to configure:

- Database credentials
- Site URL
- Environment settings

## Features

- User authentication (Login/Register)
- User dashboard
- Skill management
- Help request system
- Mentor matching
- User API
- Contact form

## Usage

- Visit the home page: `index.php`
- Create an account: `auth/register.php`
- Login: `auth/login.php`
- Access dashboard: `dashboard/index.php`

## API Documentation

### Users Endpoint: `/api/users.php`

**GET** - Get users

- `GET /api/users.php` - Get all users
- `GET /api/users.php?id=1` - Get specific user

**POST** - Create user

```json
{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "password123",
  "first_name": "John",
  "last_name": "Doe"
}
```

## Contributing

Feel free to fork and submit pull requests!

## License

MIT License

## Support

For support, please use the contact form: `contact.php`
