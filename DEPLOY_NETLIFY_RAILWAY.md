# Deploy on Netlify + Railway

## Overview

This guide deploys your Library Management System with:
- **Frontend**: Netlify (FREE)
- **Backend**: Railway (FREE trial, then $5-10/month)
- **Database**: Railway MySQL (included)

**Total Time**: 30-40 minutes  
**Cost**: FREE (trial)

---

## Prerequisites

Create these free accounts:
1. **GitHub** - https://github.com (for code storage)
2. **Netlify** - https://netlify.com (for frontend)
3. **Railway** - https://railway.app (for backend & database)

---

## STEP 1: Push Code to GitHub (2 minutes)

### 1.1 Initialize Git (if not done)

```bash
cd "c:\New folder\library-management-system"

# Check if git exists
git status
```

If error, initialize:
```bash
git init
git config user.email "your-email@college.edu"
git config user.name "Your Name"
```

### 1.2 Commit Everything

```bash
cd "c:\New folder\library-management-system"

git add .
git commit -m "chore: prepare for Netlify + Railway deployment

- Add Netlify configuration (netlify.toml)
- Update environment setup for production
- Configure frontend API URL via environment variables
- Add Procfile for Railway backend
- Ready for public deployment"
```

### 1.3 Create GitHub Repository

If you haven't already:

1. Go to https://github.com/new
2. Create repository: `library-management-system`
3. Choose public or private
4. Copy the commands shown

Run the commands:
```bash
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/library-management-system.git
git push -u origin main
```

**✓ Your code is now on GitHub**

---

## STEP 2: Deploy Backend to Railway (10-15 minutes)

### 2.1 Create Railway Account

1. Go to https://railway.app
2. Click "Get Started"
3. Sign in with GitHub (recommended)
4. Authorize Railway

### 2.2 Deploy Backend

1. Click "Create New Project"
2. Select "Deploy from GitHub"
3. Select your `library-management-system` repository
4. Railway shows deployment options

### 2.3 Configure Backend Service

1. Wait for Railway to detect your project
2. Click on the detected service
3. Go to "Settings"
4. Set "Root Directory" to `/backend`

### 2.4 Add MySQL Database

1. In your Railway project, click "Add Plugin"
2. Search for "MySQL"
3. Click to add MySQL
4. Railway auto-configures database connection

### 2.5 Set Environment Variables

In Railway, for your Laravel service, go to "Variables" tab:

Add these variables:

```
APP_NAME=Library Management System
APP_ENV=production
APP_DEBUG=false
SANCTUM_STATEFUL_DOMAINS=your-netlify-url.netlify.app,your-railway-url.railway.app
LOG_LEVEL=warning
```

Database variables are auto-filled by Railway MySQL plugin:
- DB_CONNECTION=mysql
- DB_HOST=(auto)
- DB_PORT=3306
- DB_USERNAME=(auto)
- DB_PASSWORD=(auto)
- DB_DATABASE=railway

### 2.6 Wait for Deployment

1. Railway auto-deploys
2. Check logs for "Server running..."
3. Wait until green checkmark appears
4. This takes 10-15 minutes

### 2.7 Save Your Railway URL

Look for "Public URL" or "Domain" in Railway:
```
https://xxx.railway.app
```

**Save this - you need it for the frontend!**

---

## STEP 3: Deploy Frontend to Netlify (5-10 minutes)

### 3.1 Create Netlify Account

1. Go to https://netlify.com
2. Click "Sign Up"
3. Choose "GitHub" (easiest)
4. Authorize Netlify

### 3.2 Create New Site

1. Click "Add new site"
2. Select "Import an existing project"
3. Choose GitHub provider
4. Authorize Netlify to access GitHub

### 3.3 Select Your Repository

1. Find `library-management-system`
2. Click to select it

### 3.4 Configure Build Settings

Netlify should auto-detect, but verify:

**Build command:**
```
npm run build
```

**Publish directory:**
```
.next
```

**Root directory:**
```
frontend
```

### 3.5 Add Environment Variables

**IMPORTANT:** Before clicking Deploy!

1. Scroll to "Environment variables"
2. Add this variable:

```
NEXT_PUBLIC_API_URL = https://xxx.railway.app/api
```

(Replace `xxx.railway.app` with your actual Railway URL from Step 2.7)

### 3.6 Deploy

1. Click "Deploy site"
2. Wait for deployment (2-5 minutes)
3. Netlify shows "Site deployed"
4. You get a URL like: `https://xxx.netlify.app`

### 3.7 Save Your Netlify URL

```
https://xxx.netlify.app
```

**This is your college submission URL!**

---

## STEP 4: Update Backend Configuration (2 minutes)

Now that you have both URLs, update Railway:

### 4.1 Go Back to Railway

1. Open https://railway.app
2. Select your project
3. Click on your Laravel service
4. Go to "Variables"

### 4.2 Update SANCTUM_STATEFUL_DOMAINS

Find or add this variable:

```
SANCTUM_STATEFUL_DOMAINS=your-netlify-url.netlify.app,your-railway-url.railway.app
```

**Example:**
```
SANCTUM_STATEFUL_DOMAINS=my-library.netlify.app,my-library-backend.railway.app
```

### 4.3 Railway Auto-Redeploys

Once you save, Railway automatically redeploys with the new configuration.

**✓ Authentication is now configured**

---

## STEP 5: Verify Everything Works (10 minutes)

### 5.1 Open Your Frontend URL

```
https://your-netlify-url.netlify.app
```

You should see the Login page.

### 5.2 Test Login

```
Email:    admin@library.com
Password: 1234567
```

Should redirect to Dashboard.

### 5.3 Test All Pages

Click through and verify:
- ✓ Dashboard (shows statistics)
- ✓ Books (shows book list)
- ✓ Members (shows member list)
- ✓ Categories
- ✓ Issues
- ✓ Returns
- ✓ Fines
- ✓ Reports
- ✓ Logout (clears session)

### 5.4 Check for Errors

1. Open browser DevTools (F12)
2. Go to "Console" tab
3. Verify NO red error messages
4. Go to "Network" tab
5. Click a page button
6. Verify API calls show your Railway URL (not localhost)
7. Verify status is 200 (not 404/500)

### 5.5 Test Refresh

1. Refresh the page after login
2. Should stay logged in (token persists)
3. Try logging out and refreshing
4. Should go to login page

**✓ Everything is working!**

---

## Your Final URLs

After completing all steps:

```
FRONTEND (College Submission):
https://xxx.netlify.app

BACKEND API:
https://xxx.railway.app/api

DEMO LOGIN:
Email:    admin@library.com
Password: 1234567
```

---

## Netlify-Specific Features

### Automatic Redeploys

Every time you push to GitHub:
1. Netlify detects the push
2. Builds your project
3. Deploys automatically
4. No manual action needed

### Custom Domain (Optional)

Netlify lets you add a custom domain:
1. Go to Netlify dashboard
2. Click on your site
3. Go to "Domain management"
4. Add your own domain

### Preview Deploys

Every pull request gets a preview URL:
1. Push to a new branch
2. Create pull request on GitHub
3. Netlify creates preview URL
4. Test before merging to main

---

## Troubleshooting

### Issue: Frontend loads but login fails

**Check:**
1. Go to Netlify dashboard
2. Click your site
3. Go to "Site settings" → "Environment variables"
4. Verify `NEXT_PUBLIC_API_URL` is set correctly
5. Should be your Railway URL + `/api`

**Fix:**
1. Update the environment variable
2. Trigger a redeploy (go to "Deploys" → "Trigger deploy")

### Issue: CORS error in console

**Fix:**
1. Go to Railway dashboard
2. Find your Laravel service
3. Update `SANCTUM_STATEFUL_DOMAINS` with both URLs
4. Railway auto-redeploys
5. Wait 2-3 minutes for changes to take effect

### Issue: API returns 404

**Fix:**
1. Check Railway logs
2. Look for "Migrating..." messages
3. Should show all migrations running
4. If not, migrations may have failed
5. Check that Procfile is in /backend folder

### Issue: Netlify deployment fails

**Check:**
1. Go to Netlify dashboard
2. Click your site
3. Go to "Deploys"
4. Click the failed deploy
5. Scroll down to see error messages

**Common issues:**
- Wrong build command (should be `npm run build`)
- Wrong publish directory (should be `.next`)
- Wrong root directory (should be `frontend`)
- Missing environment variable

### Issue: Can't push to GitHub

**Fix:**
```bash
# Generate GitHub Personal Access Token
# Go to: https://github.com/settings/tokens
# Create token with 'repo' permissions
# Use token as password when pushing

git push origin main
# When asked for password, paste the token
```

---

## Monitoring Your Deployment

### Netlify Dashboard
- Check build logs
- Monitor bandwidth
- See deploy history
- Configure domain

### Railway Dashboard
- Check logs for errors
- Monitor backend performance
- See database status
- Manage environment variables

### Browser DevTools
- F12 Console: Check for JavaScript errors
- Network tab: Verify API calls reach Railway
- Application tab: Check localStorage (token storage)

---

## Next Steps After Deployment

### 1. Custom Domain (Optional)
Add your own domain to Netlify instead of xxx.netlify.app

### 2. Custom Monitoring
Set up error monitoring with services like:
- Sentry (error tracking)
- LogRocket (frontend logging)

### 3. Backup Database
Set up automatic backups in Railway for MySQL

### 4. Scale if Needed
If you get more users, Railway can auto-scale

---

## College Submission Template

Email your college:

```
Subject: Library Management System - Public Demo URL

Dear [College Name],

Please access my Library Management System at:
https://your-netlify-url.netlify.app

Demo Credentials:
Email:    admin@library.com
Password: 1234567

FEATURES INCLUDED:
✓ User authentication with secure login/logout
✓ Dashboard with library statistics
✓ Book management (add, edit, delete, search)
✓ Member management
✓ Book issuance tracking
✓ Book return management
✓ Fine management for overdue books
✓ Reports and analytics
✓ Responsive design (works on desktop, tablet, mobile)

The system is fully functional and accessible from any browser
without requiring any installation. It includes pre-populated
sample data for demonstration purposes.

Backend API available at: https://your-railway-url.railway.app/api

Thank you for evaluating my work.

Best regards,
[Your Name]
```

---

## Summary

| Step | Time | Platform | Action |
|------|------|----------|--------|
| 1 | 2 min | GitHub | Push code |
| 2 | 10-15 min | Railway | Deploy backend + database |
| 3 | 5-10 min | Netlify | Deploy frontend |
| 4 | 2 min | Railway | Configure domains |
| 5 | 10 min | Browser | Test everything |
| **TOTAL** | **~40 min** | | **Ready to submit!** |

---

## Final Checklist

Before submitting to college:

- [ ] Frontend URL works
- [ ] Login works
- [ ] Dashboard loads
- [ ] All pages load
- [ ] Logout works
- [ ] No console errors
- [ ] API calls go to Railway (not localhost)
- [ ] Page refresh works (token persists)
- [ ] Tested from fresh browser
- [ ] Verified with demo credentials

**If all boxes checked: Ready for college submission! ✓**

---

**Questions?** Check the troubleshooting section or review the steps again!

Good luck! 🚀📚
