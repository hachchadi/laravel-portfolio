# Laravel Portfolio

A modern, responsive portfolio website built with Laravel 10, Livewire 3, and Tailwind CSS. Perfect for developers who want to showcase their skills, projects, and experience in a professional and elegant way.

## 🚀 Features

- **Modern Design**: Clean, professional design with smooth animations and transitions
- **Fully Responsive**: Works perfectly on desktop, tablet, and mobile devices
- **Admin Panel**: Comprehensive admin interface for content management
- **Contact Form**: Functional contact form with email notifications and spam protection
- **Project Gallery**: Interactive project showcase with modal views and image galleries
- **Skills Display**: Visual representation of technical skills with progress bars
- **SEO Optimized**: Built-in SEO optimization, meta tags, and structured data
- **Performance**: Optimized for speed with caching, lazy loading, and image optimization
- **Accessibility**: WCAG compliant with proper ARIA labels and keyboard navigation
- **Dark Mode**: Toggle between light and dark themes
- **Real-time Updates**: Livewire components for seamless user interactions

## 🛠 Tech Stack

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
- **Database**: MySQL/PostgreSQL with Redis for caching
- **Email**: SMTP with queue support for reliable delivery
- **Assets**: Vite for modern asset compilation and hot reloading
- **Testing**: PHPUnit with comprehensive test coverage
- **Deployment**: Docker support with automated deployment scripts

## 📋 Requirements

- PHP 8.1 or higher
- Composer 2.0+
- Node.js 16.0+
- MySQL 8.0+ or PostgreSQL 13+
- Redis 6.0+ (recommended)

## 🚀 Quick Start

### Local Development

```bash
# Clone the repository
git clone https://github.com/your-username/laravel-portfolio.git
cd laravel-portfolio

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run dev

# Start development server
php artisan serve
```

Visit `http://localhost:8000` to see your portfolio!

### Docker Setup

```bash
# Build and start containers
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install && npm run build

# Setup database
docker-compose exec app php artisan migrate --seed
```

## 📚 Documentation

### Setup and Deployment
- **[Installation Guide](INSTALLATION.md)** - Complete setup instructions for local and production environments
- **[API Documentation](API_DOCUMENTATION.md)** - Comprehensive API reference for all endpoints

### User Guides
- **[Admin Panel Guide](ADMIN_GUIDE.md)** - Complete guide for managing your portfolio content

### Development
- **Testing**: Run `php artisan test` for the full test suite
- **Code Style**: Follow PSR-12 standards with Laravel Pint
- **Contributing**: See [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines

## 🎨 Customization

### Themes and Styling
- Modify `tailwind.config.js` for custom colors and styling
- Update Livewire components in `app/Livewire/` for functionality changes
- Customize views in `resources/views/` for layout modifications

### Configuration
- Update `.env` file for environment-specific settings
- Modify `config/` files for application configuration
- Customize email templates in `resources/views/emails/`

## 🧪 Testing

The application includes comprehensive test coverage:

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

### Test Coverage
- **Feature Tests**: Complete user workflow testing
- **Unit Tests**: Individual component and model testing
- **Browser Tests**: Responsive design and interaction testing
- **API Tests**: Comprehensive API endpoint testing

## 🚀 Deployment

### Automated Deployment

Use the included deployment script:

```bash
chmod +x deploy.sh
./deploy.sh
```

### Manual Deployment

1. **Server Setup**: Configure web server (Nginx/Apache)
2. **Dependencies**: Install PHP, Composer, Node.js
3. **Application**: Clone repo and install dependencies
4. **Environment**: Configure `.env` for production
5. **Database**: Run migrations and optimizations
6. **Assets**: Build production assets
7. **Services**: Configure queue workers and cron jobs

See [INSTALLATION.md](INSTALLATION.md) for detailed deployment instructions.

## 🔧 Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME="Laravel Portfolio"
APP_ENV=production
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_DATABASE=laravel_portfolio

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
CONTACT_EMAIL=your-email@example.com

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Admin Access

Create an admin user:

```bash
php artisan make:admin your-email@example.com
```

## 📊 Performance

### Optimization Features
- **Caching**: Redis-based caching for database queries and views
- **Image Optimization**: Automatic image resizing and compression
- **Lazy Loading**: Images load as they enter the viewport
- **Asset Optimization**: Minified CSS/JS with Vite
- **Database**: Optimized queries with eager loading

### Performance Metrics
- **Page Load**: < 3 seconds on standard connections
- **Lighthouse Score**: 90+ for Performance, Accessibility, SEO
- **Core Web Vitals**: Optimized for Google's performance metrics

## 🔒 Security

### Security Features
- **CSRF Protection**: All forms protected against CSRF attacks
- **Rate Limiting**: Contact form and API endpoints rate limited
- **Input Validation**: Comprehensive server-side validation
- **File Upload Security**: Secure file handling with type validation
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Output escaping and content security policies

### Security Best Practices
- Regular security updates
- Strong password requirements
- Secure session configuration
- HTTPS enforcement in production
- Regular security audits

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

### Development Workflow
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

### Code Standards
- Follow PSR-12 coding standards
- Write comprehensive tests
- Document new features
- Use meaningful commit messages

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

- **Laravel Team** - For the amazing framework
- **Livewire Team** - For reactive components
- **Tailwind CSS** - For the utility-first CSS framework
- **Alpine.js** - For lightweight JavaScript interactions

## 📞 Support

- **Documentation**: Check the guides in the `/docs` folder
- **Issues**: Report bugs on [GitHub Issues](https://github.com/your-username/laravel-portfolio/issues)
- **Email**: Contact the developer at your-email@example.com
- **Community**: Join discussions in [GitHub Discussions](https://github.com/your-username/laravel-portfolio/discussions)

## 🗺 Roadmap

### Upcoming Features
- [ ] Multi-language support
- [ ] Blog functionality
- [ ] Advanced analytics dashboard
- [ ] Social media integration
- [ ] PDF resume generation
- [ ] Advanced SEO tools

### Version History
- **v1.0.0** - Initial release with core portfolio features
- **v1.1.0** - Admin panel enhancements and API improvements
- **v1.2.0** - Performance optimizations and security updates

---

**Made with ❤️ by [Your Name](https://your-domain.com)**

*Star ⭐ this repository if you found it helpful!*