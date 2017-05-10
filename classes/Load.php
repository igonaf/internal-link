<?php

require_once ('LinksChecking.php');
require_once ('Settings.php');

/**
 * Class Load
 */
class Load
{
    /**
     * Load objects: settings and parse
     */
    public function __construct()
    {
        new LinksChecking();
        new Settings();
    }
}