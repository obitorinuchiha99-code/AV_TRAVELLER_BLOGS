<?php
$adminTitle = 'Manage Packages';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('packages.php');
    }
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    if ($action === 'delete' && $id > 0) {
        db_execute('UPDATE tour_packages SET is_active = 0 WHERE id = ?', [$id]);
        flash('Package removed.');
        admin_redirect('packages.php');
    }
    if ($action === 'save') {
        $existing = $id > 0 ? db_one('SELECT image_url FROM tour_packages WHERE id = ?', [$id]) : null;
        $title = clean_text($_POST['title'] ?? '', 180);
        $type = clean_text($_POST['type'] ?? '', 80);
        $duration = clean_text($_POST['duration'] ?? '', 80);
        $price = max(0, (float) ($_POST['price'] ?? 0));
        $image = admin_image_value('image_url', 'image_file', 'gallery', $existing['image_url'] ?? null);
        $description = clean_text($_POST['description'] ?? '', 2000);
        if ($title === '' || $type === '' || $duration === '' || $image === '' || $description === '') {
            flash('Please complete all package fields.', 'danger');
            admin_redirect($id ? 'packages.php?edit=' . $id : 'packages.php');
        }
        if ($id > 0) {
            db_execute('UPDATE tour_packages SET title=?, type=?, duration=?, price=?, image_url=?, description=?, is_active=1 WHERE id=?', [$title, $type, $duration, $price, $image, $description, $id]);
            flash('Package updated.');
        } else {
            db_execute('INSERT INTO tour_packages (title, type, duration, price, image_url, description) VALUES (?, ?, ?, ?, ?, ?)', [$title, $type, $duration, $price, $image, $description]);
            flash('Package added.');
        }
        admin_redirect('packages.php');
    }
}

$edit = isset($_GET['edit']) ? db_one('SELECT * FROM tour_packages WHERE id = ?', [(int) $_GET['edit']]) : null;
$packages = db_all('SELECT * FROM tour_packages WHERE is_active = 1 ORDER BY id DESC') ?: get_packages();
?>
<section class="admin-card">
    <div class="admin-section-title"><h1><?= $edit ? 'Edit Package' : 'Add Package' ?></h1><?php if ($edit): ?><a class="btn-muted" href="packages.php">Cancel</a><?php endif; ?></div>
    <form method="post" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
        <div><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div><label>Type</label><input name="type" value="<?= e($edit['type'] ?? '') ?>" placeholder="Weekend, Family, Temple" required></div>
        <div><label>Duration</label><input name="duration" value="<?= e($edit['duration'] ?? '') ?>" required></div>
        <div><label>Price</label><input name="price" type="number" min="0" step="100" value="<?= e((string) ($edit['price'] ?? 0)) ?>" required></div>
        <div><label>Image URL</label><input name="image_url" value="<?= e($edit['image_url'] ?? '') ?>"></div>
        <div><label>Upload Image</label><input name="image_file" type="file" accept="image/*"></div>
        <div class="full"><label>Description</label><textarea name="description" rows="4" required><?= e($edit['description'] ?? '') ?></textarea></div>
        <div class="full"><button class="btn-admin" type="submit">Save Package</button></div>
    </form>
</section>
<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Packages</h2></div>
    <div class="table-responsive">
        <table class="table"><thead><tr><th>Image</th><th>Title</th><th>Type</th><th>Price</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($packages as $package): ?>
            <?php $imageSrc = str_starts_with((string) $package['image_url'], 'uploads/') ? '../' . $package['image_url'] : $package['image_url']; ?>
            <tr>
                <td><img class="admin-thumb" src="<?= e($imageSrc) ?>" alt=""></td>
                <td><strong><?= e($package['title']) ?></strong><br><small class="text-muted"><?= e($package['duration']) ?></small></td>
                <td><?= e($package['type']) ?></td>
                <td><?= money_inr($package['price']) ?></td>
                <td><div class="inline-actions"><a class="btn-muted" href="packages.php?edit=<?= (int) $package['id'] ?>">Edit</a><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $package['id'] ?>"><button class="btn-muted" data-confirm="Remove package?">Delete</button></form></div></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

