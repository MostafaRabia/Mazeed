# Render.com Deployment Checklist

Use this checklist to ensure everything is properly configured before deploying to Render.

## Pre-Deployment Setup

### ✅ Project Validation
- [ ] Run `bash validate-deployment.sh` locally
- [ ] All checks should pass (✓ 10 passed, ✗ 0 failed)
- [ ] No uncommitted changes

### ✅ GitHub Configuration
- [ ] Code is pushed to GitHub
- [ ] Branch is up to date with remote
- [ ] No sensitive data in version control
- [ ] .env file is NOT in git (check .gitignore)

### ✅ LinkedIn OAuth Setup
- [ ] LinkedIn App credentials are obtained
- [ ] `LINKEDIN_CLIENT_ID` is noted
- [ ] `LINKEDIN_CLIENT_SECRET` is noted
- [ ] Redirect URI will be: `https://YOUR_DOMAIN.onrender.com/auth/linkedin/callback`

### ✅ Database Preparation
- [ ] Decide on database option:
  - [ ] Use Render's MySQL service (easiest)
  - [ ] Use external database (AWS RDS, DigitalOcean, etc.)
  - [ ] Use existing managed database
- [ ] Have database credentials ready:
  - [ ] Host
  - [ ] Port
  - [ ] Database name
  - [ ] Username
  - [ ] Password

## Render.com Deployment Steps

### Step 1: Create Render Account
- [ ] Sign up at [render.com](https://render.com)
- [ ] Link GitHub account
- [ ] Create Render team (optional)

### Step 2: Create Web Service
- [ ] Go to Render Dashboard
- [ ] Click "New +" → "Web Service"
- [ ] Select repository: `Mazeed`
- [ ] Name: `mazeed-app`
- [ ] Runtime: `PHP`
- [ ] Region: Choose closest to your location
- [ ] Branch: `main`
- [ ] Plan: `Free` (or `Starter` for production)

### Step 3: Configure Build & Start Commands
- [ ] Build Command: `bash build.sh`
- [ ] Start Command: `php -S 0.0.0.0:$PORT public/index.php`
- [ ] Health Check Path: `/`

### Step 4: Set Environment Variables

Add the following environment variables in Render Dashboard:

**Application Configuration:**
```
APP_NAME=Mazeed
APP_ENV=production
APP_DEBUG=false
APP_KEY=[Copy from local .env - starts with base64:]
APP_URL=[Will be assigned by Render - update after deployment]
```

**Logging & Caching:**
```
LOG_CHANNEL=stderr
LOG_LEVEL=debug
CACHE_DRIVER=file
SESSION_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
```

**LinkedIn OAuth:**
```
LINKEDIN_CLIENT_ID=your_linkedin_client_id
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
LINKEDIN_REDIRECT=[Will be your Render domain]/auth/linkedin/callback
```

**Database Configuration (choose one option):**

**Option A: Render MySQL Service**
```
DB_CONNECTION=mysql
DB_HOST=[From Render MySQL service]
DB_PORT=3306
DB_DATABASE=[From Render MySQL service]
DB_USERNAME=[From Render MySQL service]
DB_PASSWORD=[From Render MySQL service]
```

**Option B: External Database**
```
DB_CONNECTION=mysql
DB_HOST=your-database-host.com
DB_PORT=3306
DB_DATABASE=mazid
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Deploy
- [ ] Review all settings
- [ ] Click "Deploy"
- [ ] Monitor deployment in "Logs" tab
- [ ] Wait for build to complete

### Step 6: Post-Deployment Configuration

#### Get Your Render Domain
- [ ] Note your assigned domain: `https://your-service.onrender.com`
- [ ] Update `APP_URL` environment variable with your domain
- [ ] Redeploy to apply URL change

#### Configure LinkedIn OAuth
1. Go to LinkedIn App settings
2. Update "Authorized redirect URLs":
   - Add: `https://your-service.onrender.com/auth/linkedin/callback`
3. Save settings

#### Test the Application
- [ ] Visit `https://your-service.onrender.com`
- [ ] Test user registration/login
- [ ] Test LinkedIn OAuth flow
- [ ] Test badge generation
- [ ] Test badge sharing to LinkedIn

## Post-Deployment Tasks

### ✅ Monitoring
- [ ] Set up email alerts in Render Dashboard
- [ ] Monitor application logs regularly
- [ ] Check for errors in "Logs" tab

### ✅ Maintenance
- [ ] Set up automated backups for database
- [ ] Monitor Render service status
- [ ] Plan for scaling if needed

### ✅ Database Management
- [ ] Connect to database to verify data
- [ ] Create database backups
- [ ] Document database connection string
- [ ] Monitor storage usage

## Troubleshooting

### Build Fails
**Problem**: Build exits with error
**Solution**:
- [ ] Check build logs in Render Dashboard
- [ ] Verify all environment variables are set
- [ ] Run `bash validate-deployment.sh` locally
- [ ] Check that build.sh has correct permissions

### App Won't Start
**Problem**: Service keeps restarting or crashes
**Solution**:
- [ ] Check application logs
- [ ] Verify database connectivity
- [ ] Check APP_KEY is properly set
- [ ] Verify storage directory permissions

### Database Connection Error
**Problem**: "Can't connect to MySQL server"
**Solution**:
- [ ] Verify DB_* environment variables match database
- [ ] Check database is running and accessible
- [ ] Verify firewall allows Render IP
- [ ] Test connection from Render shell

### LinkedIn OAuth Fails
**Problem**: "Invalid redirect URI"
**Solution**:
- [ ] Verify LINKEDIN_REDIRECT matches LinkedIn app settings
- [ ] Ensure domain in redirect URL matches your Render domain
- [ ] Wait a few minutes for settings to propagate
- [ ] Clear browser cache

### Static Files Not Loading
**Problem**: CSS/JS files return 404
**Solution**:
- [ ] Run `npm run build` (included in build.sh)
- [ ] Check that files exist in public/build
- [ ] Verify FILESYSTEM_DISK=public is set
- [ ] Rebuild and redeploy

## Environment Variables Reference

| Variable | Example | Purpose |
|----------|---------|---------|
| APP_ENV | `production` | Laravel environment |
| APP_DEBUG | `false` | Debug mode (must be false for production) |
| APP_KEY | `base64:...` | Application key (from `php artisan key:generate`) |
| APP_URL | `https://mazeed.onrender.com` | Application URL |
| DB_CONNECTION | `mysql` | Database type |
| DB_HOST | `instance.region.rds.amazonaws.com` | Database host |
| LOG_CHANNEL | `stderr` | Log output channel for Render |
| LINKEDIN_CLIENT_ID | `your_linkedin_client_id` | LinkedIn OAuth app ID (from LinkedIn app settings) |
| LINKEDIN_CLIENT_SECRET | `your_linkedin_client_secret` | LinkedIn OAuth secret (from LinkedIn app settings) |

## Support Resources

- [Render.com Documentation](https://render.com/docs)
- [Render Laravel Guide](https://render.com/docs/deploy-laravel)
- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Render Community Forum](https://community.render.com)
- [LinkedIn API Documentation](https://learn.microsoft.com/en-us/linkedin/shared/authentication/authentication)

---

**Last Updated**: 2026-06-06
**Status**: Ready for Deployment ✅
