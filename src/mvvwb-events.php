<?php
/**
 * Entry point for registering post types, metaboxes, etc.
 */
/**
 * Plugin Name: MVVWB-Events
 * Description: Plugin, which allows the user to create and display events.
 * Version: {{version}}
 * Author: Florian Preinfalk
 * Author URI: http://www.preinfalk.co.at
 */

 /** Bootstrap everything */
include 'start.php';

MVVWB\Events\RegisterHelper::register();
