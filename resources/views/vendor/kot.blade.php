<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        KOT #{{ $order->id }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f3f4f6;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
        }

        .kot-wrapper {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 10px;
        }

        .restaurant-name {
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .kot-title {
            text-align: center;
            font-size: 16px;
            font-weight: 800;
            margin-top: 5px;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 10px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 5px 0;
            font-size: 12px;
        }

        .info-label {
            font-weight: 700;
        }

        .seat-section {
            margin-top: 5px;
            font-size: 12px;
        }

        .items-title {
            font-weight: 800;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            margin: 7px 0;
        }

        .item-name {
            flex: 1;
        }

        .item-qty {
            width: 30px;
            text-align: right;
        }

        .instructions {
            border: 1px dashed #000;
            padding: 7px;
            margin-top: 8px;
            font-size: 11px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 800;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 12px;
        }

        .print-button {
            display: block;
            width: 80mm;
            max-width: 100%;
            margin: 20px auto;
            padding: 10px;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
        }

        @media print {

            @page {
                size: 80mm auto;
                margin: 5mm;
            }

            body {
                background: #fff;
                padding: 0;
            }

            .kot-wrapper {
                width: 100%;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    @php
    /*
    |--------------------------------------------------------------------------
    | Seat Numbers
    |--------------------------------------------------------------------------
    */

    $seatNumbers = [];

    if (!empty($order->passenger_seats)) {

    foreach ($order->passenger_seats as $passenger) {

    if (!empty($passenger['seat_number'])) {
    $seatNumbers[] = $passenger['seat_number'];
    }
    }
    }

    /*
    |--------------------------------------------------------------------------
    | Old Order Fallback
    |--------------------------------------------------------------------------
    */

    if (empty($seatNumbers) && !empty($order->seat_number)) {
    $seatNumbers[] = $order->seat_number;
    }

    /*
    |--------------------------------------------------------------------------
    | Route Name
    |--------------------------------------------------------------------------
    */

    $routeName = optional($order->bus?->busRoute)->name
    ?? optional($order->bus?->busRoute)->route_name
    ?? 'N/A';
    @endphp


    <div class="kot-wrapper">

        {{-- Restaurant --}}
        <div class="restaurant-name">
            {{ $restaurant->restaurant_name ?? 'Restaurant' }}
        </div>

        <div class="kot-title">
            KITCHEN ORDER TICKET
        </div>

        <div class="center small">
            Order #{{ $order->id }}
        </div>


        <div class="line"></div>


        {{-- Order Information --}}

        <div class="info-row">
            <span class="info-label">
                Customer
            </span>

            <span>
                {{ $order->passenger_name ?? 'N/A' }}
            </span>
        </div>


        <div class="info-row">
            <span class="info-label">
                Passengers
            </span>

            <span>
                {{ $order->passenger_count ?? 1 }}
            </span>
        </div>


        <div class="seat-section">
            <span class="info-label">
                Seat Nos:
            </span>

            @if(count($seatNumbers) > 0)
            {{ implode(', ', $seatNumbers) }}
            @else
            N/A
            @endif
        </div>


        <div class="info-row">
            <span class="info-label">
                Bus
            </span>

            <span>
                {{ $order->bus->bus_number ?? 'N/A' }}
            </span>
        </div>


        <div class="info-row">
            <span class="info-label">
                Route
            </span>

            <span>
                {{ $routeName }}
            </span>
        </div>


        <div class="info-row">
            <span class="info-label">
                Date
            </span>

            <span>
                {{ $order->created_at?->format('d M Y h:i A') }}
            </span>
        </div>


        <div class="line"></div>


        {{-- Items --}}

        <div class="items-title">
            ORDER ITEMS
        </div>


        @foreach($order->items as $item)

        <div class="item-row">

            <div class="item-name">

                {{ $item->menuItem->name
                    ?? $item->menuItem->item_name
                    ?? 'Food Item' }}

            </div>

            <div class="item-qty">
                x{{ $item->quantity }}
            </div>

        </div>

        @endforeach


        <div class="line"></div>


        {{-- Cooking Instructions --}}

        @if(!empty($order->cooking_instructions))

        <div class="instructions">

            <strong>Instructions:</strong>

            <br>

            {{ $order->cooking_instructions }}

        </div>

        @endif


        {{-- Payment --}}

        <div class="info-row">
            <span class="info-label">
                Payment
            </span>

            <span>
                {{ strtoupper($order->payment_method ?? 'N/A') }}
            </span>
        </div>


        <div class="info-row">
            <span class="info-label">
                Status
            </span>

            <span>
                {{ strtoupper($order->payment_status ?? 'N/A') }}
            </span>
        </div>


        <!-- <div class="total-row">

            <span>
                TOTAL
            </span>

            <span>
                ₹{{ number_format($order->total_amount ?? 0, 2) }}
            </span>

        </div> -->


        <div class="line"></div>


        <div class="footer">
            Please prepare the order carefully.
            <br>
            QuickDines
        </div>

    </div>


    {{-- Print Button --}}

    <button
        type="button"
        class="print-button no-print"
        onclick="window.print()">

        🖨 Print KOT

    </button>




</body>

</html>
