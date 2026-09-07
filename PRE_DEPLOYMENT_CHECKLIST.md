# Pre-Deployment Checklist

**Run this checklist BEFORE you start deploying to Railway and Vercel**

---

## ✓ LOCAL DEVELOPMENT VERIFICATION

### 1. Frontend Builds Successfully

```bash
cd c:\New folder\library-management-system\frontend
npm run build
```

**Expected Result**: ✓ Compiled successfully (no errors)

**If you see errors**: Check that you have Node.js 16+ installed
```bash
node --version  # Should be v16+
npm --version   # Should be v7+
```

---

## ✓ BACKEND CONFIGURATION

### 1. Backend Can Start

```bash
cd c:\New folder\library-management-system\backend
php artisan serve
```

**Expected Result**: 
```
INFO  Server running on [http://127.0.0.1:8000]
```

Press Ctrl+C to stop.

### 2. Database Migrations Work

```bash
cd c:\New folder\library-management-system\backend
php artisan migrate:status
```

**Expected Result**: All migrations show "Yes"
```
| Ran? | Migration                                          | Batch |
|------|-------------------------------------------------------|-------|
| Yes  | 2014_10_12_000000_create_users_table                | 1     |
| Yes  | ...                                                   | ...   |
```

### 3. Admin User Exists

Test login locally:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@library.com","password":"1234567"}'
```

**Expected Result**:
```json
{
  "token": "...",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@library.com",
    "role": "admin"
  }
}
```

---

## ✓ FRONTEND CONFIGURATION

### 1. API Uses Environment Variable

```bash
cd c:\New folder\library-management-system\frontend
cat lib/api.ts | grep "process.env"
```

**Expected Result**:
```
const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
```

### 2. .env.local Exists and Has Correct Value

```bash
cd c:\New folder\library-management-system\frontend
cat .env.local
```

**Expected Result**:
```
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### 3. Test Frontend Locally

```bash
cd c:\New folder\library-management-system\frontend
npm run dev
```

**Expected Result**: 
```
✓ Ready in X.Xs
```

Open http://localhost:3000 in browser:
- [ ] Login page appears
- [ ] Can log in with admin@library.com / 1234567
- [ ] Dashboard loads
- [ ] No errors in F12 Console

---

## ✓ GIT CONFIGURATION

### 1. Git Repository Exists

```bash
cd c:\New folder\library-management-system
git status
```

**Expected Result**: Shows branch name and status

If error "not a git repository", run:
```bash
git init
git config user.email "your-email@college.edu"
git config user.name "Your Name"
```

### 2. .gitignore Ignores Secrets

```bash
cd c:\New folder\library-management-system\frontend
cat .gitignore | grep "\.env"
```

**Expected Result**: Contains `.env*`

```bash
cd c:\New folder\library-management-system\backend
cat .gitignore | grep "\.env"
```

**Expected Result**: Contains `.env`

### 3. Staging Area Clean

```bash
cd c:\New folder\library-management-system
git status
```

**Expected Result**: "nothing to commit, working tree clean" OR shows files ready to commit

---

## ✓ DEPLOYMENT FILES EXIST

### Frontend Files

```bash
cd c:\New folder\library-management-system\frontend
ls -la | grep "\.env"
```

**Expected Result**:
```
.env.local
.env.example
```

### Backend Files

```bash
cd c:\New folder\library-management-system\backend
ls -la | grep "Procfile" || ls -la Procfile
cat .env.production | head -10
```

**Expected Results**:
- Procfile exists with deployment instructions
- .env.production has example configuration

### Documentation Files

```bash
cd c:\New folder\library-management-system
ls -la | grep DEPLOYMENT
ls -la | grep QUICK
ls -la | grep README_DEPLOYMENT
```

**Expected Result**: These files exist:
- DEPLOYMENT_GUIDE.md
- QUICK_DEPLOYMENT_STEPS.md
- README_DEPLOYMENT.md
- DEPLOYMENT_SUMMARY.txt
- PRE_DEPLOYMENT_CHECKLIST.md (this file)
- EXECUTE_DEPLOYMENT.md

---

## ✓ NO HARDCODED LOCALHOST URLS

### Check Frontend Code

```bash
cd c:\New folder\library-management-system\frontend
grep -r "localhost:8000" app/ lib/ components/ 2>/dev/null || echo "✓ No localhost:8000 found"
grep -r "127.0.0.1:8000" app/ lib/ components/ 2>/dev/null || echo "✓ No 127.0.0.1:8000 found"
```

**Expected Result**: ✓ No localhost URLs found

### Check Backend Code

```bash
cd c:\New folder\library-management-system\backend
grep -r "localhost" app/ routes/ 2>/dev/null | grep -v "\.env" || echo "✓ No localhost references"
```

**Expected Result**: ✓ No localhost references (except in comments)

---

## ✓ DATABASE STATE

### Check Seeded Data

```bash
cd c:\New folder\library-management-system\backend
php artisan tinker
```

Then in tinker:
```php
App\Models\User::count()
App\Models\Book::count()
App\Models\Member::count()
App\Models\Category::count()
exit
```

**Expected Results**:
- User count: 2 (admin + librarian)
- Book count: 16
- Member count: 10
- Category count: 5

---

## ✓ AUTHENTICATION ENDPOINTS

### Test All Three Endpoints

```bash
# 1. Test Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@library.com","password":"1234567"}'

# Expected: Returns token and user
```

Save the token from response, then:

```bash
TOKEN="paste-token-here"

# 2. Test Get User
curl http://localhost:8000/api/user \
  -H "Authorization: Bearer $TOKEN"

# Expected: Returns current user
```

```bash
# 3. Test Logout
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer $TOKEN"

# Expected: Returns success message
```

Then try to use the revoked token:
```bash
# Should fail with 401 Unauthorized
curl http://localhost:8000/api/user \
  -H "Authorization: Bearer $TOKEN"
```

---

## ✓ FINAL VERIFICATION

Run this complete test:

```bash
echo "=== DEPLOYMENT VERIFICATION CHECKLIST ==="
echo ""
echo "Frontend:"
cd c:\New folder\library-management-system\frontend
npm run build 2>&1 | tail -5
echo ""

echo "Backend:"
cd c:\New folder\library-management-system\backend
php artisan route:list 2>&1 | grep -E "login|user|logout"
echo ""

echo "Configuration:"
echo "Frontend .env.local:"
cat .env.local 2>/dev/null || echo "Missing!"
echo ""
echo "Backend Procfile:"
cat Procfile 2>/dev/null || echo "Missing!"
echo ""

echo "=== ALL CHECKS COMPLETE ==="
```

---

## ✓ READY TO DEPLOY?

If all of the above check marks pass ✓, you are ready to deploy!

### Before You Deploy

1. **Backup your project**:
   ```bash
   # Make a copy of your entire project folder
   # In case anything goes wrong
   ```

2. **Save your database locally**:
   ```bash
   # Your local MySQL database contains all demo data
   # It will not be affected by deployment
   ```

3. **Have these ready**:
   - [ ] GitHub account (free at github.com)
   - [ ] Railway account (free at railway.app)
   - [ ] Vercel account (free at vercel.com)
   - [ ] GitHub personal access token (for CLI authentication)

4. **Commit your work**:
   ```bash
   cd c:\New folder\library-management-system
   git add .
   git commit -m "Ready for deployment"
   ```

---

## DEPLOYMENT FLOW

Once you pass this checklist:

1. **Push to GitHub** (2 min)
   - Your code goes to GitHub

2. **Deploy Backend** (10-15 min)
   - Railway pulls from GitHub
   - Builds and deploys Laravel
   - Databases migrates automatically

3. **Deploy Frontend** (5-10 min)
   - Vercel pulls from GitHub
   - Builds and deploys Next.js
   - Public URL created

4. **Update Backend Config** (2 min)
   - Set SANCTUM_STATEFUL_DOMAINS
   - Railway redeploys

5. **Verify** (5-10 min)
   - Test public URL
   - Test login, pages, logout
   - Check for errors

**Total Time**: ~30-40 minutes

---

## TROUBLESHOOTING THIS CHECKLIST

### Issue: Frontend build fails
```bash
# Delete node_modules and reinstall
cd frontend
rm -r node_modules
npm install
npm run build
```

### Issue: Backend won't start
```bash
# Check PHP version
php --version  # Should be 7.4+

# Check Composer
composer install

# Check database
php artisan migrate:status
```

### Issue: Can't log in locally
```bash
# Reset admin password
php artisan tinker
App\Models\User::first()->update(['password' => Hash::make('1234567')])
exit

# Try login again
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@library.com","password":"1234567"}'
```

---

## NEXT STEPS

Once you pass this checklist with all ✓ marks:

1. **Read**: `EXECUTE_DEPLOYMENT.md`
2. **Follow**: Step-by-step deployment instructions
3. **Deploy**: Backend to Railway
4. **Deploy**: Frontend to Vercel
5. **Verify**: Test at public URL
6. **Submit**: URLs to your college

---

**When ready, proceed to EXECUTE_DEPLOYMENT.md**

