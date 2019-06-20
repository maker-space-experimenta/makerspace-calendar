<?php


if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( ! class_exists( 'Makerspace_Calendar' ) ) {


    class Makerspace_Calendar_Main{

        const VERSION = '1.0.0';

        /**
         * Static Singleton Holder
         * @var self
         */
        protected static $instance;

        /**
         * Get (and instantiate, if necessary) the instance of the class
         *
         * @return self
         */
        public static function instance() {
            if ( ! self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        function __construct() {
            // add_action('admin_enqueue_scripts', array($this, 'load_styles') );

            require_once plugin_dir_path( __FILE__ ) . '/PostTypes/workshop/workshop.php';
            $cal = new WorkshopPostType();
            $cal->register();

        }

        public static function activate() {
            global $wpdb;

            $sql = "
                CREATE TABLE IF NOT EXISTS makerspace_calendar_workshop_registrations (
                  mse_cal_workshop_registration_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  mse_cal_workshop_post_id INT NOT NULL,
                  mse_cal_workshop_registration_email VARCHAR(255) NOT NULL,
                  mse_cal_workshop_registration_firstname VARCHAR(255) NOT NULL,
                  mse_cal_workshop_registration_lastname VARCHAR(255) NOT NULL,
                  mse_cal_workshop_registration_count INT NOT NULL
                )
            ";

            $wpdb->get_results( $sql );
        }

        public static function deactivate( $network_deactivating ) {

        }

        public function load_styles() {
            wp_enqueue_style('boot_css', plugins_url('assets/styles/main.css',__FILE__ ));
        }
    }

}