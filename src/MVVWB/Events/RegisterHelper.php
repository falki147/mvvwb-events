<?php

namespace MVVWB\Events;

class RegisterHelper {
    private static function init() {
        register_post_type('event', [
            'labels' => [
                'name'          => __('Events', 'mvvwb-events'),
                'singular_name' => __('Event', 'mvvwb-events'),
                'add_new'       => __('Add Event', 'mvvwb-events'),
                'edit_item'     => __('Edit Event', 'mvvwb-events')
            ],
            'public'            => false,
            'show_ui'           => true,
            'capability_type'   => 'post',
            'map_meta_cap'      => true,
            'hierarchical'      => false,
            'rewrite'           => false,
            'query_var'         => false,
            'show_in_nav_menus' => false,
            'delete_with_user'  => false,
            'supports'          => [ 'title' ],
            'show_in_rest'      => false,
            'menu_icon'         => 'dashicons-calendar-alt'
        ]);

        wp_register_script(
            'mvvwb-events-admin-js',
            MVVWB_EVENTS_BASE . 'admin.js',
            [ 'wp-blocks', 'wp-element', 'wp-data', 'jquery' ]
        );

        wp_enqueue_style('mvvwb-events-style', MVVWB_EVENTS_BASE . 'style.css');

        register_block_type('mvvwb/events', [
            'editor_script' => 'mvvwb-events-admin-js',
            'render_callback' => function ($attributes, $content ) {
                $events = array_map(function ($event) {
                    return [
                        'event' => EventHelper::getStartDate($event),
                        'title' => $event->post_title,
                        'content' => EventHelper::getFullString($event),
                        'additionalText' => EventHelper::getAdditionalText($event)
                    ];
                }, EventHelper::getEvents());
            
                ob_start();
                include MVVWB_EVENTS_VIEWS . 'EventsBlockView.php';
                return ob_get_clean();
            }
        ]);
    }

    private static function widgetsInit() {
        register_widget(new EventsWidget);
    }

    private static function addMetaBoxes() {
        EventMetabox::addMetabox();
    }

    private static function adminAddScripts() {
        wp_enqueue_script('mvvwb-events-admin-js');
        wp_enqueue_style('mvvwb-events-admin-css', MVVWB_EVENTS_BASE . 'admin.css', false, '1.0.0');
    }


    private static function saveMetaBoxes($postID) {
        EventMetabox::saveMetabox($postID, $_POST);
    }

    public static function register() {
        load_plugin_textdomain('mvvwb-events', false, MVVWB_EVENTS_TRANLATIONS);

        add_action('init', function () { self::init(); });
        add_action('widgets_init', function () { self::widgetsInit(); });
        add_action('add_meta_boxes', function () { self::addMetaBoxes(); });
        add_action('admin_enqueue_scripts', function () { self::adminAddScripts(); });
        add_action('save_post', function ($postID) { self::saveMetaBoxes($postID); });
    }
}
