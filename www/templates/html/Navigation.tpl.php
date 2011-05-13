<ul>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>">MediaHub</a>
    <?php
    if (!$controller->isLoggedIn()) {
        echo '<ul><li><a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaHub_Controller::getURL()).'">Login</a></li></ul>';
    } else {
        echo '<ul><li><a href="'.UNL_MediaHub_Controller::getURL().'manager/">Your Media</a></li></ul>';
        echo '<ul><li><a href="?logout">Logout</a></li></ul>';
    }
    ?>
    </li>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/">Channels</a></li>
</ul>