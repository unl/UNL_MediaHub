<ul>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>">MediaHub</a>
        <ul>
        <?php
        if (!$controller->isLoggedIn()) {
            echo '
            <li><a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaHub_Controller::getURL()).'">Login</a></li>';
        } else {
            echo '
            <li><a href="'.UNL_MediaHub_Controller::getURL().'manager/">Your Media</a></li>
            <li><a href="?logout">Logout</a></li>';
        }
        ?>
        </ul>
    </li>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/">Channels</a></li>
</ul>