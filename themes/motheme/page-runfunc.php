<?php 
/*
Template Name: Run Function do not use
*/


global $wpdb;
    $q = $wpdb->query( "        
        UPDATE $wpdb->term_taxonomy 
        SET taxonomy = 'category'
        WHERE taxonomy = 'review-category';" );

    var_dump($q);