# Knowledge Learning - Code Documentation

## Overview

This document provides an overview and explanation of the main components of the Knowledge Learning e-learning platform backend, focusing on the cart and purchase functionalities.

## CartController

Located at `app/Controllers/CartController.php`, this controller manages the shopping cart operations including:

- Adding items (courses or lessons) to the cart
- Viewing the cart contents
- Removing items from the cart
- Clearing the cart
- Checkout and purchase processing via Stripe API

### Key Methods

- `add()`: Adds a course or lesson to the cart stored in the PHP session. It prevents duplicate items by checking existing cart contents. It supports custom lessons with negative IDs.
- `index()`: Displays the cart contents.
- `remove($id)`: Removes an item from the cart by ID.
- `clear()`: Empties the cart.
- `checkout()`: Handles payment processing using Stripe's PaymentIntent API.
- `purchase()`: Finalizes the purchase and clears the cart.

## Models

- `Course.php`: Provides course details.
- `Lesson.php`: Provides lesson details.

## Testing

Unit tests for cart functionality are located in `tests/CartTest.php`. These tests cover:

- Adding lessons and courses to the cart
- Preventing duplicate additions
- Ensuring correct cart contents after operations

## How to Run Tests

1. Ensure PHPUnit is installed.
2. Run tests with the command:

```bash
vendor/bin/phpunit tests/CartTest.php
```

## Further Documentation

- User authentication and registration tests are in `tests/AuthTest.php`.
- Database schema and migrations are located in the `database/migrations` directory.

## Notes

- The cart is stored in PHP sessions.
- Duplicate prevention is based on item type and ID.
- Custom lessons are supported with special handling.
