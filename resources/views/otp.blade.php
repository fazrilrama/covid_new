<div class="modal fade" id="modal-otp">
    <div class="modal-dialog modal-sm">
        <form action="{{ $url }}" method="POST" id="otp" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Masukkan Password</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <p align="center">
                        Apakah Anda yakin?
                        Mohon masukkan password untuk melakukan konfirmasi.
                    </p>
                    
                    @if(isset($annotation) && $annotation == 'yes')
                    <div class="form-group">
                        <textarea type="annotation" name="annotation" class="form-control" id="annotation" placeholder="Alasan Close?"></textarea>
                    </div>
                    @endif
                    
                    <div class="form-group">
                        <input type="password" name="password" required class="form-control" id="password" placeholder="Masukkan password disini">
                    </div>                    
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-flat">Lanjutkan</button>
                </div>
            </div>
        </form>
    </div>
</div>