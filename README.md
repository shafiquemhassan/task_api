Start the Server:
bash
php artisan serve
Access the Dashboard: http://localhost:8000
Access the API Docs: http://localhost:8000/api-docs
Verification Results
Automated Tests Passing
text
PASS  Tests\Feature\TaskApiTest
  ✓ user can register
  ✓ user can login
  ✓ authenticated user can create a task and it logs activity
  ✓ authenticated user can update their task
  ✓ authenticated user can delete their task
  ✓ user cannot access other users tasks
  ✓ user can filter tasks
