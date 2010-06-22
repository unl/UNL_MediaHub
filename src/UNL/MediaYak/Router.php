<?php
class UNL_MediaYak_Router
{
    public static function getRoute($requestURI)
    {
        var_dump($requestURI);

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr(
                $requestURI,
                0,
                -strlen($_SERVER['QUERY_STRING']) - 1
            );
        }

        $base       = UNL_MediaYak_Controller::getURL();
        $quotedBase = preg_quote($base, '/');

        $options = array();

        switch(true) {
            case preg_match('/'.$quotedBase.'channels\/$/', $requestURI, $matches):
                $options['view'] = 'channels';
                break;
            case preg_match('/'.$quotedBase.'search\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'search';
                $options['q']    = $matches[1];
                break;
            case preg_match('/'.$quotedBase.'media\/([0-9]+)$/', $requestURI, $matches):
                $options['view'] = 'media';
                $options['id']   = $matches[1];
                break;
            case preg_match('/'.$quotedBase.'channels\/(.+)\/image$/', $requestURI, $matches):
                $options['view']  = 'channel_image';
                $options['title'] = urldecode($matches[1]);
                break;
            case preg_match('/'.$quotedBase.'channels\/(.*)$/', $requestURI, $matches):
                $options['view'] = 'channels';
                if (isset($matches[1])) {
                    $options['title'] = $matches[1];
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