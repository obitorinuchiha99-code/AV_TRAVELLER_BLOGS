<?php
$adminTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

$stats = [
    'Cars' => admin_count('cars'),
    'Bookings' => admin_count('bookings'),
    'Customers' => admin_count('customers'),
    'Payments' => admin_count('payments', "status = 'Success'"),
];
$recentBookings = db_all('SELECT booking_code, customer_name, car_name, pickup_date, status, payment_status, created_at FROM bookings ORDER BY id DESC LIMIT 8');
$notifications = db_all('SELECT title, message, type, created_at FROM admin_notifications ORDER BY id DESC LIMIT 6');
?>
<div class="stat-grid">
    <?php foreach ($stats as $label => $value): ?>
        <div class="stat-card">
            <span><?= e($label) ?></span>
            <strong><?= (int) $value ?></strong>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-8">
        <section class="admin-card">
            <div class="admin-section-title">
                <h1>Recent Bookings</h1>
                <a class="btn-muted" href="bookings.php">Manage</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Code</th><th>Customer</th><th>Car</th><th>Date</th><th>Status</th><th>Payment</th></tr></thead>
                    <tbody>
                    <?php if ($recentBookings): ?>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td><?= e($booking['booking_code']) ?></td>
                                <td><?= e($booking['customer_name']) ?></td>
                                <td><?= e($booking['car_name']) ?></td>
                                <td><?= e($booking['pickup_date']) ?></td>
                                <td><?= e($booking['status']) ?></td>
                                <td><?= e($booking['payment_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-muted">No bookings yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <div class="col-xl-4">
        <section class="admin-card">
            <div class="admin-section-title">
                <h2>Notifications</h2>
            </div>
            <?php if ($notifications): ?>
                <?php foreach ($notifications as $note): ?>
                    <div class="border-bottom border-secondary-subtle py-2">
                        <strong><?= e($note['title']) ?></strong>
                        <p class="text-muted mb-1"><?= e($note['message']) ?></p>
                        <small class="text-muted"><?= e($note['type']) ?> · <?= e($note['created_at']) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No notifications yet.</p>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
