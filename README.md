 # PHP Bar & Lounge CMS

A lightweight content management system built with PHP and deployed on Google Cloud.

The project demonstrates a database-driven website where an authenticated administrator can update public website content without modifying application code. The current implementation allows an administrator to update business information, homepage hero content, and the hero image through a CMS interface.

This project was created as a portfolio demonstration of PHP application development and integration with managed Google Cloud services.

## Features

- PHP-based public website and administrative CMS
- Administrator login and logout using PHP sessions
- Protected administrative routes
- Database-driven site configuration
- Homepage content editing through the CMS
- Hero image upload and replacement
- Image type and file-size validation
- Private image storage using Google Cloud Storage
- MySQL persistence using Google Cloud SQL
- Database access using PDO and prepared statements
- Application secrets managed through Google Cloud Secret Manager
- Automated deployment from GitHub using Google Cloud Build and Cloud Run

## Architecture

The application uses several managed Google Cloud services:

    GitHub
       |
       v
    Cloud Build
       |
       v
    Cloud Run
       |
       +---- Cloud SQL (MySQL)
       |
       +---- Cloud Storage
       |
       +---- Secret Manager

**Cloud Run** hosts the PHP application.

**Cloud SQL** stores editable CMS settings such as the business name, hero heading, hero description, and hero image reference.

**Cloud Storage** stores uploaded hero images. The bucket is private, and the application retrieves approved image objects for display through the PHP application.

**Secret Manager** is used for sensitive runtime configuration such as database credentials and the administrator password hash.

**Cloud Build** provides continuous deployment from the GitHub repository.

## CMS

The administrative Site Settings interface allows an authenticated administrator to:

- Change the business name
- Change the homepage hero heading
- Change the hero description
- Upload a replacement hero image
- Preview the currently configured hero image

Changes are stored in MySQL and reflected on the public website.

Uploaded images are validated for file size, MIME type, and image validity before being stored in Cloud Storage.

## Authentication

The administrative portion of the application uses PHP session-based authentication.

The administrator username is supplied through runtime configuration, while the password is stored as a password hash rather than plaintext. Administrative routes are protected through the application's PHP router.

This authentication system is sufficient for the scope of this demonstration but is not intended to represent a complete production authentication system.

## Security and Production-Readiness

This repository is a portfolio/demo implementation and is **not intended to be deployed unchanged as a production CMS**.

Several additional controls would be required before production use:

- **CSRF protection:** State-changing administrative forms currently do not use CSRF tokens. A production implementation should generate a cryptographically secure token associated with the authenticated session and validate that token before accepting state-changing requests.

- **Shared session storage:** The application currently uses PHP's default file-based sessions. Cloud Run instances do not share their local filesystem. A production deployment should therefore use an appropriate shared session store so authentication remains consistent across multiple instances and instance replacements.

- **Session hardening:** Production deployment should explicitly configure secure cookie attributes, session expiration, and other session-management controls.

- **Rate limiting:** Authentication and administrative endpoints do not currently implement application-level request throttling.

- **Audit logging:** CMS changes are not recorded in a dedicated administrative audit trail.

- **Additional deployment hardening:** Production deployment would require additional review of server configuration, routing, IAM permissions, monitoring, validation, credential rotation, and other environment-specific security controls.

The PHP built-in web server and custom router used by this demonstration are also intended for the scope of this project rather than as a complete production web-server architecture.

These limitations are documented intentionally to distinguish the functionality demonstrated by the project from the additional controls required for a production system.

## Technologies

- PHP 8.3
- MySQL
- PDO
- HTML
- CSS
- Google Cloud Run
- Google Cloud SQL
- Google Cloud Storage
- Google Cloud Secret Manager
- Google Cloud Build
- GitHub

## Purpose

The purpose of this project is to demonstrate practical experience building and deploying a PHP application that integrates frontend development, server-side application logic, relational data storage, file handling, authentication, cloud infrastructure, IAM, secrets management, and CI/CD.

The application is intentionally limited in scope so that the focus remains on the underlying architecture and integrations rather than building a full commercial CMS.
