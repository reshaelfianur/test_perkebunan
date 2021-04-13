<div class="container create-form">
    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url($route . 'store') ?>" method="post" id="transaksi-detail-form" class="form-horizontal">
                <div class="form-group mb-4 row">
                    <label for="notrans" class="col-sm-3 col-form-label text-md-right">No Transaksi*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="notrans" id="notrans" autocomplete="off">
                    </div>
                </div>
                <div class="form-group mb-4 row">
                    <label for="idbuah" class="col-sm-3 col-form-label text-md-right">Kriteria Buah*</label>
                    <div class="col-sm-9">
                        <select name="idbuah" id="idbuah" class="form-control select-picker show-tick" data-live-search="true">
                            <option value="">~Pilih Kriteria~</option>

                            <?php foreach ($kriteria as $row) : ?>
                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-4 row">
                    <label for="jumlah" class="col-sm-3 col-form-label text-md-right">Jumlah*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="jumlah" id="jumlah" autocomplete="off">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>