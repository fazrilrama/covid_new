@section('js')
    <script>
        $(document).ready(function () {
            $('form').submit(function(e){
                //e.preventDefault();
                if (confirm('Apakah Anda yakin akan menyimpan data?')) {
                    return true;
                }

                return false;
            })
        });
    </script>
@endsection