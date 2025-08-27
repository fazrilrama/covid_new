<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="format-detection" content="date=no">
  <meta name="format-detection" content="telephone=no">
  <title>Notification Email</title>
  <style type="text/css">
    body {
      margin: 0;
      padding: 0;
      -ms-text-size-adjust: 100%;
      -webkit-text-size-adjust: 100%;
    }

    table {
      border-spacing: 0;
    }

    table td {
      border-collapse: collapse;
    }

    .ExternalClass {
      width: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
      line-height: 100%;
    }

    .ReadMsgBody {
      width: 100%;
      background-color: #ebebeb;
    }

    table {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      -ms-interpolation-mode: bicubic;
    }

    .yshortcuts a {
      border-bottom: none !important;
    }

    @media screen and (max-width: 599px) {

      .force-row,
      .container {
        width: 100% !important;
        max-width: 100% !important;
      }
    }

    @media screen and (max-width: 400px) {
      .container-padding {
        padding-left: 12px !important;
        padding-right: 12px !important;
      }
    }

    .ios-footer a {
      color: #aaaaaa !important;
      text-decoration: underline;
    }

    a[href^="x-apple-data-detectors:"],
    a[x-apple-data-detectors] {
      color: inherit !important;
      text-decoration: none !important;
      font-size: inherit !important;
      font-family: inherit !important;
      font-weight: inherit !important;
      line-height: inherit !important;
    }
  </style>
</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <!-- 100% background wrapper (grey background) -->
  <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
    <tr>
      <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

        <br>

        <!-- 600px container (white background) -->
        <table border="0" width="800" cellpadding="0" cellspacing="0" class="container" style="width:800px;max-width:800px;">
          <tr>
            <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#EC6101;padding-left:24px;padding-right:24px">
              WINA
            </td>
          </tr>
          <tr>
            <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
              <br>

              <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">SPBK
              </div>
              <br>

              <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                <table width="100%">
                  <tr>
                    <th>Project</th>
                    <td>:</td>
                    <td>{{ $advanceNotice->project->name }}</td>
                    <th>Origin</th>
                    <td>:</td>
                    <td>{{$advanceNotice->origin->name}}</td>
                  </tr>
                  <tr>
                    <th>Tanggal Dibuat</th>
                    <td>:</td>
                    <td>{{$advanceNotice->created_at}}</td>
                    <th>Est. Time Delivery</th>
                    <td>:</td>
                    <td>{{$advanceNotice->etd}}</td>
                  </tr>
                  <tr>
                    <th>No. Kontrak</th>
                    <td>:</td>
                    <td>{{$advanceNotice->contract_number}}</td>
                    <th>@if($advanceNotice->type == 'inbound') Shipper @else Cabang/Subcabang @endif</th>
                    <td>:</td>
                    <td>{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name}}</td>
                  </tr>
                  <tr>
                    <th>No. SPK</th>
                    <td>:</td>
                    <td>{{$advanceNotice->spmp_number}}</td>
                    <th>@if($advanceNotice->type == 'inbound') Shipper @else Cabang/Subcabang @endif Address</th>
                    <td>:</td>
                    <td>{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address}}</td>
                  </tr>
                  <tr>
                    <th>Jenis Kegiatan</th>
                    <td>:</td>
                    <td>{{$advanceNotice->advance_notice_activity->name}}</td>
                    <th>Destination</th>
                    <td>:</td>
                    <td>{{$advanceNotice->destination->name}}</td>
                  </tr>
                  <tr>
                    <th>Moda Kegiatan</th>
                    <td>:</td>
                    <td>{{$advanceNotice->transport_type->name}}</td>
                    <th>Est. Time Arrival</th>
                    <td>:</td>
                    <td>{{$advanceNotice->eta}}</td>
                  </tr>
                  <tr>
                    <th>Customer Reference</th>
                    <td>:</td>
                    <td>{{$advanceNotice->ref_code}}</td>
                    <th>@if ($advanceNotice->type == 'inbound') Cabang/Subcabang @else Consignee @endif</th>
                    <td>:</td>
                    <td>{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name}}</td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td>:</td>
                    <td>
                      @if(isset($from_job))
                          @php
                          $logged_user = \App\User::find($job_user_id)
                          @endphp
                          @if(!$logged_user->hasRole('CargoOwner'))
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
                    <th>@if ($advanceNotice->type == 'inbound') Cabang/Subcabang @else Consignee @endif Address</th>
                    <td>:</td>
                    <td>{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address}}</td>
                  </tr>
                  <tr>
                    <th>Pembuat</th>
                    <td>:</td>
                    <td>{{@$advanceNotice->user->user_id}}</td>
                    <th>Warehouse Supervisor</th>
                    <td>:</td>
                    <td>{{$advanceNotice->employee_name}}</td>
                  </tr>
                  <tr>
                    <th>&nbsp;</th>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    @if(isset($advanceNotice->warehouse))
                    <th>Warehouse</th>
                    <td>:</td>
                    <td>{{ $advanceNotice->warehouse->name }}</td>
                    @endif
                  </tr>
                </table>
                <br><br>
              </div>
            </td>
          </tr>
          <tr>
            <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
              <br><br>

              You are receiving this email because you opted in on our website. Update your <a href="#" style="color:#aaaaaa">email
                preferences</a> or <a href="#" style="color:#aaaaaa">unsubscribe</a>.
              <br><br>

              <strong>Copyright WINA, 2019</strong><br>
              <br><br>

            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>

</html>