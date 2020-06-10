<!DOCTYPE html>
<html>
    <head>
        <?php
            
            include("header.php");
            include("config.php");
            include("mysql.php");
        
        ?>
        <title><?php  echo($titlename); ?></title>
    </head>
    <script>
        function gosnackbar(values){
                    mdui.snackbar({
                        message: values
                    });
                }
    </script>
    <body background=<?php echo($background); ?>>
        <?php
        
            if(isset($_POST['gogo'])){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $commd="select * from users where username='$username'";
                $result = mysqli_query($db,$commd);
                $result = mysqli_fetch_row($result);
                if(empty($result)){
                    echo
                        <<<EOF
                        <script>
                            gosnackbar("没有这个用户，点击主页“注册”进行注册");
                        </script>
EOF;
                }
                else{
                    $password_correct = $result[1];
                    if($password_correct == $password){
                        session_start();
                        $ma_name = $result[2];
                        $_SESSION['username'] = $username;
                        $_SESSION['Ma_name'] = $ma_name;
                        echo("<script>gosnackbar('身份验证成功')</script>");
                        header("Refresh:5;url='./user/index.php'");
                    }
                    else{
                        echo("<script>gosnackbar('密码不正确，身份验证失败')</script>");
                    }
                }
            }
            if(isset($_POST['sign_up'])){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $password_con = $_POST['password-1'];
                $ma_name = $_POST['ma_name'];
                $e_mail = $_POST['e_mail'];
                if(empty($username)){
                    echo("<script>gosnackbar('用户名不能为空')</script>");
                }
                if(empty($password)){
                    echo("<script>gosnackbar('密码不能为空')</script>");
                }
                if(empty($ma_name)){
                    echo("<script>gosnackbar('设备名称不能为空')</script>");
                }
                if(empty($e_mail)){
                    echo("<script>gosnackbar('邮箱不能为空')</script>");
                }
                if($password != $password_con){
                    echo("<script>gosnackbar('二次输入密码不一致')</script>");
                }
                if(!empty($username) && !empty($password) && !empty($password_con) && !empty($ma_name) && !empty($e_mail)){
                    $commd = "select * from users where username='$username'";
                    $result = mysqli_query($db,$commd);
                    if(empty(mysqli_fetch_assoc($result))){
                        echo("<script>gosnackbar('验证通过，可以注册')</script>");
                        $commd = "insert into users values('$username','$password','$ma_name','$e_mail')";
                        mysqli_query($db,$commd);
                    }
                    else{
                        echo("<script>gosnackbar('该用户已存在，请更换用户名重试或直接登录')</script>");
                    }
                }
            }
        
        ?>
        <div class="mdui-tab mdui-tab-centered" mdui-tab>
          <a href="#first-page" class="mdui-ripple">首页</a>
          <a href="#login_go" class="mdui-ripple" mdui-dialog="{target: '#login-go'}">登录</a>
          <a href="#reg" class="mdui-ripple" mdui-dialog="{target: '#reg-go'}">注册</a>
          <a href="#insss" class="mdui-ripple">关于XUSOFT</a>
        </div>
        <div id="first-page" class="mdui-p-a-2">
            <center>
                <h2>欢迎来到家庭信息管理系统</h2>
            </center>
        </div>
        <div id="login_go" class="mdui-p-a-2">
            <div class="mdui-dialog" id="login-go">
                <center>
                    <div class="mdui-dialog-title"><center>LOGIN</center></div>
                    <div class="mdui-dialog-content">
                        <div class="mdui-chip">
                          <img class="mdui-chip-icon" src="https://cn.bing.com/th?id=OIP.JkM2SKbWPGWJCfTTidZfXAHaEb&pid=Api&rs=1"/>
                          <span class="mdui-chip-title">欢迎登录家庭信息管理系统</span>
                        </div>
                    </div>
                    <form name="form-1" action="./index.php" method="post">
                        <div class="mdui-container">
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">用户名</label>
                            <input class="mdui-textfield-input" name="username" type="text"/>
                          </div>
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">密码</label>
                            <input class="mdui-textfield-input" name="password" type="password"/>
                          </div>
                        </div>
                        <button class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple" type="submit" name="gogo">LOGIN</button>
                        <br /><br />
                    </form>
                </center>
            </div>
        </div>
        <div id="reg" class="mdui-p-a-2">
            <div class="mdui-dialog" id="reg-go">
                <center>
                    <div class="mdui-dialog-title"><center>Sign up</center></div>
                    <div class="mdui-dialog-content">
                        <div class="mdui-chip">
                          <img class="mdui-chip-icon" src="https://cn.bing.com/th?id=OIP.JkM2SKbWPGWJCfTTidZfXAHaEb&pid=Api&rs=1"/>
                          <span class="mdui-chip-title">欢迎注册家庭信息管理系统</span>
                        </div>
                    </div>
                    <form name="form-1" action="./index.php" method="post">
                        <div class="mdui-container">
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">用户名</label>
                            <input class="mdui-textfield-input" name="username" type="text"/>
                          </div>
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">密码</label>
                            <input class="mdui-textfield-input" name="password" type="password"/>
                          </div>
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">确认密码</label>
                            <input class="mdui-textfield-input" name="password-1" type="password"/>
                          </div>
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">设备名称</label>
                            <input class="mdui-textfield-input" name="ma_name" type="text"/>
                          </div>
                          <div class="mdui-textfield mdui-textfield-floating-label">
                            <label class="mdui-textfield-label">E-mail</label>
                            <input class="mdui-textfield-input" name="e_mail" type="email"/>
                          </div>
                        </div>
                        <button class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple" type="submit" name="sign_up">Sign up</button>
                        <br /><br />
                    </form>
                </center>
            </div>
        </div>
    <!--<script src="//mdui-aliyun.cdn.w3cbus.com/source/dist/js/mdui.min.js"></script>-->
    </body>
    
</html>