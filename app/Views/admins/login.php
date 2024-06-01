<?= $this->extend('layout/admin.php') ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <?php if (isset(session()->getFlashdata()["msg"])) : ?>
                        <div class="alert alert-warning">
                            <?= session()->getFlashdata()["msg"] ?>
                        </div>
                    <?php endif; ?>
                    <h5 class="card-title mt-5">Login</h5>
                    <form method="POST" class="p-auto" action="<?= url_to("admins.login.check") ?>">
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Email" />
                        </div>
                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" name="password" id="form2Example2" placeholder="Password" class="form-control" />
                        </div>
                        <!-- Submit button -->
                        <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection(); ?>