# Library Management System - Deployment Guide

## Overview

This guide will help you deploy your Library Management System to a public URL that you can submit to your college.

**Recommended Architecture:**
- **Frontend**: Vercel (Free tier)
- **Backend**: Railway (Free tier + Pay-as-you-go)
- **Database**: Railway MySQL (Included)

**Estimated Cost**: Free trial, then ~$5-10/month after trial

---

## PART A: DEPLOY BACKEND TO RAILWAY

### Step 1: Prepare Railway Account

1. Go to https://railway.app
2. Sign up with GitHub (easiest option)
3. Create a new project

### Step 2: Connect Your GitHub Repository

1. In Railway dashboard, click "New Project"
2. Select "Deploy from GitHub"
3. Authorize Railway to access your GitHub
4. Select your repository
5. Select the `/backend` directory as the root

### Step 3: Add MySQL Database

1. In the Railway project, click "Add Plugin"
2. Search for and add "MySQL"
3. Railway will automatically configure the database connection

### Step 4: Configure Environment Variables in Railway

In the Railway dashboard for your Laravel service, set these environment variables:

```
APP_NAME=Library Management System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:67Ts+ut3TWvT1yNZcneF9Sg3LUyzktueRSxzVi8+1Iw=
APP_URL=https://{your-railway-backend-url}
LOG_LEVEL=warning
SANCTUM_STATEFUL_DOMAINS={your-frontend-vercel-url}.vercel.app,{your-railway-backend-url}.railway.app
```

**Note**: Railroad will auto-generate these from MySQL plugin:
- `DB_CONNECTION=mysql`
- `DB_HOST` (auto-filled)
- `DB_PORT=3306`
- `DB_USERNAME` (auto-filled)
- `DB_PASSWORD` (auto-filled)
- `DB_DATABASE=railway`

### Step 5: Set up Procfile for Railway

Create a file named `Procfile` in your `/backend` directory:

```
web: composer install && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

Push this to your repository.

### Step 6: Get Your Backend URL

After deployment, Railway will provide a URL like:
```
https://your-railway-backend-url.railway.app
```

**Save this URL** - you'll need it for the frontend.

---

## PART B: DEPLOY FRONTEND TO VERCEL

### Step 1: Prepare Vercel Account

1. Go to https://vercel.com
2. Sign up with GitHub
3. Import your project

### Step 2: Import Project

1. Click "Add New" → "Project"
2. Select "Import Git Repository"
3. Select your repository
4. Set the root directory to `/frontend`

### Step 3: Configure Environment Variables

Before deploying, add this environment variable in Vercel:

**Name:** `NEXT_PUBLIC_API_URL`  
**Value:** `https://your-railway-backend-url.railway.app/api`

(Replace with your actual Railway backend URL from Step A.6)

### Step 4: Deploy

Click "Deploy" and wait for completion.

After deployment, Vercel will provide a URL like:
```
https://your-frontend-name.vercel.app
```

**Save this URL** - this is your college submission URL.

### Step 5: Update Backend CORS (if needed)

If you want to restrict CORS to only your frontend, edit `/backend/config/cors.php` and update:

```php
'allowed_origins' => [
    'https://your-frontend-name.vercel.app',
    'https://your-railway-backend-url.railway.app',
],
```

Then push to GitHub and Railway will redeploy automatically.

---

## PART C: VERIFY DEPLOYMENT

### Test the Deployed Application

1. Open your frontend URL in browser:
   ```
   https://your-frontend-name.vercel.app
   ```

2. Test login with credentials:
   ```
   Email: admin@library.com
   Password: 1234567
   ```

3. Verify these work:
   - Dashboard page loads and shows data
   - Click on "Books" - books list loads
   - Click on "Members" - members list loads
   - Click on "Issues" - issues list loads
   - Logout button works
   - Token is properly revoked after logout

### Check Browser Console

1. Open DevTools (F12)
2. Go to Console tab
3. Verify NO errors appear:
   - No CORS errors
   - No "localhost" references
   - No 401/403 errors

4. Go to Network tab
5. Click a page button and verify API calls go to your Railway URL (not localhost)

---

## PART D: DEMO CREDENTIALS FOR YOUR COLLEGE

Use these credentials to demonstrate the system:

**Test Account:**
```
Email: admin@library.com
Password: 1234567
Role: Administrator
```

**Sample Data Included:**
- 16 Books (Fiction, Science, History, Programming, Business)
- 10 Members (Students)
- 5 Categories
- Book issues and returns
- Fines for overdue books

---

## TROUBLESHOOTING

### Issue: Frontend shows "Cannot POST /login"
**Solution**: Check that `NEXT_PUBLIC_API_URL` is correctly set in Vercel environment variables.

### Issue: CORS errors in browser console
**Solution**: Verify `SANCTUM_STATEFUL_DOMAINS` in Railway includes both your frontend and backend URLs.

### Issue: 404 Not Found for API endpoints
**Solution**: 
1. Verify Railway has the latest code (check GitHub commits)
2. Ensure migrations ran: Check Railway logs for `php artisan migrate`
3. Verify database connection in Railway

### Issue: Login fails with 422 error
**Solution**: Database might not be migrated. Railway should run migrations automatically, but you can manually trigger:
1. In Railway terminal, run: `php artisan migrate:fresh --seed`
2. This resets database and seeds demo data

### Issue: Database connection error
**Solution**:
1. In Railway, verify MySQL plugin is added
2. Check Database credentials match in environment variables
3. Verify DB host and port are Railway-provided values

---

## URLS YOU'LL NEED

After deployment, you'll have three URLs:

1. **College Submission URL** (Frontend):
   ```
   https://your-frontend-name.vercel.app
   ```

2. **Backend API URL**:
   ```
   https://your-railway-backend-url.railway.app/api
   ```

3. **For Testing Direct API** (optional):
   ```
   https://your-railway-backend-url.railway.app/api/login
   POST request with: {"email":"admin@library.com","password":"1234567"}
   ```

---

## FILES CHANGED FOR DEPLOYMENT

**Frontend:**
- `lib/api.ts` - Uses environment variable for API URL
- `.env.local` - Local development configuration
- `.env.example` - Production template

**Backend:**
- `.env.production` - Production configuration template
- `Procfile` - Deployment instructions for Railway

**No code logic was changed** - only configuration for deployment.

---

## LOCAL DEVELOPMENT STILL WORKS

Your local development setup continues to work:

```bash
# Terminal 1 - Backend
cd backend
php artisan serve

# Terminal 2 - Frontend
cd frontend
npm run dev
```

Frontend will use `.env.local` and point to `http://localhost:8000/api`

---

## IMPORTANT NOTES

1. **DO NOT commit `.env` files** with real secrets
2. **Use `.env.local`** for local development (not committed)
3. **Use Railway/Vercel dashboard** for production secrets
4. **APP_KEY must be the same** in production (already generated)
5. **Database migrations run automatically** on Railway deployment
6. **Seeded demo data** is included in the first migration run

---

## ESTIMATED TIMELINE

- **Setup**: 10-15 minutes (account creation)
- **Backend Deployment**: 5-10 minutes (Railway auto-deploys)
- **Frontend Deployment**: 2-5 minutes (Vercel auto-deploys)
- **Testing**: 5-10 minutes
- **Total**: ~30-40 minutes

---

## SUPPORT

If you encounter issues:

1. Check Railway logs: Railway Dashboard → Your Project → Logs
2. Check Vercel logs: Vercel Dashboard → Your Project → Deployments → View Logs
3. Verify environment variables are set correctly in both platforms
4. Ensure your database has been migrated (check Railway logs)

