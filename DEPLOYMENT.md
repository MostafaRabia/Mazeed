# Deployment to Render.com with Docker

This guide explains how to deploy the Mazeed project to Render.com using Docker.

## Prerequisites

- A Render.com account
- The project pushed to GitHub
- LinkedIn OAuth credentials
- Docker configured in the project (Dockerfile included)

## Deployment Steps

### 1. Local Docker Testing (Optional)

Before deploying to Render, you can test locally with Docker:

```bash
# Start Docker containers
docker-compose up -d

# Access the app at http://localhost:8000
# Database admin at http://localhost:8080 (phpMyAdmin)

# View logs
docker-compose logs -f app

# Run migrations
docker-compose exec app php artisan migrate

# Stop containers
docker-compose down
```

### 2. Connect Your Repository to Render

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click "New +" → "Web Service"
3. Connect your GitHub repository
4. Select the `Mazeed` repository

### 3. Configure the Docker Service

#### Basic Settings
- **Name**: `mazeed-app`
- **Runtime**: `Docker`
- **Plan**: Free (or paid if needed)
- **Region**: Choose closest to your location
- **Branch**: `main`

Render will automatically:
- Build the Docker image from the Dockerfile
- Run migrations on deployment
- Start the application with Apache

#### Environment Variables

Add the following environment variables in the Render dashboard (look for "Environment"):

```env
APP_NAME=Mazeed
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://your-render-domain.onrender.com

LOG_CHANNEL=stderr
CACHE_DRIVER=file
SESSION_DRIVER=file
FILESYSTEM_DISK=public

DB_CONNECTION=mysql
DB_HOST=your-mysql-host.render.com
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

LINKEDIN_CLIENT_ID=your_linkedin_client_id
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
LINKEDIN_REDIRECT=https://your-render-domain.onrender.com/auth/linkedin/callback
```

### 4. Set Up MySQL Database

You have these options:

**Option A: Render's MySQL Service (Easiest)**
1. In Render Dashboard, click "New +" → "MySQL"
2. Configure your database
3. Copy the connection details
4. Use these for `DB_*` environment variables

**Option B: External Database Service**
- AWS RDS
- DigitalOcean Managed Database
- Any other external MySQL service

### 5. Deploy

1. Review all settings
2. Click "Create Web Service"
3. Render automatically starts the deployment
4. Monitor deployment in the "Logs" tab

### 6. Post-Deployment Configuration

#### Get Your Render Domain
- Note your assigned domain: `https://your-service.onrender.com`
- Update `APP_URL` environment variable with your domain
- Redeploy to apply URL change

#### Configure LinkedIn OAuth
1. Go to LinkedIn App settings
2. Update "Authorized redirect URLs":
   - Add: `https://your-service.onrender.com/auth/linkedin/callback`
3. Save settings

#### Test the Application
- Visit `https://your-service.onrender.com`
- Test user registration/login
- Test LinkedIn OAuth flow
- Test badge generation
- Test badge sharing to LinkedIn

## Docker Files Reference

### Dockerfile
- PHP 8.3 with Apache
- Pre-installs all PHP extensions needed for Laravel
- Installs dependencies via Composer
- Builds frontend assets via npm
- Optimizes Laravel configuration

### docker-compose.yml
Local development setup with:
- Laravel application (Apache + PHP)
- MySQL 8.0 database
- phpMyAdmin for database management

### .dockerignore
Excludes unnecessary files from Docker build context for faster builds

## Storage & Persistence

- **Badge Images**: Stored in `storage/app/public/badges/`
- **Database**: Persisted in external MySQL database
- **Logs**: Captured from stderr (visible in Render dashboard)
- **Temporary Files**: Stored in `/tmp` (ephemeral - cleared on restart)

## Troubleshooting

### Docker Build Fails
**Problem**: Build logs show errors
**Solution**:
- Check build logs in Render Dashboard ("Logs" tab)
- Verify all environment variables are set
- Ensure Dockerfile is valid: `docker build -t mazeed:test .`
- Test locally: `docker-compose build`

### App Won't Start
**Problem**: Service crashes immediately after deployment
**Solution**:
- [ ] Check application logs in Render dashboard
- [ ] Verify `APP_KEY` is set and valid
- [ ] Check database connectivity
- [ ] Verify `DB_*` environment variables match database

### Database Connection Error
**Problem**: "Can't connect to MySQL server"
**Solution**:
- [ ] Verify all `DB_*` environment variables
- [ ] Check database is running
- [ ] Test connection from Render shell
- [ ] Check firewall allows Render IP

### LinkedIn OAuth Fails
**Problem**: "Invalid redirect URI"
**Solution**:
- [ ] Verify `LINKEDIN_REDIRECT` matches LinkedIn app settings exactly
- [ ] Ensure domain matches your Render domain
- [ ] Clear browser cache and cookies

### Static Files Not Loading
**Problem**: CSS/JS files return 404
**Solution**:
- [ ] Verify npm build ran in Dockerfile
- [ ] Check files exist in `public/build/`
- [ ] Verify `FILESYSTEM_DISK=public` is set

## Docker Commands for Local Development

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan tinker
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Access container shell
docker-compose exec app bash

# Rebuild image
docker-compose build --no-cache

# Stop services
docker-compose down

# Remove volumes (careful - deletes database)
docker-compose down -v
```

## Performance Notes

- Free tier Render services spin down after 15 minutes of inactivity
- For production, upgrade to a paid plan
- Docker containers have better performance than traditional shared hosting

## Updating the Application

1. Make changes locally
2. Test with `docker-compose up -d`
3. Push to GitHub
4. Render automatically detects the push
5. Docker image rebuilds and redeploys automatically
6. Monitor deployment in Render Dashboard

## Support Resources

- [Render.com Documentation](https://render.com/docs)
- [Render Docker Guide](https://render.com/docs/deploy-docker)
- [Docker Documentation](https://docs.docker.com/)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Render Community Forum](https://community.render.com)

---

**Last Updated**: 2026-06-06
**Deployment Method**: Docker
**Status**: Ready for Deployment ✅
