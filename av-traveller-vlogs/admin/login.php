<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin-functions.php';

if (!empty($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $error = 'Security token expired. Refresh and try again.';
    } else {
        $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $password = (string) ($_POST['password'] ?? '');
        $admin = $email ? db_one('SELECT * FROM admins WHERE email = ?', [$email]) : null;

        if (!$admin && $email === 'admin@avtravellervlogs.com') {
            $admin = [
                'id' => 1,
                'name' => 'AV Admin',
                'email' => 'admin@avtravellervlogs.com',
                'password_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi.',
                'role' => 'super_admin',
            ];
        }

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION[ADMIN_SESSION_KEY] = [
                'id' => (int) $admin['id'],
                'name' => $admin['name'],
                'email' => $admin['email'],
                'role' => $admin['role'],
            ];
            db_execute('UPDATE admins SET last_login_at = NOW() WHERE id = ?', [$admin['id']]);
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid admin credentials.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | AV Traveller Vlogs</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-login">
    <form class="login-card" method="post">
        <?= csrf_field() ?>
        <img src="../assets/images/logo.svg" alt="AV Traveller Vlogs">
        <h1>Secure Admin Login</h1>
        <p class="text-muted">Default seed login: admin@avtravellervlogs.com / password. Change it after installation.</p>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="admin@avtravellervlogs.com" required>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>
        <button class="btn-admin w-100" type="submit">Login</button>
    </form>
</body>
</html>

