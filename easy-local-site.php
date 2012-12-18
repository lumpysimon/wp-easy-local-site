<?php
/*
Plugin Name:  Easy Local Site
Description:  Aid the development process on a local WordPress site by adding some handy reminders and overriding outgoing emails.
Version:      0.4
License:      GPL v2 or later
Plugin URI:   https://github.com/lumpysimon/wp-easy-local-site
Author:       Simon Blackbourn @ Lumpy Lemon
Author URI:   https://twitter.com/lumpysimon
Author Email: simon@lumpylemon.co.uk
Text Domain:  easy_local_site
Domain Path:  /easy-local-languages/



	What it does
	------------

	When working on a local site and a production site at the same time,
	things can easily get confusing and you forget which site you're looking at.
	This plugin adds a hard-to-miss, bright orange reminder to the toolbar
	and prepends [LOCAL] to the <title> tag on your local site.

	If you've imported a database from a production site, you may also want to avoid
	sending emails out to real users (e.g. for testing contact forms or notifications).
	This plugin provides two options: you can override the 'to' address of all outgoing emails,
	sending them instead to an address specified by you (the original recipient's email address is
	prepended to the subject line so you can see at a glance who it was originally intended for);
	or you can disable the outgoing email completely and instead log it as the 'Local Email' custom post type.


	Requirements
	------------

	You must use the WP_LOCAL_DEV constant method as outlined by Mark Jaquith:
	http://markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips/

	If the WP_LOCAL_DEV constant is not defined this plugin will do nothing.
	This means you can safely put it in your mu-plugins folder and
	include it in your Git repository on a production site.

	To override outgoing emails, you must also define WP_LOCAL_EMAIL as follows:

	define( 'WP_LOCAL_EMAIL', 'me@example.com' );

	To disable outgoing emails completely and instead log them as a 'Local Email' custom post type,
	you must define WP_LOCAL_EMAIL as follows:

	define( 'WP_LOCAL_EMAIL', 'post' );

	Ideally you should install Easy Local Site in wp-content/mu-plugins
	(plugins in here are 'Must Use' and are automatically activated).
	This will mean that it is always activated, even after migrating a database
	from a production site where the plugin is not installed/activated,
	so you don't have to remember to manually activate it.



	License
	-------

	Copyright (c) Lumpy Lemon Ltd. All rights reserved.

	Released under the GPL license:
	http://www.opensource.org/licenses/gpl-license.php

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.



	Changelog
	---------

	0.4
	Option to disable outgoing emails and instead log them by creating a 'Local Email' custom post type. Define WP_LOCAL_EMAIL as 'post' to use this.

	0.3
	Use WP_LOCAL_EMAIL constant for email address (for easier use by teams in a version-controlled environment).
	Prepare for localisation.
	Add PHPDoc comments.

	0.2
	Add email overriding.

	0.1
	Initial release.



*/



class easyLocalSite {



	/**
	 * Class constructor
	 *
	 * Hook into various actions & filters,
	 * using very low priority where required
	 * to ensure things run after other plugins
	 * have done their stuff.
	 *
	 */
	public function __construct() {

		add_action( 'init',                                   array( $this, 'init'          ) );
		add_action( 'admin_bar_menu',                         array( $this, 'toolbar_menu'  ) );
		add_action( 'wp_head',                                array( $this, 'toolbar_style' ) );
		add_action( 'admin_head',                             array( $this, 'toolbar_style' ) );

		add_filter( 'wp_title',                               array( $this, 'title'         ), 999, 2 );
		add_filter( 'admin_title',                            array( $this, 'admin_title'   ), 999 );
		add_filter( 'admin_notices',                          array( $this, 'admin_notice'  ) );
		add_filter( 'wp_mail',                                array( $this, 'override_mail' ), 999 );
		add_filter( 'manage_local-email_posts_columns',       array( $this, 'cols' ) );
		add_filter( 'manage_local-email_posts_custom_column', array( $this, 'col' ) );

	}



	/**
	 * Load localisation files, set up custom post type.
	 *
	 * @return null
	 */
	function init() {

		load_plugin_textdomain(
			'easy_local_site',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/easy-local-languages'
			);

		if ( defined( 'WP_LOCAL_EMAIL' ) and 'post' == WP_LOCAL_EMAIL ) {

			register_post_type(
				'local-email',
				array(
					'label'                => 'Local emails',
					'labels'               => array(
												'name'               => 'Local emails',
												'singular_name'      => 'Local email',
												'add_new'            => 'Add new local email',
												'all_items'          => 'All local emails',
												'add_new_item'       => 'local email',
												'edit_item'          => 'Edit local email',
												'new_item'           => 'New local email',
												'view_item'          => 'View local email',
												'search_items'       => 'Search local emails',
												'not_found'          => 'No local emails found',
												'not_found_in_trash' => 'No local emails found in trash'
												),
					'public'               => false,
					'show_ui'              => true,
					'menu_position'        => 999,
					'hierarchical'         => false,
					'supports'             => array(
												'title',
												'editor'
												),
					'has_archive'          => false,
					'query_var'            => false,
					'can_export'           => true
					)
				);

		/*
		register_taxonomy(
			'local-email-recipient',
			'local-email',
			array(
				'label'         => 'Recipients',
				'labels'        => array(
									'name'                       => 'Recipients',
									'singular_name'              => 'Recipient',
									'search_items'               => 'Search recipients',
									'popular_items'              => 'Popular recipients',
									'all_items'                  => 'All recipients',
									'edit_item'                  => 'Edit recipient',
									'update_item'                => 'Update recipient',
									'add_new_item'               => 'Add new recipient',
									'new_item_name'              => 'New recipient',
									'separate_items_with_commas' => 'Separate recipients with commas',
									'add_or_remove_items'        => 'Add or remove recipients',
									'choose_from_most_used'      => 'Choose from most used recipients',
									'menu_name'                  => 'Recipients'
									),
				'public'        => false,
				'show_ui'       => true,
				'show_tagcloud' => false,
				'hierarchical'  => false
				)
			);
		*/

		}

	}



	/**
	 * Add a handy reminder to the toolbar.
	 * @param  object $toolbar The toolbar
	 * @return null
	 */
	function toolbar_menu( $toolbar ) {

		if ( ! is_admin_bar_showing() )
			return;

		$toolbar->add_node( array(
			'id'    => 'lumpy-local-site',
			'title' => __( 'Local Site', 'easy_local_site' ),
			'href'  => site_url()
		) );

	}



	/**
	 * Add some styling to the <head> section of the page
	 * to make sure you don't miss that toolbar reminder.
	 *
	 * @return null
	 */
	function toolbar_style() {

		if ( ! is_admin_bar_showing() )
			return;

		?>

		<style>
			#wpadminbar #wp-admin-bar-lumpy-local-site a {
				background-color: #c30;
				color: #fff;
				text-shadow: 0 0 0;
			}
		</style>

		<?php
	}



	/**
	 * Prepend a reminder to the page title for front-end pages.
	 *
	 * @param  string $title Page title
	 * @param  string $sep   Separator
	 * @return string        Page title
	 */
	function title( $title, $sep ) {

		$title = sprintf(
					'[%s] %s',
					__( 'LOCAL', 'easy_local_site' ),
					$title
					);

		return $title;

	}



	/**
	 * Prepend a reminder to the page title for admin pages.
	 *
	 * @param  string $admin_title Admin page title
	 * @return string              Admin page title
	 */
	function admin_title( $admin_title ) {

		$admin_title = sprintf(
							'[%s] %s',
							__( 'LOCAL', 'easy_local_site' ),
							$admin_title
							);

		return $admin_title;

	}



	/**
	 * Show an admin notice if user has admin bar turned off.
	 *
	 * @return null
	 */
	function admin_notice() {

		if ( ! is_admin_bar_showing() ) {
			printf(
				'<div class="error"><p>%s</p></div>',
				__( 'This is the local site', 'easy_local_site' )
				);
		}

	}



	/**
	 * Override all outgoing emails & prepend the original recipient to the subject.
	 *
	 * @todo   check if there's a better way than null 'to' field to prevent email sending
	 *
	 * @param  array $mail The email
	 * @return array       The email
	 */
	function override_mail( $mail ) {

		// do nothing if the WP_LOCAL_EMAIL constant isn't defined
		if ( ! defined( 'WP_LOCAL_EMAIL' ) )
			return $mail;

		// if the constant is set to 'post'
		// create a post & set the recipient to null so no email is sent
		if ( 'post' == WP_LOCAL_EMAIL ) {
			self::create_post( $mail );
			$mail['to'] = null;
			return $mail;
		}

		// otherwise check if the constant is a valid email address
		if ( ! is_email( WP_LOCAL_EMAIL ) )
			return $mail;

		// prepend the original recipient to the subject
		// and set the new recipient
		$mail['subject'] = '[LOCAL: ' . $mail['to'] . '] ' . $mail['subject'];
		$mail['to']      = WP_LOCAL_EMAIL;
		return $mail;

	}



	/**
	 * Create a new 'local email' post from the content of the email.
	 *
	 * @param  array $mail The email
	 * @return null
	 */
	function create_post( $mail ) {

		// set the required post fields (post author is the main admin user)
		$postdata = array(
						'post_type'    => 'local-email',
						'post_title'   => wp_strip_all_tags( $mail['subject'] ),
						'post_content' => $mail['message'],
						'post_author'  => 1,
						'post_status'  => 'publish'
						);

		// insert the post & if successful set the recipient postmeta field
		if ( $post_id = wp_insert_post( $postdata ) ) {
			add_post_meta( $post_id, 'easy-local-site-to', esc_html( $mail['to'] ) );
			/*
			wp_insert_term( $mail['to'], 'local-email-recipient' );
			wp_set_object_terms( $post_id, $mail['to'], 'local-email-recipient' );
			*/
		}

	}



	/**
	 * Define the columns to be used on the Local Email edit.php screen
	 *
	 * @param  array $cols The columns
	 * @return array       The columns
	 */
	function cols( $cols ) {

		$cols = array(
					'cb'          => '<input type="checkbox">',
					'title'       => __( 'Title' ),
					'els-to'      => __( 'Recipient', 'easy_local_site' ),
					'els-message' => __( 'Excerpt' ),
					'date'        => __( 'Date' )
					);

		return $cols;

	}



	/**
	 * Output the custom columns for the Local Email post type
	 *
	 * @param  string $col Column name
	 * @return null
	 */
	function col( $col ) {

		global $post;

		switch ( $col ) {

			case 'els-to':
				echo get_post_meta( $post->ID, 'easy-local-site-to', true );
			break;

			case 'els-message':
				echo wp_trim_words( wp_strip_all_tags( $post->post_content ), 40 );
			break;

		}

	}



}



// kick in only if the WP_LOCAL_DEV constant is set

if ( defined( 'WP_LOCAL_DEV' ) and WP_LOCAL_DEV ) {
	$easy_local_site = new easyLocalSite;
}



?>