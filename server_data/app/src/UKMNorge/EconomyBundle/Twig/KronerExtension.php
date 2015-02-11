<?php
// src/UKMNorge/EconomyBundle/Twig/KronerExtension.php
namespace UKMNorge\EconomyBundle\Twig;


class KronerExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('kroner', array($this, 'kronerFilter')),
        );
    }

    public function kronerFilter($number, $decimals = 0, $decPoint = ',', $thousandsSep = ' ')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = ''.$price;

        return $price;
    }

    public function getName()
    {
        return 'kroner_extension';
    }
}