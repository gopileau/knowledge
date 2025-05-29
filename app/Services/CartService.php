<?php

namespace App\Services;

class CartService {
    public static function addItem($item) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Removed check to allow adding lessons even if course is in cart
        // if ($item['type'] === 'lesson') {
        //     foreach ($_SESSION['cart'] as $cart_item) {
        //         if ($cart_item['type'] === 'course' && isset($item['course_id']) && $cart_item['id'] == $item['course_id']) {
        //             // Course already in cart, do not add lesson separately
        //             return false;
        //         }
        //     }
        // }

        // Prevent adding course if any of its lessons are already in cart
        if ($item['type'] === 'course') {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($ci) use ($item) {
                return !($ci['type'] === 'lesson' && isset($ci['course_id']) && $ci['course_id'] == $item['id']);
            });
        }

        // Check if item already in cart, increment quantity if so
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['id'] == $item['id'] && $cart_item['name'] == $item['name']) {
                $cart_item['quantity'] += 1;
                return true;
            }
        }
        unset($cart_item);

        // If not found, add new item
        $_SESSION['cart'][] = $item;
        return true;
    }

    public static function removeItem($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $id) {
                    unset($_SESSION['cart'][$key]);
                    // Reindex array
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    return true;
                }
            }
        }
        return false;
    }

    public static function getCart() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['cart'] ?? [];
    }
}
