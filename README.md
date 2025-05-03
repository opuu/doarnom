# Adoption

## API

Import adoption.sql in mysql database, then configure the `/api/App/Configs/Config.php` file.

Then run:

```bash
cd api

php -S localhost:8000 -t public/
```

## Website

```bash
cd website

pnpm i

pnpm run dev
```
