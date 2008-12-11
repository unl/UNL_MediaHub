<?php
if (count($this->items)) {
    foreach ($this->items as $feed) {
        echo $feed->title;
    }
} else {
    echo 'Sorry, you have no channels/feeds. Would you like to create one?';
}