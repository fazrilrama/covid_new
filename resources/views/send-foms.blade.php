<div class="modal fade" id="modal-send-foms">
    <div class="modal-dialog modal-lg">
        <form action="{{ $url }}" method="POST" id="send-foms" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Lengkapi Data FOMS</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">

                                <select class="form-control" name="traffic">
                                    <option value="" selected disabled>-- Pilih Traffic --</option>
                                    <option value="1" @if($stockTransport->traffic == 1){{'selected'}}@endif>PTP</option>
                                    <option value="2" @if($stockTransport->traffic == 2){{'selected'}}@endif>DTD</option>
                                    <option value="3" @if($stockTransport->traffic == 3){{'selected'}}@endif>DTP</option>
                                    <option value="4" @if($stockTransport->traffic == 4){{'selected'}}@endif>PTD</option>
                                </select>

                            </div>
                            <div class="form-group">

                                <input type="text" name="origin_address" class="form-control" placeholder="Origin Address"
                                    value="{{old('origin_address', $stockTransport->origin_address)}}">

                            </div>
                            <div class="form-group">

                                <input type="text" name="origin_postcode" class="form-control" placeholder="Origin Post Code"
                                    value="{{old('origin_postcode', $stockTransport->origin_postcode)}}">

                            </div>
                            <div class="form-group">

                                <input type="text" name="origin_latitude" class="form-control" placeholder="Origin Latitude"
                                    value="{{old('origin_latitude', $stockTransport->origin_latitude)}}">

                            </div>
                            <div class="form-group">

                                <input type="text" name="origin_longitude" class="form-control" placeholder="Origin Longitude"
                                    value="{{old('origin_longitude', $stockTransport->origin_longitude)}}">

                            </div>
                            <button type="button" class="btn btn-primary btn-large btn-block pick-location-origin">Pick
                                Origin Location</button>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">

                                <select class="form-control" name="load_type">
                                    <option value="" selected disabled>-- Pilih Loading Type --</option>
                                    <option value="1" @if($stockTransport->load_type == 1){{'selected'}}@endif>LTL</option>
                                    <option value="2" @if($stockTransport->load_type == 2){{'selected'}}@endif>FTL</option>
                                </select>

                            </div>
                            <div class="form-group">

                                <input type="text" name="dest_address" class="form-control" placeholder="Dest Address"
                                    value="{{old('dest_address', $stockTransport->dest_address)}}">

                            </div>
                            <div class="form-group">

                                <input type="text" name="dest_postcode" class="form-control" placeholder="Dest Post Code"
                                    value="{{old('dest_postcode', $stockTransport->dest_postcode)}}">

                            </div>
                            <div class="form-group">

                                <input type="text" name="dest_latitude" class="form-control" placeholder="Dest Latitude"
                                    value="{{old('dest_latitude', $stockTransport->dest_latitude)}}">

                            </div>
                            <div class="form-group">

                                <input type="text" name="dest_longitude" class="form-control" placeholder="Dest Longitude"
                                    value="{{old('dest_longitude', $stockTransport->dest_longitude)}}">

                            </div>
                            <button type="button" class="btn btn-primary btn-large btn-block pick-location-destination">Pick
                                Destination Location</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-flat">Kirim Data</button>
                </div>
            </div>
        </form>
    </div>
</div>