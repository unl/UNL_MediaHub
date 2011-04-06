<?php
class UNL_MediaYak_Router
{
    public static function getRoute($requestURI)
    {

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen(urldecode($_SERVER['QUERY_STRING'])) - 1);
        }

        // Trim the base part of the URL
        $requestURI = substr($requestURI, strlen(parse_url(UNL_MediaYak_Controller::getURL(), PHP_URL_PATH)));

        $options = array();

        if (empty($requestURI)) {
            // Default view/homepage
            return $options;
        }

        switch (true) {
            case preg_match('/^developers\/?$/', $requestURI):
                $options['view'] = 'dev';
                break;
            case preg_match('/^search\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'search';
                $options['q']    = urldecode($matches[1]);
                break;
			case preg_match('/^tags\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'tags';
                $options['t']    = urldecode($matches[1]);
                break;
            case preg_match('/^media\/([0-9]+)$/', $requestURI, $matches):
                $options['view'] = 'media';
                $options['id']   = $matches[1];
                break;
            case preg_match('/^channels\/$/', $requestURI):
                $options['view'] = 'feeds';
                break;
            // Live stream view for a channel
            case preg_match('/^channels\/(.+)\/live$/', $requestURI, $matches):
                $options['view'] = 'live';
                if (preg_match('/^[\d]+$/', $matches[1])) {
                    $options['feed_id'] = $matches[1];
                } else {
                    $options['title'] = urldecode($matches[1]);
                }
                break;
            case preg_match('/^channels\/(.+)\/image(\.[\w]+)?$/', $requestURI, $matches):
                $options['view'] = 'feed_image';
                if (preg_match('/^[\d]+$/', $matches[1])) {
                    $options['feed_id'] = $matches[1];
                } else {
                    $options['title'] = urldecode($matches[1]);
                }
                break;
            case preg_match('/^channels\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'feed';
                if (preg_match('/^[\d]+$/', $matches[1])) {
                    $options['feed_id'] = $matches[1];
                } else {
                    $options['title'] = urldecode($matches[1]);
                }
                break;
            default:
                $options['view'] = 'unknown';
        }
        return $options;
    }
}