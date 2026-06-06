# Docker Setup Guide

This project is now configured with Docker for easy local development and deployment to Render.com.

## Quick Start - Local Development

### Prerequisites
- Docker Desktop installed and running ([download here](https://www.docker.com/products/docker-desktop))
- Docker Compose (included with Docker Desktop)

### Getting Started

1. **Clone or pull the latest code**
   ```bash
   git pull origin main
   ```

2. **Start the Docker containers**
   ```bash
   docker-compose up -d
   ```
   This will:
   - Build the Laravel application container
   - Start MySQL database
   - Start phpMyAdmin for database management

3. **Access the application**
   - **Laravel App**: http://localhost:8000
   - **phpMyAdmin**: http://localhost:8080 (username: `mazeed_user`, password: `mazeed_password`)

4. **Run migrations** (first time only)
   ```bash
   docker-compose exec app php artisan migrate
   ```

5. **View logs**
   ```bash
   docker-compose logs -f app
   ```

## Common Docker Commands

### Start Services
```bash
docker-compose up -d
```

### Stop Services
```bash
docker-compose down
```

### Run Artisan Commands
```bash
# Migrate database
docker-compose exec app php artisan migrate

# Create seeder data
docker-compose exec app php artisan db:seed

# Interactive shell (Tinker)
docker-compose exec app php artisan tinker

# Generate app key
docker-compose exec app php artisan key:generate
```

### Access Container Shell
```bash
docker-compose exec app bash
```

### View Service Logs
```bash
# All services
docker-compose logs -f

# Just the app
docker-compose logs -f app

# Just the database
docker-compose logs -f db
```

### Rebuild Docker Image
```bash
docker-compose build --no-cache
```

### Remove Everything (including database!)
```bash
docker-compose down -v
```

## Project Structure

- **Dockerfile**: PHP 8.3 + Apache configuration for production
- **docker-compose.yml**: Local development setup with MySQL and phpMyAdmin
- **.dockerignore**: Files excluded from Docker build context
- **render.yaml**: Production deployment configuration for Render.com

## Environment Variables

### Local Development (.env)
Already configured in `docker-compose.yml`:
- `DB_HOST=db` (Docker container name)
- `DB_PORT=3306`
- `DB_DATABASE=mazid`
- `DB_USERNAME=mazeed_user`
- `DB_PASSWORD=mazeed_password`

### Production (Render.com)
Set these in Render dashboard:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` (your actual database)
- `LINKEDIN_CLIENT_ID`, `LINKEDIN_CLIENT_SECRET`

## Database Management

### Via phpMyAdmin
- URL: http://localhost:8080
- Server: `db`
- Username: `mazeed_user`
- Password: `mazeed_password`

### Via Command Line
```bash
# Enter MySQL shell
docker-compose exec db mysql -u mazeed_user -pmazeed_password mazid

# Run query
docker-compose exec db mysql -u mazeed_user -pmazeed_password mazid -e "SELECT * FROM users;"
```

## File Permissions

Docker automatically handles file permissions. No need to manually set them.

## Troubleshooting

### Ports Already in Use
If you get "port already allocated" errors:

**Option 1**: Stop the services using those ports
```bash
docker-compose down
```

**Option 2**: Change ports in `docker-compose.yml`
```yaml
ports:
  - "8001:80"      # Change 8000 to 8001
  - "8081:80"      # Change 8080 to 8081
```

### Container Exits Immediately
Check logs:
```bash
docker-compose logs app
```

Common causes:
- Missing environment variables
- Database not ready yet (wait a few seconds)
- PHP or Apache errors

### Database Connection Failed
Make sure database is running:
```bash
docker-compose ps
```

Both `app` and `db` should be "Up". If not, restart:
```bash
docker-compose restart db
```

### Changes Not Reflecting
If you modify code but don't see changes:

1. **For PHP code**: Usually reflects automatically
2. **For npm assets**: Rebuild
   ```bash
   docker-compose exec app npm run build
   ```
3. **For Dockerfile changes**: Rebuild image
   ```bash
   docker-compose build --no-cache
   ```

## Performance Tips

- On macOS/Windows: Docker Desktop shares files slower than native Linux
- For better performance on Mac: consider moving project to Docker volume
- Keep `.dockerignore` updated to exclude unnecessary files

## Deployment to Render.com

Once you have everything working locally:

1. Push to GitHub
2. Connect Render to your GitHub repository
3. Create a Web Service with Docker runtime
4. Set environment variables
5. Deploy!

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed instructions.

## Support

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Docker Documentation](https://laravel.com/docs/deployment#docker)
- [Render.com Docker Guide](https://render.com/docs/deploy-docker)

---

**Happy coding! 🚀**
