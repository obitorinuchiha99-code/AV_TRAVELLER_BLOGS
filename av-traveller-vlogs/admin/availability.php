<?php
$adminTitle = 'Live Availability';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('availability.php');
    }

    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        db_execute('DELETE FROM availability WHERE id = ?', [(int) ($_POST['id'] ?? 0)]);
        flash('Unavailable date removed.');
        admin_redirect('availability.php');
    }

    $carId = (int) ($_POST['car_id'] ?? 0);
    $date = clean_text($_POST['unavailable_date'] ?? '', 20);
    $status = in_array($_POST['status'] ?? '', ['Booked', 'Maintenance'], true) ? $_POST['status'] : 'Booked';
    $note = clean_text($_POST['note'] ?? '', 255);
    if ($carId > 0 && valid_date($date)) {
        db_execute(
            'INSERT INTO availability (car_id, unavailable_date, status, note) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = VALUES(status), note = VALUES(note)',
            [$carId, $date, $status, $note]
        );
        db_execute('UPDATE cars SET availability_status = ? WHERE id = ?', [$status, $carId]);
        flash('Availability updated.');
    }
    admin_redirect('availability.php');
}

$cars = db_all('SELECT id, name, availability_status FROM cars WHERE is_active = 1 ORDER BY name') ?: get_cars();
$rows = db_all('SELECT a.*, c.name AS car_name FROM availability a JOIN cars c ON c.id = a.car_id WHERE a.unavailable_date >= CURRENT_DATE ORDER BY a.unavailable_date, c.name');
?>
<section class="admin-card">
    <div class="admin-section-title"><h1>Add Unavailable Date</h1></div>
    <form method="post" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <div><label>Car</label><select name="car_id" required><?php foreach ($cars as $car): ?><option value="<?= (int) $car['id'] ?>"><?= e($car['name']) ?> · <?= e($car['availability_status']) ?></option><?php endforeach; ?></select></div>
        <div><label>Date</label><input name="unavailable_date" type="date" required></div>
        <div><label>Status</label><select name="status"><option>Booked</option><option>Maintenance</option></select></div>
        <div><label>Note</label><input name="note" placeholder="Reason or booking reference"></div>
        <div class="full"><button class="btn-admin" type="submit"><i class="fa-solid fa-calendar-plus"></i>Save Date</button></div>
    </form>
</section>
<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Upcoming Unavailable Dates</h2></div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Car</th><th>Date</th><th>Status</th><th>Note</th><th>Action</th></tr></thead>
            <tbody>
            <?php if ($rows): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['car_name']) ?></td>
                        <td><?= e($row['unavailable_date']) ?></td>
                        <td><?= e($row['status']) ?></td>
                        <td><?= e($row['note']) ?></td>
                        <td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button class="btn-muted" data-confirm="Remove this date?">Remove</button></form></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-muted">No unavailable dates set.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
