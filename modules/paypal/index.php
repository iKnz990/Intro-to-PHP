<?php
include '../../core/header.php';
?>

<div class="content-container">
    <div class="content">
        <div class="paypal-content-container">
            <div class="paypal-content">
                <h1 class="paypal-title">Subscribe to Monthly Content Audits</h1>

                <!-- Filler Image -->
                <img src="https://via.placeholder.com/300" alt="Monthly Content Audits">

                <!-- PayPal Subscription Button -->
                <div id="paypal-button-container-P-5WH3190965219494RMUVPDRA"></div>

                <!-- PayPal SDK -->
                <script
                    src="https://www.paypal.com/sdk/js?client-id=AZWXOctP-VgENxbnKpKaXuqfeyGoJCHmv0m62rwhGBKagJF6ickBD8ZL49WKOZlFxHCkrqP4BL2o-TJG&vault=true&intent=subscription"
                    data-sdk-integration-source="button-factory"></script>

                <!-- PayPal Button Configuration -->
                <script>
                    paypal.Buttons({
                        style: {
                            shape: 'rect',
                            color: 'silver',
                            layout: 'horizontal',
                            label: 'paypal'
                        },
                        createSubscription: function (data, actions) {
                            return actions.subscription.create({
                                /* Creates the subscription */
                                plan_id: 'P-5WH3190965219494RMUVPDRA'
                            });
                        },
                        onApprove: function (data, actions) {
                            alert(data.subscriptionID); // You can add optional success message for the subscriber here
                        }
                    }).render('#paypal-button-container-P-5WH3190965219494RMUVPDRA'); // Renders the PayPal button
                </script>
            </div>
        </div>
        Note: This is a live subscription -- if you proceed with payment you will be charged.
    </div>
</div>
<?php
include '../../core/footer.php';
?>