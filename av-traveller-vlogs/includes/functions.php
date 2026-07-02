<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

function whatsapp_link(string $message = ''): string
{
    $text = $message ?: "Hello AV Traveller Vlogs,\nI want to book a rental car.";
    return 'https://wa.me/' . PRIMARY_WHATSAPP . '?text=' . rawurlencode($text);
}

function csrf_token(): string
{
    if (empty($_SESSION[CSRF_KEY])) {
        $_SESSION[CSRF_KEY] = bin2hex(random_bytes(32));
    }

    return $_SESSION[CSRF_KEY];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION[CSRF_KEY])
        && hash_equals($_SESSION[CSRF_KEY], $token);
}

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function clean_text(?string $value, int $maxLength = 255): string
{
    $value = trim(strip_tags((string) $value));
    $value = preg_replace('/\s+/', ' ', $value) ?? '';
    return function_exists('mb_substr') ? mb_substr($value, 0, $maxLength) : substr($value, 0, $maxLength);
}

function clean_phone(?string $value): string
{
    $phone = preg_replace('/[^0-9+]/', '', (string) $value) ?? '';
    return function_exists('mb_substr') ? mb_substr($phone, 0, 18) : substr($phone, 0, 18);
}

function valid_date(?string $date): bool
{
    if (!$date) {
        return false;
    }

    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return $dt && $dt->format('Y-m-d') === $date;
}

function booking_code(): string
{
    return 'AV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

function require_admin(): void
{
    if (empty($_SESSION[ADMIN_SESSION_KEY])) {
        header('Location: login.php');
        exit;
    }
}

function current_admin(): ?array
{
    return $_SESSION[ADMIN_SESSION_KEY] ?? null;
}

function flash(?string $message = null, string $type = 'success'): ?array
{
    if ($message !== null) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
        return null;
    }

    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function money_inr(float|int|string $amount): string
{
    return '₹' . number_format((float) $amount);
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    $value = trim($value, '-');
    return $value !== '' ? $value : bin2hex(random_bytes(3));
}
