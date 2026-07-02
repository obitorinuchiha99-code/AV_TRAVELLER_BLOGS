<?php
$adminTitle = 'Manage Gallery';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('gallery.php');
    }
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    if ($action === 'delete' && $id > 0) {
        db_execute('UPDATE gallery SET is_active = 0 WHERE id = ?', [$id]);
        flash('Gallery image removed.');
        admin_redirect('gallery.php');
    }
    if ($action === 'save') {
        $title = clean_text($_POST['title'] ?? '', 160);
        $image = admin_image_value('image_url', 'image_file', 'gallery');
        $alt = clean_text($_POST['alt_text'] ?? $title, 190);
        $sort = max(1, (int) ($_POST['sort_order'] ?? 99));
        if ($title === '' || $image === '') {
            flash('Title and image are required.', 'danger');
            admin_redirect('gallery.php');
        }
        db_execute('INSERT INTO gallery (title, image_url, alt_text, sort_order) VALUES (?, ?, ?, ?)', [$title, $image, $alt, $sort]);
        flash('Gallery image added.');
        admin_redirect('gallery.php');
    }
}

$gallery = db_all('SELECT * FROM gallery WHERE is_active = 1 ORDER BY sort_order, id DESC') ?: gallery_images();
?>
<section class="admin-card">
    <div class="admin-section-title"><h1>Add Gallery Image</h1></div>
    <form method="post" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <div><label>Title</label><input name="title" required></div>
        <div><label>Sort Order</label><input name="sort_order" type="number" min="1" value="99"></div>
        <div><label>Image URL</label><input name="image_url" placeholder="https://images.unsplash.com/..."></div>
        <div><label>Upload Image</label><input name="image_file" type="file" accept="image/*"></div>
        <div class="full"><label>Alt Text</label><input name="alt_text"></div>
        <div class="full"><button class="btn-admin" type="submit">Add Image</button></div>
    </form>
</section>
<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Gallery</h2></div>
    <div class="table-responsive">
        <table class="table"><thead><tr><th>Image</th><th>Title</th><th>Sort</th><th>Action</th></tr></thead><tbody>
        <?php foreach ($gallery as $item): ?>
            <?php $imageSrc = str_starts_with((string) $item['image_url'], 'uploads/') ? '../' . $item['image_url'] : $item['image_url']; ?>
            <tr>
                <td><img class="admin-thumb" src="<?= e($imageSrc) ?>" alt=""></td>
                <td><?= e($item['title']) ?></td>
                <td><?= e((string) ($item['sort_order'] ?? '')) ?></td>
                <td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) ($item['id'] ?? 0) ?>"><button class="btn-muted" data-confirm="Remove image?">Delete</button></form></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

