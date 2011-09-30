<ul>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>">MediaHub</a>
        <ul>
        <?php
        if (!$controller->isLoggedIn()) {
            echo '
            <li><a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaHub_Controller::getURL()).'">Login</a></li>';
        }
        ?>
        </ul>
    </li>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/">Channels</a></li>
    <?php if ($controller->isLoggedIn()) { ?>
    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>manager/">Your Media</a>
        <ul>
            <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>manager/">Add media</a></li>
            <li><a href="?logout">Logout</a></li>
        </ul>
    </li>
    <?php } ?>
</ul>