# NormaFlow - Technical Architecture

## Overview

NormaFlow is built using **Hexagonal Architecture** (also known as Ports and Adapters) combined with **Clean Architecture** principles. This document describes the technical architecture in detail.

## Architecture Layers

### 1. Domain Layer (`app/Domain/`)

The Domain layer contains all **business logic** and is completely independent of any framework or external tool.

```
Domain/
├── Companies/       # Company aggregate
│   ├── Entities/    # Company, Site
│   ├── ValueObjects/# CompanyStatus, RFC, TaxId
│   ├── Repositories/ # Repository interfaces
│   └── Events/     # Domain events
├── Compliance/     # Compliance aggregate
│   ├── Entities/    # Regulation, Requirement, Evidence, Finding, ActionPlan
│   ├── ValueObjects/# ComplianceStatus, EvidenceType
│   ├── Repositories/
│   └── Events/
├── Identity/       # User management aggregate
│   ├── Entities/   # User, Role, Permission
│   ├── ValueObjects/
│   └── Repositories/
└── Shared/         # Cross-cutting concerns
    ├── ValueObjects/ # UUID, Money, DateRange
    └── Events/     # Shared events
```

**Responsibilities:**
- Define entities, value objects, and aggregates
- Encapsulate business rules and invariants
- Define repository interfaces (ports)
- Emit domain events
- **No dependencies on frameworks or external libraries**

### 2. Application Layer (`app/Application/`)

The Application layer orchestrates the domain objects to fulfill use cases.

```
Application/
├── Commands/       # Write operations (CQRS)
│   ├── CreateCompany/
│   ├── CreateSite/
│   ├── UploadEvidence/
│   └── CloseFinding/
├── Queries/        # Read operations (CQRS)
│   ├── GetCompanyDetails/
│   ├── GetComplianceDashboard/
│   └── GetExpiringRequirements/
├── Services/       # Application services
│   ├── ComplianceService/
│   ├── AuditService/
│   └── NotificationService/
├── DTOs/           # Data Transfer Objects
└── Mappers/        # Entity <-> DTO mapping
```

**Responsibilities:**
- Implement use cases (CQRS pattern)
- Coordinate domain objects
- Handle transactions
- Emit application events
- **Depends only on Domain layer**

### 3. Infrastructure Layer (`app/Infrastructure/`)

The Infrastructure layer contains implementations of the ports defined in the Domain layer.

```
Infrastructure/
├── Persistence/
│   ├── Eloquent/   # Eloquent implementations
│   │   ├── Models/ # Eloquent models
│   │   └── Repositories/ # Repository implementations
│   └── Doctrine/    # Alternative Doctrine implementations
├── Storage/        # File storage adapters
│   ├── S3Storage/
│   └── LocalStorage/
├── External/       # External service adapters
│   ├── Stripe/
│   └── AWS/
└── Notifications/  # Notification channels
    ├── Email/
    ├── SMS/
    └── Push/
```

**Responsibilities:**
- Implement repository interfaces (adapters)
- Handle database operations
- Manage file storage
- Integrate with external services
- **Depends on Domain and external libraries**

### 4. UI Layer (`app/UI/`)

The UI layer handles HTTP requests and responses.

```
UI/
├── API/
│   ├── Controllers/  # API controllers
│   ├── Requests/     # Form requests (validation)
│   ├── Resources/    # API resources (transformers)
│   └── Middleware/   # API-specific middleware
└── Web/
    ├── Controllers/   # Web controllers
    ├── Views/         # Blade templates
    ├── Components/   # Blade components
    └── Middleware/    # Web-specific middleware
```

**Responsibilities:**
- Handle HTTP requests/responses
- Input validation
- Response transformation
- **Depends on Application layer**

## Multi-Tenancy Strategy

NormaFlow uses a **shared database with tenant_id** approach for multi-tenancy:

```
┌─────────────────────────────────────────────────┐
│                   Shared Database               │
├─────────────────────────────────────────────────┤
│  tenants                                        │
│  ├── id (PK)                                   │
│  ├── name                                       │
│  ├── slug                                       │
│  ├── domain                                     │
│  ├── settings (JSON)                           │
│  └── subscription_plan                          │
├─────────────────────────────────────────────────┤
│  companies                                      │
│  ├── id (PK)                                   │
│  ├── tenant_id (FK)                            │
│  ├── name                                       │
│  ├── rfc                                       │
│  └── ...                                       │
├─────────────────────────────────────────────────┤
│  users                                          │
│  ├── id (PK)                                   │
│  ├── tenant_id (FK)                            │
│  ├── company_id (FK)                          │
│  └── ...                                       │
└─────────────────────────────────────────────────┘
```

### Tenant Resolution

1. **Subdomain**: `company.normaflow.com`
2. **Domain**: Custom domain mapping
3. **Header**: `X-Tenant-ID` header for API

## Module Dependencies

```
┌─────────────┐
│   UI/API    │  ←─ Depends on Application
└──────┬──────┘
       │
┌──────▼──────┐
│ Application │  ←─ Depends on Domain
└──────┬──────┘
       │
┌──────▼──────┐
│   Domain    │  ←─ No dependencies (pure business logic)
└─────────────┘
       ▲
       │
┌──────┴──────┐
│Infrastructure│  ←─ Implements Domain interfaces
└─────────────┘
```

## Communication Patterns

### 1. Command Query Responsibility Segregation (CQRS)

**Commands (Writes):**
```php
// CreateCompanyCommand
class CreateCompanyCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $rfc,
        public readonly string $industry,
        public readonly int $userId
    ) {}
}
```

**Queries (Reads):**
```php
// GetCompanyQuery
class GetCompanyQuery
{
    public function __construct(
        public readonly int $companyId
    ) {}
}
```

### 2. Domain Events

```php
// CompanyCreatedEvent
class CompanyCreatedEvent
{
    public function __construct(
        public readonly int $companyId,
        public readonly string $name,
        public readonly DateTimeImmutable $occurredAt
    ) {}
}
```

### 3. Service Bus Pattern

All commands and queries go through a message bus for:
- Decoupling
- Async processing (via queues)
- Audit logging
- Retry mechanisms

## Scalability Considerations

### Horizontal Scaling

The architecture supports horizontal scaling:

1. **Stateless Application Servers**: All state in database/Redis
2. **Load Balancer**: Distribute traffic
3. **Session Storage**: Redis for session management
4. **File Storage**: S3 for media/files

### Event Sourcing (Future)

For audit-intensive features:

```php
// Event Sourcing aggregate
class ComplianceAggregate
{
    public function apply(RequirementCreatedEvent $event): void;
    public function apply(EvidenceUploadedEvent $event): void;
    public function apply(FindingCreatedEvent $event): void;
}
```

### Microservices Migration (Future)

Bounded contexts can be extracted to microservices:

1. **Identity Service**: Users, roles, permissions
2. **Compliance Service**: Regulations, requirements, evidence
3. **Billing Service**: Subscriptions, invoices
4. **Notification Service**: Emails, SMS, push

## Security

### Authentication

- **Web**: Laravel Sanctum (session-based)
- **API**: Laravel Passport (OAuth2)

### Authorization

- **RBAC**: Spatie Laravel-Permission
- **Resource Policies**: Laravel Policies
- **Middleware**: Custom permission middleware

### Multi-Tenant Security

- **Tenant Scoping**: Global scopes on all queries
- **Data Isolation**: Row-level security
- **Audit Trail**: All actions logged

## Testing Strategy

### Unit Tests

```bash
# Domain entities and value objects
tests/Unit/Domain/

# Application services
tests/Unit/Application/
```

### Integration Tests

```bash
# Eloquent repositories
tests/Integration/Persistence/

# External services
tests/Integration/External/
```

### Feature Tests

```bash
# API endpoints
tests/Feature/API/

# Web controllers
tests/Feature/Web/
```

## File Structure

```
NormaFlow/
├── app/
│   ├── Console/
│   ├── Domain/           # Business logic
│   ├── Application/     # Use cases
│   ├── Infrastructure/  # Adapters
│   └── UI/              # Presentation
├── bootstrap/            # Laravel bootstrap
├── config/              # Configuration
├── database/
│   ├── migrations/      # Database schema
│   ├── factories/        # Test factories
│   └── seeders/         # Database seeders
├── resources/
│   ├── views/           # Blade templates
│   ├── lang/            # Translations
│   ├── css/             # Styles
│   └── js/              # JavaScript
├── routes/              # Route definitions
├── storage/             # Storage (logs, cache, uploads)
├── tests/              # Test suites
├── composer.json
└── README.md
```

## Next Steps

1. [ ] Set up Laravel skeleton
2. [ ] Create database migrations
3. [ ] Implement Domain entities
4. [ ] Create repository interfaces
5. [ ] Implement Application services
6. [ ] Build API controllers
7. [ ] Build Web controllers
8. [ ] Add authentication
9. [ ] Implement multi-tenancy
10. [ ] Add tests

---

**Document Version**: 1.0
**Last Updated**: 2026-03-19
