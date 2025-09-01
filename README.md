# Amazon Clone - Full Stack E-commerce Platform

A complete, professional e-commerce platform built with Laravel 10 backend, modern frontend with HTML/CSS/JavaScript, and Docker deployment. This project includes all the features of a modern e-commerce site with additional tools like n8n for workflow automation.

## 🚀 Features

### Frontend Features
- **Responsive Design**: Mobile-first design that works on all devices
- **Product Catalog**: Browse products with advanced filtering and sorting
- **Product Details**: Detailed product pages with multiple images and descriptions
- **Shopping Cart**: Full cart functionality with quantity management
- **User Authentication**: Login and registration system
- **Order Management**: Order history and tracking
- **Wishlist**: Save favorite products
- **Admin Panel**: Complete admin interface for managing products, orders, and users
- **Search & Filters**: Advanced search with category, price, and sorting filters

### Backend Features
- **RESTful API**: Complete REST API built with Laravel 10
- **Authentication**: Secure authentication with Laravel Sanctum
- **Database Management**: MySQL with migrations and seeders
- **Product Management**: CRUD operations for products
- **Order System**: Complete order processing workflow
- **User Management**: Admin and customer user roles
- **Cart System**: Session-based cart management
- **Validation**: Comprehensive input validation
- **Error Handling**: Proper error handling and responses

### Additional Tools
- **n8n Integration**: Workflow automation platform for business processes
- **Docker Deployment**: Complete containerized deployment
- **Database Seeding**: 50 sample products with realistic data

## 📁 Project Structure

```
amazon-clone/
├── backend/                    # Laravel 10 Backend
│   ├── app/
│   │   ├── Http/Controllers/   # API Controllers
│   │   ├── Models/            # Eloquent Models
│   │   └── Http/Middleware/   # Custom Middleware
│   ├── database/
│   │   ├── migrations/        # Database Migrations
│   │   └── seeders/          # Database Seeders
│   ├── routes/api.php         # API Routes
│   ├── Dockerfile            # Backend Docker Configuration
│   └── .env                  # Environment Configuration
├── frontend/                  # Frontend Application
│   ├── index.html            # Homepage
│   ├── product.html          # Product Detail Page
│   ├── cart.html             # Shopping Cart
│   ├── orders.html           # Order History
│   ├── admin.html            # Admin Panel
│   ├── css/style.css         # Custom Styles
│   └── js/main.js           # JavaScript Functionality
├── docker-compose.yml        # Docker Compose Configuration
└── README.md                # This file
```

## 🛠 Tech Stack

- **Backend**: Laravel 10, PHP 8.2, MySQL 8.0
- **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript
- **Authentication**: Laravel Sanctum with JWT tokens
- **Database**: MySQL with Eloquent ORM
- **Deployment**: Docker & Docker Compose
- **Automation**: n8n workflow automation

## ⚡ Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Git (for cloning the repository)

### 1. Clone the Repository
```bash
git clone <repository-url>
cd amazon-clone
```

### 2. Start the Application
```bash
docker-compose up --build
```

This single command will:
- Build and start the Laravel backend on port 8000
- Start MySQL database on port 3306
- Start n8n automation platform on port 5678
- Set up all necessary dependencies

### 3. Initialize the Database
```bash
# Run migrations and seed data
docker-compose exec backend php artisan migrate --seed
```

### 4. Access the Application

- **Frontend**: Open `frontend/index.html` in your browser
- **Backend API**: http://localhost:8000/api
- **n8n Dashboard**: http://localhost:5678 (admin/admin123)
- **Database**: localhost:3306 (user/password)

## 🔧 API Endpoints

### Products
- `GET /api/products` - List products with filtering
- `GET /api/products/{id}` - Get product details
- `POST /api/admin/products` - Create product (Admin)
- `PUT /api/admin/products/{id}` - Update product (Admin)
- `DELETE /api/admin/products/{id}` - Delete product (Admin)

### Cart
- `POST /api/cart/add` - Add item to cart
- `GET /api/cart` - Get cart contents
- `PUT /api/cart/update` - Update cart item
- `DELETE /api/cart/remove` - Remove from cart

### Orders
- `POST /api/orders` - Create order
- `GET /api/orders` - Get user orders
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}/cancel` - Cancel order

### Authentication
- `POST /api/register` - Register user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/user` - Get authenticated user

### Admin
- `GET /api/admin/dashboard` - Dashboard stats
- `GET /api/admin/users` - Manage users
- `GET /api/admin/orders` - Manage orders

## 👥 Default Users

After running the seeder, you'll have these accounts:

### Admin Account
- **Email**: admin@amazon-clone.com
- **Password**: admin123
- **Role**: Administrator

### Test User Account
- **Email**: test@example.com
- **Password**: password
- **Role**: Customer

## 🎯 Sample Data

The application comes with 50 pre-loaded products across 6 categories:
- Electronics (iPhones, Laptops, Headphones, etc.)
- Fashion (Shoes, Clothing, Accessories)
- Home & Garden (Appliances, Smart Home devices)
- Sports & Outdoors (Fitness equipment, Outdoor gear)
- Books (Bestsellers across various genres)
- Beauty (Skincare, Makeup, Personal care)

## 🔐 Security Features

- **Input Validation**: All inputs are validated on both frontend and backend
- **Authentication**: Secure token-based authentication with Laravel Sanctum
- **Authorization**: Role-based access control (Admin/User)
- **CORS Configuration**: Properly configured for cross-origin requests
- **SQL Injection Prevention**: Using Eloquent ORM and prepared statements
- **XSS Protection**: Input sanitization and output escaping

## 🎨 Frontend Features

### Responsive Design
- Mobile-first approach
- Optimized for tablets and desktops
- Touch-friendly interface

### User Experience
- Smooth animations and transitions
- Loading states and error handling
- Toast notifications for user feedback
- Intuitive navigation and search

### Accessibility
- Keyboard navigation support
- Screen reader compatible
- High contrast mode support
- Focus indicators for better usability

## 🔄 n8n Workflow Automation

The included n8n platform allows you to create automated workflows such as:
- Order confirmation emails
- Inventory management alerts
- Customer follow-up sequences
- Data synchronization between systems

Access n8n at: http://localhost:5678
- Username: admin
- Password: admin123

## 🚢 Deployment

### Production Deployment
For production deployment, update the following:

1. **Environment Variables**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Database Configuration**:
   ```bash
   DB_HOST=your-database-host
   DB_DATABASE=your-database-name
   DB_USERNAME=your-database-user
   DB_PASSWORD=your-secure-password
   ```

3. **Security**:
   - Generate a new APP_KEY: `php artisan key:generate`
   - Use HTTPS in production
   - Configure proper CORS settings
   - Set up proper backup procedures

### Scaling Considerations
- Use a load balancer for multiple backend instances
- Implement Redis for session storage and caching
- Use CDN for static assets
- Consider database optimization and indexing

## 🛠 Development

### Adding New Features
1. **Backend**: Create controllers, models, and migrations in Laravel
2. **Frontend**: Add new HTML pages and update JavaScript functionality
3. **Database**: Create migrations for schema changes
4. **API**: Document new endpoints in the API section above

### Testing
- Backend: Use Laravel's testing framework
- Frontend: Implement unit tests for JavaScript functions
- Integration: Test API endpoints with tools like Postman

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

If you encounter any issues or have questions:
1. Check the troubleshooting section below
2. Create an issue in the repository
3. Contact the development team

## 🔧 Troubleshooting

### Common Issues

**Docker containers won't start**:
- Ensure Docker is running
- Check port availability (8000, 3306, 5678)
- Run `docker-compose down` and try again

**Database connection issues**:
- Wait for MySQL container to fully initialize
- Check database credentials in .env file
- Ensure database is created and seeded

**Frontend not loading products**:
- Verify backend is running on port 8000
- Check browser console for CORS errors
- Ensure API endpoints are accessible

**Permission errors**:
- Check Docker container permissions
- Ensure proper file ownership

### Performance Optimization

1. **Database**:
   - Add indexes for frequently queried columns
   - Optimize queries using Laravel Debugbar
   - Consider database caching

2. **Frontend**:
   - Implement lazy loading for images
   - Minimize JavaScript bundle size
   - Use CDN for static assets

3. **Backend**:
   - Implement Redis caching
   - Optimize database queries
   - Use queue workers for heavy tasks

---

**Built with ❤️ for learning and demonstration purposes**

This project showcases modern full-stack development practices and can serve as a foundation for real e-commerce applications.