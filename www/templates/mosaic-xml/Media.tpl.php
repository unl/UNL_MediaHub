<video>
    <title><?php echo htmlspecialchars($context->title); ?></title>
    <id><?php echo $context->id ?></id>
    <description>
        <?php echo strip_tags($context->description); ?>
    </description>
    <?php 
    //Generate the correct date format, they want UTC
    $datetime = new DateTime($context->datecreated);
    $datetime->setTimezone(new DateTimeZone('UTC'));
    ?>
    <date><?php echo $datetime->format('Y-m-d\TH:i:s') ?>Z</date>
    <thumbnail_url><?php echo $context->getThumbnailURL() ?></thumbnail_url>
    <content_url><?php echo $context->url ?></content_url>
    <num_views><?php echo $context->play_count ?></num_views>
    <author><?php echo $context->uidcreated ?></author>
    <provider>UNL MediaHub</provider>
</video>
