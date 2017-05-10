<?php
/*
Plugin Name: internal links
Plugin URI:  https://github.com/igonaf/internal-link
Description: Simple tool for searching internal links in different post types
Version:     1.0
Author:      igonaf
Author URI:  https://github.com/igonaf
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once ('classes/Load.php');
new Load();