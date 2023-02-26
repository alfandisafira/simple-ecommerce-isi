@extends('template')

@section('content')

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- Customer Identity -->
            <div class="col-md-6">
                <!-- Billing Details -->
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Billing address</h3>
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" id="fullName" placeholder="Full Name">
                    </div>
                    <div class="form-group">
                        <input class="input" type="email" id="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" id="address" placeholder="Address">
                    </div>
                    <div class="form-group">
                        <input class="input" type="tel" id="phoneNumber" placeholder="Telephone">
                    </div>
                </div>
                <!-- /Billing Details -->

            </div>
            <!-- /Customer Identity -->

            <!-- Order Details -->
            <div class="col-md-6 order-details">
                <div class="section-title text-center">
                    <h3 class="title">Your Order</h3>
                </div>
                <div class="order-summary">
                </div>
            </div>
            <!-- /Order Details -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

@endsection