<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customer List</title>
    <link rel="stylesheet" href="{{ asset('css/report-view.css') }}">
</head>

<div class="font-color-black">

    <center style="float:none;">
        <h4><b>Customer List</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{config('enum.company_name')}}</b><br>
            <b>Email: </b>{{config('enum.company_email')}} <br>
            <b>Phone No: </b>{{config('enum.company_phone')}}
        </div>
        <div style="float: right;">
            <b>Customer: </b>{{ $parms->customer ?? 'All' }} <br>
        </div>
    </div>
    <br><br><br><br>

    <div class="tableFixHead font-color-black">

        <table class="font-x-medium" border="1" style="border: 0.5px solid black;" width="100%">

            <thead>
                <tr class="border-black">
                    <th class="text-center">Sr.</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Contact#</th>
                    <th class="text-center">CNIC</th>
                    <th class="text-center">Date Of Birth</th>
                    <th class="text-center">Anniversary</th>
                    <th class="text-center">Ring</th>
                    <th class="text-center">Bangle</th>
                    <th class="text-center">Address</th>
                </tr>
            </thead>
            <tbody>
                @if(count($parms->data)>0)
                @foreach ($parms->data as $index=>$item)

                <tr>
                    <td class="text-left">{{ $index+1 }}</td>
                    <td class="text-left">{{ ucwords($item->name ?? '')}}</td>
                    <td class="text-left">{{ $item->email ?? '' }}</td>
                    <td class="text-left">{{$item->contact??''}}</td>
                    <td class="text-left">{{$item->cnic??''}}</td>
                    <td class="text-left">{{ date('d-M-Y', strtotime($item->date_of_birth??'')) }}</td>
                    <td class="text-left">{{ date('d-M-Y', strtotime($item->anniversary_date??'')) }}</td>
                    <td class="text-left">{{ $item->ring_size ?? '' }}</td>
                    <td class="text-left">{{ $item->bangle_size ?? '' }}</td>
                    <td class="text-left">{{ $item->address ?? '' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10" class="text-center">Data Not Found!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

</div>

</html>

{{-- @php
    exit();
@endphp --}}