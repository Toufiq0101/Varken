$(document).on("change", "#notify_me", function () {
    Notification.requestPermission(function (status) {
        console.log('Notification permission status:', status);
    });
});
// function displayNotification() {
//     if (Notification.permission == 'granted') {
//         navigator.serviceWorker.getRegistration().then(function (reg) {
//             reg.showNotification('Hello world!');
//         });
//     }
// }
let no_of_orders = 0;
function displayNotification() {
    if (Notification.permission == 'granted') {
        $.ajax({
            url: "./control/check_orders.php",
            success: function (data) {
                console.log(data,no_of_orders);
                if (no_of_orders !== data && data !== 0&&data>1) {
                    navigator.serviceWorker.getRegistration().then(function (reg) {
                        var options = {
                            body: `${no_of_orders-1} customers are Waiting..!`,
                            icon: 'images/example.png',
                            vibrate: [100, 50, 100],
                            data: {
                                dateOfArrival: Date.now(),
                                primaryKey: 1
                            }
                        };
                        reg.showNotification('Hello world!', options);
                    });
                    no_of_orders = data;
                };
            }
        });
        setTimeout(displayNotification, 10000);
    }
};
displayNotification();