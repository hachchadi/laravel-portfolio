# Laravel Portfolio - Admin Panel User Guide

This guide will help you understand and use the admin panel to manage your portfolio content effectively.

## Table of Contents

- [Getting Started](#getting-started)
- [Dashboard Overview](#dashboard-overview)
- [Profile Management](#profile-management)
- [Project Management](#project-management)
- [Skills Management](#skills-management)
- [Contact Messages](#contact-messages)
- [Settings and Configuration](#settings-and-configuration)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

## Getting Started

### Accessing the Admin Panel

1. Navigate to `https://your-domain.com/admin`
2. Enter your admin credentials
3. Click "Login" to access the dashboard

### First Time Setup

After logging in for the first time:

1. **Update Your Profile**: Add your personal information, bio, and avatar
2. **Add Your Skills**: Create skill categories and add your technical skills
3. **Create Projects**: Add your portfolio projects with descriptions and images
4. **Test Contact Form**: Ensure the contact form is working properly

## Dashboard Overview

The admin dashboard provides a quick overview of your portfolio:

### Statistics Cards

- **Projects**: Total, published, draft, and featured project counts
- **Skills**: Total skills and breakdown by category
- **Messages**: Contact form submissions (unread, read, replied)
- **Recent Activity**: Latest contact messages and system updates

### Quick Actions

- **Add New Project**: Quickly create a new project
- **Manage Skills**: Jump to skills management
- **View Messages**: Check new contact form submissions
- **Update Profile**: Edit your personal information

## Profile Management

### Personal Information

#### Basic Details
- **Name**: Your full name as displayed on the portfolio
- **Title**: Your professional title (e.g., "Senior Laravel Developer")
- **Bio**: A brief description of your background and experience
- **Location**: Your current location or "Remote"

#### Contact Information
- **Email**: Your professional email address
- **Phone**: Your contact phone number (optional)
- **LinkedIn URL**: Link to your LinkedIn profile
- **GitHub URL**: Link to your GitHub profile

#### Avatar Management

1. Click "Choose File" to select an image
2. Supported formats: JPG, PNG, GIF
3. Maximum size: 2MB
4. Recommended dimensions: 400x400px
5. The image will be automatically resized and optimized

**Tips for Avatar Images:**
- Use a professional headshot
- Ensure good lighting and clear image quality
- Square aspect ratio works best
- Avoid busy backgrounds

### Social Media Links

Add links to your professional social media profiles:

- **LinkedIn**: Professional networking
- **GitHub**: Code repositories
- **Twitter**: Professional updates (optional)
- **Portfolio Website**: Additional portfolio site (optional)

## Project Management

### Creating a New Project

1. Click "Add New Project" from the dashboard or projects page
2. Fill in the required information:

#### Basic Information
- **Title**: Project name (required)
- **Description**: Detailed project description (required)
- **Status**: Draft or Published
- **Featured**: Mark as featured project (appears prominently)

#### Technical Details
- **Technologies**: Add technologies used (Laravel, Vue.js, etc.)
- **GitHub URL**: Link to source code repository
- **Demo URL**: Link to live demo or deployed application

#### Project Images

1. Click "Add Images" to upload project screenshots
2. Supported formats: JPG, PNG, GIF, WebP
3. Maximum size: 5MB per image
4. Maximum images: 10 per project
5. Drag and drop to reorder images

**Image Guidelines:**
- First image becomes the project thumbnail
- Use high-quality screenshots
- Show different aspects of the project
- Include mobile and desktop views if applicable

### Editing Projects

1. Navigate to "Projects" in the admin menu
2. Click "Edit" on the project you want to modify
3. Make your changes and click "Save"

### Project Status Management

- **Draft**: Project is not visible on the public portfolio
- **Published**: Project is visible to visitors
- **Featured**: Project appears in the featured projects section

### Organizing Projects

- Use the drag-and-drop interface to reorder projects
- Featured projects appear first in the portfolio
- Consider showcasing your best and most recent work

## Skills Management

### Adding Skills

1. Navigate to "Skills" in the admin menu
2. Click "Add New Skill"
3. Fill in the skill information:

#### Skill Details
- **Name**: Skill or technology name (e.g., "Laravel", "JavaScript")
- **Category**: Backend, Frontend, Database, Tools, or Other
- **Proficiency**: Skill level from 1-100 (displayed as percentage)

### Skill Categories

Organize your skills into these categories:

- **Backend**: Server-side technologies (PHP, Laravel, Node.js)
- **Frontend**: Client-side technologies (JavaScript, Vue.js, React)
- **Database**: Database technologies (MySQL, PostgreSQL, Redis)
- **Tools**: Development tools (Git, Docker, AWS)
- **Other**: Additional skills that don't fit other categories

### Bulk Import Skills

For adding multiple skills at once:

1. Click "Bulk Import" in the skills section
2. Use the JSON format:

```json
[
    {
        "name": "PHP",
        "category": "Backend",
        "proficiency": 95
    },
    {
        "name": "JavaScript",
        "category": "Frontend",
        "proficiency": 85
    }
]
```

3. Click "Import" to add all skills

### Managing Skill Proficiency

**Proficiency Guidelines:**
- **90-100%**: Expert level, can teach others
- **80-89%**: Advanced, comfortable with complex tasks
- **70-79%**: Intermediate, can work independently
- **60-69%**: Basic, can complete simple tasks
- **Below 60%**: Learning, requires guidance

## Contact Messages

### Viewing Messages

1. Navigate to "Messages" in the admin menu
2. Messages are organized by status:
   - **Unread**: New messages requiring attention
   - **Read**: Messages you've viewed
   - **Replied**: Messages you've responded to

### Message Management

#### Reading Messages
1. Click on a message to view full details
2. Message automatically marks as "Read"
3. View sender information, subject, and full message

#### Responding to Messages
1. Click "Reply" on a message
2. This opens your default email client
3. Mark message as "Replied" after sending response

#### Message Actions
- **Mark as Read**: Change status to read
- **Mark as Replied**: Indicate you've responded
- **Delete**: Remove message (use carefully)

### Message Notifications

- Email notifications are sent for new messages
- Check your email regularly for new inquiries
- Respond promptly to maintain professional image

## Settings and Configuration

### Email Configuration

Ensure your email settings are properly configured:

1. Check `.env` file for mail settings
2. Test email functionality with a test message
3. Verify spam folder settings

### SEO Settings

Optimize your portfolio for search engines:

- **Meta Description**: Brief description of your portfolio
- **Keywords**: Relevant professional keywords
- **Open Graph**: Social media sharing optimization

### Performance Settings

- **Image Optimization**: Automatically enabled
- **Caching**: Configured for optimal performance
- **CDN**: Configure if using external CDN

## Best Practices

### Content Guidelines

#### Profile Information
- Keep bio concise but informative (2-3 sentences)
- Use professional language
- Highlight key achievements and experience
- Update regularly as your career progresses

#### Project Descriptions
- Start with a brief overview
- Explain the problem solved
- Highlight technologies used
- Include challenges overcome
- Mention results or impact

#### Skill Management
- Be honest about proficiency levels
- Focus on relevant professional skills
- Update skills as you learn new technologies
- Remove outdated or irrelevant skills

### Image Optimization

#### Avatar Images
- Use professional headshots
- Ensure good lighting and quality
- Keep file sizes reasonable (under 500KB)
- Update periodically to stay current

#### Project Images
- Use high-quality screenshots
- Show different aspects of projects
- Include both desktop and mobile views
- Optimize file sizes for web

### Regular Maintenance

#### Monthly Tasks
- Review and update project information
- Check for new contact messages
- Update skills and proficiency levels
- Review and update bio if needed

#### Quarterly Tasks
- Add new projects
- Remove outdated projects
- Update avatar if needed
- Review overall portfolio presentation

### Security Best Practices

- Use strong, unique passwords
- Log out when finished
- Regularly update the application
- Monitor for suspicious activity
- Keep backups of important content

## Troubleshooting

### Common Issues

#### Cannot Upload Images
**Possible Causes:**
- File too large (check size limits)
- Unsupported file format
- Server storage issues

**Solutions:**
1. Resize images before uploading
2. Convert to supported format (JPG, PNG)
3. Contact administrator if storage issues persist

#### Email Not Sending
**Possible Causes:**
- Incorrect SMTP settings
- Email provider blocking
- Server firewall issues

**Solutions:**
1. Verify email configuration in settings
2. Check spam folders
3. Test with different email provider
4. Contact hosting provider

#### Changes Not Appearing
**Possible Causes:**
- Browser cache
- Server cache
- CDN cache

**Solutions:**
1. Clear browser cache (Ctrl+F5)
2. Wait a few minutes for cache to clear
3. Try incognito/private browsing mode

#### Login Issues
**Possible Causes:**
- Incorrect credentials
- Account locked
- Session expired

**Solutions:**
1. Verify username and password
2. Use password reset if available
3. Clear browser cookies
4. Contact administrator

### Getting Help

#### Self-Help Resources
1. Check this user guide
2. Review error messages carefully
3. Try basic troubleshooting steps
4. Check browser console for errors

#### Contacting Support
If you need additional help:
- Email: your-email@example.com
- Include detailed description of the issue
- Provide screenshots if helpful
- Mention browser and operating system

### Performance Tips

#### Optimizing Load Times
- Compress images before uploading
- Limit number of projects displayed
- Use appropriate image formats
- Keep descriptions concise

#### Mobile Optimization
- Test portfolio on mobile devices
- Ensure images display properly
- Check navigation functionality
- Verify contact form works on mobile

## Advanced Features

### Custom CSS (If Available)
- Add custom styling to match your brand
- Use CSS variables for consistent theming
- Test changes on different devices
- Keep custom code minimal and clean

### Analytics Integration
- Monitor portfolio traffic
- Track visitor engagement
- Analyze popular projects
- Use data to improve content

### Backup and Export
- Regularly backup your content
- Export project data if needed
- Keep local copies of images
- Document important settings

## Conclusion

The admin panel provides powerful tools to manage your professional portfolio effectively. Regular updates and maintenance will ensure your portfolio remains current and engaging for potential clients and employers.

Remember to:
- Keep content fresh and updated
- Respond promptly to contact messages
- Maintain professional presentation
- Monitor performance and analytics
- Backup important content regularly

For additional support or questions, don't hesitate to reach out to the development team.