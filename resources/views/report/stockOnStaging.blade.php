@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    @if($type == 'inbound')
        <h1>Laporan Stock On Staging IN</h1>
    @else
        <h1>Laporan Stock On Staging OUT</h1>
    @endif
@stop

@section('content')
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
            <form action="{{ route('report.stock-on-staging',$type) }}" method="GET" class="form-horizontal">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sku" class="col-sm-4 col-form-label">Item SKU</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="sku" name="sku" required>
                                        <option value="" selected disabled>--Pilih SKU--</option>
                                        <option value="">All</option>
                                        @foreach($skus as $sku)
                                            <option value="{{ $sku->item_id }}" {{ !empty($data['sku']) ? ($data['sku'] == $sku->item_id ? 'selected' : '') : '' }}>{{ $sku->item->sku }} / {{ $sku->item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ref" class="col-sm-4 col-form-label">Group Ref</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="ref" name="ref" required>
                                        <option value="" selected disabled>--Pilih Group Ref--</option>
                                        @foreach($ref_codes as $ref)
                                            <option value="{{ $ref }}" {{ !empty($data['ref']) ? ($data['ref'] == $ref ? 'selected' : '') : '' }}>{{ $ref }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- <div class="form-group">
                                <label for="storage_id" class="col-sm-4 col-form-label">Warehouse ID</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="warehouse_id" name="warehouse_id" required>
                                        <option value="" selected disabled>--Pilih Warehouse--</option>
                                        <option value="">All</option>
                                        @foreach($storages as $storage)
                                            <option value="{{ $storage->id }}" {{ !empty($data['storage_id']) ? ($data['storage_id'] == $storage->id ? 'selected' : '') : '' }}>{{ $storage->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            @if($type == 'outbound')
                                <div class="form-group">
                                    <label for="storage_id" class="col-sm-4 col-form-label">Storage ID</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="storage_id" name="storage_id" required>
                                            <option value="" selected disabled>--Pilih Storage--</option>
                                            <option value="">All</option>
                                            @foreach($warehouses as $warehouse)
                                                <optgroup label="{{ $warehouse->warehouse->name }}">
                                                    @foreach($warehouse->storages as $storage)
                                                        <option value="{{ $storage->id }}" {{ !empty($data['storage_id']) ? ($data['storage_id'] == $storage->id ? 'selected' : '') : '' }}>{{ $storage->code }}</option>
                                                    @endforeach
                                                </optgroup>                                       
                                            @endforeach    
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="control_date" class="col-sm-4 col-form-label">Control Date</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="control_date" name="control_date" required>
                                        <option value="" selected disabled>--Pilih Control Date--</option>
                                        <option value="">All</option>
                                        @foreach($control_dates as $control_date)
                                            <option value="{{ $control_date }}" {{ !empty($data['control_date']) ? ($data['control_date'] == $control_date ? 'selected' : '') : '' }}>{{ $control_date }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-sm btn-primary" name="submit" value="0">
                        <i class="fa fa-search"></i> Cari
                    </button>
                    <button class="btn btn-sm btn-success" name="submit" value="1">
                        <i class="fa fa-download"></i> Export ke Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover no-margin" width="100%">
        <thead>
            <tr>
                <th>No Transaksi:</th>
                <th>Item SKU / Name:</th>
                <th>Group Ref:</th>
                <th>Control Date:</th>
                <th>Uom:</th>
                @if($type == 'outbound')
                    <th>Storage:</th>
                @endif
                <!-- <th>Notes:</th> -->
                <th>QTY:</th>
                <!-- <th>Available:</th> -->
            </tr>
        </thead>
        <tbody> 
            @foreach($collections as $collection)
                <tr>
                    @if($collection->type == 'inbound')
                        <td>SA/IN:{{ $collection->code }}</td>
                    @else
                        <td>SA/OUT:{{ $collection->code }}</td>
                    @endif
                    <td>{{ $collection->item->sku }} / {{ $collection->item->name }}</td>
                    <td>{{ $collection->ref_code }}</td>
                    <td>{{ $collection->control_date }}</td>
                    <td>{{ $collection->item->default_uom->name }}</td>
                    @if($type == 'outbound')
                        <td>{{ $collection->storage->code }}</td>
                    @endif
                    <!-- <td>{{ $collection->item_notes }}</td> -->
                    <td>{{ number_format($collection->qty, 2, ',', '.') }}</td>
                    <!-- <td>{{ number_format($collection->item_qty_available, 2, ',', '.') }}</td> -->
                </tr>
            @endforeach
            @if($type == 'outbound')
                <tr>
                    <td colspan="6" style="text-align: right;font-weight: bold;font-size: 16px">Stock Staging</td>
                    <td colspan="1" style="font-weight: bold;font-size: 16px">
                        {{ number_format($stock, 2, ',', '.') }}
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="5" style="text-align: right;font-weight: bold;font-size: 16px">Stock Staging</td>
                    <td colspan="1" style="font-weight: bold;font-size: 16px">
                        {{ number_format($stock, 2, ',', '.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@endsection
