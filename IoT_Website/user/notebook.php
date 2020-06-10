<!DOCTYPE html>
<html>
    <head>
        <?php
        
            include("header.php");
            include("config.php");
            include("mysql.php");
            session_start();
            $username = $_SESSION['username'];
            $ma_name = $_SESSION['Ma_name'];
            $commd = "select * from information where ma_name='$ma_name'";
            $result = mysqli_query($db,$commd);
        ?>
        <title><?php echo($titlename); ?></title>
    </head>
    <body background=<?php echo($background); ?> class="mdui-drawer-body-left mdui-drawer-body-right">
        <center>
            <h2>
                以下为设备<?php echo($ma_name); ?>的工作日志
            </h2>
                <div class="mdui-table-fluid">
                  <table class="mdui-table mdui-table-hoverable">
                    <thead>
                        <tr>
                            <th>设备编号</th>
                            <th>动作</th>
                            <th>时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            while ($row = mysqli_fetch_row($result))
                                {
                                    echo("<tr>");
                                    foreach($row as $row1){
                                        echo("<th>$row1</th>");
                                    }
                                    echo("</tr>");
                                }
                        
                        ?>
                    </tbody>
        </center>
    </body>
</html>