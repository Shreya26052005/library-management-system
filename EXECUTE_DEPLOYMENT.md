# EXECUTE DEPLOYMENT - Step by Step

## Verification Status ✓

This document guides you through EXACTLY what to do to deploy your project publicly.

---

## ⚠️ IMPORTANT: What I Cannot Do

I cannot directly:
- Create Railway/Vercel accounts (requires manual signup)
- Push to GitHub (requires your GitHub credentials)
- Deploy to external services (requires dashboard access)
- Set environment variables on external platforms

**What I CAN provide**: Exact steps, exact commands, and exact values to use.

---

## STEP 1: VERIFY PROJECT IS READY ✓

### 1a. Verify .env.local won't be committed

```bash
cd c:\New folder\library-management-system\frontend
cat .gitignore | grep "\.env"
```

**Expected Output**: 
```
.env*
```

**What this means**: `.env.local` will NOT be committed to GitHub ✓

### 1b. Verify api.ts uses environment variable

```bash
cd c:\New folder\library-management-system\frontend
cat lib/api.ts | grep "process.env.NEXT_PUBLIC_API_URL"
```

**Expected Output**:
```
const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
```

**What this means**: Frontend will use production URL when deployed ✓

### 1c. Verify no hardcoded localhost in code

```bash
cd c:\New folder\library-management-system\frontend
find . -name "*.ts" -o -name "*.tsx" -o -name "*.js" | xargs grep -l "localhost:8000" 2>/dev/null || echo "✓ No hardcoded localhost URLs found"
```

**Expected Output**: 
```
✓ No hardcoded localhost URLs found
```

**What this means**: No localhost URLs hardcoded in production code ✓

### 1d. Verify Procfile exists for Railway

```bash
cd c:\New folder\library-management-system\backend
cat Procfile
```

**Expected Output**:
```
web: composer install --no-dev && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

**What this means**: Railway knows how to start your backend ✓

---

## STEP 2: PREPARE GITHUB

### 2a. Initialize Git (if not already initialized)

```bash
cd c:\New folder\library-management-system

# Check if git is initialized
git status
```

If you see an error "not a git repository", run:
```bash
git init
git config user.email "your-email@college.edu"
git config user.name "Your Name"
```

### 2b. Add all deployment files

```bash
cd c:\New folder\library-management-system

git add -A
git status
```

**What you'll see**: All new files staged (green):
- `frontend/.env.local`
- `frontend/.env.example`
- `backend/.env.production`
- `backend/Procfile`
- `DEPLOYMENT_GUIDE.md`
- `QUICK_DEPLOYMENT_STEPS.md`
- etc.

### 2c. Create first commit

```bash
git commit -m "chore: prepare project for production deployment

- Add environment configuration for Railway + Vercel
- Create Procfile for backend deployment
- Add deployment documentation
- Configure frontend to use NEXT_PUBLIC_API_URL environment variable
- Maintain local development configuration in .env.local

No code logic changes - only deployment configuration."
```

### 2d. Add GitHub remote (if not already added)

```bash
git remote add origin https://github.com/YOUR_USERNAME/library-management-system.git
git branch -M main
```

### 2e. Push to GitHub

```bash
git push -u origin main
```

**⚠️ You will need:**
- GitHub account (free at https://github.com)
- Your GitHub username
- Your GitHub personal access token (for password authentication)

**After this step**: Your code is on GitHub ✓

---

## STEP 3: DEPLOY BACKEND TO RAILWAY

### 3a. Create Railway Account

1. Go to https://railway.app
2. Click "Get Started"
3. Click "GitHub" (easiest option)
4. Authorize Railway to access your GitHub account
5. Click "Create New Project"

### 3b. Connect Your Repository

1. Railway will show "Deploy from GitHub"
2. Select your `library-management-system` repository
3. Click "Deploy Now"

### 3c. Configure Root Directory

Railway should auto-detect, but if not:
1. Go to your project settings
2. Set "Root Directory" to `/backend`

### 3d. Add MySQL Database

1. In Railway dashboard, click "Add Plugin"
2. Search for "MySQL"
3. Click to add
4. Railway auto-generates database credentials

### 3e. Set Environment Variables

In Railway dashboard for your **Laravel service**:

Click on the service → Variables tab → Add these:

```
APP_NAME                = Library Management System
APP_ENV                 = production
APP_DEBUG               = false
APP_URL                 = (keep blank, Railway will add)
LOG_LEVEL               = warning
SANCTUM_STATEFUL_DOMAINS = (will update after Vercel deployment)
```

**Database variables** (auto-filled by MySQL plugin):
- `DB_CONNECTION` = mysql
- `DB_HOST` = (auto-filled)
- `DB_PORT` = 3306
- `DB_USERNAME` = (auto-filled)
- `DB_PASSWORD` = (auto-filled)
- `DB_DATABASE` = railway

### 3f. Wait for Deployment

1. Railway will build your Laravel app
2. Check the logs for "Server running on..."
3. Wait until you see a green checkmark ✓

### 3g. Get Your Railway Backend URL

1. In Railway dashboard, click your project
2. Look for "Public URL" or "Domain"
3. Copy the URL (looks like: `https://xxx.railway.app`)
4. **SAVE THIS** - you need it for Step 4

**After this step**: Your backend is live ✓

---

## STEP 4: DEPLOY FRONTEND TO VERCEL

### 4a. Create Vercel Account

1. Go to https://vercel.com
2. Click "Sign Up"
3. Choose "Continue with GitHub"
4. Authorize Vercel to access your GitHub account

### 4b. Import Your Project

1. Click "Add New" → "Project"
2. Click "Import Git Repository"
3. Find and select `library-management-system`
4. Click "Import"

### 4c. Configure Root Directory

1. Vercel will show project settings
2. Scroll to "Root Directory"
3. Change to `./frontend`

### 4d. Add Environment Variable

Before clicking Deploy:

1. Scroll to "Environment Variables"
2. Add a new variable:
   ```
   Name:  NEXT_PUBLIC_API_URL
   Value: https://xxx.railway.app/api
   ```
   (Replace `xxx.railway.app` with your Railway URL from Step 3g)

3. Make sure "Production" is selected

### 4e. Deploy

1. Click "Deploy"
2. Wait for deployment to complete (2-5 minutes)
3. You'll see "Congratulations! Your project has been deployed"

### 4f. Get Your Vercel Frontend URL

1. Look for "Domains" section
2. Copy the URL (looks like: `https://xxx.vercel.app`)
3. **SAVE THIS** - this is your college submission URL

**After this step**: Your frontend is live ✓

---

## STEP 5: UPDATE BACKEND SANCTUM CONFIGURATION

Now that you have both URLs, update Railway:

### 5a. Go Back to Railway

1. Open https://railway.app
2. Select your project
3. Click on the Laravel service
4. Click "Variables"

### 5b. Update SANCTUM_STATEFUL_DOMAINS

Find the `SANCTUM_STATEFUL_DOMAINS` variable and update:

```
SANCTUM_STATEFUL_DOMAINS = your-vercel-frontend.vercel.app,your-railway-backend.railway.app
```

**Example**:
```
SANCTUM_STATEFUL_DOMAINS = my-library.vercel.app,my-library-backend.railway.app
```

### 5c. Railway Auto-Redeploys

Once you save, Railway will automatically redeploy with the new configuration.

**After this step**: Authentication is configured ✓

---

## STEP 6: VERIFY EVERYTHING WORKS

### 6a. Open Your Frontend

1. Open your Vercel URL in browser:
   ```
   https://xxx.vercel.app
   ```

2. You should see the Login page

### 6b. Test Login

1. Enter email: `admin@library.com`
2. Enter password: `1234567`
3. Click "Login"

**Expected**: Redirects to Dashboard

### 6c. Test Dashboard

You should see:
- Dashboard title
- Statistics cards (Total Books, Available Books, etc.)
- Quick Actions (Add Book, Add Member, Issue Book, Return Book)
- System Info

### 6d. Test Each Page

Click these in the sidebar and verify they load:

- [ ] Dashboard (loads data)
- [ ] Books (shows list of books)
- [ ] Categories (shows list of categories)
- [ ] Members (shows list of members)
- [ ] Issue Books (shows issued books)
- [ ] Return Books (shows returned books)
- [ ] Fines (shows fines)
- [ ] Reports (shows report data)

### 6e. Test Logout

1. Click "Logout" button
2. Should redirect to Login page
3. Try refreshing - should still be on Login page (token cleared)

### 6f. Verify No Errors in Browser

1. Open browser DevTools: Press **F12**
2. Go to **Console** tab
3. Verify NO red error messages
4. Verify NO "localhost" references

### 6g. Verify Network Calls

1. Open **Network** tab in DevTools
2. Click on a page (e.g., "Books")
3. Look at the API calls
4. Verify they show your Railway URL (not localhost)
5. Verify status is 200 (not 404 or 500)

**After this step**: Deployment verified ✓

---

## STEP 7: COLLEGE SUBMISSION

### Your Final URLs:

**COLLEGE OPENS THIS URL:**
```
https://your-vercel-frontend.vercel.app
```

**TEST CREDENTIALS:**
```
Email:    admin@library.com
Password: 1234567
```

**BACKEND API (reference only):**
```
https://your-railway-backend.railway.app/api
```

### How College Can Access

1. They open your Vercel URL in any browser
2. They see the Login page
3. They log in with the credentials above
4. They can access all functionality from any device/browser
5. Works on desktop, tablet, mobile

---

## TROUBLESHOOTING

### Issue: Login fails with error
**Solution**: 
1. Check F12 Console - what's the error?
2. Check Network tab - is the API call reaching Railway?
3. Verify `NEXT_PUBLIC_API_URL` is set correctly in Vercel

### Issue: "Cannot reach localhost:8000" error
**Solution**:
1. Go to Vercel dashboard
2. Verify `NEXT_PUBLIC_API_URL` environment variable is set
3. Redeploy the frontend

### Issue: CORS error in console
**Solution**:
1. Go to Railway dashboard
2. Verify `SANCTUM_STATEFUL_DOMAINS` is set correctly
3. Wait for Railway to redeploy

### Issue: API returns 404
**Solution**:
1. Check Railway logs
2. Verify migrations ran (should see "Migrating" in logs)
3. If not, migrations might have failed
4. Check that Procfile is correct

### Issue: Database connection error
**Solution**:
1. Verify MySQL plugin is added in Railway
2. Verify database variables are auto-filled
3. Check Railway logs for connection errors

---

## CHECKSUM VERIFICATION

After all steps, you should have:

**Frontend URL**: `https://xxx.vercel.app`
**Backend URL**: `https://xxx.railway.app`
**Demo Login**: `admin@library.com` / `1234567`
**All Pages Working**: ✓
**No Errors in Console**: ✓
**No localhost references**: ✓

---

## EXACT COMMANDS TO RUN LOCALLY

If you need to re-deploy or troubleshoot locally:

```bash
# Build frontend
cd c:\New folder\library-management-system\frontend
npm run build

# Should see "✓ Generating static pages" with no errors

# Start backend locally
cd c:\New folder\library-management-system\backend
php artisan serve
# Should see "INFO  Server running on [http://127.0.0.1:8000]"

# Start frontend locally (in new terminal)
cd c:\New folder\library-management-system\frontend
npm run dev
# Should see "✓ Ready in X.Xs"

# Open http://localhost:3000
# Should see Login page
```

---

## FINAL CHECKLIST

- [ ] Project pushed to GitHub
- [ ] Backend deployed to Railway
- [ ] Frontend deployed to Vercel
- [ ] SANCTUM_STATEFUL_DOMAINS updated
- [ ] Login works at public URL
- [ ] All pages work
- [ ] No errors in console
- [ ] No localhost in network calls
- [ ] URLs saved for college submission
- [ ] Demo credentials ready
- [ ] Tested from fresh browser (not localhost)

---

## SUBMIT TO COLLEGE

Email your college:

**Subject**: Library Management System - College Submission

**Body**:
```
Dear [College Name],

Please access the Library Management System at:

URL: https://your-vercel-frontend.vercel.app

Demo Credentials:
Email: admin@library.com
Password: 1234567

The system includes:
- Authentication with secure token-based login
- Book management (add, edit, delete, search)
- Member management
- Book issue/return tracking
- Fine management
- Reports and analytics
- Dashboard with statistics

All pages are fully functional and the database is pre-populated with sample data for demonstration.

Backend API: https://your-railway-backend.railway.app/api

Thank you,
[Your Name]
```

---

## ✅ YOU'RE DONE!

Once all steps are complete, you have successfully deployed your Library Management System to production!

**Timeline**: 30-40 minutes  
**Cost**: Free (trial)  
**Status**: Production Ready  
**Ready for College**: ✓  

