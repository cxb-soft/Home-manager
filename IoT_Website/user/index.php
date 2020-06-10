<!DOCTYPE html>
<html>
    <head>
        <?php
            include("config.php");
            include("mysql.php");
            include("header.php");
            session_start();
            $username = $_SESSION['username'];
            $ma_name = $_SESSION['Ma_name'];
        ?>
        <title><?php echo($titlename); ?></title>
    </head>
    <body background=<?php echo($background); ?> class="mdui-drawer-body-left mdui-drawer-body-right" >
        <center>
            <h2>
                欢迎您,<?php echo($username); ?>!
                <br />
                您的设备编号是：<?php echo($ma_name); ?>
            </h2>
        </center>
    </body>
</html>