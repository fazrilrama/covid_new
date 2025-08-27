<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Pencarian Data</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <form method="GET" class="form-horizontal">
                <div class="box-body">
                    <div class="row">
                        @yield('additional_field')
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_from" class="col-sm-4 col-form-label">Tanggal Mulai</label>
                                <div class="col-sm-8">
                                    <input type="text" name="date_from" id="date_from" class="datepicker-normal form-control" placeholder="Tanggal mulai" value="{{ $data['date_from'] }}" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_to" class="col-sm-4 col-form-label">Tanggal Akhir</label>
                                <div class="col-sm-8">
                                    <input type="text" name="date_to" id="date_to" class="end-datepicker-normal form-control" placeholder="Tanggal akhir" value="{{ $data['date_to'] }}" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                    <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export ke Excel</button>
                    <!-- @if(isset($print_this))
                        <a href="JavaScript:poptastic('/report/stock_mutation/print')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px" title="Cetak Tally Sheet">
                            <i class="fa fa-print"></i> Cetak Mutasi Hari Ini
                        </a>
                    @endif -->
                </div>
            </form>
        </div>
    </div>
</div>