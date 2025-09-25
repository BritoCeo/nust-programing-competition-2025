# MESMTF Testing Guide

## Medical Expert System for Malaria & Typhoid Fever - Comprehensive Testing Suite

### Overview

This document outlines the comprehensive testing strategy for the MESMTF (Medical Expert System for Malaria & Typhoid Fever) application. Our testing approach ensures reliability, security, and performance of the medical system.

---

## Testing Strategy

### 1. **Unit Tests** (Model Layer)
- **Purpose**: Test individual model methods, relationships, and business logic
- **Coverage**: Models, relationships, scopes, accessors, mutators
- **Location**: `tests/Unit/Models/`

#### Test Categories:
- **User Model Tests**
  - User creation and validation
  - Role assignment and checking
  - Medical professional identification
  - Patient relationship management
  - Attribute casting and hiding

- **Medical Record Tests**
  - Record creation and validation
  - Patient/doctor relationships
  - Record number generation
  - Status management
  - File attachment handling

- **Appointment Tests**
  - Appointment scheduling
  - Conflict detection
  - Status management
  - Time validation

- **Diagnosis Tests**
  - Expert system integration
  - Confidence scoring
  - Disease matching
  - Symptom analysis

### 2. **Feature Tests** (Controller Layer)
- **Purpose**: Test complete user workflows and business processes
- **Coverage**: Controllers, middleware, routes, views
- **Location**: `tests/Feature/`

#### Test Categories:
- **Authentication Tests**
  - User registration and login
  - Password management
  - Profile updates
  - Role-based access control

- **Medical Record Tests**
  - CRUD operations
  - File uploads
  - Search and filtering
  - Permission-based access

- **Appointment Tests**
  - Scheduling workflows
  - Conflict resolution
  - Status updates
  - Calendar integration

- **Expert System Tests**
  - Symptom analysis
  - Disease diagnosis
  - Confidence scoring
  - Treatment recommendations

### 3. **API Tests** (Integration Layer)
- **Purpose**: Test REST API endpoints and data exchange
- **Coverage**: API routes, authentication, data validation
- **Location**: `tests/Feature/Api/`

#### Test Categories:
- **Authentication API**
  - Login/logout endpoints
  - Token management
  - User profile API
  - Password change API

- **Medical Records API**
  - CRUD operations via API
  - File upload/download
  - Search and filtering
  - Pagination

- **Expert System API**
  - Symptom analysis endpoints
  - Disease information API
  - Diagnosis generation
  - Statistics API

- **Dashboard API**
  - Role-based statistics
  - Recent activity feeds
  - Performance metrics

### 4. **Integration Tests** (System Layer)
- **Purpose**: Test complete system workflows
- **Coverage**: End-to-end processes, external integrations
- **Location**: `tests/Integration/`

#### Test Categories:
- **Workflow Tests**
  - Patient registration to treatment
  - Appointment scheduling to completion
  - Diagnosis to treatment workflow
  - Report generation workflows

- **External Integration Tests**
  - Email notifications
  - File storage
  - Database transactions
  - Cache operations

### 5. **Performance Tests** (Load Testing)
- **Purpose**: Test system performance under load
- **Coverage**: Response times, memory usage, database performance
- **Location**: `tests/Performance/`

#### Test Categories:
- **Load Tests**
  - Concurrent user simulation
  - Database query performance
  - API response times
  - Memory usage monitoring

- **Stress Tests**
  - High-volume data processing
  - Expert system performance
  - Report generation speed
  - File upload handling

---

## Test Configuration

### PHPUnit Configuration
```xml
<phpunit>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
        <testsuite name="Api">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### Test Database
- **Driver**: SQLite (in-memory for speed)
- **Migrations**: Automatic migration before tests
- **Seeders**: Role and permission seeding
- **Isolation**: Each test runs in isolation

### Test Data Factory
```php
// User Factory
User::factory()->create([
    'name' => 'Dr. John Doe',
    'email' => 'doctor@example.com',
    'role' => 'doctor'
]);

// Medical Record Factory
MedicalRecord::factory()->create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'chief_complaint' => 'Fever and headache'
]);
```

---

## Running Tests

### 1. **Run All Tests**
```bash
php artisan test
```

### 2. **Run Specific Test Suites**
```bash
# Unit tests only
php artisan test --testsuite=Unit

# Feature tests only
php artisan test --testsuite=Feature

# API tests only
php artisan test --testsuite=Api

# Integration tests only
php artisan test --testsuite=Integration
```

### 3. **Run Tests with Coverage**
```bash
php artisan test --coverage
php artisan test --coverage-html=storage/logs/coverage
```

### 4. **Run Specific Tests**
```bash
# Run specific test class
php artisan test tests/Unit/Models/UserTest.php

# Run specific test method
php artisan test --filter=it_can_create_a_user

# Run tests matching pattern
php artisan test --filter=User
```

### 5. **Run Tests in Parallel**
```bash
php artisan test --parallel
```

---

## Test Categories

### 1. **Model Tests** (`tests/Unit/Models/`)

#### UserTest.php
- ✅ User creation and validation
- ✅ Role assignment and checking
- ✅ Medical professional identification
- ✅ Patient relationship management
- ✅ Attribute casting and hiding
- ✅ Password hashing
- ✅ Email uniqueness validation

#### MedicalRecordTest.php
- ✅ Record creation and validation
- ✅ Patient/doctor relationships
- ✅ Record number generation
- ✅ Status management
- ✅ File attachment handling
- ✅ Vital signs casting
- ✅ Scope methods

#### AppointmentTest.php
- ✅ Appointment scheduling
- ✅ Conflict detection
- ✅ Status management
- ✅ Time validation
- ✅ Patient/doctor relationships
- ✅ Appointment number generation

#### DiagnosisTest.php
- ✅ Expert system integration
- ✅ Confidence scoring
- ✅ Disease matching
- ✅ Symptom analysis
- ✅ Status management
- ✅ Treatment recommendations

### 2. **Feature Tests** (`tests/Feature/`)

#### AuthTest.php
- ✅ User registration
- ✅ User login/logout
- ✅ Password management
- ✅ Profile updates
- ✅ Role-based access control
- ✅ Session management

#### MedicalRecordTest.php
- ✅ CRUD operations
- ✅ File uploads
- ✅ Search and filtering
- ✅ Permission-based access
- ✅ Status management
- ✅ Attachment downloads

#### AppointmentTest.php
- ✅ Scheduling workflows
- ✅ Conflict resolution
- ✅ Status updates
- ✅ Calendar integration
- ✅ Patient/doctor access
- ✅ Cancellation handling

#### ExpertSystemTest.php
- ✅ Symptom analysis
- ✅ Disease diagnosis
- ✅ Confidence scoring
- ✅ Treatment recommendations
- ✅ Rule evaluation
- ✅ Statistics generation

### 3. **API Tests** (`tests/Feature/Api/`)

#### BasicApiTest.php
- ✅ Health check endpoint
- ✅ Authentication endpoints
- ✅ Protected route access
- ✅ Dashboard API
- ✅ Error handling
- ✅ Response formatting

#### AuthApiTest.php
- ✅ Login/logout API
- ✅ Registration API
- ✅ Profile management API
- ✅ Password change API
- ✅ Token management
- ✅ Permission checking

#### MedicalRecordApiTest.php
- ✅ CRUD operations via API
- ✅ File upload/download
- ✅ Search and filtering
- ✅ Pagination
- ✅ Status management
- ✅ Permission validation

#### ExpertSystemApiTest.php
- ✅ Symptom analysis API
- ✅ Disease information API
- ✅ Diagnosis generation API
- ✅ Statistics API
- ✅ Rule management API
- ✅ Performance metrics

---

## Test Data Management

### 1. **Database Seeding**
```php
// Create roles and permissions
$this->createRolesAndPermissions();

// Create test users
$doctor = $this->createDoctor();
$patient = $this->createPatient();
$admin = $this->createAdmin();
```

### 2. **Factory Usage**
```php
// User factories
User::factory()->create(['role' => 'doctor']);
User::factory()->create(['role' => 'patient']);

// Medical record factories
MedicalRecord::factory()->create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id
]);
```

### 3. **Test Isolation**
- Each test runs in isolation
- Database is refreshed between tests
- No test dependencies
- Clean state for each test

---

## Coverage Requirements

### 1. **Code Coverage Targets**
- **Unit Tests**: 90%+ coverage
- **Feature Tests**: 80%+ coverage
- **API Tests**: 85%+ coverage
- **Integration Tests**: 70%+ coverage

### 2. **Coverage Reports**
```bash
# Generate HTML coverage report
php artisan test --coverage-html=storage/logs/coverage

# Generate XML coverage report
php artisan test --coverage-clover=coverage.xml

# Generate text coverage report
php artisan test --coverage-text
```

### 3. **Coverage Analysis**
- **Models**: 95%+ coverage
- **Controllers**: 85%+ coverage
- **Services**: 90%+ coverage
- **Middleware**: 80%+ coverage

---

## CI/CD Integration

### 1. **GitHub Actions Workflow**
```yaml
name: CI/CD Pipeline
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php-version: [8.1, 8.2, 8.3]
    steps:
      - uses: actions/checkout@v4
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: php artisan test --coverage
```

### 2. **Test Automation**
- **Trigger**: On every push and pull request
- **Matrix Testing**: Multiple PHP versions
- **Coverage**: Automatic coverage reporting
- **Security**: Security audit integration
- **Performance**: Performance testing

### 3. **Quality Gates**
- **Test Coverage**: Minimum 80%
- **Security**: No high-severity vulnerabilities
- **Performance**: Response time < 2 seconds
- **Code Quality**: PHPStan level 5

---

## Best Practices

### 1. **Test Naming**
```php
// Good: Descriptive test names
public function it_can_create_a_medical_record()
public function user_can_login_with_valid_credentials()
public function api_requires_authentication_for_protected_routes()

// Bad: Vague test names
public function test_medical_record()
public function test_login()
public function test_api()
```

### 2. **Test Structure**
```php
/** @test */
public function it_can_create_a_medical_record()
{
    // Arrange
    $patient = $this->createPatient();
    $doctor = $this->createDoctor();
    
    // Act
    $response = $this->post('/medical-records', [
        'patient_id' => $patient->id,
        'chief_complaint' => 'Fever and headache'
    ]);
    
    // Assert
    $response->assertStatus(201);
    $this->assertDatabaseHas('medical_records', [
        'patient_id' => $patient->id,
        'chief_complaint' => 'Fever and headache'
    ]);
}
```

### 3. **Test Data**
```php
// Use factories for consistent test data
$user = User::factory()->create(['role' => 'doctor']);

// Use realistic test data
$medicalRecord = [
    'chief_complaint' => 'Fever and headache',
    'history_of_present_illness' => 'Patient reports fever for 3 days',
    'physical_examination' => 'General appearance: ill-looking'
];
```

### 4. **Assertions**
```php
// Test response status
$response->assertStatus(201);

// Test database state
$this->assertDatabaseHas('medical_records', [
    'patient_id' => $patient->id
]);

// Test JSON response
$response->assertJson(['success' => true]);

// Test view content
$response->assertSee('Medical Records');
```

---

## Troubleshooting

### 1. **Common Issues**
- **Database errors**: Ensure migrations are run
- **Permission errors**: Check role and permission setup
- **Authentication errors**: Verify user creation and login
- **File upload errors**: Check storage configuration

### 2. **Debug Tips**
```php
// Debug response content
$response->assertStatus(200);
dd($response->json());

// Debug database state
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);
dd(User::where('email', 'test@example.com')->first());

// Debug authentication
$this->assertAuthenticated();
dd(auth()->user());
```

### 3. **Performance Issues**
- Use in-memory SQLite for faster tests
- Run tests in parallel when possible
- Avoid unnecessary database operations
- Use factories instead of creating data manually

---

## Test Metrics

### 1. **Coverage Metrics**
- **Total Coverage**: 85%+
- **Critical Paths**: 95%+ coverage
- **API Endpoints**: 90%+ coverage
- **Business Logic**: 95%+ coverage

### 2. **Performance Metrics**
- **Test Execution Time**: < 5 minutes
- **API Response Time**: < 2 seconds
- **Database Query Time**: < 100ms
- **Memory Usage**: < 512MB

### 3. **Quality Metrics**
- **Test Reliability**: 99%+ pass rate
- **Code Quality**: PHPStan level 5
- **Security**: No high-severity issues
- **Maintainability**: Clear test structure

---

## Conclusion

The MESMTF testing suite provides comprehensive coverage of all system components, ensuring reliability, security, and performance of the medical expert system. The testing strategy includes unit tests, feature tests, API tests, and integration tests, with automated CI/CD pipeline for continuous quality assurance.

**Key Benefits:**
- ✅ **Reliability**: Comprehensive test coverage ensures system stability
- ✅ **Security**: Authentication and authorization testing
- ✅ **Performance**: Load and stress testing for optimal performance
- ✅ **Maintainability**: Well-structured tests for easy maintenance
- ✅ **Automation**: CI/CD pipeline for continuous testing
- ✅ **Quality**: High code coverage and quality standards

The testing suite is designed to support the medical system's critical requirements while maintaining high standards of code quality and system reliability.
