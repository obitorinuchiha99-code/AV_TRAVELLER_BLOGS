<?php
$adminTitle = 'Manage Bookings';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('bookings.php');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['Pending', 'Confirmed', 'Completed', 'Cancelled'], true) ? $_POST['status'] : 'Pending';
    $payment = in_array($_POST['payment_status'] ?? '', ['Unpaid', 'Pending', 'Paid', 'Failed', 'Refunded'], true) ? $_POST['payment_status'] : 'Unpaid';
    if ($id > 0) {
        db_execute('UPDATE bookings SET status = ?, payment_status = ? WHERE id = ?', [$status, $payment, $id]);
        flash('Booking updated.');
    }
    admin_redirect('bookings.php');
}

$bookings = db_all('SELECT * FROM bookings ORDER BY id DESC LIMIT 200');
?>
<section class="admin-card">
    <div class="admin-section-title">
        <h1>Bookings</h1>
        <a class="btn-admin" href="export.php?type=bookings"><i class="fa-solid fa-file-csv"></i>Export</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Code</th><th>Customer</th><th>Trip</th><th>Car</th><th>Estimate</th><th>Status</th><th>Payment</th><th>Save</th></tr></thead>
            <tbody>
            <?php if ($bookings): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><strong><?= e($booking['booking_code']) ?></strong><br><small class="text-muted"><?= e($booking['created_at']) ?></small></td>
                        <td><?= e($booking['customer_name']) ?><br><small class="text-muted"><?= e($booking['phone']) ?></small></td>
                        <td><?= e($booking['pickup_date']) ?> to <?= e($booking['return_date']) ?><br><small class="text-muted"><?= e($booking['pickup_location']) ?> → <?= e($booking['drop_location']) ?></small></td>
                        <td><?= e($booking['car_name']) ?><br><small class="text-muted"><?= (int) $booking['driver_required'] ? 'Driver required' : 'Self-drive enquiry' ?></small></td>
                        <td><?= money_inr($booking['estimated_amount']) ?></td>
                        <td colspan="3">
                            <form class="inline-actions" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int) $booking['id'] ?>">
                                <select name="status">
                                    <?php foreach (['Pending', 'Confirmed', 'Completed', 'Cancelled'] as $status): ?>
                                        <option <?= $booking['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="payment_status">
                                    <?php foreach (['Unpaid', 'Pending', 'Paid', 'Failed', 'Refunded'] as $status): ?>
                                        <option <?= $booking['payment_status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn-admin" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-muted">No bookings yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

