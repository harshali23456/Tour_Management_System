<?php
session_start();
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors
include('includes/config.php');

$msg = ""; // Initialize $msg
$error = ""; // Initialize $error

if (isset($_POST['confirm_booking'])) {
    // Get booking details from the form
    $pid = intval($_GET['pkgid']);
    $useremail = $_SESSION['login'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $comment = $_POST['comment'];
    $travel_vehicles = $_POST['travel_vehicles'];
    $driver = $_POST['driver'] === "Yes" ? 'Yes' : 'No';
    $guide = $_POST['guide'] === "Yes" ? 'Yes' : 'No';
    $hotel_booking_preference = $_POST['hotel_booking_preference'];
    $aemail = $_POST['aemail'];

    //price
    $sql = "SELECT PackagePrice, Discount FROM tbltourpackages WHERE PackageId=:pid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pid', $pid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $packagePrice = $result->PackagePrice - ($result->Discount / 100) * $result->PackagePrice;

    // Store booking details in session
    $_SESSION['booking_details'] = [
        'pid' => $pid,
        'useremail' => $useremail,
        'fromdate' => $fromdate,
        'todate' => $todate,
        'comment' => $comment,
        'travel_vehicles' => $travel_vehicles,
        'driver' => $driver,
        'guide' => $guide,
        'hotel_booking_preference' => $hotel_booking_preference,
        'aemail' => $aemail,
        'packagePrice' => $packagePrice // Save package price in session
    ];

    // Redirect to Razorpay checkout form
    header('Location: razorpay_checkout.php');
    exit();
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Yatra | Package Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="applijewelleryion/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); 
        function hideURLbar(){ window.scrollTo(0,1); } 
    </script>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.css" />
    <script>
        new WOW().init();
    </script>
    <script src="js/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#datepicker,#datepicker1").datepicker();
        });
    </script>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .discounted-price {
            color: red;
            font-weight: bold;
        }

        .original-price {
            text-decoration: line-through;
        }
    </style>
</head>

<body>
    <!-- top-header -->
    <?php include('includes/header.php'); ?>
    <div class="banner-3">
        <div class="container">
            <h1 class="wow zoomIn animated animated" data-wow-delay=".5s"
                style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> TMS - Package Details</h1>
        </div>
    </div>
    <!--- /banner ---->
    <!--- selectroom ---->
    <div class="selectroom">
        <div class="container">
            <?php
            $pid = intval($_GET['pkgid']);
            $sql = "SELECT * FROM tbltourpackages WHERE PackageId=:pid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pid', $pid, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            if ($query->rowCount() > 0) {
                foreach ($results as $result) { ?>

                    <form name="book" method="post">
                        <div class="selectroom_top">
                            <div class="col-md-4 selectroom_left wow fadeInLeft animated" data-wow-delay=".5s">
                                <img src="admin/pacakgeimages/<?php echo htmlentities($result->PackageImage); ?>"
                                    class="img-responsive" alt="">
                            </div>
                            <div class="col-md-8 selectroom_right wow fadeInRight animated" data-wow-delay=".5s">
                                <h2><?php echo htmlentities($result->PackageName); ?></h2>
                                <p class="dow">#PKG-<?php echo htmlentities($result->PackageId); ?></p>
                                <p><b>Package Type :</b> <?php echo htmlentities($result->PackageType); ?></p>
                                <p><b>Package Location :</b> <?php echo htmlentities($result->PackageLocation); ?></p>
                                <p><b>Features :</b> <?php echo htmlentities($result->PackageFetures); ?></p>
                                <div class="ban-bottom">
                                    <div class="bnr-right">
                                        <label class="inputLabel">From</label>
                                        <input class="date" id="datepicker" type="text" placeholder="dd-mm-yyyy" name="fromdate"
                                            required="">
                                    </div>
                                    <div class="bnr-right">
                                        <label class="inputLabel">To</label>
                                        <input class="date" id="datepicker1" type="text" placeholder="dd-mm-yyyy" name="todate"
                                            required="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="grand">
                                    <p>Grand Total</p>
                                    <?php if ($result->Discount > 0) { ?>
                                        <h3 class="discounted-price"><?php echo htmlentities($result->Discount); ?>% OFF</h3>
                                        <h4 class="original-price">₹ <?php echo htmlentities($result->PackagePrice); ?></h4>
                                        <h3 class="discounted-price">₹
                                            <?php echo htmlentities($result->PackagePrice - ($result->PackagePrice * $result->Discount / 100)); ?>
                                        </h3>
                                    <?php } else { ?>
                                        <h3>₹ <?php echo htmlentities($result->PackagePrice); ?></h3>
                                    <?php } ?>

                                </div>
                            </div>
                            <h3>Package Details</h3>
                            <p style="padding-top: 1%"><?php echo htmlentities($result->PackageDetails); ?> </p>
                            <div class="clearfix"></div>
                        </div>

                        <div class="selectroom_top">
                            <h2>Travels</h2>
                            <div class="selectroom-info animated wow fadeInUp animated" data-wow-duration="1200ms"
                                data-wow-delay="500ms"
                                style="visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp; margin-top: -70px">
                                <ul>
                                    <li class="spe">
                                        <label class="inputLabel">Select Travel Vehicles</label><br>
                                        <input type="radio" id="car" name="travel_vehicles" value="car" required>
                                        <label for="car">Car</label>
                                        <input type="radio" id="train" name="travel_vehicles" value="train">
                                        <label for="train">Train</label>
                                        <input type="radio" id="plane" name="travel_vehicles" value="plane">
                                        <label for="plane">Plane</label>
                                    </li>
                                    <li class="spe">
                                        <label class="inputLabel">Require Driver?</label><br>
                                        <select class="special" name="driver">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </li>
                                    <li class="spe">
                                        <label class="inputLabel">Require Guide?</label><br>
                                        <select class="special" name="guide">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </li>
                                    <li class="spe">
                                        <label class="inputLabel">Hotel Booking Preference</label><br>
                                        <select class="special" name="hotel_booking_preference">
                                            <option value="from_us">Book Hotel from Us</option>
                                            <option value="manual">Book Hotel Manually</option>
                                        </select>
                                    </li>
                                    <li class="spe">
                                        <label class="inputLabel">Alternate Email</label>
                                        <input class="special" type="email" name="aemail" required="">
                                    </li>
                                    <li class="spe">
                                        <label class="inputLabel">Comment</label>
                                        <input class="special" type="text" name="comment" required="">
                                    </li>
                                    <?php if (isset($_SESSION['login'])) { ?>
                                        <li class="spe" align="center">
                                            <button type="submit" name="confirm_booking" class="btn-primary btn">Pay & Book</button>
                                        </li>
                                    <?php } else { ?>
                                        <li class="sigi" align="center" style="margin-top: 1%">
                                            <a href="#" data-toggle="modal" data-target="#myModal4" class="btn-primary btn">Book</a>
                                        </li>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                </ul>
                            </div>
                        </div>

                    </form>
                <?php }
            } ?>
        </div>
    </div>
    <!--- /selectroom ---->
    <!--- /footer-top ---->
    <?php include('includes/footer.php'); ?>
    <!-- signup -->
    <?php include('includes/signup.php'); ?>
    <!-- //signup -->
    <!-- signin -->
    <?php include('includes/signin.php'); ?>
    <!-- //signin -->
    <!-- write us -->
    <?php include('includes/write-us.php'); ?>
</body>

</html>
