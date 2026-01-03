#!/bin/bash

# SchoolDream+ LMS Startup Script

echo "========================================"
echo "  SchoolDream+ LMS - Starting Server"
echo "========================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    echo "✓ .env file created"
    echo ""
    echo "⚠️  Please edit .env file with your database credentials"
    echo "   Then run: php database/setup.php"
    echo "   And restart this script"
    exit 1
fi

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install
    echo "✓ Dependencies installed"
    echo ""
fi

# Check if database is set up
if [ ! -f ".db_setup_done" ]; then
    echo "Database not set up yet."
    read -p "Do you want to set up the database now? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php database/setup.php
        if [ $? -eq 0 ]; then
            touch .db_setup_done
            echo ""
        else
            echo "Database setup failed. Please check your configuration."
            exit 1
        fi
    else
        echo "Skipping database setup."
        echo "Run 'php database/setup.php' manually when ready."
        echo ""
    fi
fi

# Create upload directories if they don't exist
mkdir -p public/uploads/courses
mkdir -p public/uploads/certificates
mkdir -p public/uploads/profile_pictures
mkdir -p storage/logs
mkdir -p storage/cache
mkdir -p storage/sessions

# Create placeholder image if it doesn't exist
if [ ! -f "public/images/course-placeholder.jpg" ]; then
    mkdir -p public/images
    echo "Creating placeholder image..."
    # Create a simple colored placeholder (this would need imagemagick in production)
    touch public/images/course-placeholder.jpg
fi

echo "========================================"
echo "  Starting PHP Development Server"
echo "========================================"
echo ""
echo "  Application URL: http://localhost:3000"
echo "  Press Ctrl+C to stop the server"
echo ""
echo "Default login credentials:"
echo "  Admin: admin@schooldream.com / admin123"
echo "  Instructor: jpmunyaneza@schooldream.com / instructor123"
echo "  Student: alice@example.com / student123"
echo ""
echo "========================================"
echo ""

# Start PHP server on port 3000
php -S localhost:3000 -t public
