<?php
/**
 * This file contains all the routes use within the application
 *
 * @see RegExpRouter
 */
$routes = array();

$routes['/^$/'] = 'UNL_MediaHub_DefaultHomepage';

$routes['/^developers\/?$/'] = 'UNL_MediaHub_Developers';

$routes['/^search\/(?P<q>.*)$/'] = 'UNL_MediaHub_MediaList';

$routes['/^tags\/(?P<t>.*)$/'] = 'UNL_MediaHub_MediaList';

$routes['/^media\/(?P<id>[0-9]+)\/(?P<format>vtt)$/'] = 'media_vtt';

$routes['/^media\/(?P<id>[0-9]+)\/image(\.[\w]+)?$/'] = 'media_image';

$routes['/^media\/(?P<id>[0-9]+)\/embed$/'] = 'media_embed';

$routes['/^media\/(?P<id>[0-9]+)\/file(\.[\w]+)?$/'] = 'media_file';

$routes['/^media\/(?P<id>[0-9]+)$/'] = 'media';

$routes['/^channels\/(?P<id>[\d]+)\/live$/'] = 'UNL_MediaHub_Feed_LiveStream';
$routes['/^channels\/(?P<title>.+)\/live$/'] = 'UNL_MediaHub_Feed_LiveStream';

$routes['/^channels\/(?P<feed_id>[\d]+)\/image(\.[\w]+)?$/'] = 'feed_image';
$routes['/^channels\/(?P<title>.+)\/image(\.[\w]+)?$/'] = 'feed_image';

$routes['/^channels\/(?P<feed_id>[\d]+)$/'] = 'UNL_MediaHub_FeedAndMedia';
$routes['/^channels\/(?P<title>.+)$/'] = 'UNL_MediaHub_FeedAndMedia';

$routes['/^channels\/$/'] = 'UNL_MediaHub_FeedList';

// Now all the ?view= routes
$routes += array(
    'search'  => 'UNL_MediaHub_MediaList',
    'tags'    => 'UNL_MediaHub_MediaList',
    'default' => 'UNL_MediaHub_DefaultHomepage',
    'feeds'   => 'UNL_MediaHub_FeedList',
    'feed'    => 'UNL_MediaHub_FeedAndMedia',
    'dev'     => 'UNL_MediaHub_Developers',
    'live'    => 'UNL_MediaHub_Feed_LiveStream',
);

return $routes;
