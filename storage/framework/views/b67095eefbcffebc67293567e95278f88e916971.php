<div class="modal fade" id="modal-otp">
    <div class="modal-dialog modal-sm">
        <form action="<?php echo e($url); ?>" method="POST" id="otp" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Masukkan Keterangan</h4>
                </div>
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group container1">
                    
                    <div>
                        <button class="add_form_field">Tambah Keterangan &nbsp; 
                        <span style="font-size:16px; font-weight:bold;">+ </span>
                        </button>
                        <input class="form-control" type="text" name="keterangan[]" required>
                        <br>
                    </div>
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
<script src="<?php echo e(asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js')); ?>"></script>

<script>
    $(document).ready(function() {
        var max_fields = 10;
        var wrapper = $(".container1");
        var add_button = $(".add_form_field");

        var x = 1;
        $(add_button).click(function(e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(wrapper).append('<div><input type="text" class="form-control" name="keterangan[]" required/><a href="#" class="delete">Delete</a></div>'); //add input box
            } else {
                alert('You Reached the limits')
            }
        });

        $(wrapper).on("click", ".delete", function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>