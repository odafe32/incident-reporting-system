# Hospital Incident Management & Resource Allocation System

Status: Active | License: MIT | Framework: Laravel 11.x

# About the Project

A web-based hospital management system built with Laravel,
designed to streamline incident reporting, resource allocation, and emergency
response. This project was developed to address the inefficiencies in
traditional paper/manual hospital systems. It enables staff to report incidents
in real time, allows administrators to allocate resources efficiently, and
provides analytics for data-driven decision-making.

# Features

·
Authentication & Roles: Admin Dashboard,
Hospital Staff (Doctors/Nurses)

·
Incident Reporting: Report emergencies &
issues, track status (Pending → Assigned → Resolved)

·
Resource Allocation: Manage beds, ventilators,
staff availability; auto/manual allocation

·
Notifications: Real-time alerts via dashboard,
email, or SMS

·
Analytics & Reports: Incident trends,
resource usage, response time tracking

·
Secure Data: Role-based access control,
encrypted patient & hospital data

# Tech Stack

·
Framework: Laravel (PHP 8+)

·
Frontend: Blade, Bootstrap, JavaScript

·
Database: MySQL / PostgreSQL

·
Real-time: Laravel Echo + Pusher / WebSockets

·
Auth: Laravel Breeze / Jetstream

·
Charts/Reports: Chart.js / Laravel Charts

# Database Schema (Core Tables)

·
Users → Admins, Doctors, Nurses

·
Patients → Patient records (optional module)

·
Incidents → Reported issues (severity, status,
location)

·
Resources → Beds, equipment, staff availability

·
Incident Actions → Actions taken to resolve
incidents

·
Notifications → Alerts sent to users

# Installation

Clone the repo: git clone
https://github.com/your-username/hospital-incident-management.git

Install dependencies: composer install
&& npm install && npm run dev

Set up environment file: cp .env.example .env
&& php artisan key:generate

Configure database in .env, then migrate: php artisan migrate --seed

Run the server: php artisan serve

# Usage

Admin: Log in → Manage incidents, allocate resources,
generate reports.

Hospital Staff: Report incidents, view assignments, mark
incidents as resolved.

# Contributing

Pull requests are welcome! To contribute: fork the repo,
create a feature branch, and submit a pull request.

# Security

If you discover a security issue, please create a private
issue or contact the maintainer directly.

# License

This project is open-sourced software licensed under the MIT
license.

# Author

Name: Odafe Godfrey

Email: Godfreyj.sule1@gmail.com
