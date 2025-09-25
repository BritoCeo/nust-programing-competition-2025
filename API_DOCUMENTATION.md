# MESMTF API Documentation

## Medical Expert System for Malaria & Typhoid Fever (MESMTF)

### Base URL
```
https://mesmtf.nust.edu.na/api
```

### Authentication
All API endpoints (except public routes) require authentication using Bearer tokens.

```bash
Authorization: Bearer {your-token}
```

---

## Authentication Endpoints

### POST /api/auth/login
Login user and receive authentication token.

**Request Body:**
```json
{
    "email": "doctor@example.com",
    "password": "password123",
    "remember": false
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Dr. John Doe",
            "email": "doctor@example.com",
            "role": "doctor",
            "permissions": ["view medical records", "create medical records"]
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### POST /api/auth/register
Register new user account.

**Request Body:**
```json
{
    "name": "Dr. Jane Smith",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "doctor",
    "phone": "+264811234567",
    "specialization": "Internal Medicine"
}
```

### GET /api/auth/user
Get authenticated user information.

### PUT /api/auth/profile
Update user profile.

### POST /api/auth/change-password
Change user password.

---

## Dashboard Endpoints

### GET /api/dashboard/stats
Get dashboard statistics based on user role.

**Response:**
```json
{
    "success": true,
    "data": {
        "total_patients": 150,
        "today_appointments": 8,
        "pending_diagnoses": 3,
        "active_treatments": 25
    }
}
```

### GET /api/dashboard/recent-activity
Get recent system activity.

---

## Medical Records Endpoints

### GET /api/medical-records
Get paginated list of medical records.

**Query Parameters:**
- `page` (optional): Page number
- `per_page` (optional): Records per page
- `search` (optional): Search term
- `status` (optional): Filter by status
- `date_from` (optional): Start date filter
- `date_to` (optional): End date filter

### POST /api/medical-records
Create new medical record.

**Request Body:**
```json
{
    "patient_id": 1,
    "visit_date": "2025-01-15",
    "chief_complaint": "Fever and headache",
    "history_of_present_illness": "Patient reports fever for 3 days...",
    "vital_signs": {
        "blood_pressure": "120/80",
        "temperature": "38.5",
        "pulse": "85"
    },
    "physical_examination": "General appearance: ill-looking...",
    "assessment": "Suspected malaria",
    "plan": "Blood test and treatment"
}
```

### GET /api/medical-records/{id}
Get specific medical record.

### PUT /api/medical-records/{id}
Update medical record.

### DELETE /api/medical-records/{id}
Delete medical record.

### GET /api/medical-records/{id}/download/{index}
Download medical record attachment.

---

## Appointment Endpoints

### GET /api/appointments
Get paginated list of appointments.

### POST /api/appointments
Create new appointment.

**Request Body:**
```json
{
    "patient_id": 1,
    "doctor_id": 2,
    "appointment_date": "2025-01-20",
    "appointment_time": "10:00",
    "type": "consultation",
    "reason": "Follow-up visit",
    "notes": "Patient needs follow-up"
}
```

### GET /api/appointments/today
Get today's appointments.

### GET /api/appointments/upcoming
Get upcoming appointments.

### POST /api/appointments/{id}/confirm
Confirm appointment.

### POST /api/appointments/{id}/cancel
Cancel appointment.

---

## Expert System Endpoints

### GET /api/expert-system/symptoms
Get available symptoms for analysis.

### GET /api/expert-system/diseases
Get available diseases.

### POST /api/expert-system/analyze
Analyze symptoms using AI expert system.

**Request Body:**
```json
{
    "symptoms": [1, 2, 3, 4],
    "patient_id": 1,
    "clinical_notes": "Patient presents with fever and headache"
}
```

**Response:**
```json
{
    "success": true,
    "analysis": {
        "recommendations": [
            {
                "disease_id": 1,
                "disease_name": "Malaria",
                "confidence_score": 0.85,
                "confidence_level": "high",
                "recommended_tests": ["Blood smear", "RDT"],
                "treatment_guidelines": "Antimalarial treatment recommended"
            }
        ],
        "requires_xray": false,
        "urgency_level": "medium"
    }
}
```

### GET /api/expert-system/statistics
Get expert system performance statistics.

---

## Pharmacy Endpoints

### GET /api/pharmacy/drugs
Get available drugs.

### GET /api/pharmacy/inventory
Get drug inventory.

### POST /api/pharmacy/dispense
Dispense medication.

### GET /api/pharmacy/prescriptions
Get prescriptions.

---

## Report Endpoints

### GET /api/reports
Get generated reports.

### POST /api/reports/generate
Generate new report.

**Request Body:**
```json
{
    "report_type": "diagnosis_report",
    "report_name": "Monthly Diagnosis Report",
    "description": "Analysis of diagnoses for January 2025",
    "period_start": "2025-01-01",
    "period_end": "2025-01-31",
    "filters": {
        "doctor_id": 1
    }
}
```

### GET /api/reports/templates
Get available report templates.

### GET /api/reports/{id}/download
Download report file.

---

## Search Endpoint

### GET /api/search
Global search across the system.

**Query Parameters:**
- `q`: Search query
- `type`: Search type (all, patients, records, appointments)

**Response:**
```json
{
    "success": true,
    "data": {
        "patients": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com"
            }
        ],
        "records": [
            {
                "id": 1,
                "record_number": "MR20250115001",
                "chief_complaint": "Fever and headache"
            }
        ]
    }
}
```

---

## Health Check

### GET /api/health
System health check endpoint.

**Response:**
```json
{
    "status": "healthy",
    "timestamp": "2025-01-15T10:30:00Z",
    "version": "1.0.0",
    "services": {
        "database": "connected",
        "cache": "connected",
        "queue": "connected"
    }
}
```

---

## Error Responses

### 400 Bad Request
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 401 Unauthorized
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### 403 Forbidden
```json
{
    "success": false,
    "message": "Insufficient permissions"
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Internal server error"
}
```

---

## Rate Limiting

API requests are rate limited:
- **Authenticated users**: 1000 requests per hour
- **Unauthenticated users**: 100 requests per hour

Rate limit headers are included in responses:
```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1642248000
```

---

## Pagination

List endpoints support pagination:

**Query Parameters:**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

**Response:**
```json
{
    "success": true,
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150
    },
    "links": {
        "first": "https://api.example.com/endpoint?page=1",
        "last": "https://api.example.com/endpoint?page=10",
        "prev": null,
        "next": "https://api.example.com/endpoint?page=2"
    }
}
```

---

## Webhooks

The API supports webhooks for real-time notifications:

### Available Events
- `medical_record.created`
- `appointment.scheduled`
- `diagnosis.completed`
- `treatment.started`
- `prescription.dispensed`

### Webhook Payload
```json
{
    "event": "medical_record.created",
    "timestamp": "2025-01-15T10:30:00Z",
    "data": {
        "id": 1,
        "patient_id": 1,
        "doctor_id": 2,
        "record_number": "MR20250115001"
    }
}
```

---

## SDKs and Libraries

### JavaScript/Node.js
```bash
npm install mesmtf-api-client
```

### PHP
```bash
composer require mesmtf/api-client
```

### Python
```bash
pip install mesmtf-api
```

---

## Support

For API support and questions:
- **Email**: api-support@mesmtf.nust.edu.na
- **Documentation**: https://docs.mesmtf.nust.edu.na
- **Status Page**: https://status.mesmtf.nust.edu.na
