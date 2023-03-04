<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Bootstrap core CSS -->
        <link
            href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />

        <!-- Mandatory js duitku bundle  -->
        <!-- Switch if using sandbox / production  -->
        <script src="https://app-sandbox.duitku.com/lib/js/duitku.js"></script>
        <!--<script src="https://app-prod.duitku.com/lib/js/duitku.js"></script>-->
        <!-- Bootstrap core JavaScript
		================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"
        ></script>
    </head>

    <body>
        <div class="col-md-6 col-lg-4 offset-lg-4 offset-md-3">
            <div class="bg-light p-4 border shadow">
                <!-- Form -->
                <form>
                    <center>
                        <h4>Duitku Pop</h4>
                    </center>
                    <br />

                    <div class="mb-2">
                        <label>Amount</label>
                        <input
                            required
                            id="amount"
                            min="1"
                            value="10000"
                            type="number"
                            class="form-control"
                            placeholder="10000"
                        />
                    </div>

                    <div class="mb-2">
                        <label>Product Detail</label>
                        <input
                            required
                            id="productDetail"
                            value="Sepatu Trendy"
                            type="text"
                            class="form-control"
                            placeholder="Sepatu Trendy"
                        />
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input
                            required
                            id="email"
                            value="customer@duitku.com"
                            type="text"
                            class="form-control"
                            placeholder="customer@duitku.com"
                        />
                    </div>

                    <div class="mb-2">
                        <label>Phone Number</label>
                        <input
                            required
                            id="phoneNumber"
                            value="08123456789"
                            type="number"
                            class="form-control"
                            placeholder="08123456789"
                        />
                    </div>

                    <div class="mb-2">
                        <label>Payment Interface</label>
                        <select id="paymentUi" class="form-control">
                            <option value="1">PopUp UI</option>
                            <option value="2">Redirect UI</option>
                        </select>
                    </div>

                    <button
                        type="button"
                        id="submit"
                        class="btn btn-primary w-100 my-2 shadow"
                        onClick="payment();"
                    >
                        Purchase
                    </button>
                </form>
                <!-- Form -->
            </div>
        </div>

        <!-- Request to backend with ajax (example)  -->
        <script type="text/javascript">
            function payment() {
                var amount = document.getElementById("amount").value;
                var productDetail =
                    document.getElementById("productDetail").value;
                var email = document.getElementById("email").value;
                var phoneNumber = document.getElementById("phoneNumber").value;
                var paymentUi = document.getElementById("paymentUi").value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        // Parameter PaymentMethod is optional
                        // paymentMethod: '', // PaymentMethod list => https://docs.duitku.com/pop/id/#payment-method
                        paymentAmount: amount,
                        productDetail: productDetail,
                        email: email,
                        phoneNumber: phoneNumber,
                    },
                    url: "checkout",
                    dataType: "json",
                    cache: false,
                    success: function (result) {
                        alert(result);
                        console.log(result.reference);
                        console.log(result);

                        if (paymentUi === "2") {
                            // user redirect payment interface
                            window.location = result.paymentUrl;
                        }

                        checkout.process(result.reference, {
                            successEvent: function (result) {
                                // begin your code here
                                console.log("success");
                                console.log(result);
                                alert("Payment Success");
                            },
                            pendingEvent: function (result) {
                                // begin your code here
                                console.log("pending");
                                console.log(result);
                                alert("Payment Pending");
                            },
                            errorEvent: function (result) {
                                // begin your code here
                                console.log("error");
                                console.log(result);
                                alert("Payment Error");
                            },
                            closeEvent: function (result) {
                                // begin your code here
                                console.log(
                                    "customer closed the popup without finishing the payment"
                                );
                                console.log(result);
                                alert(
                                    "customer closed the popup without finishing the payment"
                                );
                            },
                        });
                    },
                });
            }
        </script>
    </body>
</html>
