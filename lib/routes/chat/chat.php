<?php
session_start();
header('Content-Type: application/json');

require_once(__DIR__ . '/../../function/env.php');
loadEnv(__DIR__ . '/../../../.env');

$message = trim($_POST['message'] ?? '');
if (!$message) {
    echo json_encode(['reply' => 'Please type a message.']);
    exit;
}

$faqs = [
    // Delivery
    ['q' => ['deliver', 'delivery', 'shipping', 'ship', 'drop'], 'a' => 'Yes, AMI Grocery offers doorstep delivery! Call us at <strong>+94 77 123 4567</strong> to place a delivery order and we\'ll bring groceries right to your door.'],
    ['q' => ['delivery time', 'how long', 'when will', 'how fast'], 'a' => 'Delivery usually takes 1–3 hours within the city and same-day for orders placed before 4 PM.'],
    ['q' => ['delivery charge', 'delivery fee', 'cost deliver'], 'a' => 'Delivery charges vary by location. Call <strong>+94 77 123 4567</strong> for a quote — orders over Rs. 2,000 often qualify for free delivery.'],
    // Returns
    ['q' => ['return', 'refund', 'exchange', 'money back'], 'a' => 'We have a hassle-free return policy! You can return items within 3 days of purchase if they are damaged, expired, or incorrect. Contact us at <strong>+94 77 123 4567</strong> or visit the store.'],
    ['q' => ['how to return', 'return process', 'return steps'], 'a' => 'To return an item: (1) Contact us within 3 days of purchase. (2) Keep the original packaging and receipt. (3) We\'ll arrange a pickup or you can visit our store. Refunds are processed within 24–48 hours.'],
    // Payment
    ['q' => ['payment', 'pay', 'visa', 'mastercard', 'card', 'cash', 'online pay'], 'a' => 'We accept cash on delivery, Visa, Mastercard, and bank transfers. Card payments are processed securely — we never store your full card number.'],
    // Hours
    ['q' => ['hours', 'open', 'close', 'timing', 'when open'], 'a' => 'AMI Grocery is open <strong>Monday–Saturday, 7 AM – 9 PM</strong> and <strong>Sunday, 8 AM – 7 PM</strong>.'],
    // Contact
    ['q' => ['phone', 'contact', 'call', 'number', 'reach'], 'a' => 'You can reach us at <strong>+94 77 123 4567</strong> or email <strong>support@amigrocery.lk</strong>. We\'re happy to help!'],
    // Products
    ['q' => ['fresh', 'quality', 'organic', 'product'], 'a' => 'All our products are sourced from trusted local suppliers. We check freshness daily — if anything doesn\'t meet our standard, it\'s removed from the shelves.'],
    // Orders
    ['q' => ['order', 'my order', 'track', 'status'], 'a' => 'You can view your orders in the <strong>My Orders</strong> section of your account dashboard. For delivery tracking, call us at <strong>+94 77 123 4567</strong>.'],
    // Greeting
    ['q' => ['hi', 'hello', 'hey', 'hiya', 'greetings'], 'a' => 'Hello! 👋 Welcome to AMI Grocery. How can I help you today? You can ask me about delivery, returns, payments, or anything else!'],
    ['q' => ['thank', 'thanks', 'appreciate'], 'a' => 'You\'re welcome! Is there anything else I can help you with? 😊'],
    ['q' => ['bye', 'goodbye', 'see you', 'cya'], 'a' => 'Goodbye! Thanks for shopping with AMI Grocery. Have a great day! 🛒'],
];

$lower = strtolower($message);
$reply = null;

foreach ($faqs as $faq) {
    foreach ($faq['q'] as $keyword) {
        if (str_contains($lower, $keyword)) {
            $reply = $faq['a'];
            break 2;
        }
    }
}

// If no FAQ match and Gemini key is set, use Gemini
if (!$reply) {
    $apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
    if ($apiKey && $apiKey !== 'your_gemini_api_key_here') {
        $payload = json_encode([
            'contents' => [[
                'parts' => [['text' => 'You are a helpful assistant for AMI Grocery, a grocery store in Sri Lanka. Answer briefly and helpfully. User says: ' . $message]]
            ]]
        ]);
        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'X-goog-api-key: ' . $apiKey]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        $res = curl_exec($ch);
        curl_close($ch);
        if ($res) {
            $data = json_decode($res, true);
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }
    }
}

if (!$reply) {
    $reply = "I'm not sure about that, but you can call us at <strong>+94 77 123 4567</strong> and we'll be happy to help! 😊";
}

echo json_encode(['reply' => $reply]);
