<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/admin/dishes') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dishes
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Dish</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/dishes/edit/' . $dish['id']) ?>" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Dish Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $dish['name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="price" class="form-label">Price (₹)</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= old('price', $dish['price']) ?>" step="0.01" min="0" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= old('description', $dish['description']) ?></textarea>
            </div>
            
            <div class="mb-3">
                <?php if ($dish['image']): ?>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div>
                            <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" alt="<?= $dish['name'] ?>" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                <?php endif; ?>
                
                <label for="image" class="form-label">Change Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Upload a new image to replace the current one (Max size: 1MB, Formats: JPG, PNG, GIF)</div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="available" name="available" value="1" <?= old('available', $dish['available']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="available">Available for ordering</label>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Dish
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
