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
    // [podaż], [koszty zakupu], [popyt], [ceny sprzedaży], [koszty transportu]
        $brokerArray1 = new BrokerArray([7, 14, 15 ,25],
                                        [10, 12, 20, 24],
                                        [10, 35],
                                        [30, 25],
                                        [[8, 20], [12, 10], [15, 5], [16, 2]]
        );

        $brokerArray2 = new BrokerArray([7, 14, 15 ,22],
                                        [10, 12, 20, 24],
                                        [10, 28],
                                        [30, 25],
                                        [[8, 14], [12, 9], [10, 5], [16, 2]]
        );

        $brokerArray3 = new BrokerArray([9, 11, 10 ,22],
                                        [5, 7, 10, 24],
                                        [10, 22],
                                        [30, 21],
                                        [[8, 14], [12, 9], [10, 5], [16, 2]]
                                    );
    ?>
</body>
<footer>
    <div class="authors-div">
        <p class="author-title-p">Autorzy:</p>
        <div class="authors-container-div">
            <p class="author-p">Szymon Nowak <br>
                <a href="https://github.com/sznowak134" target="_blank">GitHub</a>
                <a href="https://www.linkedin.com/in/szymon-nowak-0669051b5/" target="_blank">LinkedIn</a></p>
            <p class="author-p">Radosław Niestrój <br>
                <a href="https://github.com/radoslawniestroj" target="_blank">GitHub</a>
                <a href="https://www.linkedin.com/in/rados%C5%82aw-niestr%C3%B3j-533901194/" target="_blank">LinkedIn</a></p>
        </div>
    </div>
</footer>
</html>

<script type="text/javascript">

</script>
