<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart Page</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/font-awesome.min.css">
</head>
<body id="page-top">
    <!-- Masthead -->
    <header class="masthead">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-10 align-self-end mb-4 page-title">
                    <h3 class="text-white ">[Cart List]</h3>
                    
                </div>
            </div>
        </div>
    </header>

    <!-- Cart Section -->
    <section class="page-section" id="menu">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="sticky">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8"><b>Cart</b></div>
                                    <div class="col-md-4 text-right"><b>Total</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    if (isset($_SESSION['login_user_id'])) {
                        $data = "WHERE c.user_id = '".$_SESSION['login_user_id']."' ";    
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $data = "WHERE c.client_ip = '".$ip."' ";    
                    }
                    $total = 0;
                    $get = $conn->query("SELECT *, c.id as cid FROM cart c INNER JOIN product_list p ON p.id = c.product_id " . $data);
                    while ($row = $get->fetch_assoc()):
                        $total += ($row['qty'] * $row['price']);
                    ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <a href="javascript:void(0)" class="rem_cart btn btn-sm btn-outline-danger" data-id="<?php echo $row['cid']; ?>"><i class="fa fa-trash"></i></a>
                                    <img src="assets/img/<?php echo $row['img_path']; ?>" alt="" class="img-fluid">
                                </div>
                                <div class="col-md-4">
                                    <p><b><?php echo $row['name']; ?></b></p>
                                    <p class="truncate"><b><small>Desc: <?php echo $row['description']; ?></small></b></p>
                                    <p><b><small>Unit Price: <?php echo number_format($row['price'], 2); ?></small></b></p>
                                    <p><small>QTY:</small></p>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary qty-minus" type="button" data-id="<?php echo $row['cid']; ?>"><i class="fa fa-minus"></i></button>
                                        </div>
                                        <input type="number" readonly value="<?php echo $row['qty']; ?>" min="1" class="form-control text-center" name="qty">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary qty-plus" type="button" data-id="<?php echo $row['cid']; ?>"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <b><?php echo number_format($row['qty'] * $row['price'], 2); ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="col-md-4">
                    <div class="sticky">
                        <div class="card">
                            <div class="card-body">
                                <p><b>Total Amount</b></p>
                                <hr>
                                <p class="text-right"><b><?php echo number_format($total, 2); ?></b></p>
                                <hr>
                                <div class="text-center">
                                    <button class="btn btn-block btn-outline-primary" type="button" id="checkout">Proceed to Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Styles -->
    <style>
        .card p {
            margin: 0;
        }
        .card img {
            max-width: 100%;
            max-height: 59%;
        }
        .sticky {
            position: sticky;
            top: 4.7em;
            z-index: 10;
            background: white;
        }
        .rem_cart {
            position: absolute;
            left: 0;
        }
    </style>

    <!-- Scripts -->
    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <script>
        // Remove product from cart
        $('.rem_cart').click(function() {
            const id = $(this).data('id');
            // Add code to handle remove action (e.g., AJAX request)
        });

        // Decrease quantity
        $('.qty-minus').click(function() {
            let qtyInput = $(this).closest('.input-group').find('input[name="qty"]');
            let qty = parseInt(qtyInput.val());
            if (qty > 1) {
                qtyInput.val(qty - 1);
                update_qty(qty - 1, $(this).data('id'));
            }
        });

        // Increase quantity
        $('.qty-plus').click(function() {
            let qtyInput = $(this).closest('.input-group').find('input[name="qty"]');
            let qty = parseInt(qtyInput.val());
            qtyInput.val(qty + 1);
            update_qty(qty + 1, $(this).data('id'));
        });

        // Update quantity in the database
        function update_qty(qty, id) {
            $.ajax({
                url: 'admin/ajax.php?action=update_cart_qty',
                method: "POST",
                data: { id: id, qty: qty },
                success: function(resp) {
                    if (resp == 1) {
                        // Reload cart or update cart total
                    }
                }
            });
        }

        // Checkout button click
        $('#checkout').click(function() {
            if ('<?php echo isset($_SESSION['login_user_id']) ? '1' : '0'; ?>' == '1') {
                location.replace("index.php?page=checkout");
            } else {
                // Open login modal
            }
        });
    </script>
</body>
</html>
