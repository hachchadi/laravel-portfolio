# Laravel Portfolio - API Documentation

This document describes the API endpoints available in the Laravel Portfolio application.

## Table of Contents

- [Authentication](#authentication)
- [Public Endpoints](#public-endpoints)
- [Admin Endpoints](#admin-endpoints)
- [Contact Form API](#contact-form-api)
- [File Upload API](#file-upload-api)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)
- [Response Formats](#response-formats)

## Authentication

The Laravel Portfolio uses session-based authentication for admin panel access and API token authentication for API endpoints.

### Admin Authentication

```http
POST /admin/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "redirect": "/admin"
}
```

### API Token Authentication

For API access, include the API token in the Authorization header:

```http
Authorization: Bearer your-api-token
```

## Public Endpoints

### Get Portfolio Data

Retrieve all public portfolio information including user profile, projects, and skills.

```http
GET /api/portfolio
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "title": "Senior Laravel Developer",
            "bio": "Experienced developer with 5+ years in Laravel",
            "avatar": "/storage/avatars/avatar.jpg",
            "linkedin_url": "https://linkedin.com/in/johndoe",
            "github_url": "https://github.com/johndoe",
            "location": "Remote",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "projects": [
            {
                "id": 1,
                "title": "E-commerce Platform",
                "description": "Full-featured e-commerce platform built with Laravel",
                "technologies": ["Laravel", "Vue.js", "MySQL", "Redis"],
                "github_url": "https://github.com/johndoe/ecommerce",
                "demo_url": "https://demo.example.com",
                "featured": true,
                "status": "published",
                "images": [
                    {
                        "id": 1,
                        "image_path": "/storage/projects/project1-1.jpg",
                        "alt_text": "E-commerce homepage",
                        "sort_order": 0
                    }
                ],
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "skills": [
            {
                "id": 1,
                "name": "Laravel",
                "category": "Backend",
                "proficiency": 95,
                "sort_order": 0,
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    }
}
```

### Get Projects

Retrieve all published projects.

```http
GET /api/projects
```

**Query Parameters:**
- `featured` (boolean): Filter by featured projects
- `technology` (string): Filter by technology
- `limit` (integer): Limit number of results (default: 10)
- `page` (integer): Page number for pagination

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "E-commerce Platform",
            "description": "Full-featured e-commerce platform",
            "technologies": ["Laravel", "Vue.js", "MySQL"],
            "github_url": "https://github.com/johndoe/ecommerce",
            "demo_url": "https://demo.example.com",
            "featured": true,
            "images": [...]
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 2,
        "per_page": 10,
        "total": 15
    }
}
```

### Get Single Project

Retrieve details of a specific project.

```http
GET /api/projects/{id}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "E-commerce Platform",
        "description": "Full-featured e-commerce platform built with Laravel",
        "technologies": ["Laravel", "Vue.js", "MySQL", "Redis"],
        "github_url": "https://github.com/johndoe/ecommerce",
        "demo_url": "https://demo.example.com",
        "featured": true,
        "status": "published",
        "images": [
            {
                "id": 1,
                "image_path": "/storage/projects/project1-1.jpg",
                "alt_text": "E-commerce homepage",
                "sort_order": 0
            }
        ],
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### Get Skills

Retrieve all skills grouped by category.

```http
GET /api/skills
```

**Query Parameters:**
- `category` (string): Filter by category (Backend, Frontend, Database, Tools)

**Response:**
```json
{
    "success": true,
    "data": {
        "Backend": [
            {
                "id": 1,
                "name": "Laravel",
                "category": "Backend",
                "proficiency": 95,
                "sort_order": 0
            }
        ],
        "Frontend": [
            {
                "id": 2,
                "name": "Vue.js",
                "category": "Frontend",
                "proficiency": 85,
                "sort_order": 1
            }
        ]
    }
}
```

## Contact Form API

### Submit Contact Form

Submit a contact form message.

```http
POST /api/contact
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Project Inquiry",
    "message": "I would like to discuss a potential project."
}
```

**Validation Rules:**
- `name`: required, string, min:2, max:255
- `email`: required, email, max:255
- `subject`: required, string, min:5, max:255
- `message`: required, string, min:10, max:2000

**Response (Success):**
```json
{
    "success": true,
    "message": "Message sent successfully! I'll get back to you within 24 hours.",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "subject": "Project Inquiry",
        "status": "unread",
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

**Response (Validation Error):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field must be a valid email address."],
        "message": ["The message field must be at least 10 characters."]
    }
}
```

## Admin Endpoints

All admin endpoints require authentication and admin privileges.

### Get Dashboard Statistics

```http
GET /api/admin/dashboard
Authorization: Bearer your-api-token
```

**Response:**
```json
{
    "success": true,
    "data": {
        "projects": {
            "total": 10,
            "published": 8,
            "draft": 2,
            "featured": 5
        },
        "skills": {
            "total": 15,
            "by_category": {
                "Backend": 6,
                "Frontend": 4,
                "Database": 3,
                "Tools": 2
            }
        },
        "messages": {
            "total": 25,
            "unread": 5,
            "read": 15,
            "replied": 5
        }
    }
}
```

### Manage Projects

#### Create Project

```http
POST /api/admin/projects
Authorization: Bearer your-api-token
Content-Type: application/json

{
    "title": "New Project",
    "description": "Project description",
    "technologies": ["Laravel", "Vue.js"],
    "github_url": "https://github.com/user/project",
    "demo_url": "https://demo.example.com",
    "featured": true,
    "status": "published"
}
```

#### Update Project

```http
PUT /api/admin/projects/{id}
Authorization: Bearer your-api-token
Content-Type: application/json

{
    "title": "Updated Project Title",
    "description": "Updated description"
}
```

#### Delete Project

```http
DELETE /api/admin/projects/{id}
Authorization: Bearer your-api-token
```

### Manage Skills

#### Create Skill

```http
POST /api/admin/skills
Authorization: Bearer your-api-token
Content-Type: application/json

{
    "name": "New Skill",
    "category": "Backend",
    "proficiency": 80
}
```

#### Update Skill

```http
PUT /api/admin/skills/{id}
Authorization: Bearer your-api-token
Content-Type: application/json

{
    "name": "Updated Skill",
    "proficiency": 90
}
```

#### Bulk Import Skills

```http
POST /api/admin/skills/bulk-import
Authorization: Bearer your-api-token
Content-Type: application/json

{
    "skills": [
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
}
```

### Manage Contact Messages

#### Get Messages

```http
GET /api/admin/messages
Authorization: Bearer your-api-token
```

**Query Parameters:**
- `status` (string): Filter by status (unread, read, replied)
- `limit` (integer): Limit number of results
- `page` (integer): Page number

#### Mark Message as Read

```http
PATCH /api/admin/messages/{id}/read
Authorization: Bearer your-api-token
```

#### Mark Message as Replied

```http
PATCH /api/admin/messages/{id}/replied
Authorization: Bearer your-api-token
```

## File Upload API

### Upload Avatar

```http
POST /api/admin/profile/avatar
Authorization: Bearer your-api-token
Content-Type: multipart/form-data

avatar: [file]
```

**Validation:**
- File type: jpg, jpeg, png, gif
- Max size: 2MB
- Dimensions: Max 2000x2000px

### Upload Project Images

```http
POST /api/admin/projects/{id}/images
Authorization: Bearer your-api-token
Content-Type: multipart/form-data

images[]: [file1]
images[]: [file2]
```

**Validation:**
- File type: jpg, jpeg, png, gif, webp
- Max size: 5MB per file
- Max files: 10 per request

## Error Handling

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error

### Error Response Format

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Error message for this field"]
    },
    "code": "ERROR_CODE"
}
```

### Common Error Codes

- `VALIDATION_FAILED` - Input validation failed
- `UNAUTHORIZED` - Authentication required
- `FORBIDDEN` - Insufficient permissions
- `NOT_FOUND` - Resource not found
- `RATE_LIMITED` - Too many requests
- `FILE_UPLOAD_FAILED` - File upload error
- `DATABASE_ERROR` - Database operation failed

## Rate Limiting

### Public Endpoints

- Contact form: 5 requests per minute per IP
- General API: 60 requests per minute per IP

### Admin Endpoints

- File uploads: 10 requests per minute
- General admin API: 120 requests per minute

### Rate Limit Headers

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

## Response Formats

### Success Response

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        // Response data
    },
    "meta": {
        // Pagination or additional metadata
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        // Validation errors or error details
    }
}
```

### Pagination Meta

```json
{
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50,
        "from": 1,
        "to": 10
    }
}
```

## Webhooks (Optional)

### Contact Form Webhook

If configured, the application can send webhook notifications when new contact messages are received.

```http
POST https://your-webhook-url.com/contact
Content-Type: application/json

{
    "event": "contact.message.received",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "subject": "Project Inquiry",
        "message": "Message content",
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

## SDK and Libraries

### JavaScript SDK Example

```javascript
class PortfolioAPI {
    constructor(baseURL = '/api') {
        this.baseURL = baseURL;
    }

    async getPortfolio() {
        const response = await fetch(`${this.baseURL}/portfolio`);
        return response.json();
    }

    async getProjects(params = {}) {
        const query = new URLSearchParams(params);
        const response = await fetch(`${this.baseURL}/projects?${query}`);
        return response.json();
    }

    async submitContact(data) {
        const response = await fetch(`${this.baseURL}/contact`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }
}

// Usage
const api = new PortfolioAPI();
const portfolio = await api.getPortfolio();
```

## Testing

### API Testing with cURL

```bash
# Get portfolio data
curl -X GET "http://localhost:8000/api/portfolio" \
     -H "Accept: application/json"

# Submit contact form
curl -X POST "http://localhost:8000/api/contact" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "name": "John Doe",
       "email": "john@example.com",
       "subject": "Test",
       "message": "This is a test message"
     }'
```

### Postman Collection

A Postman collection is available at `/docs/postman/Laravel-Portfolio.postman_collection.json` with all API endpoints pre-configured.

## Changelog

### Version 1.0.0
- Initial API release
- Public portfolio endpoints
- Contact form API
- Admin management endpoints
- File upload functionality
- Rate limiting implementation

## Support

For API support and questions:
- Email: your-email@example.com
- Documentation: https://your-domain.com/docs
- GitHub Issues: https://github.com/your-username/laravel-portfolio/issues