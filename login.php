<?php
include('./constant/layout/head.php');
include('./constant/connect.php');

session_start();

$errors = array();

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        if (empty($username)) {
            $errors[] = "Username is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }
    } else {
        // Prepare the SQL statement
        $stmt = $connect->prepare("SELECT user_id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $stored_password);
            $stmt->fetch();
            
            // Compare the entered password with the stored password
            if ($password === $stored_password) {
                $_SESSION['userId'] = $user_id;
                // Regenerate session ID
                session_regenerate_id(true);
                
                echo '<div class="popup popup--icon -success js_success-popup popup--visible">
                        <div class="popup__background"></div>
                        <div class="popup__content">
                            <h3 class="popup__content__title">Success</h3>
                            <p>Login Successfully</p>
                            <p>' . '<script>setTimeout(function(){window.location.href = "dashboard.php";},1500);</script>' . '</p>
                        </div>
                      </div>';
            } else {
                echo '<div class="popup popup--icon -error js_error-popup popup--visible">
                        <div class="popup__background"></div>
                        <div class="popup__content">
                            <h3 class="popup__content__title">Error</h3>
                            <p>Incorrect username/password combination</p>
                            <p><a href="login.php"><button class="button button--error" data-for="js_error-popup">Close</button></a></p>
                        </div>
                      </div>';
            }
        } else {
            echo '<div class="popup popup--icon -error js_error-popup popup--visible">
                    <div class="popup__background"></div>
                    <div class="popup__content">
                        <h3 class="popup__content__title">Error</h3>
                        <p>Username does not exist</p>
                        <p><a href="login.php"><button class="button button--error" data-for="js_error-popup">Close</button></a></p>
                    </div>
                  </div>';
        }
        $stmt->close();
    }
}
?>

<div id="main-wrapper">
    <div class="unix-login">
        <div class="container-fluid" style="background-image: url('assets/myimages/background.jpg'); background-color: #ffffff; background-size: cover;">
            <div class="row">
                <div class="col-lg-4 ml-auto">
                    <div class="login-content">
                        <div class="login-form">
                            <center><img src="./assets/uploadImage/Logo/logo.png" style="width: 100%;"></center><br>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="loginForm">
                                <div class="form-group">
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                                </div>
                                <button type="submit" name="login" class="f-w-600 btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button>
                                <div class="forgot-phone text-left f-left">
                                    <a href="mailto:mayuri.infospace@gmail.com?subject=Project Development Requirement&body=I saw your projects. I want to develop a project" class="text-right f-w-600">Click here to contact me</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/lib/jquery/jquery.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/popper.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="./assets/js/jquery.slimscroll.js"></script>
<script src="./assets/js/sidebarmenu.js"></script>
<script src="./assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="./assets/js/custom.min.js"></script>
<script>
    function onReady(callback) {
        var intervalID = window.setInterval(checkReady, 1000);
        function checkReady() {
            if (document.getElementsByTagName('body')[0] !== undefined) {
                window.clearInterval(intervalID);
                callback.call(this);
            }
        }
    }

    function show(id, value) {
        document.getElementById(id).style.display = value ? 'block' : 'none';
    }

    onReady(function () {
        show('page', true);
        show('loading', false);
    });
</script>
</body>
</html>
