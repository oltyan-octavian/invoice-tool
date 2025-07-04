<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @font-face {
            font-family: 'source_sans_3';
            src: url('{{ storage_path('fonts/source_sans_3/SourceSans3-Regular.ttf') }}') format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'source_sans_3';
            src: url('{{ storage_path('fonts/source_sans_3/SourceSans3-Bold.ttf') }}') format('truetype');
            font-weight: bold;
        }
        body {
            font-family: 'source_sans_3', sans-serif;
            padding-top: 0;
            line-height: 1;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 20px;
            font-size: 11px;
        }

        .invoice_table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }

        .price_table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 55px;
        }

        .invoice_table th {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .invoice_table td {
            padding: 8px;
        }

        td {
            text-align: left;
        }

        th {
            border-top: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .title{
            margin-top: 50px;
            font-size: 20px;
        }

        .name{
            margin-top: 20px;
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div style="text-align: right; margin-bottom: 60px;">
    <img src="{{ public_path('images/aico_logo.png') }}" alt="Logo" style="width: 140px;">
</div>

<div>
    @if($customer->is_legal_entity)
        <div><strong>{{ $customer->company_name }}</strong> </div>
        <div><strong>{{ $customer->company_street }}</strong> </div>
        <div><strong>{{ $customer->company_zip }} {{ $customer->company_city }}</strong> </div>
        <div><strong>{{ $customer->company_country }}</strong> </div>
    @else
        <div><strong>{{ $customer->name }}</strong> </div>
        <div><strong>{{ $customer->street }}</strong> </div>
        <div><strong>{{ $customer->zip }} {{ $customer->city }}</strong> </div>
        <div><strong>{{ $customer->country }}</strong> </div>
    @endif
</div>

<div class="title">{{ __('invoice.title')}}</div>

<div class="name"><strong>{{$name}}</strong></div>

<div style="margin-bottom: 70px">
    <div>{{__('invoice.customer_nr')}}: {{$customer->id}}</div>
    @if($customer->is_legal_entity)
        <div>{{ __('invoice.contact_person') }}: {{$customer->name}}</div>
    @endif
    <div>{{__('invoice.due_date')}}: {{$due_date}}</div>
    @if($customer->is_legal_entity)
        <div>{{ __('invoice.vat') }}: {{$customer->company_vat}}</div>
    @endif
</div>

<div>{{__('invoice.dear')}}</div>
<div>{{__('invoice.invoice_introduction')}}</div>

<table class="invoice_table">
    <thead>
    <tr>
        <th>{{__('invoice.item_name')}}</th>
        <th class="right">{{__('invoice.quantity')}}</th>
        <th class="right">{{__('invoice.unit_type')}}</th>
        <th class="right">{{__('invoice.unit_price')}}</th>
        <th class="right">{{__('invoice.total')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td class="right">{{ $item['quantity'] }}</td>
            <td class="right">{{ $item['unit_type'] }}</td>
            <td class="right">{{ number_format($item['price'], 2) }}</td>
            <td class="right">{{ number_format($item['total'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="price_table">
    <tr>
        <td> {{__('invoice.net_total')}}</td>
        <td class="right">{{number_format($total, 2)}}</td>
    </tr>
    <tr>
        <td> {{__('invoice.including_vat')}} {{$tax}}%</td>
        <td class="right">{{number_format($total * ($tax / 100), 2)}}</td>
    </tr>
    <tr>
        <td><strong> {{__('invoice.total_including_vat')}}</strong></td>
        <td class="right"><strong>CHF {{number_format($total * (100 + $tax)/100, 2)}}</strong></td>
    </tr>
</table>

<div>{{__('invoice.questions')}}</div>
<br>
<div>{{__('invoice.goodbye')}}</div>
<div>aiconomy AG</div>
</body>
</html>
