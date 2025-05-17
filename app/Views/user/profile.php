<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-edit"></i> Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/user/profile') ?>" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= $user['email'] ?>" readonly disabled>
                            <div class="form-text">Email cannot be changed.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= $user['address'] ?></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-key"></i> Change Password</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Password change functionality is not implemented in this demo.
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
