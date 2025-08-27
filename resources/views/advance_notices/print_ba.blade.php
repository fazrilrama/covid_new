@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 class="col-sm-12" style="text-align: center;font-weight: bold">
    @if($advanceNotice->type == 'inbound')
    Berita Acara Selesai (AIN)
    @else
    Berita Acara Selesai (AON)
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
                @if(isset($advanceNotice->annotation))
                <tr>
                    <td>Alasan Close</td>
                    <td>:</td>
                    <td>{{@$advanceNotice->annotation}}</td>
                </tr>
                @endif
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
                        <th>Outstanding:</th>
                        <th>UOM:</th>
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
                            <td>
                                {{ number_format($detail->outstanding,2,',','.') }}
                            </td>
                            <td>{{$detail->uom->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                <tr>
                    <td align="center"><b>Checker</b></td>
                    <td align="center"><b>Admin</b></td>
                    <td align="center"><b>Kepala Gudang<b></td>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 120px">
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">  
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">                        
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                        <td rowspan="5" style="vertical-align: bottom;text-align: center">
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        </td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection