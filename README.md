
## About Rental Management System

This is a rental management system made in laravel that enables all tenancy stakeholders to permorm automated lease actions.Its mainly useful for agencies who can manage rental houses/ properties on behalf of landlords. This means that we have various types of users,ranging from tenants,landlords,agency/admin and general users

### Admin/Agent
This is the main user,who has the followig capabilitites:
- Manage landlords
- Manage tenants
- Manage Properties
- Manage property units
- Create and manage leases
- Manage Invoices
- Manage calendar events
- Manage other users
- View reports
- Manage support tickets


### Tenant

Tenants can log in and :
- Raise support tickets
- View active leases
- View Invoices

### Landlord
Landlords can log in and :
- View properties he owns
- View monthly report on the amount he will recieve
- Raise support tickets

### General Users
These ones can only log in and view support tickets assigned to them.E.g assigned ticket to maintain a lawn.

## Installation Steps:

- Clone the project in a specified folder
- Update .env file with all the relevant parameters needed.These are the needed extra parameters
--  GOOGLE_MAPS_API_KEY=XXXXXXXXXXXXXXXXXXXXXXXXXXX
- Run composer update
- Run php artisan migrate
- Run php artisan db:seed
- Run php artisan storage:link 
- Run php artisan serve
