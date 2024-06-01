<?= $this->extend("layout/admin"); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-5 d-inline">Update Categories</h5>
                    <form method="POST" action="<?= url_to("categories.update", $category["id"]) ?>" enctype="multipart/form-data">
                        <!-- Email input -->
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="name" value="<?= $category["name"] ?? "" ?>" id="form2Example1" class="form-control" placeholder="name" />
                        </div>
                        <!-- Submit button -->
                        <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endsection(); ?>