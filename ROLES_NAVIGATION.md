# Roles and Navigation Visibility

## Roles in the System

The following roles are created by `RoleSeeder`:
- `super_admin` - Full system access (bypasses all permissions)
- `admin` - Admin role
- `hr` - HR role (lowercase)
- `HR` - HR role (uppercase, for backward compatibility)
- `candidate` - Candidate role

## User Types (Different from Roles)

Users also have a `type` field in the database:
- `admin` - Admin user type
- `hr` - HR user type  
- `candidate` - Candidate user type

**Note:** User types are checked via `isAdmin()`, `isHR()`, `isCandidate()` methods, while roles are separate permissions-based roles.

---

## Navigation Menu Visibility by Role/Type

### 1. **super_admin Role** (with `type='admin'`)
**Navigation Shows:**
- ✅ **Dashboard**
- ✅ **Users** (User Management group)
- ✅ **Candidates** (User Management group)
- ✅ **HRs** (User Management group)
- ✅ **Job Postings** (Jobs group)
- ✅ **Applications** (Jobs group)
- ✅ **Roles** (Filament Shield)

**Why:** Super admin bypasses all policies and has full access to everything.

---

### 2. **admin Role/Type** (`type='admin'`)
**Navigation Shows:**
- ✅ **Dashboard**
- ✅ **Users** (User Management group) - *If has `view_any_user` permission*
- ✅ **Candidates** (User Management group) - *If has `view_any_candidate` permission*
- ✅ **HRs** (User Management group) - *If has `view_any_h::r` permission*
- ✅ **Job Postings** (Jobs group) - *Always visible* (`shouldRegisterNavigation()` returns true for `isAdmin()`)
- ✅ **Applications** (Jobs group) - *Always visible* (`shouldRegisterNavigation()` returns true for `isAdmin()`)
- ✅ **Roles** (Filament Shield) - *If has permissions*

**Why:** Admins have `type='admin'` which makes `isAdmin()` return true, showing Jobs and Applications. Other resources depend on Filament Shield permissions.

---

### 3. **hr Role/Type** (`type='hr'`)
**Navigation Shows:**
- ✅ **Dashboard**
- ✅ **Job Postings** (Jobs group) - *Always visible* (`shouldRegisterNavigation()` returns true for `isHR()`)
- ✅ **Applications** (Jobs group) - *Always visible* (`shouldRegisterNavigation()` returns true for `isHR()`)
- ✅ **Users** - *If has `view_any_user` permission*
- ✅ **Candidates** - *If has `view_any_candidate` permission*
- ✅ **HRs** - *If has `view_any_h::r` permission*
- ✅ **Roles** - *If has permissions*

**Special Features:**
- Can only see applications for their own jobs (filtered in `ListApplications` page)
- Can only see their own jobs (filtered in `ListJobs` page)
- Can Accept/Reject/Hire applications (visible in Applications table actions)

---

### 4. **candidate Role/Type** (`type='candidate'`)
**Navigation Shows:**
- ✅ **Dashboard**
- ✅ **Job Postings** (Jobs group) - *Always visible* (`shouldRegisterNavigation()` returns true for `isCandidate()`)
- ✅ **Applications** (Jobs group) - *Always visible* (`shouldRegisterNavigation()` returns true for `isCandidate()`)
- ✅ **Users** - *If has `view_any_user` permission*
- ✅ **Candidates** - *If has `view_any_candidate` permission*
- ✅ **HRs** - *If has `view_any_h::r` permission*
- ✅ **Roles** - *If has permissions*

**Special Features:**
- Can only see their own applications (filtered in `ListApplications` page)
- Can only see active jobs (filtered in `ListJobs` page)
- Can apply for jobs (Apply button visible in Job Postings table)
- Cannot create applications directly (must apply through jobs)

---

## Navigation Groups

Resources are organized into navigation groups:

1. **User Management Group:**
   - Users
   - Candidates
   - HRs

2. **Jobs Group:**
   - Job Postings
   - Applications

3. **Shield Group (Filament Shield):**
   - Roles

---

## Summary Table

| Resource | super_admin | admin | hr | candidate |
|----------|-------------|-------|----|----|
| **Dashboard** | ✅ | ✅ | ✅ | ✅ |
| **Users** | ✅ | ⚠️* | ⚠️* | ⚠️* |
| **Candidates** | ✅ | ⚠️* | ⚠️* | ⚠️* |
| **HRs** | ✅ | ⚠️* | ⚠️* | ⚠️* |
| **Job Postings** | ✅ | ✅ | ✅ | ✅ |
| **Applications** | ✅ | ✅ | ✅ | ✅ |
| **Roles** | ✅ | ⚠️* | ⚠️* | ⚠️* |

*⚠️ = Depends on Filament Shield permissions (if `view_any_{resource}` permission exists)

---

## Important Notes

1. **Super Admin Bypass:** Users with `super_admin` role bypass all policy checks and see everything.

2. **User Type vs Role:** 
   - `type` field controls `isAdmin()`, `isHR()`, `isCandidate()` checks
   - `roles` (from Spatie Permission) control Filament Shield permissions
   - Both are used together in the application

3. **Navigation Visibility Logic:**
   - Resources check `shouldRegisterNavigation()` method
   - If not overridden, Filament checks policies via `canViewAny()`
   - Super admin bypasses everything in policies (added recently)

4. **Filtering:**
   - HR users see only their own jobs/applications (filtered in list queries)
   - Candidate users see only their own applications (filtered in list queries)
   - Admin/Super Admin see everything (no filtering)

