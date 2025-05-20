<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Banner</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('/admin/banners') ?>">Banners</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Banner
        </div>
        <div class="card-body">
            <form action="<?= base_url('/admin/banners/edit/' . $banner['id']) ?>" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $banner['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?= old('subtitle', $banner['subtitle']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="button_text" class="form-label">Button Text</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="<?= old('button_text', $banner['button_text']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="button_link" class="form-label">Button Link</label>
                            <input type="text" class="form-control" id="button_link" name="button_link" value="<?= old('button_link', $banner['button_link']) ?>">
                            <div class="form-text">Example: /dishes or /booking/create</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Recommended size: 1600x450 pixels. Max file size: 2MB. Leave empty to keep the current image.</div>
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="order" name="order" value="<?= old('order', $banner['order']) ?>" min="0">
                            <div class="form-text">Lower numbers will be displayed first.</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= old('is_active', $banner['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="mb-3">
                            <div id="currentImage" class="mt-2">
                                <p>Current Image:</p>
                                <?php if ($banner['image']): ?>
                                    <img src="<?= base_url('/uploads/banners/' . $banner['image']) ?>" alt="<?= $banner['title'] ?>" class="img-fluid img-thumbnail" style="max-height: 200px;">
                                <?php else: ?>
                                    <p class="text-muted">No image available</p>
                                <?php endif; ?>
                            </div>

                            <div id="imagePreview" class="mt-2 d-none">
                                <p>New Image Preview:</p>
                                <img src="" alt="Preview" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/admin/banners') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = imagePreview.querySelector('img');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.classList.add('d-none');
            }
        });
    });
</script>
<?= $this->endSection() ?>
