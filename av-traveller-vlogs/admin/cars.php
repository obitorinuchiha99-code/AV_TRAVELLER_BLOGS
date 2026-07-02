<?php
$adminTitle = 'Manage Cars';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('cars.php');
    }

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id > 0) {
        db_execute('UPDATE cars SET is_active = 0 WHERE id = ?', [$id]);
        flash('Car removed from public fleet.');
        admin_redirect('cars.php');
    }

    if ($action === 'save') {
        $existing = $id > 0 ? db_one('SELECT image_url FROM cars WHERE id = ?', [$id]) : null;
        $name = clean_text($_POST['name'] ?? '', 160);
        $slug = slugify(clean_text($_POST['slug'] ?? $name, 190));
        $category = clean_text($_POST['category'] ?? '', 120);
        $imageUrl = admin_image_value('image_url', 'image_file', 'cars', $existing['image_url'] ?? null);
        $seats = max(1, (int) ($_POST['seats'] ?? 5));
        $fuel = clean_text($_POST['fuel'] ?? '', 80);
        $transmission = clean_text($_POST['transmission'] ?? '', 120);
        $price = max(0, (float) ($_POST['price_per_day'] ?? 0));
        $status = in_array($_POST['availability_status'] ?? '', ['Available', 'Booked', 'Maintenance'], true) ? $_POST['availability_status'] : 'Available';
        $description = clean_text($_POST['description'] ?? '', 2000);
        $sortOrder = max(1, (int) ($_POST['sort_order'] ?? 99));

        if ($name === '' || $category === '' || $imageUrl === '' || $fuel === '' || $transmission === '' || $description === '') {
            flash('Please complete all required car fields.', 'danger');
            admin_redirect($id ? 'cars.php?edit=' . $id : 'cars.php');
        }

        if ($id > 0) {
            db_execute(
                'UPDATE cars SET name=?, slug=?, category=?, image_url=?, seats=?, fuel=?, transmission=?, price_per_day=?, availability_status=?, description=?, sort_order=?, is_active=1 WHERE id=?',
                [$name, $slug, $category, $imageUrl, $seats, $fuel, $transmission, $price, $status, $description, $sortOrder, $id]
            );
            flash('Car updated.');
        } else {
            db_execute(
                'INSERT INTO cars (name, slug, category, image_url, seats, fuel, transmission, price_per_day, availability_status, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [$name, $slug, $category, $imageUrl, $seats, $fuel, $transmission, $price, $status, $description, $sortOrder]
            );
            flash('Car added.');
        }
        admin_redirect('cars.php');
    }
}

$edit = isset($_GET['edit']) ? db_one('SELECT * FROM cars WHERE id = ?', [(int) $_GET['edit']]) : null;
$cars = db_all('SELECT * FROM cars WHERE is_active = 1 ORDER BY sort_order, id') ?: get_cars();
?>
<section class="admin-card">
    <div class="admin-section-title">
        <h1><?= $edit ? 'Edit Car' : 'Add Car' ?></h1>
        <?php if ($edit): ?><a class="btn-muted" href="cars.php">Cancel Edit</a><?php endif; ?>
    </div>
    <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
        <div class="form-grid">
            <div><label>Name</label><input class="auto-slug-source" data-slug-target="#carSlug" name="name" value="<?= e($edit['name'] ?? '') ?>" required></div>
            <div><label>Slug</label><input id="carSlug" class="auto-slug-target" name="slug" value="<?= e($edit['slug'] ?? '') ?>"></div>
            <div><label>Category</label><input name="category" value="<?= e($edit['category'] ?? '') ?>" required></div>
            <div><label>Image URL</label><input name="image_url" value="<?= e($edit['image_url'] ?? '') ?>" placeholder="https://images.unsplash.com/..."></div>
            <div><label>Upload Image</label><input name="image_file" type="file" accept="image/*"></div>
            <div><label>Seats</label><input name="seats" type="number" min="1" value="<?= e((string) ($edit['seats'] ?? 5)) ?>" required></div>
            <div><label>Fuel</label><input name="fuel" value="<?= e($edit['fuel'] ?? '') ?>" required></div>
            <div><label>Transmission</label><input name="transmission" value="<?= e($edit['transmission'] ?? '') ?>" required></div>
            <div><label>Price Per Day</label><input name="price_per_day" type="number" min="0" step="100" value="<?= e((string) ($edit['price_per_day'] ?? 0)) ?>" required></div>
            <div><label>Status</label><select name="availability_status"><option>Available</option><option <?= ($edit['availability_status'] ?? '') === 'Booked' ? 'selected' : '' ?>>Booked</option><option <?= ($edit['availability_status'] ?? '') === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option></select></div>
            <div><label>Sort Order</label><input name="sort_order" type="number" min="1" value="<?= e((string) ($edit['sort_order'] ?? 99)) ?>"></div>
            <div class="full"><label>Description</label><textarea name="description" rows="4" required><?= e($edit['description'] ?? '') ?></textarea></div>
        </div>
        <button class="btn-admin mt-3" type="submit"><i class="fa-solid fa-floppy-disk"></i>Save Car</button>
    </form>
</section>

<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Fleet</h2></div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Image</th><th>Name</th><th>Specs</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($cars as $car): ?>
                <?php $imageSrc = str_starts_with((string) $car['image_url'], 'uploads/') ? '../' . $car['image_url'] : $car['image_url']; ?>
                <tr>
                    <td><img class="admin-thumb" src="<?= e($imageSrc) ?>" alt=""></td>
                    <td><strong><?= e($car['name']) ?></strong><br><small class="text-muted"><?= e($car['category']) ?></small></td>
                    <td><?= (int) $car['seats'] ?> seats · <?= e($car['fuel']) ?> · <?= e($car['transmission']) ?></td>
                    <td><?= money_inr($car['price_per_day']) ?></td>
                    <td><span class="badge-soft badge-<?= e(strtolower($car['availability_status'])) ?>"><?= e($car['availability_status']) ?></span></td>
                    <td>
                        <div class="inline-actions">
                            <a class="btn-muted" href="cars.php?edit=<?= (int) $car['id'] ?>">Edit</a>
                            <form method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $car['id'] ?>">
                                <button class="btn-muted" type="submit" data-confirm="Remove this car?">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
