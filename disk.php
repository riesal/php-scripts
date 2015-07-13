<?php
/*
Plugin Name: Folder Sizes Dashboard Widget
Plugin URI: http://wordpress.stackexchange.com/q/67876/12615
Description: List the following folder sizes in a Dashboard Widget: Uploads dir, WP Content dir, WordPress base dir. 
Observation: PHP folder size functions from this Answer: http://stackoverflow.com/a/8348396/1287812
*/

add_action( 'wp_dashboard_setup', 'wpse_67876_wp_dashboard_setup' );

function wpse_67876_wp_dashboard_setup() 
{
    // Admins only
    if( current_user_can( 'install_plugins' ) )
        wp_add_dashboard_widget( 'wpse_67876_folder_sizes', __( 'Folder Sizes' ), 'wpse_67876_wp_add_dashboard_widget' );
}

function wpse_67876_wp_add_dashboard_widget() 
{
    $upload_dir     = wp_upload_dir(); 
    $upload_space   = wpse_67876_foldersize( $upload_dir['basedir'] );
    $content_space  = wpse_67876_foldersize( WP_CONTENT_DIR );
    $wp_space       = wpse_67876_foldersize( ABSPATH );

    /* ABSOLUTE paths not being shown in Widget */

    // echo '<b>' . $upload_dir['basedir'] . ' </b><br />';
    echo '<i>Uploads</i>: ' . wpse_67876_format_size( $upload_space ) . '<br /><br />';

    // echo '<b>' . WP_CONTENT_DIR . ' </b><br />';
    echo '<i>wp-content</i>: ' . wpse_67876_format_size( $content_space ) . '<br /><br />';  

    if( is_multisite() )
    {
        echo '<i>wp-content/blogs.dir</i>: ' . wpse_67876_format_size( wpse_67876_foldersize( WP_CONTENT_DIR . '/blogs.dir' ) ) . '<br /><br />';  
    }

    // echo '<b>' . ABSPATH . ' </b><br />';
    echo '<i>WordPress</i>: ' . wpse_67876_format_size( $wp_space );    
}



function wpse_67876_foldersize( $path ) 
{
    $total_size = 0;
    $files = scandir( $path );
    $cleanPath = rtrim( $path, '/' ) . '/';

    foreach( $files as $t ) {
        if ( '.' != $t && '..' != $t ) 
        {
            $currentFile = $cleanPath . $t;
            if ( is_dir( $currentFile ) ) 
            {
                $size = wpse_67876_foldersize( $currentFile );
                $total_size += $size;
            }
            else 
            {
                $size = filesize( $currentFile );
                $total_size += $size;
            }
        }   
    }

    return $total_size;
}

function wpse_67876_format_size($size) 
{
    $units = explode( ' ', 'B KB MB GB TB PB' );

    $mod = 1024;

    for ( $i = 0; $size > $mod; $i++ )
        $size /= $mod;

    $endIndex = strpos( $size, "." ) + 3;

    return substr( $size, 0, $endIndex ) . ' ' . $units[$i];
}
