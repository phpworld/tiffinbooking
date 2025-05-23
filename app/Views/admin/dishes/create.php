<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/admin/dishes') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dishes
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Add New Dish</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/dishes/create') ?>" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Dish Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="price" class="form-label">Price (₹)</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= old('price') ?>" step="0.01" min="0" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Dish Image (Optional)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Upload an image of the dish (Max size: 1MB, Formats: JPG, PNG, GIF)</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="available" name="available" value="1" <?= old('available') ? 'checked' : '' ?> checked>
                        <label class="form-check-label" for="available">Available for ordering</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_vegetarian" name="is_vegetarian" value="1" <?= old('is_vegetarian', 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_vegetarian">
                            <span class="text-success"><i class="fas fa-leaf"></i> Vegetarian</span>
                        </label>
                        <div class="form-text">Uncheck for non-vegetarian dishes</div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Dish
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>