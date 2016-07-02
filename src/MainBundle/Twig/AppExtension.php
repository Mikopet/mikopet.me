<?php
/**
 * Created by PhpStorm.
 * User: mikopet
 * Date: 2016.07.02.
 * Time: 19:29
 */

namespace MainBundle\Twig;


class AppExtension extends \Twig_Extension
{
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('subdomain', array($this, 'subdomainPath')),
        );
    }

    public function subdomainPath($sub)
    {
        if (is_null($sub)) {
            $url = $this->param;
        } else {
            $url = '//'.$sub.'.'.$this->param;
        }
        return $url;
    }

    public function getName()
    {
        return 'app_extension';
    }
}