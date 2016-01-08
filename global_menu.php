<?php
/**
 * Created by PhpStorm.
 * User: ukito
 * Date: 15/12/29
 * Time: 21:55
 */
?>
<div id="header">
    <div class="container">
        <div class="logo">
            <a href="index.php">
                <img class="logo menu" src="img/logo.png" alt="未決定">
            </a>
        </div>
        <div class="main_menu">
            <nav id="main_menu">
                <ul class="right_menu">
                    <li class="menu">
                        <a href="">about</a>
                    </li>
                    <li class="menu">
                        <a href="cookie_cart.php">category</a>
                    </li>
                    <li class="menu">
                        <a href="">contact</a>
                    </li>
                    <?php
                    if ($_SESSION["member"]) {
                        echo <<<EOT
                          <li class="menu">
                            <a href="logout.php">logout</a>
                          </li>
EOT;
                    } else {
                            echo <<<EOT
                        <li class="menu">
                            <a href="join.php">join</a>
                        </li>
                        <li class="menu">
                            <a href="login.php">login</a>
                        </li>
EOT;
                    }
                    ?>
                    <li class="menu">
                        <a href="look_cart.php">cart</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
