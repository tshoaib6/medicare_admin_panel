# Medicare Admin Panel - Flutter API Documentation

## 📱 Complete API Guide for Flutter Implementation

### Base Configuration

```dart
class ApiConfig {
  static const String baseUrl = 'https://your-domain.com/api/v1';
  static const String contentType = 'application/json';
  
  // Add your actual domain here
  // Example: static const String baseUrl = 'https://medicare-admin.com/api/v1';
}
```

---

## 🔐 Authentication APIs

### 1. User Registration
```dart
// POST /api/v1/auth/signup
Future<Map<String, dynamic>> register({
  required String firstName,
  required String lastName,
  required String email,
  required String phoneNumber,
  required String password,
  required String passwordConfirmation,
  String? dateOfBirth,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/signup'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({
      'first_name': firstName,
      'last_name': lastName,
      'email': email,
      'phone_number': phoneNumber,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'date_of_birth': dateOfBirth,
    }),
  );
  
  return jsonDecode(response.body);
}
```

**Response Example:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "phone_number": "+1234567890",
      "is_admin": false,
      "email_verified_at": null
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  },
  "message": "User registered successfully"
}
```

### 2. User Login
```dart
// POST /api/v1/auth/login
Future<Map<String, dynamic>> login({
  required String phoneNumber,
  required String password,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/login'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({
      'phone_number': phoneNumber,
      'password': password,
    }),
  );
  
  return jsonDecode(response.body);
}
```

### 3. Google Login
```dart
// POST /api/v1/auth/google
Future<Map<String, dynamic>> googleLogin({
  required String googleToken,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/google'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({
      'google_token': googleToken,
    }),
  );
  
  return jsonDecode(response.body);
}
```

### 4. Get User Profile
```dart
// GET /api/v1/auth/me
Future<Map<String, dynamic>> getUserProfile(String token) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/auth/me'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

### 5. Update Profile
```dart
// PUT /api/v1/user/profile
Future<Map<String, dynamic>> updateProfile({
  required String token,
  String? firstName,
  String? lastName,
  String? email,
  String? phoneNumber,
  String? dateOfBirth,
  String? password,
  String? passwordConfirmation,
}) async {
  Map<String, dynamic> body = {};
  
  if (firstName != null) body['first_name'] = firstName;
  if (lastName != null) body['last_name'] = lastName;
  if (email != null) body['email'] = email;
  if (phoneNumber != null) body['phone_number'] = phoneNumber;
  if (dateOfBirth != null) body['date_of_birth'] = dateOfBirth;
  if (password != null) body['password'] = password;
  if (passwordConfirmation != null) body['password_confirmation'] = passwordConfirmation;

  final response = await http.put(
    Uri.parse('${ApiConfig.baseUrl}/user/profile'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode(body),
  );
  
  return jsonDecode(response.body);
}
```

### 6. Logout
```dart
// POST /api/v1/auth/logout
Future<Map<String, dynamic>> logout(String token) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/logout'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

---

## 📧 Email Verification & Password Reset

### 7. Request Email Verification
```dart
// POST /api/v1/auth/email/verify/request
Future<Map<String, dynamic>> requestEmailVerification({
  required String email,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/email/verify/request'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({'email': email}),
  );
  
  return jsonDecode(response.body);
}
```

### 8. Confirm Email Verification
```dart
// POST /api/v1/auth/email/verify/confirm
Future<Map<String, dynamic>> confirmEmailVerification({
  required String email,
  required String otp,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/email/verify/confirm'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({
      'email': email,
      'otp': otp,
    }),
  );
  
  return jsonDecode(response.body);
}
```

### 9. Forgot Password
```dart
// POST /api/v1/auth/password/forgot
Future<Map<String, dynamic>> forgotPassword({
  required String email,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/password/forgot'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({'email': email}),
  );
  
  return jsonDecode(response.body);
}
```

### 10. Reset Password
```dart
// POST /api/v1/auth/password/reset
Future<Map<String, dynamic>> resetPassword({
  required String email,
  required String otp,
  required String password,
  required String passwordConfirmation,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/password/reset'),
    headers: {'Content-Type': ApiConfig.contentType},
    body: jsonEncode({
      'email': email,
      'otp': otp,
      'password': password,
      'password_confirmation': passwordConfirmation,
    }),
  );
  
  return jsonDecode(response.body);
}
```

---

## 🏢 Companies & Plans APIs

### 11. Get All Companies
```dart
// GET /api/v1/companies
Future<Map<String, dynamic>> getCompanies({
  String? search,
  int page = 1,
  int perPage = 15,
}) async {
  Map<String, String> queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  if (search != null && search.isNotEmpty) {
    queryParams['search'] = search;
  }
  
  final uri = Uri.parse('${ApiConfig.baseUrl}/companies')
      .replace(queryParameters: queryParams);
      
  final response = await http.get(uri);
  return jsonDecode(response.body);
}
```

**Response Example:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Medicare Health Solutions",
        "description": "Leading Medicare provider",
        "website": "https://example.com",
        "phone": "+1234567890",
        "email": "info@example.com",
        "logo_url": "https://example.com/logo.png",
        "is_active": true,
        "plans_count": 5
      }
    ],
    "total": 10,
    "per_page": 15
  }
}
```

### 12. Get Company Details
```dart
// GET /api/v1/companies/{id}
Future<Map<String, dynamic>> getCompany(int companyId) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/companies/$companyId'),
  );
  
  return jsonDecode(response.body);
}
```

### 13. Get All Plans
```dart
// GET /api/v1/plans
Future<Map<String, dynamic>> getPlans({
  String? search,
  int? companyId,
  String? type,
  bool? isAvailable,
  int page = 1,
  int perPage = 15,
}) async {
  Map<String, String> queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  if (search != null) queryParams['search'] = search;
  if (companyId != null) queryParams['company_id'] = companyId.toString();
  if (type != null) queryParams['type'] = type;
  if (isAvailable != null) queryParams['is_available'] = isAvailable.toString();
  
  final uri = Uri.parse('${ApiConfig.baseUrl}/plans')
      .replace(queryParameters: queryParams);
      
  final response = await http.get(uri);
  return jsonDecode(response.body);
}
```

### 14. Get Plan Details
```dart
// GET /api/v1/plans/{id}
Future<Map<String, dynamic>> getPlan(int planId) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/plans/$planId'),
  );
  
  return jsonDecode(response.body);
}
```

**Plan Response Example:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "company_id": 1,
    "name": "Medicare Advantage Plan A",
    "description": "Comprehensive healthcare coverage",
    "type": "medicare_advantage",
    "monthly_premium": 89.99,
    "deductible": 500.00,
    "max_out_of_pocket": 7550.00,
    "coverage_areas": ["California", "Nevada"],
    "benefits": {
      "dental": true,
      "vision": true,
      "prescription": true
    },
    "is_available": true,
    "company": {
      "id": 1,
      "name": "Medicare Health Solutions"
    },
    "questionnaires_count": 2
  }
}
```

---

## ❓ Questionnaires APIs

### 15. Get All Questionnaires
```dart
// GET /api/v1/questionnaires
Future<Map<String, dynamic>> getQuestionnaires({
  String? search,
  int? planId,
  String? status,  // 'active' or 'inactive'
  int page = 1,
  int perPage = 15,
}) async {
  Map<String, String> queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  if (search != null && search.isNotEmpty) queryParams['search'] = search;
  if (planId != null) queryParams['plan_id'] = planId.toString();
  if (status != null) queryParams['status'] = status;
  
  final uri = Uri.parse('${ApiConfig.baseUrl}/questionnaires')
      .replace(queryParameters: queryParams);
      
  final response = await http.get(uri);
  return jsonDecode(response.body);
}
```

**Response Example:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Medicare Advantage Eligibility Assessment",
        "description": "Determine your eligibility and best options for Medicare Advantage plans.",
        "plan_id": 1,
        "instructions": "Please answer all questions honestly to help us find the best Medicare Advantage plan for your needs.",
        "estimated_time": 10,
        "is_active": true,
        "created_at": "2025-11-30T07:53:18.000000Z",
        "updated_at": "2025-11-30T07:53:18.000000Z",
        "plan": {
          "id": 1,
          "title": "Medicare Advantage Premium",
          "description": "Comprehensive Medicare Advantage plan with $0 premium",
          "company_id": 1
        }
      }
    ],
    "total": 3,
    "per_page": 15,
    "last_page": 1
  },
  "message": "Questionnaires retrieved successfully"
}
```

### 16. Get Questionnaire Details
```dart
// GET /api/v1/questionnaires/{id}
Future<Map<String, dynamic>> getQuestionnaire(int questionnaireId) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/questionnaires/$questionnaireId'),
  );
  
  return jsonDecode(response.body);
}
```

**Detailed Response Example:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Medicare Advantage Eligibility Assessment",
    "description": "Determine your eligibility and best options for Medicare Advantage plans.",
    "plan_id": 1,
    "instructions": "Please answer all questions honestly to help us find the best Medicare Advantage plan for your needs.",
    "estimated_time": 10,
    "is_active": true,
    "created_at": "2025-11-30T07:53:18.000000Z",
    "updated_at": "2025-11-30T07:53:18.000000Z",
    "plan": {
      "id": 1,
      "title": "Medicare Advantage Premium",
      "description": "Comprehensive Medicare Advantage plan with $0 premium, prescription drug coverage, and additional benefits like dental and vision.",
      "company": {
        "id": 1,
        "name": "Medicare Advantage Plus",
        "description": "Leading provider of comprehensive Medicare Advantage plans"
      }
    },
    "questions": [
      {
        "id": 1,
        "questionnaire_id": 1,
        "question_text": "What is your current age?",
        "question_type": "single_choice",
        "is_required": true,
        "order_number": 1,
        "created_at": "2025-11-30T07:53:18.000000Z",
        "updated_at": "2025-11-30T07:53:18.000000Z",
        "options": [
          {
            "id": 1,
            "question_id": 1,
            "label": "Under 65",
            "value": "under_65",
            "created_at": "2025-11-30T07:53:18.000000Z",
            "updated_at": "2025-11-30T07:53:18.000000Z"
          },
          {
            "id": 2,
            "question_id": 1,
            "label": "65-70",
            "value": "65_70",
            "created_at": "2025-11-30T07:53:18.000000Z",
            "updated_at": "2025-11-30T07:53:18.000000Z"
          }
        ]
      }
    ]
  },
  "message": "Questionnaire retrieved successfully"
}
```

### 17. Get Questionnaire Questions
```dart
// GET /api/v1/questionnaires/{id}/questions
Future<Map<String, dynamic>> getQuestionnaireQuestions(int questionnaireId) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/questionnaires/$questionnaireId/questions'),
  );
  
  return jsonDecode(response.body);
}
```

**Questions Response Example:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "questionnaire_id": 1,
      "question_text": "What is your current age?",
      "question_type": "single_choice",
      "is_required": true,
      "order_number": 1,
      "created_at": "2025-11-30T07:53:18.000000Z",
      "updated_at": "2025-11-30T07:53:18.000000Z",
      "options": [
        {
          "id": 1,
          "question_id": 1,
          "label": "Under 65",
          "value": "under_65",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        },
        {
          "id": 2,
          "question_id": 1,
          "label": "65-70", 
          "value": "65_70",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        }
      ]
    },
    {
      "id": 2,
      "questionnaire_id": 1,
      "question_text": "Are you currently enrolled in Medicare Part A and Part B?",
      "question_type": "single_choice",
      "is_required": true,
      "order_number": 2,
      "created_at": "2025-11-30T07:53:18.000000Z",
      "updated_at": "2025-11-30T07:53:18.000000Z",
      "options": [
        {
          "id": 6,
          "question_id": 2,
          "label": "Yes, both Part A and Part B",
          "value": "both",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        },
        {
          "id": 7,
          "question_id": 2,
          "label": "Only Part A",
          "value": "part_a_only",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        },
        {
          "id": 8,
          "question_id": 2,
          "label": "Only Part B",
          "value": "part_b_only",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        },
        {
          "id": 9,
          "question_id": 2,
          "label": "Neither",
          "value": "neither",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        }
      ]
    },
    {
      "id": 3,
      "questionnaire_id": 1,
      "question_text": "Which additional benefits are most important to you? (Select all that apply)",
      "question_type": "multiple_choice",
      "is_required": false,
      "order_number": 3,
      "created_at": "2025-11-30T07:53:18.000000Z",
      "updated_at": "2025-11-30T07:53:18.000000Z",
      "options": [
        {
          "id": 10,
          "question_id": 3,
          "label": "Prescription drug coverage",
          "value": "prescription",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        },
        {
          "id": 11,
          "question_id": 3,
          "label": "Dental coverage",
          "value": "dental",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        },
        {
          "id": 12,
          "question_id": 3,
          "label": "Vision coverage",
          "value": "vision",
          "created_at": "2025-11-30T07:53:18.000000Z",
          "updated_at": "2025-11-30T07:53:18.000000Z"
        }
      ]
    }
  ],
  "message": "Questions retrieved successfully"
}
```

**Question Types Available:**
- `single_choice`: Select one option from multiple choices
- `multiple_choice`: Select multiple options from choices
- `text`: Short text input
- `textarea`: Long text input
- `number`: Numeric input
- `email`: Email address input
- `date`: Date picker
- `yes_no`: Simple yes/no question

---

## 📝 Questionnaire Responses APIs (Core Feature)

### 18. Start a Questionnaire
```dart
// POST /api/v1/questionnaires/{id}/start
Future<Map<String, dynamic>> startQuestionnaire({
  required String token,
  required int questionnaireId,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/questionnaires/$questionnaireId/start'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

**Success Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "questionnaire_id": 1,
    "status": "in_progress",
    "started_at": "2025-12-01T23:33:44.000000Z",
    "completed_at": null,
    "metadata": {
      "ip_address": "127.0.0.1",
      "user_agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)"
    },
    "created_at": "2025-12-01T23:48:44.000000Z",
    "updated_at": "2025-12-01T23:48:44.000000Z",
    "questionnaire": {
      "id": 1,
      "title": "Medicare Advantage Eligibility Assessment",
      "description": "Determine your eligibility and best options for Medicare Advantage plans.",
      "estimated_time": 10
    }
  },
  "message": "Questionnaire started successfully"
}
```

**If Already Started Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "questionnaire_id": 1,
    "status": "in_progress",
    "started_at": "2025-12-01T23:33:44.000000Z",
    "completed_at": null,
    "question_responses": []
  },
  "message": "Questionnaire response already exists"
}
```

### 19. Submit Answers
```dart
// POST /api/v1/questionnaire-responses/{id}/answers
Future<Map<String, dynamic>> submitAnswers({
  required String token,
  required int responseId,
  required List<Map<String, dynamic>> answers,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/questionnaire-responses/$responseId/answers'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode({
      'answers': answers,
    }),
  );
  
  return jsonDecode(response.body);
}
```

**Request Body Examples:**
```dart
// For single choice question (select one option)
List<Map<String, dynamic>> singleChoiceAnswers = [
  {
    'question_id': 1,
    'answer_value': [2], // Option ID array (even for single choice)
    'answer_text': null,
  },
];

// For multiple choice question (select multiple options)
List<Map<String, dynamic>> multipleChoiceAnswers = [
  {
    'question_id': 3,
    'answer_value': [10, 11, 12], // Multiple option IDs
    'answer_text': null,
  },
];

// For text question
List<Map<String, dynamic>> textAnswers = [
  {
    'question_id': 4,
    'answer_value': null,
    'answer_text': 'I have diabetes and need comprehensive medication coverage',
  },
];

// Submit all answers at once
List<Map<String, dynamic>> allAnswers = [
  {
    'question_id': 1,
    'answer_value': [1], // "Under 65"
    'answer_text': null,
  },
  {
    'question_id': 2,
    'answer_value': [8], // "Only Part B"
    'answer_text': null,
  },
  {
    'question_id': 3,
    'answer_value': [10, 12], // "Prescription drug coverage" and "Vision coverage"
    'answer_text': null,
  },
];

await submitAnswers(
  token: userToken,
  responseId: questionnaireResponseId,
  answers: allAnswers,
);
```

**Success Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "questionnaire_id": 1,
    "status": "in_progress",
    "started_at": "2025-12-01T23:33:44.000000Z",
    "completed_at": null,
    "question_responses": [
      {
        "id": 4,
        "questionnaire_response_id": 2,
        "question_id": 1,
        "answer_value": [1],
        "answer_text": null,
        "created_at": "2025-12-01T23:51:52.000000Z",
        "updated_at": "2025-12-01T23:51:52.000000Z",
        "question": {
          "id": 1,
          "question_text": "What is your current age?",
          "question_type": "single_choice",
          "options": [...]
        }
      }
    ]
  },
  "message": "Answers submitted successfully"
}
```

**Validation Rules:**
- `answers` array is required
- `question_id` must exist in questions table
- For choice questions: `answer_value` must be array of valid option IDs
- For text questions: `answer_text` must be provided
- Either `answer_value` or `answer_text` is required (not both)

### 20. Complete Questionnaire
```dart
// POST /api/v1/questionnaire-responses/{id}/complete
Future<Map<String, dynamic>> completeQuestionnaire({
  required String token,
  required int responseId,
}) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/questionnaire-responses/$responseId/complete'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

**Success Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "questionnaire_id": 1,
    "status": "completed",
    "started_at": "2025-12-01T23:33:44.000000Z",
    "completed_at": "2025-12-02T00:05:12.000000Z",
    "questionnaire": {
      "id": 1,
      "title": "Medicare Advantage Eligibility Assessment",
      "plan": {
        "id": 1,
        "title": "Medicare Advantage Premium",
        "company": {
          "id": 1,
          "name": "Medicare Advantage Plus"
        }
      }
    },
    "question_responses": [...]
  },
  "message": "Questionnaire completed successfully"
}
```

### 21. Get My Questionnaire Responses
```dart
// GET /api/v1/my/questionnaire-responses
Future<Map<String, dynamic>> getMyResponses({
  required String token,
  int page = 1,
  int perPage = 15,
}) async {
  Map<String, String> queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  final uri = Uri.parse('${ApiConfig.baseUrl}/my/questionnaire-responses')
      .replace(queryParameters: queryParams);
      
  final response = await http.get(
    uri,
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

**Response Example:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "user_id": 1,
        "questionnaire_id": 1,
        "status": "completed",
        "started_at": "2025-12-01T23:33:44.000000Z",
        "completed_at": "2025-12-01T23:43:44.000000Z",
        "created_at": "2025-12-01T23:48:44.000000Z",
        "updated_at": "2025-12-01T23:48:44.000000Z",
        "questionnaire": {
          "id": 1,
          "title": "Medicare Advantage Eligibility Assessment",
          "estimated_time": 10,
          "plan": {
            "id": 1,
            "title": "Medicare Advantage Premium",
            "company_id": 1
          }
        },
        "question_responses_count": 3
      }
    ],
    "total": 1,
    "per_page": 15,
    "last_page": 1
  },
  "message": "Your questionnaire responses retrieved successfully"
}
```

### 22. Get Specific Response Details
```dart
// GET /api/v1/questionnaire-responses/{id}
Future<Map<String, dynamic>> getResponseDetails({
  required String token,
  required int responseId,
}) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/questionnaire-responses/$responseId'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

**Detailed Response Example:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "questionnaire_id": 1,
    "status": "completed",
    "started_at": "2025-12-01T23:33:44.000000Z",
    "completed_at": "2025-12-01T23:43:44.000000Z",
    "metadata": {
      "ip_address": "127.0.0.1",
      "user_agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)"
    },
    "created_at": "2025-12-01T23:48:44.000000Z",
    "updated_at": "2025-12-01T23:48:44.000000Z",
    "user": {
      "id": 1,
      "first_name": "Admin",
      "last_name": "User",
      "email": "admin@medicare.com",
      "phone_number": "+1-555-0001"
    },
    "questionnaire": {
      "id": 1,
      "title": "Medicare Advantage Eligibility Assessment",
      "description": "Determine your eligibility and best options for Medicare Advantage plans.",
      "estimated_time": 10,
      "plan": {
        "id": 1,
        "title": "Medicare Advantage Premium",
        "description": "Comprehensive Medicare Advantage plan with $0 premium, prescription drug coverage, and additional benefits like dental and vision.",
        "company": {
          "id": 1,
          "name": "Medicare Advantage Plus",
          "description": "Leading provider of comprehensive Medicare Advantage plans"
        }
      }
    },
    "question_responses": [
      {
        "id": 4,
        "questionnaire_response_id": 2,
        "question_id": 1,
        "answer_value": [1],
        "answer_text": null,
        "created_at": "2025-12-01T23:51:52.000000Z",
        "updated_at": "2025-12-01T23:51:52.000000Z",
        "question": {
          "id": 1,
          "questionnaire_id": 1,
          "question_text": "What is your current age?",
          "question_type": "single_choice",
          "is_required": true,
          "order_number": 1,
          "options": [
            {
              "id": 1,
              "question_id": 1,
              "label": "Under 65",
              "value": "under_65",
              "created_at": "2025-11-30T07:53:18.000000Z",
              "updated_at": "2025-11-30T07:53:18.000000Z"
            },
            {
              "id": 2,
              "question_id": 1,
              "label": "65-70",
              "value": "65_70",
              "created_at": "2025-11-30T07:53:18.000000Z",
              "updated_at": "2025-11-30T07:53:18.000000Z"
            }
          ]
        }
      },
      {
        "id": 5,
        "questionnaire_response_id": 2,
        "question_id": 2,
        "answer_value": [8],
        "answer_text": null,
        "created_at": "2025-12-01T23:52:02.000000Z",
        "updated_at": "2025-12-01T23:52:02.000000Z",
        "question": {
          "id": 2,
          "questionnaire_id": 1,
          "question_text": "Are you currently enrolled in Medicare Part A and Part B?",
          "question_type": "single_choice",
          "is_required": true,
          "order_number": 2,
          "options": [
            {
              "id": 6,
              "question_id": 2,
              "label": "Yes, both Part A and Part B",
              "value": "both"
            },
            {
              "id": 7,
              "question_id": 2,
              "label": "Only Part A",
              "value": "part_a_only"
            },
            {
              "id": 8,
              "question_id": 2,
              "label": "Only Part B",
              "value": "part_b_only"
            },
            {
              "id": 9,
              "question_id": 2,
              "label": "Neither",
              "value": "neither"
            }
          ]
        }
      }
    ]
  },
  "message": "Questionnaire response retrieved successfully"
}
```

### 23. Get Questionnaire Response Statistics (Admin Only)
```dart
// GET /api/v1/questionnaire-responses/stats
Future<Map<String, dynamic>> getQuestionnaireStats({
  required String token,
}) async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/questionnaire-responses/stats'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

**Statistics Response Example:**
```json
{
  "success": true,
  "data": {
    "total_responses": 150,
    "completed_responses": 120,
    "in_progress_responses": 25,
    "abandoned_responses": 5,
    "average_completion_time": 12.5,
    "responses_by_questionnaire": [
      {
        "questionnaire_id": 1,
        "count": 75,
        "questionnaire": {
          "id": 1,
          "title": "Medicare Advantage Eligibility Assessment"
        }
      }
    ],
    "recent_responses": [
      {
        "id": 2,
        "status": "completed",
        "created_at": "2025-12-01T23:48:44.000000Z",
        "user": {
          "first_name": "John",
          "last_name": "Doe"
        },
        "questionnaire": {
          "title": "Medicare Advantage Eligibility Assessment"
        }
      }
    ]
  },
  "message": "Questionnaire response statistics retrieved successfully"
}
```

### Flutter Implementation Example: Complete Questionnaire Flow

```dart
class QuestionnaireService {
  final String baseUrl = 'https://your-domain.com/api/v1';
  String? authToken;
  
  void setAuthToken(String token) {
    authToken = token;
  }
  
  Map<String, String> get headers => {
    'Content-Type': 'application/json',
    if (authToken != null) 'Authorization': 'Bearer $authToken',
  };

  // Complete questionnaire workflow
  Future<QuestionnaireWorkflow> startQuestionnaireWorkflow(int questionnaireId) async {
    try {
      // 1. Get questionnaire details with questions
      final questionnaireResponse = await http.get(
        Uri.parse('$baseUrl/questionnaires/$questionnaireId'),
        headers: headers,
      );
      
      if (questionnaireResponse.statusCode != 200) {
        throw Exception('Failed to load questionnaire');
      }
      
      final questionnaireData = jsonDecode(questionnaireResponse.body)['data'];
      
      // 2. Start questionnaire response
      final startResponse = await http.post(
        Uri.parse('$baseUrl/questionnaires/$questionnaireId/start'),
        headers: headers,
      );
      
      if (startResponse.statusCode != 200 && startResponse.statusCode != 201) {
        throw Exception('Failed to start questionnaire');
      }
      
      final responseData = jsonDecode(startResponse.body)['data'];
      
      return QuestionnaireWorkflow(
        questionnaire: Questionnaire.fromJson(questionnaireData),
        responseId: responseData['id'],
        questions: (questionnaireData['questions'] as List)
            .map((q) => Question.fromJson(q))
            .toList(),
      );
      
    } catch (e) {
      throw Exception('Error starting questionnaire: $e');
    }
  }

  Future<bool> submitAnswers(int responseId, List<QuestionAnswer> answers) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/questionnaire-responses/$responseId/answers'),
        headers: headers,
        body: jsonEncode({
          'answers': answers.map((answer) => answer.toJson()).toList(),
        }),
      );
      
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  Future<bool> completeQuestionnaire(int responseId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/questionnaire-responses/$responseId/complete'),
        headers: headers,
      );
      
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }
}

// Data Models
class QuestionnaireWorkflow {
  final Questionnaire questionnaire;
  final int responseId;
  final List<Question> questions;
  
  QuestionnaireWorkflow({
    required this.questionnaire,
    required this.responseId,
    required this.questions,
  });
}

class Questionnaire {
  final int id;
  final String title;
  final String description;
  final int? estimatedTime;
  final String? instructions;
  
  Questionnaire({
    required this.id,
    required this.title,
    required this.description,
    this.estimatedTime,
    this.instructions,
  });
  
  factory Questionnaire.fromJson(Map<String, dynamic> json) {
    return Questionnaire(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      estimatedTime: json['estimated_time'],
      instructions: json['instructions'],
    );
  }
}

class Question {
  final int id;
  final String questionText;
  final String questionType;
  final bool isRequired;
  final int orderNumber;
  final List<QuestionOption> options;
  
  Question({
    required this.id,
    required this.questionText,
    required this.questionType,
    required this.isRequired,
    required this.orderNumber,
    required this.options,
  });
  
  factory Question.fromJson(Map<String, dynamic> json) {
    return Question(
      id: json['id'],
      questionText: json['question_text'],
      questionType: json['question_type'],
      isRequired: json['is_required'] ?? false,
      orderNumber: json['order_number'] ?? 0,
      options: (json['options'] as List? ?? [])
          .map((o) => QuestionOption.fromJson(o))
          .toList(),
    );
  }
}

class QuestionOption {
  final int id;
  final String label;
  final String value;
  
  QuestionOption({
    required this.id,
    required this.label,
    required this.value,
  });
  
  factory QuestionOption.fromJson(Map<String, dynamic> json) {
    return QuestionOption(
      id: json['id'],
      label: json['label'],
      value: json['value'],
    );
  }
}

class QuestionAnswer {
  final int questionId;
  final List<int>? answerValue;
  final String? answerText;
  
  QuestionAnswer({
    required this.questionId,
    this.answerValue,
    this.answerText,
  });
  
  Map<String, dynamic> toJson() => {
    'question_id': questionId,
    'answer_value': answerValue,
    'answer_text': answerText,
  };
}

// Usage Example in Widget
class QuestionnaireScreen extends StatefulWidget {
  final int questionnaireId;
  
  const QuestionnaireScreen({Key? key, required this.questionnaireId}) : super(key: key);
  
  @override
  _QuestionnaireScreenState createState() => _QuestionnaireScreenState();
}

class _QuestionnaireScreenState extends State<QuestionnaireScreen> {
  final QuestionnaireService _service = QuestionnaireService();
  QuestionnaireWorkflow? workflow;
  Map<int, QuestionAnswer> answers = {};
  bool isLoading = true;
  
  @override
  void initState() {
    super.initState();
    _loadQuestionnaire();
  }
  
  Future<void> _loadQuestionnaire() async {
    try {
      workflow = await _service.startQuestionnaireWorkflow(widget.questionnaireId);
      setState(() {
        isLoading = false;
      });
    } catch (e) {
      // Handle error
      setState(() {
        isLoading = false;
      });
    }
  }
  
  Future<void> _submitAnswers() async {
    if (workflow == null) return;
    
    final success = await _service.submitAnswers(
      workflow!.responseId,
      answers.values.toList(),
    );
    
    if (success) {
      await _service.completeQuestionnaire(workflow!.responseId);
      // Navigate to completion screen
    }
  }
  
  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }
    
    if (workflow == null) {
      return Scaffold(
        body: Center(child: Text('Failed to load questionnaire')),
      );
    }
    
    return Scaffold(
      appBar: AppBar(
        title: Text(workflow!.questionnaire.title),
      ),
      body: ListView.builder(
        itemCount: workflow!.questions.length,
        itemBuilder: (context, index) {
          final question = workflow!.questions[index];
          return QuestionWidget(
            question: question,
            onAnswerChanged: (answer) {
              setState(() {
                answers[question.id] = answer;
              });
            },
          );
        },
      ),
      bottomNavigationBar: Padding(
        padding: EdgeInsets.all(16),
        child: ElevatedButton(
          onPressed: _submitAnswers,
          child: Text('Submit Questionnaire'),
        ),
      ),
    );
  }
}
```

**Error Handling:**
- **401 Unauthorized**: User needs to log in again
- **403 Forbidden**: User doesn't have permission to access the questionnaire response
- **404 Not Found**: Questionnaire or response doesn't exist
- **422 Validation Error**: Invalid answer format or missing required answers

---

## 📞 Callback Requests APIs

### 23. Submit Callback Request
```dart
// POST /api/v1/callback-requests
Future<Map<String, dynamic>> submitCallbackRequest({
  required String token,
  required int userId,
  required int companyId,
  required String callDate,
  required String callTime,
  String? message,
  String? status,
  String? adminNotes,
}) async {
  Map<String, dynamic> body = {
    'user_id': userId,
    'company_id': companyId,
    'call_date': callDate,        // Format: YYYY-MM-DD
    'call_time': callTime,        // Format: HH:MM (24-hour)
  };
  
  if (message != null) body['message'] = message;
  if (status != null) body['status'] = status;
  if (adminNotes != null) body['admin_notes'] = adminNotes;

  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/callback-requests'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode(body),
  );
  
  return jsonDecode(response.body);
}
```

**Request Body Example:**
```json
{
  "user_id": 1,
  "company_id": 1,
  "call_date": "2024-12-03",
  "call_time": "14:30",
  "message": "I need help choosing a Medicare plan",
  "status": "pending"
}
```

**Success Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "company_id": 1,
    "call_date": "2024-12-03",
    "call_time": "14:30",
    "message": "I need help choosing a Medicare plan",
    "status": "pending",
    "admin_notes": null,
    "created_at": "2024-12-02T10:00:00.000000Z",
    "updated_at": "2024-12-02T10:00:00.000000Z",
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "phone_number": "+1234567890"
    },
    "company": {
      "id": 1,
      "name": "Medicare Health Solutions",
      "phone": "+1-800-MEDICARE",
      "email": "info@example.com"
    }
  },
  "message": "Callback request created successfully"
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "user_id": ["The user id field is required."],
    "company_id": ["The company id field is required."],
    "call_date": ["The call date must be a date after or equal to today."],
    "call_time": ["The call time field is required."]
  }
}
```

**Validation Rules:**
- `user_id`: required, must exist in users table
- `company_id`: required, must exist in companies table  
- `call_date`: required, must be today or future date (YYYY-MM-DD format)
- `call_time`: required, string format (HH:MM)
- `message`: optional, string
- `status`: optional, must be one of: pending, scheduled, completed, cancelled (default: pending)
- `admin_notes`: optional, string

### Flutter Implementation Example for Callback Request Form

```dart
class CallbackRequestForm extends StatefulWidget {
  final int companyId;
  final String companyName;
  final String companyPhone;
  
  const CallbackRequestForm({
    Key? key,
    required this.companyId,
    required this.companyName,
    required this.companyPhone,
  }) : super(key: key);

  @override
  _CallbackRequestFormState createState() => _CallbackRequestFormState();
}

class _CallbackRequestFormState extends State<CallbackRequestForm> {
  final _formKey = GlobalKey<FormState>();
  final MedicareApiService _apiService = MedicareApiService();
  
  // Form controllers
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _messageController = TextEditingController();
  
  DateTime? _selectedDate;
  TimeOfDay? _selectedTime;
  String _selectedTimeZone = 'Central Time (CT)';
  
  bool _isLoading = false;
  
  final List<String> _timeZones = [
    'Eastern Time (ET)',
    'Central Time (CT)',
    'Mountain Time (MT)',
    'Pacific Time (PT)',
    'Alaska Time (AKT)',
    'Hawaii Time (HT)',
  ];

  @override
  void initState() {
    super.initState();
    // Pre-fill phone number from company info
    _phoneController.text = widget.companyPhone;
  }

  Future<void> _selectDate() async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now().add(Duration(days: 1)),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(Duration(days: 30)),
    );
    
    if (picked != null && picked != _selectedDate) {
      setState(() {
        _selectedDate = picked;
      });
    }
  }

  Future<void> _selectTime() async {
    final TimeOfDay? picked = await showTimePicker(
      context: context,
      initialTime: TimeOfDay(hour: 9, minute: 0),
    );
    
    if (picked != null && picked != _selectedTime) {
      setState(() {
        _selectedTime = picked;
      });
    }
  }

  Future<void> _submitCallbackRequest() async {
    if (!_formKey.currentState!.validate() || 
        _selectedDate == null || 
        _selectedTime == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Please fill all required fields')),
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      // Format date and time
      String formattedDate = '${_selectedDate!.year.toString().padLeft(4, '0')}-'
          '${_selectedDate!.month.toString().padLeft(2, '0')}-'
          '${_selectedDate!.day.toString().padLeft(2, '0')}';
      
      String formattedTime = '${_selectedTime!.hour.toString().padLeft(2, '0')}:'
          '${_selectedTime!.minute.toString().padLeft(2, '0')}';

      // Get current user ID (you'll need to implement this based on your auth system)
      final currentUser = await _getCurrentUser();
      
      final response = await _apiService.submitCallbackRequest(
        token: await _getAuthToken(),
        userId: currentUser['id'],
        companyId: widget.companyId, // Pass this from the previous screen
        callDate: formattedDate,
        callTime: formattedTime,
        message: _messageController.text.trim().isNotEmpty ? _messageController.text.trim() : null,
        status: 'pending',
      );

      if (response['success']) {
        // Show success message
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Callback request submitted successfully!'),
            backgroundColor: Colors.green,
          ),
        );
        
        // Navigate back or to confirmation screen
        Navigator.pop(context);
        
      } else {
        // Show error message
        String errorMessage = response['message'] ?? 'Failed to submit request';
        if (response['errors'] != null) {
          Map<String, dynamic> errors = response['errors'];
          errorMessage = errors.values
              .expand((list) => list as List)
              .join('\n');
        }
        
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(errorMessage),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Network error: $e'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<String> _getAuthToken() async {
    // Get token from SharedPreferences or your auth provider
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token') ?? '';
  }
  
  Future<Map<String, dynamic>> _getCurrentUser() async {
    // Get current user data from your auth system
    // This should return the authenticated user information
    final prefs = await SharedPreferences.getInstance();
    final userDataString = prefs.getString('user_data');
    if (userDataString != null) {
      return jsonDecode(userDataString);
    }
    throw Exception('User not found');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Schedule Your Call'),
        backgroundColor: Colors.blue,
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header description
              Text(
                'Request a callback from ${widget.companyName} and our Medicare specialists will contact you at your preferred time.',
                style: TextStyle(fontSize: 16, color: Colors.grey[600]),
              ),
              SizedBox(height: 20),

              // Call Summary Card
              Card(
                color: Colors.blue[50],
                child: Padding(
                  padding: EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Icon(Icons.info_outline, color: Colors.blue),
                          SizedBox(width: 8),
                          Text(
                            'Call Summary',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: Colors.blue,
                            ),
                          ),
                        ],
                      ),
                      SizedBox(height: 12),
                      Text('Company', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(widget.companyName, style: TextStyle(color: Colors.blue)),
                      SizedBox(height: 8),
                      Text('Phone Number', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(widget.companyPhone, style: TextStyle(color: Colors.blue)),
                    ],
                  ),
                ),
              ),
              SizedBox(height: 20),

              // Personal Information
              Text(
                'Personal Information',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 16),
              
              TextFormField(
                controller: _nameController,
                decoration: InputDecoration(
                  labelText: 'Full Name *',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Name is required';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),
              
              TextFormField(
                controller: _phoneController,
                decoration: InputDecoration(
                  labelText: 'Phone Number *',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.phone,
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Phone number is required';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),
              
              TextFormField(
                controller: _emailController,
                decoration: InputDecoration(
                  labelText: 'Email (optional)',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.emailAddress,
              ),
              SizedBox(height: 20),

              // Call Time Selection
              Card(
                color: Colors.orange[50],
                child: Padding(
                  padding: EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Icon(Icons.calendar_today, color: Colors.orange),
                          SizedBox(width: 8),
                          Text(
                            'Select Your Preferred Call Time',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: Colors.orange[800],
                            ),
                          ),
                        ],
                      ),
                      SizedBox(height: 16),

                      // Preferred Date
                      Text('Preferred Date *', style: TextStyle(fontWeight: FontWeight.bold)),
                      SizedBox(height: 8),
                      InkWell(
                        onTap: _selectDate,
                        child: Container(
                          padding: EdgeInsets.symmetric(horizontal: 12, vertical: 16),
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey),
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Row(
                            children: [
                              Icon(Icons.calendar_today, color: Colors.grey[600]),
                              SizedBox(width: 12),
                              Text(
                                _selectedDate != null
                                    ? '${_selectedDate!.day}/${_selectedDate!.month}/${_selectedDate!.year}'
                                    : 'Select a date',
                                style: TextStyle(
                                  fontSize: 16,
                                  color: _selectedDate != null ? Colors.black : Colors.grey[600],
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                      SizedBox(height: 8),
                      Text(
                        'Available dates: Tomorrow through next 30 days',
                        style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                      ),
                      SizedBox(height: 16),

                      // Preferred Time
                      Text('Preferred Time *', style: TextStyle(fontWeight: FontWeight.bold)),
                      SizedBox(height: 8),
                      InkWell(
                        onTap: _selectTime,
                        child: Container(
                          padding: EdgeInsets.symmetric(horizontal: 12, vertical: 16),
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey),
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Row(
                            children: [
                              Icon(Icons.access_time, color: Colors.grey[600]),
                              SizedBox(width: 12),
                              Text(
                                _selectedTime != null
                                    ? _selectedTime!.format(context)
                                    : 'Select a time',
                                style: TextStyle(
                                  fontSize: 16,
                                  color: _selectedTime != null ? Colors.black : Colors.grey[600],
                                ),
                              ),
                              Spacer(),
                              Icon(Icons.arrow_drop_down),
                            ],
                          ),
                        ),
                      ),
                      SizedBox(height: 8),
                      Text(
                        'Available Monday - Friday, 8:00 AM - 8:00 PM',
                        style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                      ),
                      SizedBox(height: 16),

                      // Time Zone
                      Text('Your Time Zone *', style: TextStyle(fontWeight: FontWeight.bold)),
                      SizedBox(height: 8),
                      DropdownButtonFormField<String>(
                        value: _selectedTimeZone,
                        decoration: InputDecoration(
                          border: OutlineInputBorder(),
                        ),
                        items: _timeZones.map((String timeZone) {
                          return DropdownMenuItem<String>(
                            value: timeZone,
                            child: Text(timeZone),
                          );
                        }).toList(),
                        onChanged: (String? newValue) {
                          if (newValue != null) {
                            setState(() {
                              _selectedTimeZone = newValue;
                            });
                          }
                        },
                      ),
                    ],
                  ),
                ),
              ),
              SizedBox(height: 20),

              // Additional Message
              Text(
                'Additional Information',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 16),
              
              TextFormField(
                controller: _messageController,
                decoration: InputDecoration(
                  labelText: 'Message (optional)',
                  hintText: 'Tell us about your specific needs or questions',
                  border: OutlineInputBorder(),
                ),
                maxLines: 4,
              ),
              SizedBox(height: 30),

              // Submit Button
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _submitCallbackRequest,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue,
                    padding: EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: _isLoading
                      ? CircularProgressIndicator(color: Colors.white)
                      : Text(
                          'Schedule Call',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _messageController.dispose();
    super.dispose();
  }
}
```

### 24. Get My Callback Requests
```dart
// GET /api/v1/my/callback-requests
Future<Map<String, dynamic>> getMyCallbackRequests({
  required String token,
  int page = 1,
  int perPage = 15,
}) async {
  Map<String, String> queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  final uri = Uri.parse('${ApiConfig.baseUrl}/my/callback-requests')
      .replace(queryParameters: queryParams);
      
  final response = await http.get(
    uri,
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

**Response Example:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "company_id": 1,
        "call_date": "2024-12-03",
        "call_time": "14:30",
        "message": "I need help choosing a Medicare plan",
        "status": "pending",
        "admin_notes": null,
        "created_at": "2024-12-02T10:00:00.000000Z",
        "updated_at": "2024-12-02T10:00:00.000000Z",
        "company": {
          "id": 1,
          "name": "Medicare Health Solutions",
          "phone": "+1-800-MEDICARE",
          "email": "info@example.com",
          "website": "https://example.com"
        }
      }
    ],
    "total": 5,
    "per_page": 15
  },
  "message": "Your callback requests retrieved successfully"
}
```

---

## 📊 Activity Logging APIs

### 25. Log User Activity
```dart
// POST /api/v1/activities/log
Future<Map<String, dynamic>> logActivity({
  required String token,
  required String action,
  String? description,
  Map<String, dynamic>? metadata,
}) async {
  Map<String, dynamic> body = {
    'action': action,
  };
  
  if (description != null) body['description'] = description;
  if (metadata != null) body['metadata'] = metadata;

  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/activities/log'),
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode(body),
  );
  
  return jsonDecode(response.body);
}
```

### 26. Get My Activities
```dart
// GET /api/v1/my/activities
Future<Map<String, dynamic>> getMyActivities({
  required String token,
  int page = 1,
  int perPage = 15,
}) async {
  Map<String, String> queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  final uri = Uri.parse('${ApiConfig.baseUrl}/my/activities')
      .replace(queryParameters: queryParams);
      
  final response = await http.get(
    uri,
    headers: {
      'Content-Type': ApiConfig.contentType,
      'Authorization': 'Bearer $token',
    },
  );
  
  return jsonDecode(response.body);
}
```

---

## 📢 Ads APIs

### 27. Get Active Ads
```dart
// GET /api/v1/ads/active
Future<Map<String, dynamic>> getActiveAds() async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/ads/active'),
  );
  
  return jsonDecode(response.body);
}
```

### 28. Track Ad Impression
```dart
// POST /api/v1/ads/{id}/impression
Future<Map<String, dynamic>> trackAdImpression(int adId) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/ads/$adId/impression'),
    headers: {'Content-Type': ApiConfig.contentType},
  );
  
  return jsonDecode(response.body);
}
```

### 29. Track Ad Click
```dart
// POST /api/v1/ads/{id}/click
Future<Map<String, dynamic>> trackAdClick(int adId) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/ads/$adId/click'),
    headers: {'Content-Type': ApiConfig.contentType},
  );
  
  return jsonDecode(response.body);
}
```

---

## 🔧 Flutter Implementation Examples

### Complete API Service Class
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class MedicareApiService {
  static const String baseUrl = 'https://your-domain.com/api/v1';
  
  // Store user token after login
  String? _authToken;
  
  void setAuthToken(String token) {
    _authToken = token;
  }
  
  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    if (_authToken != null) 'Authorization': 'Bearer $_authToken',
  };
  
  // Authentication
  Future<ApiResponse> login(String phoneNumber, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: _headers,
        body: jsonEncode({
          'phone_number': phoneNumber,
          'password': password,
        }),
      );
      
      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200 && data['success']) {
        setAuthToken(data['data']['token']);
        return ApiResponse.success(data);
      } else {
        return ApiResponse.error(data['message'] ?? 'Login failed');
      }
    } catch (e) {
      return ApiResponse.error('Network error: $e');
    }
  }
  
  // Questionnaire Response Flow
  Future<ApiResponse> startQuestionnaire(int questionnaireId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/questionnaires/$questionnaireId/start'),
        headers: _headers,
      );
      
      final data = jsonDecode(response.body);
      return response.statusCode == 201 
          ? ApiResponse.success(data)
          : ApiResponse.error(data['message'] ?? 'Failed to start questionnaire');
    } catch (e) {
      return ApiResponse.error('Network error: $e');
    }
  }
  
  Future<ApiResponse> submitQuestionnaireAnswers({
    required int responseId,
    required List<QuestionAnswer> answers,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/questionnaire-responses/$responseId/answers'),
        headers: _headers,
        body: jsonEncode({
          'answers': answers.map((answer) => answer.toJson()).toList(),
        }),
      );
      
      final data = jsonDecode(response.body);
      return response.statusCode == 200 
          ? ApiResponse.success(data)
          : ApiResponse.error(data['message'] ?? 'Failed to submit answers');
    } catch (e) {
      return ApiResponse.error('Network error: $e');
    }
  }
}

// Helper Classes
class ApiResponse {
  final bool success;
  final dynamic data;
  final String? error;
  
  ApiResponse.success(this.data) : success = true, error = null;
  ApiResponse.error(this.error) : success = false, data = null;
}

class QuestionAnswer {
  final int questionId;
  final List<int>? answerValue;
  final String? answerText;
  
  QuestionAnswer({
    required this.questionId,
    this.answerValue,
    this.answerText,
  });
  
  Map<String, dynamic> toJson() => {
    'question_id': questionId,
    'answer_value': answerValue,
    'answer_text': answerText,
  };
}
```

### Flutter Widget Example for Questionnaire
```dart
class QuestionnaireScreen extends StatefulWidget {
  final int questionnaireId;
  
  const QuestionnaireScreen({Key? key, required this.questionnaireId}) : super(key: key);
  
  @override
  _QuestionnaireScreenState createState() => _QuestionnaireScreenState();
}

class _QuestionnaireScreenState extends State<QuestionnaireScreen> {
  final MedicareApiService _apiService = MedicareApiService();
  Map<int, QuestionAnswer> _answers = {};
  int? _responseId;
  List<dynamic> _questions = [];
  bool _isLoading = true;
  
  @override
  void initState() {
    super.initState();
    _startQuestionnaire();
  }
  
  Future<void> _startQuestionnaire() async {
    final response = await _apiService.startQuestionnaire(widget.questionnaireId);
    
    if (response.success) {
      setState(() {
        _responseId = response.data['data']['id'];
        _isLoading = false;
      });
      await _loadQuestions();
    } else {
      // Handle error
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(response.error ?? 'Failed to start questionnaire')),
      );
    }
  }
  
  Future<void> _loadQuestions() async {
    // Load questions using getQuestionnaireQuestions API
  }
  
  Future<void> _submitAnswers() async {
    if (_responseId != null) {
      final response = await _apiService.submitQuestionnaireAnswers(
        responseId: _responseId!,
        answers: _answers.values.toList(),
      );
      
      if (response.success) {
        // Optionally complete the questionnaire
        await _completeQuestionnaire();
      }
    }
  }
  
  Future<void> _completeQuestionnaire() async {
    // Call complete questionnaire API
  }
  
  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(title: Text('Loading Questionnaire...')),
        body: Center(child: CircularProgressIndicator()),
      );
    }
    
    return Scaffold(
      appBar: AppBar(title: Text('Medicare Assessment')),
      body: ListView.builder(
        itemCount: _questions.length,
        itemBuilder: (context, index) {
          final question = _questions[index];
          return QuestionWidget(
            question: question,
            onAnswerChanged: (answer) {
              setState(() {
                _answers[question['id']] = answer;
              });
            },
          );
        },
      ),
      bottomNavigationBar: Padding(
        padding: EdgeInsets.all(16),
        child: ElevatedButton(
          onPressed: _submitAnswers,
          child: Text('Submit Answers'),
        ),
      ),
    );
  }
}
```

---

## 🚨 Error Handling

### Standard Error Responses
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "phone_number": ["The phone number field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### HTTP Status Codes
- **200**: Success
- **201**: Created (for POST requests)
- **400**: Bad Request (validation errors)
- **401**: Unauthorized (invalid/missing token)
- **403**: Forbidden (insufficient permissions)
- **404**: Not Found
- **422**: Unprocessable Entity (validation errors)
- **500**: Internal Server Error

### Flutter Error Handling Example
```dart
Future<ApiResponse> _handleApiCall(Future<http.Response> Function() apiCall) async {
  try {
    final response = await apiCall();
    final data = jsonDecode(response.body);
    
    switch (response.statusCode) {
      case 200:
      case 201:
        return ApiResponse.success(data);
      case 401:
        // Handle unauthorized - maybe redirect to login
        return ApiResponse.error('Please log in again');
      case 422:
        // Handle validation errors
        final errors = data['errors'] as Map<String, dynamic>?;
        String errorMessage = 'Validation failed';
        if (errors != null) {
          errorMessage = errors.values
              .expand((list) => list as List)
              .join(', ');
        }
        return ApiResponse.error(errorMessage);
      default:
        return ApiResponse.error(data['message'] ?? 'An error occurred');
    }
  } catch (e) {
    return ApiResponse.error('Network error: $e');
  }
}
```

---

## 📱 Complete Flutter Integration Checklist

### ✅ Required Dependencies
Add to your `pubspec.yaml`:
```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2  # For storing auth token
  provider: ^6.1.1  # For state management
```

### ✅ Authentication Flow
1. **Login** → Store token in SharedPreferences
2. **Auto-login** → Check stored token on app start
3. **Token refresh** → Handle 401 responses
4. **Logout** → Clear stored token

### ✅ Key Features to Implement
- [x] User registration & login
- [x] Questionnaire browsing
- [x] Questionnaire response flow
- [x] Answer submission & tracking
- [x] User profile management
- [x] Callback request submission
- [x] Activity logging

### ✅ UI Components Needed
- Login/Registration screens
- Plan browsing & details
- Questionnaire list & details
- Dynamic question rendering (text, single choice, multiple choice)
- Progress indicators
- Response history
- Profile management

---

## 🎯 Next Steps for Flutter Implementation

1. **Set up API service class** with proper error handling
2. **Implement authentication flow** with token storage
3. **Create questionnaire response workflow** with progress tracking
4. **Add form validation** for user inputs
5. **Implement offline capability** for partial questionnaire responses
6. **Add push notifications** for callback requests and updates
7. **Create responsive UI** for different screen sizes
8. **Add analytics tracking** for user behavior

This documentation provides everything you need to integrate the Medicare Admin Panel APIs into your Flutter application. Each endpoint includes request/response examples and Flutter code snippets for easy implementation.