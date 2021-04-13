<div class="container update-form">
    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url($route . 'save') ?>" method="post" id="kriteria-form" class="form-horizontal">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <div class="form-group row mb-4">
                    <label for="name" class="col-sm-3 col-form-label text-md-right">Name*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" id="name" value="<?= $row->name ?>" autocomplete="off">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>