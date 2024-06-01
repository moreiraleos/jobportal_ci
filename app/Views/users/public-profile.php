<?= $this->extend('layout/app'); ?>

<?= $this->section('content'); ?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('<?= base_url('public/assets/images/hero_1.jpg') ?>');" id="home-section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-7">
                <div class="card p-3 py-4">
                    <div class="text-center">
                        <img src="<?= base_url('public/assets/user_images/' . $singleUser->image) ?>" width="100" class="rounded-circle">
                    </div>
                    <div class="text-center mt-1">
                        <h5 class="mt-2 mb-0"><?= $singleUser->username ?></h5>
                        <span><?= $singleUser->job_title ?></span>
                        <div class="text-center mt-3">
                            <a class="text-right btn btn-success" href="<?= base_url('public/assets/cvs/' . $singleUser->cv) ?>">Download CV</a>
                        </div>
                        <div class="px-4 mt-3">
                            <p class="fonts">
                                <?= $singleUser->bio ?>
                            </p>
                        </div>
                        <div class="px-3">
                            <a href="<?= $singleUser->facebook ?>" class="pt-3 pb-3 pr-3 pl-0 underline-none"><span class="icon-facebook"></span></a>
                            <a href="<?= $singleUser->twitter ?>" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-twitter"></span></a>
                            <a href="<?= $singleUser->linkedin ?>" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-linkedin"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?= $this->endsection(); ?>