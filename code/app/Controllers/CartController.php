<?php

namespace App\Controllers;

use App\Services\CartService;

class CartController {
    public function add() {
        // Assure-toi que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lecture des données POST
        $item_type = $_POST['item_type'] ?? null;
        $item_id = $_POST['item_id'] ?? null;

        if (!$item_type || !$item_id) {
            header('Location: /');
            exit;
        }

        $item = [
            'id' => $item_id,
            'name' => ucfirst($item_type) . " #$item_id",
            'price' => 0.0,
            'quantity' => 1,
            'type' => $item_type,
        ];

        if ($item_type === 'course') {
            require_once __DIR__ . '/../../app/Models/Course.php';
            $courseModel = new \App\Models\Course();
            $course = $courseModel->getCourseDetails($item_id);
            if ($course) {
                $item['name'] = $course['title'];
                $item['price'] = (float)$course['price'];
            }
        }

            if ($item_type === 'lesson') {
                // Handle customCourses lessons with negative IDs
                $customCourses = [
                    "Cursus d’initiation à la guitare" => [
                        ['title' => 'Découverte de l’instrument', 'price' => 26.00],
                        ['title' => 'Les accords et les gammes', 'price' => 26.00],
                    ],
                    "Cursus d’initiation au piano" => [
                        ['title' => 'Découverte de l’instrument', 'price' => 26.00],
                        ['title' => 'Les accords et les gammes', 'price' => 26.00],
                    ],
                    "Cursus d’initiation au développement web" => [
                        ['title' => 'Les langages Html et CSS', 'price' => 32.00],
                        ['title' => 'Dynamiser votre site avec Javascript', 'price' => 32.00],
                    ],
                    "Cursus d’initiation au jardinage" => [
                        ['title' => 'Les outils du jardinier', 'price' => 16.00],
                        ['title' => 'Jardiner avec la lune', 'price' => 16.00],
                    ],
                    "Cursus d’initiation à la cuisine" => [
                        ['title' => 'Les modes de cuisson', 'price' => 23.00],
                        ['title' => 'Les saveurs', 'price' => 23.00],
                    ],
                    "Cursus d’initiation à l’art du dressage culinaire" => [
                        ['title' => 'Mettre en œuvre le style dans l’assiette', 'price' => 26.00],
                        ['title' => 'Harmoniser un repas à quatre plats', 'price' => 26.00],
                    ]
                ];
    
                if ($item_id < 0) {
                    $custom_course_title = $_POST['custom_course_title'] ?? null;
                    if (!$custom_course_title || !isset($customCourses[$custom_course_title])) {
                        die("Cours personnalisé non trouvé: " . htmlspecialchars($custom_course_title));
                    }
                    $lessons = $customCourses[$custom_course_title];
                    $index = abs($item_id) - 1;
                    if (!isset($lessons[$index])) {
                        die("Leçon personnalisée non trouvée avec ID: " . htmlspecialchars($item_id));
                    }
                    $lesson = $lessons[$index];
                    $item['name'] = $lesson['title'];
                    $item['price'] = (float)$lesson['price'];
                    $item['course_id'] = null; // no course id for custom lessons
                    $item['custom_course_title'] = $custom_course_title; // add custom course title for identification
                } else {
                    require_once __DIR__ . '/../../app/Models/Lesson.php';
                    $lessonModel = new \App\Models\Lesson();
                    $lesson = $lessonModel->getLessonById($item_id);
                    if (!$lesson) {
                        die("Leçon non trouvée avec ID: " . htmlspecialchars($item_id));
                    }
                    $item['name'] = $lesson['title'];
                    $item['price'] = (float)$lesson['price'];
                    $item['course_id'] = $lesson['course_id'];
    
                    // Suppression de la vérification du cours parent dans le panier pour permettre l'ajout de leçons même si le cours est présent
                    /*
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $cart_item) {
                            if ($cart_item['type'] === 'course' && $cart_item['id'] == $item['course_id']) {
                                die("Le cours de cette leçon est déjà dans le panier.");
                            }
                        }
                    }
                    */
                    
                    // Vérifie si la leçon est déjà dans le panier (pour éviter doublons)
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $cart_item) {
                            if ($cart_item['type'] === 'lesson' && intval($cart_item['id']) === intval($item_id)) {
                                die("Cette leçon est déjà dans le panier.");
                            }
                        }
                    }
                }
            }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Normaliser les valeurs pour éviter les problèmes de type
        $normalized_item_type = strtolower(trim($item_type));
        $normalized_item_id = trim($item_id); // keep as string to handle negative IDs correctly

        // DEBUG: Affiche le contenu du panier et l'élément à ajouter pour diagnostic
        error_log("Contenu du panier actuel: " . print_r($_SESSION['cart'], true));
        error_log("Élément à ajouter: " . print_r($item, true));

        // Vérifie si l'élément est déjà dans le panier
        $found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            error_log("Comparaison avec élément du panier: type=" . $cart_item['type'] . ", id=" . $cart_item['id']);
            if (strtolower(trim($cart_item['type'])) == $normalized_item_type) {
                if ($normalized_item_type === 'lesson') {
                    // Pour les leçons, comparer id et course_id ou custom_course_title
                    $same_id = trim($cart_item['id']) === $normalized_item_id;
                    $same_course = false;
                    if (isset($cart_item['course_id']) && isset($item['course_id'])) {
                        $same_course = $cart_item['course_id'] === $item['course_id'];
                    } elseif (isset($cart_item['custom_course_title']) && isset($item['custom_course_title'])) {
                        $same_course = $cart_item['custom_course_title'] === $item['custom_course_title'];
                    }
                    if ($same_id && $same_course) {
                        // Incrémente la quantité si l'élément existe déjà
                        $cart_item['quantity'] += 1;
                        $found = true;
                        break;
                    }
                } else {
                    // Pour les autres types (ex: course), comparer id uniquement
                    if (trim($cart_item['id']) === $normalized_item_id) {
                        $cart_item['quantity'] += 1;
                        $found = true;
                        break;
                    }
                }
            }
        }
        unset($cart_item);

        if (!$found) {
            $_SESSION['cart'][] = $item;
        }

        // Redirige vers la page du panier
        header('Location: /cart');
        exit;
    }

    public function index() {
        $cart = CartService::getCart();
        include __DIR__ . '/../../Views/cart.php';
    }

    public function remove($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        CartService::removeItem($id);
        header('Location: /cart');
        exit;
    }

    public function clear() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['cart']);
        header('Location: /cart');
        exit;
    }

    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $autoloadPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            require_once $autoloadPath;
        } else {
            die('Autoload file not found. Please run composer install.');
        }

        \Stripe\Stripe::setApiKey('sk_test_YOUR_SECRET_KEY');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentMethodId = $_POST['payment_method_id'] ?? null;

            $total = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['quantity'] * $item['price'];
                }
            }

            try {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => intval($total * 100),
                    'currency' => 'eur',
                    'payment_method' => $paymentMethodId,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                ]);

                if ($paymentIntent->status === 'succeeded') {
                    $_SESSION['cart'] = [];
                    header('Location: /confirmation');
                    exit;
                } else {
                    echo "Paiement en attente...";
                }
            } catch (\Stripe\Exception\ApiErrorException $e) {
                echo "Erreur de paiement : " . $e->getMessage();
            }
        } else {
            include __DIR__ . '/../../Views/checkout.php';
        }
    }

    public function purchase() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['cart']);
        header('Location: /');
        exit;
    }
}