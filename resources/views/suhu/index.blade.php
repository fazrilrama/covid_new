@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Monitoring SUHU Gudang
</h1>
@stop

@section('content')
<div class="mt-4">
    <form id="myForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Waktu</label>
                    <input type="time" class="form-control" id="time" name="time" placeholder="Waktu">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Petugas</label>
                    <input type="text" class="form-control" id="petugas" name="petugas" placeholder="Petugas">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Anter-Room (*C)</label>
                    <input type="number" class="form-control" id="anter_room" name="anter_room" placeholder="Anter-Room (*C)">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chamber 2 (*C)</label>
                    <input type="number" class="form-control" id="chamber_2" name="chamber_2" placeholder="Chamber 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chamber 3 (*C)</label>
                    <input type="number" class="form-control" id="chamber_3" name="chamber_3" placeholder="Chamber 3">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Kulkas (*C)</label>
                    <input type="number" class="form-control" id="kulkas" name="kulkas" placeholder="Kulkas">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="tindakan">Tindakan</label>
            <select class="form-control" id="tindakan" name="tindakan">
                <option selected disabled>Pilih Tindakan</option>
                @foreach($status as $sts)
                <option value="{{ $sts['id'] }}">{{ $sts['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Catatan"></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
        <button type="button" class="btn btn-info" id="reload"><i class="fa fa-repeat"></i> Reload</button>

    </form>

    <hr>

    <!-- Monitoring table -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Waktu</th>
                <th scope="col">Petugas</th>
                <th scope="col">Anter-Room</th>
                <th scope="col">Chamber 2</th>
                <th scope="col">Chamber 3</th>
                <th scope="col">Kulkas</th>
                <th scope="col">Status</th>
                <th scope="col">Tindakan</th>
                <th scope="col">Catatan</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suhu as $value => $sh)
            <tr>
                <th scope="row">{{ $value + 1 }}</th>
                <td>{{ $sh['time'] }}</td>
                <td>{{ $sh['petugas'] }}</td>
                <td>{{ $sh['anter_room'] }}</td>
                <td>{{ $sh['chamber_2'] }}</td>
                <td>{{ $sh['chamber_3'] }}</td>
                <td>{{ $sh['kulkas'] }}</td>
                <td>0</td>
                <td>{{ $sh['tindakan_name'] }}</td>
                <td>{{ $sh['catatan'] }}</td>
                <td>
                    <button class="btn btn-warning" id="edit_function" data-id="{{ $sh['id'] }}" type="button"><i class="fa fa-pencil"></i> Edit</button>
                    <button class="btn btn-danger" type="button" data-id="{{ $sh['id'] }}" id="remove_function"><i class="fa fa-trash"></i> Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div class="modal fade" id="edit-form" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Edit Suhu Monitoring</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_edit">
        <div class="modal-body">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Waktu</label>
                        <input type="time" class="form-control" id="time_edit" name="time_edit" placeholder="Waktu">
                        <input type="hidden" readonly class="form-control" id="id_edit" name="id_edit">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Petugas</label>
                        <input type="text" class="form-control" id="petugas_edit" name="petugas_edit" placeholder="Petugas">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Anter-Room (*C)</label>
                        <input type="number" class="form-control" id="anter_room_edit" name="anter_room_edit" placeholder="Anter-Room (*C)">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Chamber 2 (*C)</label>
                        <input type="number" class="form-control" id="chamber_2_edit" name="chamber_2_edit" placeholder="Chamber 2">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Chamber 3 (*C)</label>
                        <input type="number" class="form-control" id="chamber_3_edit" name="chamber_3_edit" placeholder="Chamber 3">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kulkas (*C)</label>
                        <input type="number" class="form-control" id="kulkas_edit" name="kulkas_edit" placeholder="Kulkas">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tindakan">Tindakan</label>
                <select class="form-control" id="tindakan_edit" name="tindakan_edit">
                    <option selected disabled>Pilih Tindakan</option>
                    @foreach($status as $sts)
                    <option value="{{ $sts['id'] }}">{{ $sts['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="catatan">Catatan</label>
                <textarea class="form-control" id="catatan_edit" name="catatan_edit" rows="3" placeholder="Catatan"></textarea>
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let id_suhu = 0;

        $('#reload').on('click', function() {
            location.reload();
        });

        $('body').on('click', '#edit_function', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('suhu_get') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function (res) {
                    if(res.status) {
                        const data = res.data;
                        $('#time_edit').val(data.time);
                        $('#petugas_edit').val(data.petugas);
                        $('#anter_room_edit').val(data.anter_room);
                        $('#chamber_2_edit').val(data.chamber_2);
                        $('#chamber_3_edit').val(data.chamber_3);
                        $('#kulkas_edit').val(data.kulkas);
                        $('#tindakan_edit').val(data.tindakan_id).trigger('change');
                        $('#catatan_edit').val(data.catatan);
                        $('#id_edit').val(data.id);
                        $('#edit-form').modal('show');
                    }
                },
                error: function (xhr) {
                    console.log('si');
                    if (xhr.status === 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Periksa Form terlebih dahulu!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan di server.'
                        });
                    }
                },
            });
        });

        $('body').on('click', '#remove_function', function() {
            let id = $(this).data('id');
            let token =  $('input[name="_token"]').val();

            Swal.fire({
                title: "Apakah kamu yakin?",
                text: "data yang sudah dihapus tidak akan bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus sekarang!"
            }).then((result) => {
                if (result.isConfirmed) {

                    let formData = new FormData();

                    formData.append('id', id);
                    formData.append('_token', token);

                    $.ajax({
                        url: "{{ route('suhu_remove') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            if (res.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan!'
                                });
                            }
                        },
                        error: function (xhr) {
                            console.log('si');
                            if (xhr.status === 422) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    text: 'Periksa Form terlebih dahulu!'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Server Error',
                                    text: 'Terjadi kesalahan di server.'
                                });
                            }
                        },
                    });
                }
            });
        });

        $('#form_edit').on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('suhu_update') }}", // ganti dengan route controller kamu
                type: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan!'
                        });
                    }
                },
                error: function (xhr) {
                    console.log('si');
                    if (xhr.status === 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Periksa Form terlebih dahulu!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan di server.'
                        });
                    }
                },
            });
        });

        $("#myForm").on("submit", function (e) {
            e.preventDefault(); // biar form gak reload

            $.ajax({
                url: "{{ route('suhu_store') }}", // ganti dengan route controller kamu
                type: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan!'
                        });
                    }
                },
                error: function (xhr) {
                    console.log('si');
                    if (xhr.status === 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Periksa Form terlebih dahulu!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan di server.'
                        });
                    }
                },
            });
        });
    });
</script>
@endsection