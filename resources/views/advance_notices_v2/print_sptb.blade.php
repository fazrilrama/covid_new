@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 class="col-sm-12" style="text-align: center;font-weight: bold">
    @if($advanceNotice->type == 'inbound')
    Surat Perintah Barang Masuk (SPBM)
    @else
    Surat Perintah Barang Keluar (SPBK)
    @endif
    </h3>
    <h4 class="col-sm-12" style="text-align: center;font-weight: bold;margin-top: 0px">{{$advanceNotice->code}}</h4>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>Company</td>
                    <td>:</td>
                    <td>{{$advanceNotice->project->company->name}}</td>
                </tr>
                <tr>
                    <td>Project</td>
                    <td>:</td>
                    <td>{{$advanceNotice->project->name}}</td>
                </tr>
                <tr>
                    <td>Tanggal Dibuat</td>
                    <td>:</td>
                    <td>{{$advanceNotice->created_at}}</td>
                </tr>
                <tr>
                    <td>No. Kontrak</td>
                    <td>:</td>
                    <td>{{$advanceNotice->contract_number}}</td>
                </tr>
                <tr>
                    <td>No. SPK</td>
                    <td>:</td>
                    <td>{{$advanceNotice->spmp_number}}</td>
                </tr>
                <tr>
                    <td>Jenis Kegiatan</td>
                    <td>:</td>
                    <td>{{$advanceNotice->advance_notice_activity->name}}</td>
                </tr>
                <tr>
                    <td>Moda Kegiatan</td>
                    <td>:</td>
                    <td>{{$advanceNotice->transport_type->name}}</td>
                </tr>
                <tr>
                    <td>Customer Reference</td>
                    <td>:</td>
                    <td>{{$advanceNotice->ref_code}}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        @if(isset($from_job))
                            @php
                            $logged_user = \App\User::find($job_user_id)
                            @endphp
                            @if($logged_user->hasRole('CargoOwner'))
                            {{ ($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status }}
                            @else
                            {{ ($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status) }}
                            @endif
                        @else
                            @if(!Auth::user()->hasRole('CargoOwner'))
                            {{ ($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status }}
                            @else
                            {{ ($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status) }}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Pembuat</td>
                    <td>:</td>
                    <td>{{@$advanceNotice->user->user_id}}</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>Origin</td>
                    <td>:</td>
                    <td>{{$advanceNotice->origin->name}}</td>
                </tr>
                <tr>
                    <td>Est. Time Delivery</td>
                    <td>:</td>
                    <td>{{$advanceNotice->etd}}</td>
                </tr>
                <tr>
                    <td>@if($advanceNotice->type == 'inbound') Shipper @else Cabang/Subcabang @endif</td>
                    <td>:</td>
                    <td>{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name}}</td>
                </tr>
                <tr>
                    <td>@if($advanceNotice->type == 'inbound') Shipper @else Cabang/Subcabang @endif Address</td>
                    <td>:</td>
                    <td>{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address}}</td>
                </tr>
                <tr>
                    <td>Destination</td>
                    <td>:</td>
                    <td>{{$advanceNotice->destination->name}}</td>
                </tr>
                <tr>
                    <td>Est. Time Arrival</td>
                    <td>:</td>
                    <td>{{$advanceNotice->eta}}</td>
                </tr>
                <tr>
                    <td>@if ($advanceNotice->type == 'inbound') Cabang/Subcabang @else Consignee @endif</td>
                    <td>:</td>
                    <td>{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name}}</td>
                </tr>
                <tr>
                    <td>@if ($advanceNotice->type == 'inbound') Cabang/Subcabang @else Consignee @endif Address</td>
                    <td>:</td>
                    <td>{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address}}</td>
                </tr>
                <tr>
                    <td>Warehouse</td>
                    <td>:</td>
                    <td>{{$advanceNotice->warehouse->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Warehouse Supervisor</td>
                    <td>:</td>
                    <td>{{$advanceNotice->employee_name ?? '-'}}</td>
                </tr>

                <!-- NEW BULOG -->
                <!-- <tr>
                    <td>Annotation</td>
                    <td>:</td>
                    <td>{{$advanceNotice->annotation ?? '-'}}</td>
                </tr>
                <tr>
                    <td>Contractor</td>
                    <td>:</td>
                    <td>{{$advanceNotice->contractor ?? '-'}}</td>
                </tr> -->
                <!-- <tr>
                    <td>Head Cabang/Subcabang</td>
                    <td>:</td>
                    <td>{{$advanceNotice->head_ds}}</td>
                </tr> -->
            </table>
        </div>

    </div>  
    <p>Informasi Barang</p>
    <div class="row">
        <div class="col-sm-12">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>ID:</th>
                        <th>Item SKU:</th>
                        <th>Item Name:</th>
                        <th>Group Reference:</th>
                        <th>Qty:</th>
                        <th>UOM:</th>
                        <th>Weight:</th>
                        <th colspan="2">Volume:</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advanceNotice->details->where('status', '<>', 'canceled') as $detail)
                        <tr>
                            <td>{{$detail->id}}</td>
                            <td>{{$detail->item->sku}}</td>
                            <td>{{$detail->item->name}}</td>
                            <td>{{$detail->ref_code}}</td>
                            <td>{{number_format($detail->qty, 2, ',', '.')}}</td>
                            <td>{{$detail->uom->name}}</td>
                            <td>{{number_format($detail->weight, 2, ',', '.')}}</td>
                            <td>{{number_format($detail->volume, 2, ',', '.')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection