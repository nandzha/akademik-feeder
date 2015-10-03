<?php
namespace Libraries\AppResources;
use Resources, Models;

class AppExtension extends \Twig_Extension
{

    public function __construct()
    {
        $this->c = new Resources\Controller;
        $this->m = new Models\Main;
        $this->getFilters();
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('assets_url', array($this, 'assetsUrl')),
            new \Twig_SimpleFilter('base_uri', array($this, 'baseUri')),
            new \Twig_SimpleFilter('location', array($this, 'location')),
            new \Twig_SimpleFilter('getVarOptions', array($this, 'getVarOptions')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function assetsUrl($string)
    {
         return $this->c->uri->baseUri."assets/".$string;
    }

    public function baseUri($string)
    {
        return $this->c->uri->baseUri.$string;
    }

    public function location($string)
    {
        return $this->c->location($string);
    }

    public function getVarOptions($string)
    {
        return $this->m->getVarOptions($string);
    }

    public function getName()
    {
        return 'app_extension';
    }
}