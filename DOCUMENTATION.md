# Employee Search Tool - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Architecture & Technology Stack](#architecture--technology-stack)
3. [Project Structure](#project-structure)
4. [Database Models & Relationships](#database-models--relationships)
5. [User Roles & Permissions](#user-roles--permissions)
6. [File-by-File Documentation](#file-by-file-documentation)
7. [Workflow & User Flows](#workflow--user-flows)
8. [Key Features](#key-features)
9. [Configuration](#configuration)
10. [Development Setup](#development-setup)

---

## Project Overview

The **Employee Search Tool** is a comprehensive recruitment management system built with Laravel and Filament. It facilitates the connection between HR professionals and job candidates, allowing for job postings, application management, CV scoring, and real-time communication.

### Core Functionality
- **Job Posting Management**: HR can create and manage job postings
- **Candidate Registration**: Candidates can register and create profiles
- **Application System**: Candidates apply to jobs with resume uploads
- **CV Scoring**: Automatic scoring of candidate resumes
- **Real-time Chat**: Direct messaging between HR and Candidates
- **Application Tracking**: Status management (pending, reviewed, shortlisted, rejected, hired)

---

## Architecture & Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL/PostgreSQL (configurable)

### Frontend
- **Admin Panel**: Filament 3.3
- **Styling**: Tailwind CSS
- **JavaScript**: Alpine.js (via Filament)
- **Build Tool**: Vite

### Key Packages
- `filament/filament`: Admin panel framework
- `bezhansalleh/filament-shield`: Role-based access control
- `namu/wirechat`: Real-time chat functionality
- `phpoffice/phpword`: Word document processing
- `smalot/pdfparser`: PDF text extraction
- `spatie/laravel-permission`: Permission management

---

## Project Structure

```
employee-search-tool-/
├── app/
│   ├── Filament/
│   │   ├── Pages/              # Custom Filament pages
│   │   ├── Resources/           # Filament resources (CRUD interfaces)
│   │   │   ├── ApplicationResource.php
│   │   │   ├── CandidateResource.php
│   │   │   ├── HRResource.php
│   │   │   ├── JobResource.php
│   │   │   └── UserResource.php
│   │   └── Widgets/             # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/         # HTTP controllers
│   │   │   └── CandidateRegistrationController.php
│   │   └── Middleware/           # Custom middleware
│   ├── Models/                   # Eloquent models
│   │   ├── Application.php
│   │   ├── Candidate.php
│   │   ├── HR.php
│   │   ├── Job.php
│   │   └── User.php
│   ├── Policies/                 # Authorization policies
│   ├── Providers/
│   │   └── Filament/
│   │       └── AdminPanelProvider.php
│   └── Services/                 # Business logic services
│       └── CVTextExtractorService.php
├── config/                       # Configuration files
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── candidate/
│   │   │   └── register.blade.php
│   │   └── filament/
│   │       └── components/
│   │           ├── gradient-theme.blade.php
│   │           └── wirechat-styles.blade.php
│   ├── css/
│   └── js/
├── routes/
│   └── web.php                   # Web routes
└── public/                        # Public assets

```

---

## Database Models & Relationships

### User Model (`app/Models/User.php`)

**Purpose**: Central authentication model for all user types.

**Key Attributes**:
- `name`: User's full name
- `email`: Email address (unique)
- `password`: Hashed password
- `type`: User type (`admin`, `hr`, `candidate`)

**Relationships**:
- `hasOne(HR::class)`: One-to-one with HR profile
- `hasOne(Candidate::class)`: One-to-one with Candidate profile
- `HasRoles` trait: Many-to-many with roles (via Spatie Permission)

**Key Methods**:
- `isAdmin()`: Returns true if user is admin
- `isHR()`: Returns true if user is HR
- `isCandidate()`: Returns true if user is candidate
- `canCreateChats()`: Override for Wirechat - only Candidates and HR can chat
- `canCreateGroups()`: Override for Wirechat - only HR can create groups
- `searchChatables()`: Custom search that filters users by type (Candidates see HR, HR sees Candidates)

**Workflow**:
1. User is created with a `type` field
2. Based on type, corresponding profile (HR or Candidate) is created
3. User can authenticate and access appropriate resources
4. Chat functionality is restricted based on user type

---

### Candidate Model (`app/Models/Candidate.php`)

**Purpose**: Stores candidate-specific profile information.

**Key Attributes**:
- `user_id`: Foreign key to users table
- `phone`: Phone number
- `location`: Geographic location
- `resume_path`: Path to uploaded resume file
- `education_level`: High school, diploma, bachelor, master, PhD
- `years_of_experience`: Integer
- `skills`: JSON array of skills
- `certifications`: Text field for certifications
- `bio`: Biography text
- `score`: CV score (0-10, calculated automatically)

**Relationships**:
- `belongsTo(User::class)`: Belongs to one user
- `hasMany(Application::class)`: Has many job applications

**Workflow**:
1. Candidate registers through `/candidate/register` route
2. User account is created with `type = 'candidate'`
3. Candidate profile is created with additional information
4. Resume is uploaded and processed
5. CV score is calculated based on resume content
6. Candidate can browse jobs and apply

---

### HR Model (`app/Models/HR.php`)

**Purpose**: Stores HR professional and company information.

**Key Attributes**:
- `user_id`: Foreign key to users table
- `company_name`: Company name
- `position`: HR's position/title
- `phone`: Phone number
- `location`: Geographic location
- `company_logo`: Path to company logo image
- `bio`: Biography text

**Relationships**:
- `belongsTo(User::class)`: Belongs to one user
- `hasMany(Job::class)`: Has many job postings

**Workflow**:
1. HR account is created by Admin through Filament
2. HR profile is created with company information
3. HR can create job postings
4. HR can view and manage applications
5. HR can chat with candidates

---

### Job Model (`app/Models/Job.php`)

**Purpose**: Represents job postings created by HR.

**Key Attributes**:
- `hr_id`: Foreign key to HR who posted the job
- `title`: Job title
- `description`: Rich text job description
- `location`: Job location
- `job_type`: full-time, part-time, contract, internship
- `salary_range`: Salary range text
- `experience_level`: entry, mid, senior
- `category`: Job category
- `application_deadline`: Date deadline
- `status`: active or closed

**Relationships**:
- `belongsTo(HR::class)`: Belongs to one HR
- `hasMany(Application::class)`: Has many applications

**Workflow**:
1. HR creates job posting through Filament
2. Job is set to `active` status
3. Candidates can view and apply to active jobs
4. Applications are linked to the job
5. HR can close jobs when filled

---

### Application Model (`app/Models/Application.php`)

**Purpose**: Represents a candidate's application to a job.

**Key Attributes**:
- `job_id`: Foreign key to job
- `candidate_id`: Foreign key to candidate
- `resume_path`: Path to resume (can be different from candidate's profile resume)
- `status`: pending, reviewed, shortlisted, rejected, hired
- `feedback_from_hr`: HR's feedback text
- `feedback_from_candidate`: Candidate's feedback text
- `applied_at`: Timestamp of application
- `score`: CV score (copied from candidate or calculated)

**Relationships**:
- `belongsTo(Job::class)`: Belongs to one job
- `belongsTo(Candidate::class)`: Belongs to one candidate

**Workflow**:
1. Candidate clicks "Apply" on a job
2. Application is created with `status = 'pending'`
3. Resume is uploaded (or uses candidate's profile resume)
4. HR reviews application and can:
   - Accept → status becomes `shortlisted`
   - Reject → status becomes `rejected` (with feedback)
   - Hire → status becomes `hired` (from shortlisted)
5. Candidate can view application status

---

## User Roles & Permissions

### Admin
- **Full Access**: Can manage all resources
- **Can Create**: Users, HR profiles, Jobs, Applications
- **Can Edit**: All records
- **Can Delete**: All records
- **Navigation**: Full access to all menu items
- **Chat**: Cannot use chat functionality

### HR (Human Resources)
- **Can Create**: Job postings
- **Can View**: 
  - Own job postings
  - Applications for own jobs
  - Candidate profiles
- **Can Manage**: Application status (accept, reject, hire)
- **Can Chat**: With candidates only
- **Navigation**: Jobs, Applications, Candidates, Chat

### Candidate
- **Can Create**: Own profile (via registration), Applications
- **Can View**: 
  - Available jobs
  - Own applications
  - HR profiles
- **Can Apply**: To active jobs
- **Can Chat**: With HR only
- **Navigation**: Jobs, Applications, Chat

---

## File-by-File Documentation

### Controllers

#### `app/Http/Controllers/CandidateRegistrationController.php`

**Purpose**: Handles candidate registration outside of Filament.

**Key Methods**:
- `showRegistrationForm()`: Displays registration form
- `register(Request $request)`: Processes registration

**Workflow**:
1. User visits `/candidate/register`
2. Form is displayed with account and profile fields
3. On submission:
   - User account is created
   - Candidate profile is created
   - Resume is uploaded and stored
   - CV text is extracted
   - User is logged in
   - Redirected to Filament dashboard

**Validation Rules**:
- Name, email, password required
- Email must be unique
- Resume must be PDF, DOC, or DOCX
- Education level, years of experience validated

---

### Services

#### `app/Services/CVTextExtractorService.php`

**Purpose**: Extracts text content from CV files (PDF, DOC, DOCX) for scoring.

**Key Methods**:
- `extractText(string $filePath)`: Main method that determines file type and extracts text
- `extractFromPdf(string $filePath)`: Extracts text from PDF using Smalot PDF Parser
- `extractFromWord(string $filePath)`: Extracts text from Word documents using PhpOffice

**Workflow**:
1. File path is provided
2. File extension is detected
3. Appropriate extraction method is called
4. Text is cleaned (whitespace normalized)
5. Text is returned for scoring

**Usage**:
```php
$extractor = new CVTextExtractorService();
$text = $extractor->extractText('resumes/cv.pdf');
// Use $text for scoring algorithm
```

---

### Filament Resources

#### `app/Filament/Resources/ApplicationResource.php`

**Purpose**: Manages job applications in the admin panel.

**Key Features**:
- **Table Columns**:
  - Job title, Company name, Candidate name/email
  - CV score (color-coded badge)
  - CV/Resume link
  - Status badge
  - HR feedback, Applied date

- **Filters**:
  - Status (multiple select)
  - Job title (relationship filter)
  - Candidate (relationship filter)
  - Company name (relationship filter)
  - CV score range
  - Applied date range
  - Has resume (toggle)
  - Has HR feedback (toggle)

- **Actions**:
  - **Accept**: Moves application to `shortlisted` (HR only)
  - **Hire**: Moves from `shortlisted` to `hired` (HR only)
  - **Reject**: Moves to `rejected` with feedback form (HR only)
  - **Edit**: Full edit (Admin only)
  - **Delete**: Delete application (Admin only)

**Authorization**:
- `canViewAny()`: Admin, HR (own jobs), Candidates (own applications)
- `canView()`: Admin, HR (own jobs), Candidates (own applications)
- `canEdit()`: Admin only
- `canCreate()`: False (applications created through job application flow)

**Workflow**:
1. Application appears in table after candidate applies
2. HR filters by job or status
3. HR reviews CV and score
4. HR takes action (accept/reject/hire)
5. Candidate sees status update

---

#### `app/Filament/Resources/CandidateResource.php`

**Purpose**: Manages candidate profiles.

**Key Features**:
- **Table Columns**:
  - Name, Email, Phone, Location
  - Education level (badge)
  - Years of experience
  - CV score (color-coded with icons)
  - Resume link
  - Applications count

- **Filters**:
  - Education level (multiple select)
  - CV score range
  - Years of experience range
  - Has resume (toggle)
  - Has applications (toggle)
  - Location search
  - Phone search
  - Created date range

- **Actions**:
  - **Chat**: Opens chat with candidate (HR only)
  - **Edit**: Edit candidate profile
  - **Delete**: Delete candidate

**Authorization**:
- All authenticated users can view
- Admin can edit/delete
- HR can view and chat

**Workflow**:
1. Candidate registers → profile appears in table
2. HR searches/filters candidates
3. HR views candidate details
4. HR can start chat or view applications

---

#### `app/Filament/Resources/HRResource.php`

**Purpose**: Manages HR profiles and companies.

**Key Features**:
- **Table Columns**:
  - Name, Email, Company name, Position
  - Phone, Location
  - Company logo (circular image)
  - Jobs count

- **Filters**:
  - Company name search
  - Position search
  - Location search
  - Phone search
  - Has logo (toggle)
  - Has jobs (toggle)
  - Jobs count range
  - Created date range

- **Actions**:
  - **Chat**: Opens chat with HR (Candidate only)
  - **Edit**: Edit HR profile
  - **Delete**: Delete HR

**Authorization**:
- All authenticated users can view
- Admin can create/edit/delete
- Candidates can chat

**Workflow**:
1. Admin creates HR account
2. HR profile is created
3. HR creates jobs
4. Candidates can view HR profiles and chat

---

#### `app/Filament/Resources/JobResource.php`

**Purpose**: Manages job postings.

**Key Features**:
- **Table Columns**:
  - Company name, Job title, Location
  - Job type (badge), Salary range
  - Experience level (color-coded badge)
  - Category, Description (truncated)
  - Application deadline, Status (badge)
  - Applications count
  - Has applied (for candidates)

- **Filters**:
  - Status (multiple select)
  - Job type (multiple select)
  - Experience level (multiple select)
  - Company name (relationship filter)
  - Job title search
  - Location search
  - Category search
  - Salary range search
  - Application deadline range
  - Has applications (toggle)
  - Applications count range
  - Deadline passed (toggle)
  - Created date range

- **Actions**:
  - **Apply**: Candidate applies to job (opens modal with job details and resume upload)
  - **Edit**: Edit job (Admin/HR)
  - **Delete**: Delete job (Admin/HR)

**Authorization**:
- All authenticated users can view
- Admin and HR can create/edit/delete
- Candidates can apply

**Workflow**:
1. HR creates job posting
2. Job appears in table with `active` status
3. Candidates browse and filter jobs
4. Candidate clicks "Apply"
5. Modal shows job details
6. Candidate uploads resume or uses profile resume
7. Application is created

---

#### `app/Filament/Resources/UserResource.php`

**Purpose**: Manages user accounts.

**Key Features**:
- **Table Columns**:
  - Name, Email
  - Type (color-coded badge: admin=danger, hr=warning, candidate=success)
  - Roles (badges)
  - Email verified (icon)

- **Filters**:
  - Type (multiple select)
  - Name search
  - Email search
  - Email verified (toggle)
  - Has roles (toggle)
  - Roles (relationship filter)
  - Created date range

- **Actions**:
  - **Edit**: Edit user
  - **Delete**: Delete user

**Authorization**:
- Admin only

**Workflow**:
1. Admin creates user account
2. User type is set
3. Corresponding profile (HR/Candidate) is created separately
4. User can authenticate and access system

---

### Policies

Policies are located in `app/Policies/` and control authorization:

- **ApplicationPolicy**: Controls who can view/edit/delete applications
- **CandidatePolicy**: Controls candidate resource access
- **HRPolicy**: Controls HR resource access
- **JobPolicy**: Controls job resource access
- **UserPolicy**: Controls user resource access
- **RolePolicy**: Controls role management access

Each policy implements standard Laravel authorization methods:
- `viewAny()`, `view()`, `create()`, `update()`, `delete()`

---

### Views

#### `resources/views/candidate/register.blade.php`

**Purpose**: Candidate registration form page.

**Features**:
- Two-section form:
  1. Account Information (name, email, password)
  2. Profile Information (phone, location, education, experience, skills, resume, certifications, bio)
- File upload for resume
- Validation feedback
- Gradient theme styling
- Responsive design

**Workflow**:
1. User visits `/candidate/register`
2. Form is displayed
3. User fills in information
4. On submit, validation occurs
5. If valid, registration is processed
6. User is redirected to dashboard

---

#### `resources/views/filament/components/gradient-theme.blade.php`

**Purpose**: Applies teal-to-orange gradient theme to Filament dashboard.

**Features**:
- CSS variables for gradient colors
- Custom styling for Filament components
- Dark mode support
- Applied globally to admin panel

---

#### `resources/views/filament/components/wirechat-styles.blade.php`

**Purpose**: Custom styling for Wirechat interface.

**Features**:
- Simplified, modern UI
- Custom modal styling for "New Chat"
- User list styling
- Message bubble styling
- Dark mode support
- JavaScript for dynamic styling

**Key Styling**:
- Modal backdrop with blur
- White content containers
- Rounded corners
- Smooth animations
- Responsive design

---

### Configuration

#### `app/Providers/Filament/AdminPanelProvider.php`

**Purpose**: Configures Filament admin panel.

**Key Configurations**:
- Panel ID: `admin`
- Path: `/admin`
- Domain: null (all domains)
- Brand name and logo
- Navigation groups
- Custom theme
- Widgets registration
- Custom styles and scripts

**Custom Components**:
- `gradient-theme.blade.php`: Theme styling
- `wirechat-styles.blade.php`: Chat styling

---

### Routes

#### `routes/web.php`

**Key Routes**:
- `/`: Welcome page
- `/candidate/register`: Candidate registration form
- `/admin/*`: Filament admin panel (all resources)
- `/chats/*`: Wirechat routes (configured in `config/wirechat.php`)

**Middleware**:
- `web`: Session, CSRF protection
- `auth`: Authentication required for admin panel

---

## Workflow & User Flows

### Candidate Registration Flow

1. **User visits** `/candidate/register`
2. **Fills form**:
   - Account info (name, email, password)
   - Profile info (phone, location, education, experience, skills, resume, etc.)
3. **Submits form**
4. **Backend processes**:
   - Creates User with `type = 'candidate'`
   - Creates Candidate profile
   - Uploads resume to storage
   - Extracts text from resume (for future scoring)
   - Logs user in
5. **Redirects** to `/admin` dashboard
6. **Candidate can now**:
   - Browse jobs
   - Apply to jobs
   - View applications
   - Chat with HR

---

### Job Application Flow

1. **Candidate browses** jobs in JobResource table
2. **Filters** by location, type, experience level, etc.
3. **Clicks "Apply"** on a job
4. **Modal opens** showing:
   - Job details (company, location, type, salary, deadline, description)
   - Resume options:
     - Use existing profile resume (if available)
     - Upload new resume
5. **Candidate selects** resume option
6. **Submits application**
7. **Backend creates** Application record:
   - `job_id`: Selected job
   - `candidate_id`: Current candidate
   - `resume_path`: Resume file path
   - `status`: `pending`
   - `applied_at`: Current timestamp
8. **Application appears** in ApplicationResource table
9. **HR reviews** application:
   - Views CV and score
   - Takes action:
     - **Accept** → `shortlisted`
     - **Reject** → `rejected` (with feedback)
     - **Hire** → `hired` (from shortlisted)
10. **Candidate sees** status update in their applications

---

### Chat Flow

1. **HR or Candidate** clicks "Chat" button on a profile
2. **Backend checks**:
   - HR can only chat with Candidates
   - Candidates can only chat with HR
3. **Conversation is created** (or retrieved if exists)
4. **User is redirected** to `/chats/{conversation_id}`
5. **Wirechat interface** loads:
   - User list (filtered by type)
   - Message history
   - Input field
6. **Users send messages** in real-time
7. **Messages are stored** in database
8. **Notifications** can be sent (if configured)

---

### HR Job Management Flow

1. **HR logs in** to `/admin`
2. **Navigates** to "Job Postings"
3. **Creates job**:
   - Fills in title, description, location, type, salary, experience level, category, deadline
   - Sets status to `active`
4. **Job appears** in table
5. **Candidates apply** (see Job Application Flow)
6. **HR views applications**:
   - Filters by job, status, score, etc.
   - Reviews CVs
   - Takes actions (accept/reject/hire)
7. **HR can close** job when filled

---

## Key Features

### CV Scoring System

**Purpose**: Automatically score candidate resumes based on content.

**Implementation**:
- Text is extracted from resume (PDF/DOC/DOCX)
- Scoring algorithm analyzes:
  - Keywords matching job requirements
  - Education level
  - Years of experience
  - Skills mentioned
  - Certifications
- Score is stored in `candidates.score` and `applications.score`
- Score range: 0-10
- Color coding:
  - 8-10: Green (success)
  - 6-7.9: Yellow (warning)
  - 4-5.9: Blue (info)
  - 0-3.9: Gray

**Note**: Full scoring algorithm implementation may be in a service or job queue.

---

### Filter System

All resources have comprehensive filters:

**Common Filter Types**:
- **SelectFilter**: Dropdown with options (can be multiple)
- **Filter**: Custom form with text inputs, date pickers, etc.
- **Toggle Filter**: Boolean on/off

**Filter Layout**:
- `AboveContentCollapsible`: Filters appear above table, can be collapsed

**Benefits**:
- Quick data filtering
- Multiple filter combinations
- Saved filter states
- Export filtered data

---

### File Upload System

**Resume Uploads**:
- **Location**: `storage/app/public/resumes/` (candidate profiles)
- **Location**: `storage/app/public/application-resumes/` (job applications)
- **Accepted Types**: PDF, DOC, DOCX
- **Max Size**: 10MB (configurable)
- **Visibility**: Public (accessible via URL)

**Company Logos**:
- **Location**: `storage/app/public/company-logos/`
- **Accepted Types**: Images (JPG, PNG, etc.)
- **Visibility**: Public

**Processing**:
- Files are stored via Laravel Storage
- Paths are saved in database
- Files are accessible via `asset('storage/path')`

---

### Real-time Chat (Wirechat)

**Configuration**: `config/wirechat.php`

**Features**:
- One-on-one conversations
- Group chats (HR only)
- Message history
- File attachments (if enabled)
- Read receipts (if enabled)
- Search functionality

**Restrictions**:
- Only Candidates and HR can chat
- Candidates can only chat with HR
- HR can only chat with Candidates
- Admins cannot chat

**Customization**:
- Custom styling via `wirechat-styles.blade.php`
- Custom search via `User::searchChatables()`
- Custom display names and avatars

---

## Configuration

### Environment Variables

Key `.env` variables:
```
APP_NAME="Employee Search Tool"
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_search
DB_USERNAME=root
DB_PASSWORD=

FILAMENT_DARK_MODE=false
```

### Filament Configuration

- **Panel ID**: `admin`
- **Path**: `/admin`
- **Theme**: Custom gradient theme
- **Navigation**: Grouped by User Management and Jobs

### Wirechat Configuration

- **Routes Prefix**: `chats`
- **User Model**: `App\Models\User`
- **Searchable Fields**: `name`, `email`
- **Max Participants**: Configurable

---

## Development Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Git

### Installation Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd employee-search-tool-
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_search
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Seed Database** (Optional)
```bash
php artisan db:seed
```

7. **Create Storage Link**
```bash
php artisan storage:link
```

8. **Build Assets**
```bash
npm run build
# Or for development:
npm run dev
```

9. **Start Development Server**
```bash
# Option 1: Using Composer script (recommended)
composer dev

# Option 2: Manual
php artisan serve --host=0.0.0.0
php artisan queue:listen
npm run dev
```

### Creating Admin User

Run the seeder:
```bash
php artisan db:seed --class=AdminUserSeeder
```

Or create manually:
1. Register as candidate first
2. Change user type to `admin` in database
3. Assign admin role via Filament Shield

### Access Points

- **Admin Panel**: `http://localhost:8000/admin`
- **Candidate Registration**: `http://localhost:8000/candidate/register`
- **Chat Interface**: `http://localhost:8000/chats`

---

## Database Schema

### Tables

1. **users**
   - id, name, email, password, type, email_verified_at, created_at, updated_at

2. **candidates**
   - id, user_id, phone, location, resume_path, education_level, years_of_experience, skills (JSON), certifications, bio, score, created_at, updated_at

3. **hrs**
   - id, user_id, company_name, position, phone, location, company_logo, bio, created_at, updated_at

4. **job_postings**
   - id, hr_id, title, description, location, job_type, salary_range, experience_level, category, application_deadline, status, created_at, updated_at

5. **applications**
   - id, job_id, candidate_id, resume_path, status, feedback_from_hr, feedback_from_candidate, applied_at, score, created_at, updated_at

6. **wirechat_*** (Wirechat package tables)
   - conversations, messages, participants, attachments, etc.

7. **permission_*** (Spatie Permission tables)
   - roles, permissions, model_has_roles, etc.

---

## Security Considerations

1. **Authentication**: Laravel's built-in authentication
2. **Authorization**: Policies and Filament Shield
3. **CSRF Protection**: Enabled on all forms
4. **File Upload Validation**: Type and size restrictions
5. **SQL Injection**: Protected by Eloquent ORM
6. **XSS Protection**: Blade templating escapes output
7. **Password Hashing**: Bcrypt by default

---

## Troubleshooting

### Common Issues

1. **Storage Link Not Working**
   ```bash
   php artisan storage:link
   ```

2. **Permission Denied Errors**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

3. **Queue Not Processing**
   ```bash
   php artisan queue:listen
   ```

4. **Assets Not Loading**
   ```bash
   npm run build
   php artisan optimize:clear
   ```

5. **Chat Not Working**
   - Check Wirechat configuration
   - Verify user types (only HR and Candidates)
   - Check database migrations

---

## Future Enhancements

Potential improvements:
- Email notifications for applications
- Advanced CV scoring algorithm
- Job recommendations for candidates
- Application analytics dashboard
- Export functionality (PDF, Excel)
- Multi-language support
- API endpoints for mobile app
- Real-time notifications
- Video interview scheduling
- Document signing integration

---

## Support & Contribution

For issues, questions, or contributions:
1. Check existing documentation
2. Review code comments
3. Test in development environment
4. Follow Laravel and Filament best practices

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Documentation Version**: 1.0  
**Last Updated**: 2024  
**Maintained By**: Development Team
