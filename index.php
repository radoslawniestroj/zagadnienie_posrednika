<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style/main_style.css"></link>
    <title>ZAGADNIENIE POŚREDNIKA</title>

    <div class="head-div">
    <img src="party-frog.gif" alt="this slowpoke moves" class="frog-gif">
    <h1 class="title main-title">zagadnienie pośrednika</h1>
    <img src="party-frog.gif" alt="this slowpoke moves" class="frog-gif">
    </div>

    <?php
        require_once __DIR__.'/Class/BrokerArray.php';
    ?>
</head>

<body>
    <?php
//        $brokerArray = new BrokerArray([20, 30, 20 ,30],
//                                        [10, 12, 10, 12],
//                                        [10, 28],
//                                        [30, 25],
//                                        [[8, 14], [12, 9], [8, 14], [12, 9]]
//                                    );
    $brokerArray = new BrokerArray([7, 14, 15 ,22],
                                    [10, 12, 20, 24],
                                    [10, 28],
                                    [30, 25],
                                    [[8, 14], [12, 9], [10, 5], [16, 2]]
    );
    ?>
</body>
</html>

<script type="text/javascript">

</script>
