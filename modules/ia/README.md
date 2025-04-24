[![Generic badge](https://img.shields.io/badge/php->=8.2-green.svg)](https://shields.io/)
[![Generic badge](https://img.shields.io/badge/laravel-11-red.svg)](https://shields.io/)

# Contact Module - [Digico](https://github.com/orgs/Digico-be/repositories)

<b>Digico</b> is a web project that uses <b>Laravel</b> for the API. This module manages suppliers, <b>including CRUD</b> operations and follows <b>RESTful API</b> principles.

## API Endpoints

### 🔐 Authentication - OAuth2.0 with Laravel Passport

| Method | Endpoint             | Description          | Auth Required |
| ------ |----------------------|----------------------| ------------- |
| GET    | `/api/contacts`      | List all contacts    | ✅            |
| POST   | `/api/contacts`      | Create a new contact | ✅            |
| GET    | `/api/contacts/{id}` | Get contact details  | ✅            |
| PUT    | `/api/contacts/{id}` | Update contact       | ✅            |
| DELETE | `/api/contacts/{id}` | Delete contact       | ✅            |

### Response

```http
Content-Type: application/json
Authorization: Bearer your_access_token
```

```http
HTTP/1.1 200 OK

{
    "success": boolean,
    "message": string,
    "data": {
        "items": [],
        "pagination": {}
    }
}
```

```http
HTTP/1.1 400 Bad Request

{
    "success": false,
    "message": string,
    "errors": {
        "email": ["L'email est requis."],
        "name": ["Le nom doit contenir au moins 3 caractères."]
    }
}
```

### Advanced Search Parameters

| Parameter       | Type    | Description                             |
| --------------- | ------- | --------------------------------------- |
| `search`        | string  | Combined search across name,email, etc. |
| `per_page`      | integer | Number of results per page.             |
| `page`          | integer | Page number for paginated results.      |
| `order`         | integer | Sort order (asc or desc).               |
| `sort_by`       | integer | Sort results by a specific field.       |
| --------------- | ------- | --------------------------------------- |

## Invoice Fields

| Field                      | Type                                      | Description                      | Options                          |
| -------------------------- | ----------------------------------------- | -------------------------------- | -------------------------------- |
| `id`                       | bigInteger                                | Unique identifier                | `required`, `auto-increment`     |
| `identifier`               | string                                    | Name identifier                  | `nullable`, `unique`             |
| `identifier_number`        | int                                       | Number identifier                | `nullable`                       |
| `status`                   | enum('draft','pending','payed','expired') | Invoice status                   | `required`, `default:draft`      |
| `date`                     | Date ISO 8601 (UTC)                       | Invoice date                     | `required`, `default:now`        |
| `due_date`                 | Date ISO 8601 (UTC)                       | Invoice due date                 | `nullable`, `default:now+30days` |
| `payment_date`             | Date ISO 8601 (UTC)                       | Invoice payment date             | `nullable`                       |
| `structured_communication` | string(12)                                | Invoice structured communication | `nullable`, `max:12`             |
| `contact`                  | string                                    | Invoice email                    | `nullable`,                      |
| ├─`fullname`               | string                                    | Invoice email                    | `nullable`,                      |
| ├─`email`                  | string                                    | Supplier email                   | `required`, `unique`             |
| ├─`phone`                  | string(15)                                | Supplier phone                   | `nullable`, `max:15`             |
| ├─`street`                 | string                                    | Invoice street                   | `nullable`                       |
| ├─`street_number`          | string(10)                                | Invoice street number            | `nullable`, `max:10`             |
| ├─`city`                   | string                                    | Invoice city                     | `nullable`                       |
| ├─`zipcode`                | string(10)                                | Invoice zipcode                  | `nullable`, `max:10`             |
| ├─`country`                | string(2)                                 | Invoice country                  | `nullable`, `max:2`              |
| `amount`                   | Date ISO 8601 (UTC)                       | Invoice creation date            | `nullable`                       |
| ├─`subtotal`               | string                                    | Invoice email                    | `nullable`,                      |
| ├─`taxes`                  | string                                    | Invoice email                    | `nullable`,                      |
| ├─`total`                  | string                                    | Invoice email                    | `nullable`,                      |
| `created_at`               | Date ISO 8601 (UTC)                       | Invoice creation date            | `nullable`                       |
| `updated_at`               | Date ISO 8601 (UTC)                       | Invoice creation date            | `nullable`                       |
