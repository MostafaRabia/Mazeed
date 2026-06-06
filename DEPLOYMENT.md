# Deployment to Render.com

This guide explains how to deploy the Mazeed project to Render.com.

## Prerequisites

- A Render.com account
- The project pushed to GitHub
- LinkedIn OAuth credentials

## Deployment Steps

### 1. Connect Your Repository to Render

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click "New +" → "Web Service"
3. Connect your GitHub repository
4. Select the `Mazeed` repository

### 2. Configure the Service

#### Basic Settings
- **Name**: `mazeed-app`
- **Runtime**: `PHP`
- **Plan**: Free (or paid if needed)
- **Build Command**: `bash build.sh`
- **Start Command**: `php -S 0.0.0.0:$PORT public/index.php`

#### Environment Variables

Add the following environment variables in the Render dashboard:

```env
APP_NAME=Mazeed
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE  # Copy from your local .env
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

### 3. Set Up MySQL Database (Optional)

If using Render's MySQL service:

1. In Render Dashboard, click "New +" → "MySQL"
2. Configure your database
3. Copy the connection details
4. Use these details for the `DB_*` environment variables

Alternatively, you can use:
- AWS RDS
- DigitalOcean Managed Database
- Any other external MySQL service

### 4. Configure LinkedIn OAuth

1. Go to your LinkedIn App settings
2. Update the "Authorized redirect URLs" to:
   ```
   https://your-render-domain.onrender.com/auth/linkedin/callback
   ```

### 5. Deploy

1. Click "Deploy" in Render Dashboard
2. Monitor the build logs
3. Once deployment is complete, your app will be live at `https://your-render-domain.onrender.com`

### 6. Run Migrations (First Time)

After the first deployment, you may need to manually trigger migrations:

1. Go to your Render service dashboard
2. Click "Shell" to access the service shell
3. Run: `php artisan migrate`

Or, you can SSH into the service and run migrations manually.

## Important Notes

### Static Files & Public Directory
- Render serves static files from the `public/` directory
- Make sure to run `npm run build` to generate CSS/JS assets
- Badge images are stored in `storage/app/public/badges/`

### Environment Variables
- **APP_KEY**: Generate locally with `php artisan key:generate` and copy the value
- **DATABASE**: Render's free tier MySQL has limitations. For production, use a dedicated database service
- **LinkedIn Credentials**: Keep these secure! Use Render's environment variables, not in version control

### Performance
- Free tier Render services spin down after 15 minutes of inactivity
- For production, upgrade to a paid plan
- Consider using a cache driver like Redis for better performance

### Storage
- Temporary files are stored in `/tmp` (ephemeral)
- Persistent storage is in `storage/app/public/`
- For badge images: ensure they're being saved to `storage/app/public/badges/`

### Logs
- View logs in the Render dashboard under the service's "Logs" tab
- Set `LOG_CHANNEL=stderr` to ensure logs appear in Render's log viewer

## Troubleshooting

### Build Fails
- Check the build logs in Render Dashboard
- Ensure `build.sh` is executable: `chmod +x build.sh`
- Verify all environment variables are set

### Database Connection Errors
- Verify `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` are correct
- Check if the database is accessible from Render (firewall rules)
- Ensure the database exists and migrations have run

### Static Files Not Loading
- Run `npm run build` in your build command
- Check that files are in `public/` directory
- Verify `FILESYSTEM_DISK=public` is set

### LinkedIn OAuth Fails
- Verify `LINKEDIN_REDIRECT` matches your Render domain exactly
- Check LinkedIn app settings for correct redirect URI
- Ensure `LINKEDIN_CLIENT_ID` and `LINKEDIN_CLIENT_SECRET` are correct

## Updating the Application

1. Make changes locally
2. Push to GitHub
3. Render automatically detects the push
4. Deployment starts automatically (if set up with auto-deploy)
5. Monitor deployment in Render Dashboard

## Rolling Back

If a deployment fails:

1. Go to your service in Render Dashboard
2. Click "Deployments"
3. Select a previous successful deployment
4. Click "Deploy" to restore it

## Support

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Render Community Forum](https://community.render.com)
