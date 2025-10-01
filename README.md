## Implementation

Cloning and installing dependencies.
```bash
git clone https://github.com/steevewu/health-appointment-booking.git
cd health-appointment-booking

# install dependencies
composer install
```


Database configuration

```bash
cp .env.example .env
```

Modify the database credentials in `.env` as yours, then run:

```bash
php artisan key:generate
php artisan migration:fresh
php artisan serve
```