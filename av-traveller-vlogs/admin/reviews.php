<?php
$adminTitle = 'Manage Reviews';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('Security token expired.', 'danger');
        admin_redirect('reviews.php');
    }
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    if ($action === 'delete' && $id > 0) {
        db_execute('UPDATE reviews SET is_active = 0 WHERE id = ?', [$id]);
        flash('Review removed.');
        admin_redirect('reviews.php');
    }
    if ($action === 'save') {
        $name = clean_text($_POST['name'] ?? '', 160);
        $photo = admin_image_value('photo_url', 'photo_file', 'reviews');
        $rating = min(5, max(1, (int) ($_POST['rating'] ?? 5)));
        $message = clean_text($_POST['message'] ?? '', 1200);
        if ($name === '' || $photo === '' || $message === '') {
            flash('Name, photo and message are required.', 'danger');
            admin_redirect('reviews.php');
        }
        db_execute('INSERT INTO reviews (name, photo_url, rating, message) VALUES (?, ?, ?, ?)', [$name, $photo, $rating, $message]);
        flash('Review added.');
        admin_redirect('reviews.php');
    }
}

$reviews = db_all('SELECT * FROM reviews WHERE is_active = 1 ORDER BY id DESC') ?: get_reviews();
?>
<section class="admin-card">
    <div class="admin-section-title"><h1>Add Review</h1></div>
    <form method="post" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <div><label>Name</label><input name="name" required></div>
        <div><label>Rating</label><input name="rating" type="number" min="1" max="5" value="5"></div>
        <div><label>Photo URL</label><input name="photo_url"></div>
        <div><label>Upload Photo</label><input name="photo_file" type="file" accept="image/*"></div>
        <div class="full"><label>Message</label><textarea name="message" rows="4" required></textarea></div>
        <div class="full"><button class="btn-admin" type="submit">Add Review</button></div>
    </form>
</section>
<section class="admin-card mt-4">
    <div class="admin-section-title"><h2>Reviews</h2></div>
    <div class="table-responsive">
        <table class="table"><thead><tr><th>Photo</th><th>Name</th><th>Rating</th><th>Message</th><th>Action</th></tr></thead><tbody>
        <?php foreach ($reviews as $review): ?>
            <?php $imageSrc = str_starts_with((string) $review['photo_url'], 'uploads/') ? '../' . $review['photo_url'] : $review['photo_url']; ?>
            <tr>
                <td><img class="admin-thumb" src="<?= e($imageSrc) ?>" alt=""></td>
                <td><?= e($review['name']) ?></td>
                <td><?= str_repeat('★', (int) $review['rating']) ?></td>
                <td><?= e($review['message']) ?></td>
                <td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) ($review['id'] ?? 0) ?>"><button class="btn-muted" data-confirm="Remove review?">Delete</button></form></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

