<?php
$adminTitle = 'Customers';
require_once __DIR__ . '/includes/header.php';
$customers = db_all('SELECT c.*, COUNT(b.id) AS bookings_count, MAX(b.created_at) AS last_booking FROM customers c LEFT JOIN bookings b ON b.customer_id = c.id GROUP BY c.id ORDER BY c.id DESC LIMIT 300');
$messages = db_all('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 80');
?>
<section class="admin-card">
    <div class="admin-section-title">
        <h1>Customers</h1>
        <a class="btn-admin" href="export.php?type=customers"><i class="fa-solid fa-file-csv"></i>Export</a>
    </div>
    <div class="table-responsive">
        <table class="table"><thead><tr><th>Name</th><th>Phone</th><th>WhatsApp</th><th>Bookings</th><th>Last Booking</th></tr></thead><tbody>
        <?php if ($customers): ?>
            <?php foreach ($customers as $customer): ?>
                <tr><td><?= e($customer['name']) ?></td><td><?= e($customer['phone']) ?></td><td><?= e($customer['whatsapp']) ?></td><td><?= (int) $customer['bookings_count'] ?></td><td><?= e($customer['last_booking'] ?? '-') ?></td></tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-muted">No customers yet.</td></tr>
        <?php endif; ?>
        </tbody></table>
    </div>
</section>
<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Contact Messages</h2></div>
    <div class="table-responsive">
        <table class="table"><thead><tr><th>Name</th><th>Phone</th><th>Subject</th><th>Message</th><th>Date</th></tr></thead><tbody>
        <?php if ($messages): ?>
            <?php foreach ($messages as $message): ?>
                <tr><td><?= e($message['name']) ?></td><td><?= e($message['phone']) ?></td><td><?= e($message['subject']) ?></td><td><?= e($message['message']) ?></td><td><?= e($message['created_at']) ?></td></tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-muted">No messages yet.</td></tr>
        <?php endif; ?>
        </tbody></table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

