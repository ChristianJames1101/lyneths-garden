<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $customerName = $_POST['customer_name'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO cancellation_requests (order_id, customer_name, reason, status, request_date) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("iss", $orderId, $customerName, $reason);

    if ($stmt->execute()) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <title>Cancellation Request</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Request Submitted',
                text: 'Your cancellation request has been sent successfully.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'home.php?page=orders';
            });
        </script>
        </body>
        </html>
        <?php
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <title>Error</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: <?= json_encode("Error: " . $stmt->error) ?>,
                confirmButtonText: 'Go Back'
            }).then(() => {
                window.history.back();
            });
        </script>
        </body>
        </html>
        <?php
    }
}
?>
