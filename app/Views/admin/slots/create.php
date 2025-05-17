<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/admin/delivery-slots') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Delivery Slots
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Add New Delivery Slot</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/delivery-slots/create') ?>" method="post">
            <div class="mb-3">
                <label for="slot_time" class="form-label">Slot Time</label>
                <input type="text" class="form-control" id="slot_time" name="slot_time" value="<?= old('slot_time') ?>" required>
                <div class="form-text">Example: 8:00 AM - 9:00 AM</div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= old('is_active') ? 'checked' : '' ?> checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Slot
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
