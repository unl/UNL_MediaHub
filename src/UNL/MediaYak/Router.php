<?php
class UNL_MediaYak_Router
{
    public static function getRoute($requestURI)
    {

        $base       = UNL_MediaYak_Controller::getURL();
        if (filter_var($base, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
            $base = parse_url($base, PHP_URL_PATH);
        }
        $quotedBase = preg_quote($base, '/');

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen(urldecode($_SERVER['QUERY_STRING'])) - 1);
        }

        $options = array();

        switch(true) {
            case preg_match('/'.$quotedBase.'search\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'search';
                $options['q']    = $matches[1];
                break;
            case preg_match('/'.$quotedBase.'media\/([0-9]+)$/', $requestURI, $matches):
                $options['view'] = 'media';
                $options['id']   = $matches[1];
                break;
            case preg_match('/'.$quotedBase.'channels\/(.+)\/image$/', $requestURI, $matches):
                $options['view']  = 'feed_image';
                if (preg_match('/^[\d]+$/', $matches[1])) {
                    $options['feed_id'] = $matches[1];
                } else {
                    $options['title'] = urldecode($matches[1]);
                }
                break;
            case preg_match('/'.$quotedBase.'channels\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'feed';
                if (preg_match('/^[\d]+$/', $matches[1])) {
                    $options['feed_id'] = $matches[1];
                } else {
                    $options['title'] = urldecode($matches[1]);
                }
                break;
            // Index page
            case preg_match('/'.$quotedBase.'$/', $requestURI, $matches):
                break;
            default:
                throw new Exception('Unknown route: '.$requestURI);
                break;
        }
        return $options;
    }
}