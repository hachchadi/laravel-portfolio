# Database Seeders

This directory contains comprehensive database seeders for the Laravel Portfolio application. The seeders create sample data that demonstrates all features of the portfolio website.

## Available Seeders

### DatabaseSeeder
The main seeder that orchestrates all other seeders. Run this to populate the entire database with sample data.

```bash
php artisan db:seed
# or
php artisan migrate:fresh --seed
```

### UserSeeder
Creates sample user accounts including:
- **Main Developer Profile**: John Developer (john.developer@example.com) - The primary portfolio owner
- **Admin User**: Admin User (admin@portfolio.test) - For testing admin functionality  
- **Test User**: Test User (test@example.com) - For general testing

All users have default password: `password` (admin user uses `admin123`)

### ProjectSeeder
Creates 10 sample projects showcasing different technologies and project types:
- E-Commerce Platform
- Task Management System
- Real Estate Portal
- Learning Management System
- Restaurant Management System
- Social Media Analytics Dashboard
- Healthcare Management System
- Inventory Management API
- Event Management Platform
- Portfolio Website (This Site)

Each project includes:
- Detailed descriptions
- Technology stacks
- GitHub and demo URLs
- Multiple screenshot images
- Featured/non-featured status

### SkillSeeder
Creates comprehensive skills data organized by categories:
- **Backend**: PHP, Laravel, Node.js, Python, etc.
- **Frontend**: JavaScript, Vue.js, React, Livewire, etc.
- **Database**: MySQL, PostgreSQL, Redis, MongoDB, etc.
- **Tools**: Git, Docker, AWS, Linux, etc.
- **Testing**: PHPUnit, Pest, TDD, etc.
- **Architecture**: MVC, SOLID Principles, Design Patterns, etc.
- **Management**: Agile, Scrum, Team Leadership, etc.

### ContactMessageSeeder
Creates 8 realistic contact messages with different statuses:
- Unread messages (new inquiries)
- Read messages (viewed by admin)
- Replied messages (responded to)

Messages include professional inquiries about projects, collaborations, and job opportunities.

## Sample Images

The seeders automatically generate placeholder images:

### Avatar Images
- SVG-based avatar placeholders with initials
- Stored in `storage/app/public/avatars/`
- Colorful circular designs

### Project Images
- SVG-based project screenshot placeholders
- Stored in `storage/app/public/projects/{project_id}/`
- Different types: dashboard, mobile, admin, features, API
- Featured projects get hero images

## Image Placeholder Generator

The `ImagePlaceholderGenerator` helper class creates:
- Colorful SVG placeholders
- Proper dimensions and alt text
- Realistic project screenshot mockups
- Professional avatar designs

## Running Individual Seeders

```bash
# Run specific seeders
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ProjectSeeder
php artisan db:seed --class=SkillSeeder
php artisan db:seed --class=ContactMessageSeeder
```

## Testing

Comprehensive test suite available in `tests/Feature/Seeders/`:
- UserSeederTest
- ProjectSeederTest
- SkillSeederTest
- ContactMessageSeederTest
- DatabaseSeederTest

Run tests:
```bash
./vendor/bin/phpunit tests/Feature/Seeders/
```

## Data Integrity

The seeders ensure:
- Proper relationships between models
- Valid email addresses and URLs
- Realistic content for demonstration
- SEO-friendly data structure
- File system integration
- Idempotent operations (can run multiple times)

## Production Considerations

- The main developer profile uses placeholder data
- Update user credentials before production deployment
- Replace placeholder images with real screenshots
- Modify contact information and URLs
- Consider data privacy for contact messages

## File Structure

```
database/seeders/
├── DatabaseSeeder.php          # Main orchestrator
├── UserSeeder.php             # User accounts
├── ProjectSeeder.php          # Projects and images
├── SkillSeeder.php            # Technical skills
├── ContactMessageSeeder.php   # Sample messages
├── helpers/
│   └── ImagePlaceholderGenerator.php  # Image generation
└── README.md                  # This file
```

## Sample Data Overview

After running all seeders, you'll have:
- 3 user accounts (1 main developer, 1 admin, 1 test)
- 10 projects with 39 total images
- 67 skills across 7 categories
- 8 contact messages with various statuses
- All necessary files and directories created

This provides a complete, demo-ready portfolio website with realistic data for development and testing.