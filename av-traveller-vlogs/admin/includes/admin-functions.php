<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/data.php';

function admin_count(string $table, string $where = '1=1'): int
{
    $allowed = ['cars', 'bookings', 'customers', 'gallery', 'reviews', 'tour_packages', 'travel_vlogs', 'contact_messages', 'payments', 'availability'];
    if (!in_array($table, $allowed, true)) {
        return 0;
    }

    $row = db_one("SELECT COUNT(*) AS total FROM {$table} WHERE {$where}");
    return (int) ($row['total'] ?? 0);
}

function admin_upload_image(string $field, string $folder): ?string
{
    if (empty($_FILES[$field]['tmp_name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return null;
    }

    $file = $_FILES[$field];
    if (($file['size'] ?? 0) > 4 * 1024 * 1024) {
        flash('Image must be under 4 MB.', 'danger');
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
    ];

    if (!isset($allowed[$mime])) {
        flash('Only JPG, PNG, WebP and SVG images are allowed.', 'danger');
        return null;
    }

    $folder = trim($folder, '/');
    $targetDir = __DIR__ . '/../../uploads/' . $folder;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $filename = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $target = $targetDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        flash('Could not upload image.', 'danger');
        return null;
    }

    return 'uploads/' . $folder . '/' . $filename;
}

function admin_image_value(string $urlField, string $fileField, string $folder, ?string $existing = null): string
{
    $uploaded = admin_upload_image($fileField, $folder);
    if ($uploaded) {
        return $uploaded;
    }

    $url = clean_text($_POST[$urlField] ?? '', 700);
    return $url !== '' ? $url : (string) $existing;
}

function admin_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

