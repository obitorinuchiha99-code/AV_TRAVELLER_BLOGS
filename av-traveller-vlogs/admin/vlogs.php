<?php
$adminTitle = 'Manage Vlogs';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('vlogs.php');
    }
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    if ($action === 'delete' && $id > 0) {
        db_execute('UPDATE travel_vlogs SET is_active = 0 WHERE id = ?', [$id]);
        flash('Vlog removed.');
        admin_redirect('vlogs.php');
    }
    if ($action === 'save') {
        $existing = $id > 0 ? db_one('SELECT thumbnail_url FROM travel_vlogs WHERE id = ?', [$id]) : null;
        $title = clean_text($_POST['title'] ?? '', 180);
        $video = clean_text($_POST['video_url'] ?? '', 700);
        $thumb = admin_image_value('thumbnail_url', 'thumbnail_file', 'vlogs', $existing['thumbnail_url'] ?? null);
        $description = clean_text($_POST['description'] ?? '', 1500);
        if ($title === '' || $video === '' || $thumb === '' || $description === '') {
            flash('Please complete all vlog fields.', 'danger');
            admin_redirect($id ? 'vlogs.php?edit=' . $id : 'vlogs.php');
        }
        if ($id > 0) {
            db_execute('UPDATE travel_vlogs SET title=?, video_url=?, thumbnail_url=?, description=?, is_active=1, published_at=NOW() WHERE id=?', [$title, $video, $thumb, $description, $id]);
            flash('Vlog updated.');
        } else {
            db_execute('INSERT INTO travel_vlogs (title, video_url, thumbnail_url, description, published_at) VALUES (?, ?, ?, ?, NOW())', [$title, $video, $thumb, $description]);
            flash('Vlog added.');
        }
        admin_redirect('vlogs.php');
    }
}

$edit = isset($_GET['edit']) ? db_one('SELECT * FROM travel_vlogs WHERE id = ?', [(int) $_GET['edit']]) : null;
$vlogs = db_all('SELECT * FROM travel_vlogs WHERE is_active = 1 ORDER BY published_at DESC, id DESC') ?: get_vlogs();
?>
<section class="admin-card">
    <div class="admin-section-title"><h1><?= $edit ? 'Edit Vlog' : 'Add Vlog' ?></h1><?php if ($edit): ?><a class="btn-muted" href="vlogs.php">Cancel</a><?php endif; ?></div>
    <form method="post" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
        <div><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div><label>YouTube URL</label><input name="video_url" value="<?= e($edit['video_url'] ?? '') ?>" required></div>
        <div><label>Thumbnail URL</label><input name="thumbnail_url" value="<?= e($edit['thumbnail_url'] ?? '') ?>"></div>
        <div><label>Upload Thumbnail</label><input name="thumbnail_file" type="file" accept="image/*"></div>
        <div class="full"><label>Description</label><textarea name="description" rows="4" required><?= e($edit['description'] ?? '') ?></textarea></div>
        <div class="full"><button class="btn-admin" type="submit">Save Vlog</button></div>
    </form>
</section>
<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Vlogs</h2></div>
    <div class="table-responsive">
        <table class="table"><thead><tr><th>Thumbnail</th><th>Title</th><th>Video</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($vlogs as $vlog): ?>
            <?php $imageSrc = str_starts_with((string) $vlog['thumbnail_url'], 'uploads/') ? '../' . $vlog['thumbnail_url'] : $vlog['thumbnail_url']; ?>
            <tr>
                <td><img class="admin-thumb" src="<?= e($imageSrc) ?>" alt=""></td>
                <td><?= e($vlog['title']) ?></td>
                <td><a class="text-info" href="<?= e($vlog['video_url']) ?>" target="_blank">Open</a></td>
                <td><div class="inline-actions"><a class="btn-muted" href="vlogs.php?edit=<?= (int) ($vlog['id'] ?? 0) ?>">Edit</a><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) ($vlog['id'] ?? 0) ?>"><button class="btn-muted" data-confirm="Remove vlog?">Delete</button></form></div></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

