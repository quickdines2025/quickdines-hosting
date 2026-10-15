@extends('layouts.frontend')

@section('content')

<style>
    .checkout-wrapper {
        background: #f8f9fa;
        padding: 40px 0;
        min-height: 80vh;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #000;
        margin-bottom: 30px;
    }

    .checkout-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .c-box {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .c-box-title {
        font-size: 16px;
        font-weight: 700;
        color: #000;
        margin-bottom: 20px;
    }

    .del-grid {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 15px;
        align-items: center;
        margin-bottom: 15px;
    }

    .del-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .del-value {
        font-size: 14px;
        color: #000;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .live-badge {
        background: #f0fdf4;
        color: #16a34a;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        border: 1px solid #bbf7d0;
    }

    .form-group {
        margin-top: 20px;
    }

    .form-group input[type="text"],
    .form-group input[type="tel"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #94a3b8;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        color: #000;
        box-sizing: border-box;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="tel"]:focus {
        border-color: #2563eb;
    }

    .form-hint {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    /* Payment Methods */

    .pay-method {
        display: flex;
        align-items: center;
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: 0.2s;
    }

    .pay-method.active {
        border-color: #2563eb;
        background: #fff0e6;
    }

    .pay-icon {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #475569;
    }

    .pay-method.active .pay-icon {
        background: white;
        color: #2563eb;
    }

    .pay-info h4 {
        font-size: 14px;
        font-weight: 600;
        color: #000;
        margin-bottom: 2px;
    }

    .pay-info p {
        font-size: 11px;
        color: #64748b;
        margin: 0;
    }

    .pay-radio {
        margin-left: auto;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        position: relative;
    }

    .pay-method.active .pay-radio {
        border-color: #2563eb;
    }

    .pay-method.active .pay-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background: #2563eb;
        border-radius: 50%;
    }

    /* Order Item */

    .order-item {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        gap: 15px;
        margin-bottom: 15px;
    }

    .oi-info {
        display: flex;
        gap: 12px;
        align-items: center;
        min-width: 0;
    }

    .oi-img {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .oi-name {
        font-size: 13px;
        font-weight: 600;
        color: #000;
    }

    .oi-price {
        font-size: 12px;
        color: #475569;
        margin-top: 2px;
    }

    /* =========================================
       QUANTITY CONTROLS
       ========================================= */

    .qty-controls {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        width: 110px !important;
        min-width: 110px !important;
        height: 40px !important;

        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;

        padding: 4px !important;
        box-sizing: border-box !important;

        flex-shrink: 0 !important;
    }

    .qty-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        width: 28px !important;
        height: 28px !important;
        min-width: 28px !important;

        padding: 0 !important;
        margin: 0 !important;

        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 5px !important;

        color: #000000 !important;
        font-size: 18px !important;
        font-weight: 800 !important;

        line-height: 1 !important;
        cursor: pointer !important;

        visibility: visible !important;
        opacity: 1 !important;
    }

    .qty-btn:hover {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
    }

    .qty-number {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        width: 30px !important;
        min-width: 30px !important;
        height: 30px !important;

        padding: 0 !important;
        margin: 0 !important;

        color: #000000 !important;
        background: #ffffff !important;

        font-size: 16px !important;
        font-weight: 800 !important;
        line-height: 30px !important;
        text-align: center !important;

        visibility: visible !important;
        opacity: 1 !important;

        overflow: visible !important;
    }

    /* ========================================= */

    .bill-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #475569;
        margin-bottom: 10px;
    }

    .bill-total {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 800;
        color: #1d4ed8;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #e2e8f0;
    }

    .secure-badge {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .secure-badge i {
        color: #16a34a;
        font-size: 20px;
    }

    .sb-text h4 {
        font-size: 12px;
        color: #16a34a;
        font-weight: 700;
        margin: 0;
    }

    .sb-text p {
        font-size: 10px;
        color: #15803d;
        margin: 0;
    }

    .btn-place-order {
        width: 100%;
        background: #2563eb;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: 0.2s;
    }

    .btn-place-order:hover {
        background: #1d4ed8;
    }

    .btn-place-order:disabled {
        cursor: wait;
    }

    .order-note {
        text-align: center;
        font-size: 11px;
        color: #94a3b8;
        margin-top: 12px;
    }

    .mobile-sticky-bottom {
        position: relative;
    }

    /* =========================================
       MOBILE
       ========================================= */

    @media (max-width: 768px) {

        .checkout-wrapper {
            padding: 10px 0;
            background: #fff;
        }

        .container {
            padding: 0 15px;
        }

        .page-title {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .checkout-layout {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .c-box {
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #f1f5f9;
            box-shadow: none;
        }

        .del-grid {
            grid-template-columns: 100px 1fr;
        }

        .del-label {
            font-size: 12px;
        }

        .del-value {
            font-size: 13px;
            flex-wrap: wrap;
            gap: 5px;
        }

        .order-item {
            flex-direction: row !important;
            align-items: center !important;
        }

        .oi-info {
            flex: 1;
            min-width: 0;
        }

        .oi-name {
            max-width: 150px;
            word-break: break-word;
        }

        .qty-controls {
            width: 110px !important;
            min-width: 110px !important;
        }

        .mobile-sticky-bottom {
            position: fixed;
            bottom: 65px;
            left: 0;
            right: 0;
            background: white;
            padding: 15px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-top: 1px solid #e2e8f0;
        }

        .c-box-summary {
            padding-bottom: 80px;
        }

        .mobile-hide {
            display: none !important;
        }
    }
</style>

<div class="checkout-wrapper">


    <div class="container">

        <!-- Header -->

        <div style="
        display:flex;
        align-items:center;
        gap:15px;
        margin-bottom:30px;
    ">

            <a
                href="javascript:history.back()"
                style="
                display:flex;
                align-items:center;
                justify-content:center;
                width:40px;
                height:40px;
                background:white;
                border-radius:50%;
                color:#000;
                text-decoration:none;
                box-shadow:0 2px 10px rgba(0,0,0,0.05);
            ">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <h1
                class="page-title"
                style="margin-bottom:0;">
                Checkout
            </h1>

        </div>


        <form
            id="checkout-form"
            method="POST"
            action="{{ route('checkout.process') }}"
            class="checkout-layout">

            @csrf

            <input
                type="hidden"
                name="cart_data"
                id="cart_data">

            <!-- old :here two ways payment methos online/COD -->
            <!-- <input
                type="hidden"
                name="payment_method"
                id="payment_method"
                value=""> -->

            <!-- new: here direclty given online upi only  -->
            <input
                type="hidden"
                name="payment_method"
                id="payment_method"
                value="upi">

            <input
                type="hidden"
                name="razorpay_payment_id"
                id="razorpay_payment_id"
                value="">


            <!-- =========================================
             LEFT COLUMN
             ========================================= -->

            <div>

                <!-- Delivery Information -->

                <div class="c-box">

                    <h3 class="c-box-title">
                        Delivery To
                    </h3>


                    <div class="del-grid">

                        <div class="del-label">
                            Bus Number
                        </div>

                        <div class="del-value">

                            {{ $bus ? $bus->bus_number : 'Not selected' }}

                            @if($bus)
                            <span class="live-badge">
                                Live Tracking
                            </span>
                            @endif

                        </div>


                        <div class="del-label">
                            Route
                        </div>

                        <div class="del-value">

                            {!! $bus
                            ? $bus->route_from . ' &rarr; ' . $bus->route_to
                            : 'N/A'
                            !!}

                        </div>


                        <div class="del-label">
                            Next Stop
                        </div>

                        <div class="del-value">

                            @php

                            $nextStopName = 'On the way';

                            if (
                            $bus &&
                            $bus->busRoute &&
                            $bus->busRoute->stops->count() > 0
                            ) {
                            $nextStopName =
                            $bus->busRoute->stops->first()->stop_name
                            . ' (Tracking)';
                            }

                            @endphp

                            {{ $nextStopName }}

                        </div>

                    </div>


                    <!-- Passenger Name -->

                    <div
                        class="form-group"
                        style="margin-top:15px;">

                        <label
                            class="del-label"
                            style="
                            display:block;
                            margin-bottom:8px;
                        ">
                            Passenger Name
                            <span style="color:#ef4444;">
                                *
                            </span>
                        </label>

                        <input
                            type="text"
                            name="passenger_name"
                            value="{{ auth()->check() ? auth()->user()->name : '' }}"
                            placeholder="Enter your name"
                            required>

                    </div>


                    <!-- Mobile -->

                    <div
                        class="form-group"
                        style="margin-top:15px;">

                        <label
                            class="del-label"
                            style="
                            display:block;
                            margin-bottom:8px;
                        ">
                            Mobile Number
                            <span style="color:#ef4444;">
                                *
                            </span>
                        </label>

                        <input
                            type="tel"
                            name="phone_number"
                            id="phone_number_input"
                            value="{{ auth()->check() ? auth()->user()->mobile : '' }}"
                            placeholder="Enter 10-digit mobile number"
                            required
                            inputmode="numeric"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">

                        <span
                            class="form-hint"
                            style="
                            display:block;
                            margin-top:5px;
                        ">
                            Delivery status updates will be sent here
                        </span>

                    </div>


                    <input
                        type="hidden"
                        name="order_type"
                        value="Delivery to my seat">


                    <!-- Gender -->

                    <div
                        class="form-group"
                        style="margin-top:15px;">

                        <label
                            class="del-label"
                            style="
                            display:block;
                            margin-bottom:8px;
                        ">
                            Gender
                            <span style="color:#ef4444;">
                                *
                            </span>
                        </label>

                        <div
                            style="
                            display:flex;
                            gap:20px;
                            align-items:center;
                        ">

                            <label
                                style="
                                font-size:14px;
                                cursor:pointer;
                                display:flex;
                                align-items:center;
                                gap:5px;
                                color:#000;
                                font-weight:500;
                            ">

                                <input
                                    type="radio"
                                    name="gender"
                                    value="Male"
                                    required
                                    style="width:auto;padding:0;">

                                Male

                            </label>


                            <label
                                style="
                                font-size:14px;
                                cursor:pointer;
                                display:flex;
                                align-items:center;
                                gap:5px;
                                color:#000;
                                font-weight:500;
                            ">

                                <input
                                    type="radio"
                                    name="gender"
                                    value="Female"
                                    required
                                    style="width:auto;padding:0;">

                                Female

                            </label>


                            <label
                                style="
                                font-size:14px;
                                cursor:pointer;
                                display:flex;
                                align-items:center;
                                gap:5px;
                                color:#000;
                                font-weight:500;
                            ">

                                <input
                                    type="radio"
                                    name="gender"
                                    value="Other"
                                    required
                                    style="width:auto;padding:0;">

                                Other

                            </label>

                        </div>

                    </div>


                    <!-- Seat Number -->



                    <!-- Number of Passengers -->

                    <div
                        class="form-group"
                        style="margin-top:15px;">

                        <label
                            class="del-label"
                            style="
            display:block;
            margin-bottom:8px;
        ">
                            Number of Passengers
                            <span style="color:#ef4444;">
                                *
                            </span>
                        </label>

                        <select
                            name="passenger_count"
                            id="passenger_count"
                            required
                            style="
                                    width:100%;
                                    padding:12px;
                                    border:1px solid #94a3b8;
                                    border-radius:8px;
                                    font-size:14px;
                                    outline:none;
                                    color:#000;
                                    background:#fff;
                                    box-sizing:border-box;
                                ">

                            <option value="">Select number of passengers</option>
                            <option value="1">1 Passenger</option>
                            <option value="2">2 Passengers</option>
                            <option value="3">3 Passengers</option>
                            <option value="4">4 Passengers</option>
                            <option value="5">5 Passengers</option>
                            <option value="6">6 Passengers</option>

                        </select>

                    </div>


                    <!-- Passenger Seat Numbers -->

                    <div
                        class="form-group"
                        id="passenger-seats-container"
                        style="margin-top:15px;">

                        <!-- JavaScript will generate seat inputs here -->

                    </div>

                </div>


                <!-- Cooking Instructions -->

                <div class="c-box">

                    <h3 class="c-box-title">
                        Add Cooking Instructions
                    </h3>

                    <div class="form-group">

                        <input
                            type="text"
                            name="cooking_instructions"
                            placeholder="e.g. No onion, less spicy, extra gravy, etc."
                            style="
                            width:100%;
                            padding:12px;
                            border:1px solid #94a3b8;
                            border-radius:8px;
                            font-size:14px;
                            outline:none;
                            box-sizing:border-box;
                        ">

                    </div>

                </div>


                <!-- Payment  -->
                <!-- here payement methods just hide . it working but stop temparly give dirctly place order button -->

                <div class="c-box" style="display:none;">

                    <h3 class="c-box-title">
                        Payment Methods
                    </h3>


                    <div
                        class="pay-method"
                        data-method="upi"
                        onclick="selectPaymentMethod(this)">

                        <div class="pay-icon">
                            <i class="fa-brands fa-google-pay"></i>
                        </div>

                        <div class="pay-info">

                            <h4>
                                Online Payment (UPI/Cards)
                            </h4>

                            <p>
                                Google Pay, PhonePe, Paytm, Cards
                            </p>

                        </div>

                        <div class="pay-radio"></div>

                    </div>


                    <div
                        class="pay-method"
                        data-method="cod"
                        onclick="selectPaymentMethod(this)">

                        <div class="pay-icon">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>

                        <div class="pay-info">

                            <h4>
                                Cash on Delivery
                            </h4>

                            <p>
                                Pay at your seat when order arrives
                            </p>

                        </div>

                        <div class="pay-radio"></div>

                    </div>

                </div>

            </div>


            <!-- =========================================
             RIGHT COLUMN
             ========================================= -->

            <div>

                <div
                    class="c-box c-box-summary"
                    style="
                    position:sticky;
                    top:20px;
                ">

                    <div
                        style="
                        display:flex;
                        justify-content:space-between;
                        margin-bottom:20px;
                    ">

                        <h3
                            class="c-box-title"
                            style="margin:0;">
                            Order Summary
                        </h3>

                        <span
                            id="checkout-item-count"
                            style="
                            font-size:12px;
                            color:#64748b;
                            font-weight:500;
                        ">
                            0 items
                        </span>

                    </div>


                    <div
                        id="checkout-items-list"
                        style="
                        margin-bottom:25px;
                        border-bottom:1px solid #e2e8f0;
                        padding-bottom:15px;
                    ">
                    </div>


                    <h3
                        class="c-box-title"
                        style="
                        font-size:14px;
                        margin-bottom:15px;
                    ">
                        Price Details
                    </h3>


                    <div class="bill-row">

                        <span>
                            Item Total
                        </span>

                        <span id="checkout-item-total">
                            ₹0
                        </span>

                    </div>


                    <div class="bill-row">

                        <span>
                            Platform Fee
                        </span>

                        <span id="checkout-platform-fee">
                            ₹0
                        </span>

                    </div>


                    <div class="bill-row">

                        <span>
                            GST (5%)
                        </span>

                        <span id="checkout-gst">
                            ₹0
                        </span>

                    </div>


                    <div class="bill-total">

                        <span>
                            Total Amount
                        </span>

                        <span id="checkout-grand-total">
                            ₹0
                        </span>

                    </div>


                    <div class="secure-badge mobile-hide">

                        <i class="fa-solid fa-shield-check"></i>

                        <div class="sb-text">

                            <h4>
                                Your payment information is secure
                            </h4>

                            <p>
                                We use 256-bit SSL encryption
                            </p>

                        </div>

                    </div>


                    <div class="mobile-sticky-bottom">

                        <button
                            type="submit"
                            class="btn-place-order">

                            Place Order

                            <span
                                id="checkout-btn-total"
                                style="float:right;">
                                ₹0
                            </span>

                        </button>

                        <p class="order-note mobile-hide">
                            You will receive order updates on your mobile number
                        </p>

                    </div>

                </div>

            </div>

        </form>

    </div>


</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const serverCheckoutCart =
        JSON.parse('{!! addslashes(json_encode($checkoutCart ?? null)) !!}');


    /*
    |--------------------------------------------------------------------------
    | Load Cart
    |--------------------------------------------------------------------------
    */

    let storedCart = [];

    try {

        storedCart =
            JSON.parse(
                localStorage.getItem('quickdines_cart') || '[]'
            );

    } catch (e) {

        storedCart = [];

    }


    let cart =
        Array.isArray(storedCart) &&
        storedCart.length > 0

        ?
        storedCart

        :
        (
            Array.isArray(serverCheckoutCart?.items) ?
            serverCheckoutCart.items : []
        );



    /*
|--------------------------------------------------------------------------
| Passenger Count + Seat Numbers
|--------------------------------------------------------------------------
*/

    const passengerCountSelect =
        document.getElementById('passenger_count');

    const passengerSeatsContainer =
        document.getElementById('passenger-seats-container');


    function renderPassengerSeats() {

        const count =
            Number(passengerCountSelect.value) || 0;

        passengerSeatsContainer.innerHTML = '';


        if (count === 0) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */

        const title =
            document.createElement('div');

        title.style.cssText = `
        font-size:13px;
        font-weight:600;
        color:#000;
        margin-bottom:12px;
    `;

        title.innerText =
            'Passenger Seat Numbers';

        passengerSeatsContainer.appendChild(title);


        /*
        |--------------------------------------------------------------------------
        | Generate seat inputs
        |--------------------------------------------------------------------------
        */

        for (let i = 1; i <= count; i++) {

            const wrapper =
                document.createElement('div');

            wrapper.style.cssText = `
            margin-bottom:12px;
        `;


            const label =
                document.createElement('label');

            label.style.cssText = `
            display:block;
            margin-bottom:6px;
            font-size:13px;
            color:#64748b;
            font-weight:500;
        `;

            label.innerHTML =
                `Passenger ${i} Seat Number
             <span style="color:#ef4444;">*</span>`;


            const input =
                document.createElement('input');

            input.type = 'text';

            input.name =
                `passenger_seats[${i}]`;

            input.className =
                'passenger-seat-input';

            input.dataset.passenger =
                i;

            input.placeholder =
                `Enter seat number for Passenger ${i}`;

            input.required = true;

            input.style.cssText = `
            width:100%;
            padding:12px;
            border:1px solid #94a3b8;
            border-radius:8px;
            font-size:14px;
            outline:none;
            color:#000;
            box-sizing:border-box;
        `;


            const hint =
                document.createElement('span');

            hint.style.cssText = `
            display:block;
            margin-top:5px;
            font-size:11px;
            color:#94a3b8;
        `;

            hint.innerText =
                'Seat number is required';


            wrapper.appendChild(label);

            wrapper.appendChild(input);

            wrapper.appendChild(hint);

            passengerSeatsContainer.appendChild(wrapper);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Passenger Count Change
    |--------------------------------------------------------------------------
    */

    passengerCountSelect.addEventListener(
        'change',
        function() {

            renderPassengerSeats();

        }
    );






    if (cart.length > 0) {

        localStorage.setItem(
            'quickdines_cart',
            JSON.stringify(cart)
        );

    }


    let storedTotals = null;

    try {

        storedTotals =
            JSON.parse(
                localStorage.getItem(
                    'quickdines_cart_totals'
                ) || 'null'
            );

    } catch (e) {

        storedTotals = null;

    }


    let totals =
        storedTotals ||
        serverCheckoutCart?.totals ||
        null;


    const defaultPlatformFee =
        Number(
            "{{ \App\Models\SystemSetting::getSetting('platform_commission', 10) }}"
        );


    /*
    |--------------------------------------------------------------------------
    | Format Money
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        const amount =
            Number(value) || 0;

        return '₹' +
            amount
            .toFixed(2)
            .replace(/\.00$/, '');

    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Totals
    |--------------------------------------------------------------------------
    */

    function calculateDisplayTotals() {

        const itemTotal =
            cart.reduce(
                (sum, item) => {

                    const price =
                        Number(item.price) || 0;

                    const qty =
                        Number(item.qty) || 0;

                    return sum + (price * qty);

                },
                0
            );


        const platformFee =
            defaultPlatformFee;


        const gst =
            Math.round(itemTotal * 0.05);


        const finalPrice =
            Math.round(
                (
                    itemTotal +
                    platformFee +
                    gst
                ) * 100
            ) / 100;


        return {

            itemTotal,

            platformFee,

            gst,

            finalPrice

        };

    }


    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    function selectPaymentMethod(element) {

        document
            .querySelectorAll('.pay-method')
            .forEach(el => {

                el.classList.remove('active');

            });


        element.classList.add('active');


        document.getElementById(
                'payment_method'
            ).value =
            element.getAttribute(
                'data-method'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Render Cart
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const container =
                document.getElementById(
                    'checkout-items-list'
                );


            const countDisplay =
                document.getElementById(
                    'checkout-item-count'
                );


            /*
            |--------------------------------------------------------------------------
            | Render Items
            |--------------------------------------------------------------------------
            */

            function renderCartItems() {

                container.innerHTML = '';

                let totalItems = 0;


                if (cart.length === 0) {

                    container.innerHTML = `
                    <div
                        style="
                            text-align:center;
                            padding:20px;
                            color:#64748b;
                        "
                    >
                        Your cart is empty.
                    </div>
                `;


                    countDisplay.innerText =
                        '0 items';


                    updateCheckoutTotals();

                    return;

                }


                cart.forEach(
                    function(item, index) {

                        const qty =
                            Number(item.qty) || 1;


                        const price =
                            Number(item.price) || 0;


                        totalItems += qty;


                        const itemTotal =
                            price * qty;


                        const itemDiv =
                            document.createElement(
                                'div'
                            );


                        itemDiv.className =
                            'order-item';


                        const image =
                            item.imgUrl ||
                            'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&q=80';


                        itemDiv.innerHTML = `

                        <div class="oi-info">

                            <img
                                src="${image}"
                                class="oi-img"
                                alt="${item.name || 'Food'}"
                            >

                            <div>

                                <div
                                    class="oi-name"
                                    style="
                                        display:flex;
                                        align-items:center;
                                        gap:6px;
                                    "
                                >

                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg"
                                        style="width:12px;"
                                        alt="Veg"
                                    >

                                    ${item.name || 'Item'}

                                </div>


                                <div
                                    class="oi-price"
                                    style="
                                        font-weight:600;
                                        color:#334155;
                                    "
                                >
                                    ₹${price.toFixed(2).replace(/\.00$/, '')}
                                </div>


                                <div
                                    style="
                                        font-size:11px;
                                        color:#64748b;
                                        margin-top:3px;
                                    "
                                >

                                    Item Total:

                                    <strong
                                        style="color:#0f172a;"
                                    >
                                        ₹${itemTotal
                                            .toFixed(2)
                                            .replace(/\.00$/, '')}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        <div class="qty-controls">

                            <button
                                type="button"
                                class="qty-btn"
                                onclick="changeCheckoutQuantity(${index}, -1)"
                            >
                                −
                            </button>


                            <span class="qty-number">
                                ${String(qty)}
                            </span>


                            <button
                                type="button"
                                class="qty-btn"
                                onclick="changeCheckoutQuantity(${index}, 1)"
                            >
                                +
                            </button>

                        </div>

                    `;


                        container.appendChild(
                            itemDiv
                        );

                    }
                );


                countDisplay.innerText =
                    `${totalItems} ${
                    totalItems === 1
                        ? 'item'
                        : 'items'
                }`;


                updateCheckoutTotals();

            }


            /*
            |--------------------------------------------------------------------------
            | Change Quantity
            |--------------------------------------------------------------------------
            */

            window.changeCheckoutQuantity =
                function(index, change) {

                    if (!cart[index]) {

                        return;

                    }


                    let currentQty =
                        Number(cart[index].qty) || 1;


                    currentQty += change;


                    /*
                    |--------------------------------------------------------------------------
                    | Remove item when quantity reaches 0
                    |--------------------------------------------------------------------------
                    */

                    if (currentQty <= 0) {

                        cart.splice(
                            index,
                            1
                        );

                    } else {

                        cart[index].qty =
                            currentQty;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Save Cart
                    |--------------------------------------------------------------------------
                    */

                    localStorage.setItem(
                        'quickdines_cart',
                        JSON.stringify(cart)
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Re-render
                    |--------------------------------------------------------------------------
                    */

                    renderCartItems();

                };


            /*
            |--------------------------------------------------------------------------
            | Update Totals
            |--------------------------------------------------------------------------
            */

            function updateCheckoutTotals() {

                totals =
                    calculateDisplayTotals();


                localStorage.setItem(
                    'quickdines_cart_totals',
                    JSON.stringify(totals)
                );


                document.getElementById(
                        'checkout-item-total'
                    ).innerText =
                    formatMoney(
                        totals.itemTotal
                    );


                document.getElementById(
                        'checkout-platform-fee'
                    ).innerText =
                    formatMoney(
                        totals.platformFee
                    );


                document.getElementById(
                        'checkout-gst'
                    ).innerText =
                    formatMoney(
                        totals.gst
                    );


                document.getElementById(
                        'checkout-grand-total'
                    ).innerText =
                    formatMoney(
                        totals.finalPrice
                    );


                document.getElementById(
                        'checkout-btn-total'
                    ).innerText =
                    formatMoney(
                        totals.finalPrice
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Initial Render
            |--------------------------------------------------------------------------
            */

            renderCartItems();


            /*
            |--------------------------------------------------------------------------
            | Form Submit
            |--------------------------------------------------------------------------
            */

            document
                .getElementById('checkout-form')
                .addEventListener(
                    'submit',
                    async function(e) {

                        e.preventDefault();

                        const form0 = document.getElementById('checkout-form');


                        /*
                        |--------------------------------------------------------------------------
                        | Empty Cart
                        |--------------------------------------------------------------------------
                        */

                        if (cart.length === 0) {

                            alert(
                                'Your cart is empty.'
                            );

                            return;

                        }


                        // Phone: exactly 10 digits
                        const phoneInput = form0.querySelector('input[name="phone_number"]');
                        const phoneValue = (phoneInput.value || '').trim();
                        if (!/^[0-9]{10}$/.test(phoneValue)) {
                            alert('Please enter a valid 10-digit mobile number.');
                            phoneInput.focus();
                            return;
                        }

                        // Gender required
                        if (!form0.querySelector('input[name="gender"]:checked')) {
                            alert('Please select gender.');
                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Payment Method
                        |--------------------------------------------------------------------------
                        */

                        // old:here validating payment type.
                        // const paymentMethod =
                        //     document.getElementById(
                        //         'payment_method'
                        //     ).value;
                        // if (!paymentMethod) {
                        //     alert(
                        //         'Please select a payment method.'
                        //     );
                        //     return;
                        // }

                        // new:here directly set to upi.
                        const paymentMethod = 'upi';
                        document.getElementById('payment_method').value = paymentMethod;



                        const form =
                            document.getElementById(
                                'checkout-form'
                            );


                        const button =
                            form.querySelector(
                                '.btn-place-order'
                            );


                        button.disabled = true;

                        button.style.opacity =
                            '0.65';

                        button.style.cursor =
                            'wait';


                        /*
                        |--------------------------------------------------------------------------
                        | Recalculate
                        |--------------------------------------------------------------------------
                        */

                        totals =
                            calculateDisplayTotals();


                        const passengerCount =
                            Number(
                                document.getElementById('passenger_count').value
                            ) || 0;


                        if (passengerCount < 1 || passengerCount > 6) {

                            alert('Please select the number of passengers.');

                            button.disabled = false;
                            button.style.opacity = '1';
                            button.style.cursor = 'pointer';

                            return;
                        }


                        const passengerSeats = [];

                        let seatsValid = true;


                        document
                            .querySelectorAll('.passenger-seat-input')
                            .forEach(function(input) {

                                const seatNumber =
                                    input.value.trim();

                                if (!seatNumber) {

                                    seatsValid = false;

                                    input.focus();

                                    return;
                                }

                                passengerSeats.push({

                                    passenger_number: Number(input.dataset.passenger),

                                    seat_number: seatNumber

                                });

                            });


                        if (!seatsValid || passengerSeats.length !== passengerCount) {

                            alert(
                                'Please enter seat number for every passenger.'
                            );

                            button.disabled = false;
                            button.style.opacity = '1';
                            button.style.cursor = 'pointer';

                            return;
                        }


                        const cartData = {

                            items: cart,

                            totals: totals,

                            passenger_count: passengerCount,

                            passenger_seats: passengerSeats

                        };


                        document.getElementById(
                                'cart_data'
                            ).value =
                            JSON.stringify(
                                cartData
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | COD
                        |--------------------------------------------------------------------------
                        */

                        if (
                            paymentMethod ===
                            'cod'
                        ) {

                            localStorage.removeItem(
                                'quickdines_cart'
                            );


                            localStorage.removeItem(
                                'quickdines_cart_totals'
                            );


                            form.submit();

                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Online Payment
                        |--------------------------------------------------------------------------
                        */

                        try {



                            const response =
                                await fetch(
                                    "{{ route('checkout.create-payment-order') }}", {

                                        method: 'POST',

                                        headers: {

                                            'Content-Type': 'application/json',

                                            'Accept': 'application/json',

                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                                        },

                                        body: JSON.stringify({

                                            passenger_name: form.querySelector(
                                                'input[name="passenger_name"]'
                                            ).value,

                                            phone_number: form.querySelector(
                                                'input[name="phone_number"]'
                                            ).value,

                                            gender: form.querySelector(
                                                'input[name="gender"]:checked'
                                            )?.value,


                                            passenger_count: passengerCount,

                                            passenger_seats: passengerSeats,

                                            cooking_instructions: form.querySelector(
                                                'input[name="cooking_instructions"]'
                                            ).value,

                                            cart_data: JSON.stringify(
                                                cartData
                                            )

                                        })

                                    }
                                );


                            const data =
                                await response.json();


                            if (
                                !response.ok ||
                                !data.success
                            ) {

                                throw new Error(
                                    data.message ||
                                    'Unable to start payment.'
                                );

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Razorpay
                            |--------------------------------------------------------------------------
                            */

                            const options = {

                                key: data.key_id,

                                amount: data.amount,

                                currency: data.currency,

                                name: 'QuickDines',

                                description: 'QuickDines Food Order',

                                image: "{{ asset('images/updatedlogo.jpg') }}",

                                order_id: data.razorpay_order_id,


                                handler: async function(response) {

                                    try {

                                        const verifyResponse =
                                            await fetch(
                                                "{{ route('checkout.verify-payment') }}", {

                                                    method: 'POST',

                                                    headers: {

                                                        'Content-Type': 'application/json',

                                                        'Accept': 'application/json',

                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'

                                                    },

                                                    body: JSON.stringify({

                                                        razorpay_order_id: response.razorpay_order_id,

                                                        razorpay_payment_id: response.razorpay_payment_id,

                                                        razorpay_signature: response.razorpay_signature

                                                    })

                                                }
                                            );


                                        const verifyData =
                                            await verifyResponse.json();


                                        if (
                                            !verifyResponse.ok ||
                                            !verifyData.success
                                        ) {

                                            throw new Error(
                                                verifyData.message ||
                                                'Payment verification failed.'
                                            );

                                        }


                                        localStorage.removeItem(
                                            'quickdines_cart'
                                        );


                                        localStorage.removeItem(
                                            'quickdines_cart_totals'
                                        );


                                        window.location.href =
                                            verifyData.redirect_url;

                                    } catch (error) {

                                        button.disabled =
                                            false;

                                        button.style.opacity =
                                            '1';

                                        button.style.cursor =
                                            'pointer';


                                        alert(
                                            error.message ||
                                            'Payment verification failed. Please contact support.'
                                        );

                                    }

                                },


                                prefill: {

                                    name: data.customer?.name ||
                                        'Customer',

                                    email: data.customer?.email ||
                                        '',

                                    contact: data.customer?.contact ||
                                        ''

                                },


                                theme: {

                                    color: '#2563eb'

                                },


                                modal: {
                                    ondismiss: function() {

                                        button.disabled = false;
                                        button.style.opacity = '1';
                                        button.style.cursor = 'pointer';

                                        // The customer backed out without paying:
                                        // release the pending order so it doesn't
                                        // sit around forever.
                                        fetch("{{ route('checkout.cancelPayment') }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                razorpay_order_id: data.razorpay_order_id
                                            })
                                        }).catch(function() {});

                                    }
                                }

                            };


                            const rzp =
                                new Razorpay(
                                    options
                                );




                            rzp.on(
                                'payment.failed',
                                function(response) {

                                    button.disabled = false;
                                    button.style.opacity = '1';
                                    button.style.cursor = 'pointer';

                                    fetch("{{ route('checkout.cancelPayment') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            razorpay_order_id: data.razorpay_order_id
                                        })
                                    }).catch(function() {});

                                    alert(
                                        'Payment failed: ' +
                                        (
                                            response.error?.description ||
                                            'Unknown error'
                                        )
                                    );

                                }
                            );


                            rzp.open();


                        } catch (error) {

                            button.disabled =
                                false;

                            button.style.opacity =
                                '1';

                            button.style.cursor =
                                'pointer';


                            alert(
                                error.message ||
                                'Unable to start payment. Please try again.'
                            );

                        }

                    }
                );

        });
</script>

@endsection
