<div class="container create-form">
    <div class="row">
        <div class="col-md-12">
            <form action="<?= base_url($route . 'store') ?>" method="post" id="transaksi-form" class="form-horizontal">
                <div class="form-group mb-4 row">
                    <label for="notrans" class="col-sm-3 col-form-label text-md-right">No Transaksi*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="notrans" id="notrans" autocomplete="off">
                    </div>
                </div>
                <div class="form-group mb-4 row">
                    <label for="tanggal-dp" class="col-sm-3 col-form-label text-md-right">Tanggal*</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="icon-calender"></i></span>
                            </div>
                            <input type="text" class="form-control date-picker" name="tanggal-dp" id="tanggal-dp" autocomplete="off">
                            <input type="hidden" name="tanggal">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4 row">
                    <label for="divisi" class="col-sm-3 col-form-label text-md-right">Divisi*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="divisi" id="divisi" autocomplete="off">
                    </div>
                </div>
                <div class="form-group mb-4 row">
                    <label for="totalbuah" class="col-sm-3 col-form-label text-md-right">Total Buah*</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="totalbuah" id="totalbuah" autocomplete="off">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>