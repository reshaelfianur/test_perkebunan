<div class="container create-form">
    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url($route . 'store') ?>" method="post" id="kriteria-form" class="form-horizontal">
                <div class="form-group mb-4 row">
                    <label for="name" class="col-sm-3 col-form-label text-md-right">Kriteria*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" id="name" autocomplete="off">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>