# Quick Deployment Steps for College Submission

## What I've Done to Prepare the Project

✅ Updated frontend API configuration to use environment variable
✅ Created `.env.local` for local development
✅ Created `.env.production` template for production
✅ Created `Procfile` for Railway deployment
✅ Updated `.gitignore` to protect secrets
✅ Verified frontend builds successfully
✅ All changes are minimal - NO code logic changed

---

## MANUAL STEPS YOU NEED TO PERFORM (30-40 minutes)

### STEP 1: Push Code to GitHub (2 min)

```bash
cd c:\New folder\library-management-system

git add .
git commit -m "Prepare for deployment: add environment configuration"
git push origin main
```

If not already on Git:
```bash
git init
git add .
git commit -m "Initial commit with deployment configuration"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/library-management-system.git
git push -u origin main
```

---

### STEP 2: Deploy Backend to Railway (10-15 min)

**Create Railway Account:**
1. Go to https://railway.app
2. Click "Start Free" 
3. Sign up with GitHub (recommended)

**Deploy Backend:**
1. Click "New Project" → "Deploy from GitHub"
2. Authorize Railway to access your GitHub
3. Select your `library-management-system` repository
4. Select `/backend` folder

**Add Database:**
1. In the Railway dashboard, click "Add Plugin"
2. Search for "MySQL" and add it
3. Railway auto-configures the connection

**Set Environment Variables:**
Click your Laravel service → "Variables" and add:

```
APP_NAME=Library Management System
APP_ENV=production
APP_DEBUG=false
APP_URL={your-railway-url}
SANCTUM_STATEFUL_DOMAINS={your-vercel-url}.vercel.app,{your-railway-url}.railway.app
LOG_LEVEL=warning
```

(Railway auto-fills DB variables from MySQL plugin)

**Get Your Backend URL:**
- Railway will provide a URL like: `https://xxx.railway.app`
- This is your `{your-railway-url}` - **SAVE IT**

---

### STEP 3: Deploy Frontend to Vercel (5-10 min)

**Create Vercel Account:**
1. Go to https://vercel.com
2. Click "Sign Up"
3. Choose "Continue with GitHub"
4. Authorize Vercel

**Import and Deploy:**
1. Click "Add New" → "Project"
2. Click "Import Git Repository"
3. Select your `library-management-system` repo
4. Set "Root Directory" to `./frontend`

**Set Environment Variable:**
Before clicking "Deploy", in the "Environment Variables" section, add:

```
Name: NEXT_PUBLIC_API_URL
Value: https://xxx.railway.app/api
```

(Replace `xxx.railway.app` with your actual Railway URL from Step 2)

5. Click "Deploy"
6. Wait for deployment to complete (2-5 min)

**Get Your Frontend URL:**
- Vercel will provide: `https://xxx.vercel.app`
- This is your **COLLEGE SUBMISSION URL** - **SAVE IT**

---

### STEP 4: Verify Deployment Works (5-10 min)

1. Open your Vercel URL in browser
2. You should see the Login page
3. Log in with:
   ```
   Email: admin@library.com
   Password: 1234567
   ```
4. Verify these pages work:
   - Dashboard (shows statistics)
   - Books (shows book list)
   - Members (shows member list)
   - Categories
   - Issues
   - Returns
   - Fines
   - Reports
   - Logout

5. Check browser console (F12 → Console):
   - Should see NO errors
   - Should see NO localhost references
   - Should see NO CORS errors

6. Check Network tab (F12 → Network):
   - Click any menu item
   - Verify API requests go to your Railway URL
   - Should NOT go to localhost

---

## YOUR FINAL URLS

After completing all steps:

**COLLEGE SUBMISSION URL:**
```
https://xxx.vercel.app
```

**BACKEND API:**
```
https://xxx.railway.app/api
```

**DEMO CREDENTIALS:**
```
Email: admin@library.com
Password: 1234567
```

---

## TROUBLESHOOTING

### Login fails or gets 422/401 error
- Check that `NEXT_PUBLIC_API_URL` is set correctly in Vercel
- Check that Railway migrations ran (check Railway logs)

### Frontend loads but pages show "Loading..." forever
- Open F12 → Network tab
- Check that API requests are going to Railway (not localhost)
- If showing CORS errors, verify Railway environment variables are set

### 404 errors on API endpoints
- Check Railway logs to see if migrations ran
- If not, Railway needs to run: `php artisan migrate --force`
- This should happen automatically in the Procfile

### Cannot find database
- Verify MySQL plugin is added in Railway
- Verify DB credentials are auto-filled in environment variables

---

## LOCAL DEVELOPMENT STILL WORKS

Your local setup continues to work unchanged:

```bash
# Terminal 1 - Backend
cd backend
php artisan serve

# Terminal 2 - Frontend  
cd frontend
npm run dev
```

Open http://localhost:3000 - uses `.env.local` pointing to localhost backend.

---

## IMPORTANT REMINDERS

✅ DO commit:
- Frontend code
- Backend code
- `.env.example` and `.env.production` (templates only, no secrets)
- `Procfile`

❌ DO NOT commit:
- `.env.local` (ignored by .gitignore)
- `.env` (ignored by .gitignore)
- Real passwords or API keys

---

## COSTS

- **Vercel**: Free for Next.js projects
- **Railway**: Free trial first, then $5-10/month
- **Total**: Essentially free during trial, low cost after

---

## STILL NEED HELP?

Check the full guide: `DEPLOYMENT_GUIDE.md` in the project root
