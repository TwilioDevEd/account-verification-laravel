# Account Verification - Laravel
[![Build Status](https://travis-ci.org/TwilioDevEd/account-verification-laravel.svg?branch=master)](https://travis-ci.org/TwilioDevEd/account-verification-laravel)

Use Authy and Twilio to verify your user's account.

[View the full tutorial here](https://www.twilio.com/docs/tutorials/walkthrough/account-verification/php/laravel)!

### Run the application

1. Clone the repository and `cd` into it.

1. Install the application's dependencies with [Composer](//getcomposer.org/)

   ```bash
   $ composer install
   ```

1. The application uses PostgreSQL as the persistence layer. If you
   don't have it already, you should install it. The easiest way is by
   using [Postgres.app](http://postgresapp.com/).

1. Create a database.

  ```bash
  $ createdb account_verification
  ```

1. Copy the sample configuration file and edit it to match your configuration.

   ```bash
   $ cp .env.example .env
   ```

  You can find your `TWILIO_ACCOUNT_SID` and `TWILIO_AUTH_TOKEN` under
  your
  [Twilio Account Settings](//www.twilio.com/user/account/settings).
  You can buy Twilio phone numbers at [Twilio numbers](//www.twilio.com/user/account/phone-numbers/search)
  `TWILIO_NUMBER` should be set to the phone number you purchased above.

  You must create an Authy app so you can get your API key, you can do this on
  your [Authy Dashboard](//dashboard.authy.com).

1. Generate an `APP_KEY`.

   ```bash
   $ php artisan key:generate
   ```

1. Run the migrations.

  ```bash
  $ php artisan migrate
  ```

1. Run the application using Artisan.

  ```bash
  $ php artisan serve
  ```

1. Go to the app's main page at http://localhost:8000

### Dependencies

This application uses the following Twilio helper library and PHP client for Authy:

* [twilio-php](//github.com/twilio/twilio-php)
* [authy-php](//github.com/authy/authy-php)

### Run the tests

1. Run at the top-level directory.

   ```bash
   $ phpunit
   ```

   If you don't have phpunit installed on your system, you can follow [these
   instructions](https://phpunit.de/manual/current/en/installation.html) to
   install it.
