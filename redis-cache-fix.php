<?php
/*
Plugin Name: Redis Object Cache fix
Plugin URI:
Description:
Version: 0.1
Author: DigitalCube
Author URI: https://www.digitalcube.jp/
License: GPLv2 or later
*/

class redis_cache_fix {
    public function __construct() {
        add_action( 'add_option',    array( $this, 'option_cache_flush' ) );
        add_action( 'update_option', array( $this, 'option_cache_flush' ) );
        add_action( 'delete_option', array( $this, 'option_cache_flush' ) );
    }

    // update_option, delete_option 時に cache をフラッシュ
    public function option_cache_flush($option, $old_value = '', $value = ''){
        if ( !empty( $option ) ) {
            wp_cache_delete( $option, 'options' );
            foreach (array('alloptions','notoptions') as $options_name) {
                $options = wp_cache_get( $options_name, 'options' );
                if ( ! is_array($options) ) {
                    $options = array();
                }
                if ( isset($options[$option]) ) {
                    unset($options[$option]);
                    wp_cache_set( $options_name, $options, 'options' );
                }
                unset($options);
            }
        }
        return;
    }
};
new redis_cache_fix();