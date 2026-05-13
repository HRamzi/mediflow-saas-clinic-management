# MediFlow — Clinic Management System

## About
MediFlow is a multi-tenant SaaS clinic management platform built for 
Dr. Karim Benali and his three clinics across Algeria (Algiers, Oran, 
Tlemcen). It manages patient records, appointments, consultations, 
billing, pharmacy stock, and laboratory results for 58 staff members 
serving 880 patients per month. Built to production healthcare standards 
with immutable audit logging and mandatory allergy verification before 
prescribing.

## Technology Stack
- PHP 8.4.5
- Laravel 13
- MySQL 8.4.3
- Redis
- Laravel Sanctum (API authentication)
- Laravel Lighthouse (GraphQL)
- Laravel Horizon (queue monitoring)
- Docker & Docker Compose
- Nginx
- GitHub Actions (CI/CD)

## Architecture Decisions
- **Multi-tenancy:** Single database, shared tables, tenant_id on every 
  table, enforced via Laravel Global Scopes. Clinics are locations within 
  a tenant, not separate tenants.
- **API:** REST for operational modules (Auth, Appointment, Consultation, 
  Pharmacy, Lab, Admin). GraphQL via Lighthouse for data-heavy modules 
  (Billing, Medical Records, Reporting). Patient module uses both.
- **Project structure:** Service Layer pattern with Repository pattern. 
  Business logic lives in Services. Database communication lives in 
  Repositories. Controllers handle HTTP only.

## Local Setup
Setup instructions coming after MB-001.

## Branch Strategy
- `main` — production code only. Never push directly.
- `develop` — integration branch. All features merge here first.
- Feature branches — one per sprint task: `feature/MB-00X-description`
- Rule: every change goes through a pull request. No exceptions.

## Sprint Progress
- Sprint 1 — Active
- Architecture phase — Complete
- Database ERD — Approved
