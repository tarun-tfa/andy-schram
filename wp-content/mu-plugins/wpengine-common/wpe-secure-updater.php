<?php
/**
 * Plugin Name: WPE Secure Updater (For the MU plugin)
 * Description: Securely update WP Engine powered plugins.
 * Version: 0.0.1
 * Author: WP Engine
 * Text Domain: wpe-secure-updater
 * License: GPLv2 or later
 *
 * @package wpe-secure-updater
 */

// Note: PHPCS linter rules would prefer we use separate files for classes.
// phpcs:ignoreFile

namespace WpeSecureUpdater\PluginUpdaterClass;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use stdClass;
use WP_Error;

/**
 * The PluginUpdater class which can be used to pull plugin updates from a new location.
 */
class PluginUpdater {
	/**
	 * The URL where the api is located.
	 *
	 * @var ApiUrl
	 */
	private $api_url;

	/**
	 * The amount of time to wait before checking for new updates.
	 *
	 * @var CacheTime
	 */
	private $cache_time;

	/**
	 * These properties are passed in when instantiating to identify the plugin and it's update location.
	 *
	 * @var Properties
	 */
	private $properties;

	/**
	 * Get the class constructed.
	 *
	 * @param Properties $properties These properties are passed in when instantiating to identify the plugin and it's update location.
	 */
	public function __construct( $properties ) {
		if (
			empty( $properties['plugin_name'] ) || // This must match the "Name" value of the plugin in its plugin header file.
			empty( $properties['plugin_author'] ) || // This must match the "Author" value of the plugin in its plugin header file.
			empty( $properties['plugin_slug'] ) // This is the slug of the plugin as found in https://plugin-updates.wpengine.com/plugins.json.
		) {
			// If any of the values we require were not passed, throw a fatal.
			// Using a fatal here should help prevent us accidentally shipping broken code, as we will immediately see the fatal in development.
			// phpcs:ignore
			error_log( 'WPE Secure Updater received a malformed request.' );
			return;
		}

		$this->api_url = 'https://plugin-updates.wpengine.com/';

		$this->cache_time = time() + HOUR_IN_SECONDS * 1;

		$this->properties = $this->get_full_plugin_properties( $properties, $this->api_url );

		if ( ! $this->properties ) {
			return;
		}

		$this->register();
	}

	/**
	 * Get the full plugin properties, including the directory name, version, basename, and add a transient name.
	 *
	 * @param Properties $properties These properties are passed in when instantiating to identify the plugin and it's update location.
	 * @param ApiUrl     $api_url    The URL where the api is located.
	 */
	public function get_full_plugin_properties( $properties, $api_url ) {
		$plugins = \get_plugins();

		// Scan through all plugins installed and find the one which matches this one in question.
		foreach ( $plugins as $plugin_basename => $plugin_data ) {
			// Ensure both the name and author of the plugin match what we are looking for.
			if ( $properties['plugin_name'] === $plugin_data['Name'] && $properties['plugin_author'] === $plugin_data['Author'] ) {

				// Add the values we need to the properties.
				$properties['plugin_dirname']                   = dirname( $plugin_basename );
				$properties['plugin_version']                   = $plugin_data['Version'];
				$properties['plugin_basename']                  = $plugin_basename; // IE: "advanced-custom-fields-pro/acf.php".
				// wpesu stands for "WPE Secure Updater".
				$properties['plugin_update_transient_name']     = 'wpesu-' . $properties['plugin_dirname'];
				$properties['plugin_update_transient_exp_name'] = 'wpesu-' . $properties['plugin_dirname'] . '-expiry';
				$properties['plugin_manifest_url']              = trailingslashit( $api_url ) . trailingslashit( $properties['plugin_slug'] ) . 'info.json';

				return $properties;
			}
		}

		// No matching plugin was found installed.
		return null;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'plugins_api', array( $this, 'filter_plugin_update_info' ), 20, 3 );
		add_filter( 'site_transient_update_plugins', array( $this, 'filter_plugin_update_transient' ) );
	}

	/**
	 * Filter the plugin update transient to take over update notifications.
	 *
	 * @param object $transient The site_transient_update_plugins transient.
	 *
	 * @handles site_transient_update_plugins
	 * @return object
	 */
	public function filter_plugin_update_transient( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$result = $this->fetch_plugin_info();

		if ( false === $result ) {
			return $transient;
		}

		if ( version_compare( $this->properties['plugin_version'], $result->version, '<' ) ) {
			$res                                 = $this->parse_plugin_info( $result );
			$transient->response[ $res->plugin ] = $res;
			$transient->checked[ $res->plugin ]  = $result->version;
		}

		return $transient;
	}

	/**
	 * Filters the plugin update information.
	 *
	 * @param object $res    The response to be modified for the plugin in question.
	 * @param string $action The action in question.
	 * @param object $args   The arguments for the plugin in question.
	 *
	 * @handles plugins_api
	 * @return object
	 */
	public function filter_plugin_update_info( $res, $action, $args ) {
		// Do nothing if this is not about getting plugin information.
		if ( 'plugin_information' !== $action ) {
			return $res;
		}

		// Do nothing if it is not our plugin.
		if ( $this->properties['plugin_dirname'] !== $args->slug ) {
			return $res;
		}

		$result = $this->fetch_plugin_info();

		// Do nothing if we don't get the correct response from the server.
		if ( false === $result ) {
			return $res;
		}

		return $this->parse_plugin_info( $result );
	}

	/**
	 * Fetches the plugin update object from the WP Product Info API.
	 *
	 * @return object|false
	 */
	private function fetch_plugin_info() {
		// Fetch cache first.
		$expiry   = get_option( $this->properties['plugin_update_transient_exp_name'], 0 );
		$response = get_option( $this->properties['plugin_update_transient_name'] );

		if ( empty( $expiry ) || time() > $expiry || empty( $response ) ) {
			$response = wp_remote_get(
				$this->properties['plugin_manifest_url'],
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json',
					),
				)
			);

			if (
				is_wp_error( $response ) ||
				200 !== wp_remote_retrieve_response_code( $response ) ||
				empty( wp_remote_retrieve_body( $response ) )
			) {
				return false;
			}

			$response = wp_remote_retrieve_body( $response );

			// Cache the response.
			update_option( $this->properties['plugin_update_transient_exp_name'], $this->cache_time, false );
			update_option( $this->properties['plugin_update_transient_name'], $response, $this->cache_time );
		}

		$decoded_response = json_decode( $response );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return false;
		}

		return $decoded_response;
	}

	/**
	 * Parses the product info response into an object that WordPress would be able to understand.
	 *
	 * @param object $response The response object.
	 *
	 * @return stdClass
	 */
	private function parse_plugin_info( $response ) {

		global $wp_version;

		$res                = new stdClass();
		$res->name          = $response->name;
		$res->slug          = $response->slug;
		$res->version       = $response->version;
		$res->requires      = $response->requires;
		$res->download_link = $response->download_link;
		$res->trunk         = $response->download_link;
		$res->new_version   = $response->version;
		$res->plugin        = $this->properties['plugin_basename'];
		$res->package       = $response->download_link;

		// Plugin information modal and core update table use a strict version comparison, which is weird.
		// If we're genuinely not compatible with the point release, use our WP tested up to version.
		// otherwise use exact same version as WP to avoid false positive.
		$res->tested = 1 === version_compare( substr( $wp_version, 0, 3 ), $response->tested )
			? $response->tested
			: $wp_version;

		$res->sections = array(
			'description' => $response->sections->description,
			'changelog'   => $response->sections->changelog,
		);

		return $res;
	}
}

/**
 * Initialize the checking for plugin updates.
 */
function check_for_updates() {

	$cache_transient_name     = 'wpesu-wpe-plugins-cache';
	$cache_transient_exp_name = 'wpesu-wpe-plugins-cache-expiry';
	$cache_time               = time() + HOUR_IN_SECONDS * 5;

	// First, get the list of WPE plugins we want to handle updates for, checking a cache first.
	$expiry   = get_option( $cache_transient_exp_name, 0 );
	$response = get_option( $cache_transient_name );

	if ( empty( $expiry ) || time() > $expiry || empty( $response ) ) {
		$response = wp_remote_get( 'https://plugin-updates.wpengine.com/plugins.json' );

		if (
			is_wp_error( $response ) ||
			200 !== wp_remote_retrieve_response_code( $response ) ||
			empty( wp_remote_retrieve_body( $response ) )
		) {
			return false;
		}

		$response = wp_remote_retrieve_body( $response );

		// Cache the response.
		update_option( $cache_transient_exp_name, $cache_time, false );
		update_option( $cache_transient_name, $response, $cache_time );
	}

	$wpe_plugins_to_handle = json_decode( $response, true );

	if ( json_last_error() !== JSON_ERROR_NONE ) {
		return false;
	}

	if ( empty( $wpe_plugins_to_handle ) || ! is_array( $wpe_plugins_to_handle ) ) {
		return false;
	}

	foreach ( $wpe_plugins_to_handle as $plugin_slug => $wpe_plugin_to_handle ) {

		if (
			empty( $wpe_plugin_to_handle['plugin_name'] ) ||
			empty( $wpe_plugin_to_handle['plugin_author'] ) ||
			empty( $plugin_slug )
		) {
			// Response for this plugin was malformed, skip it.
			continue;
		}

		$properties = array(
			'plugin_name'   => $wpe_plugin_to_handle['plugin_name'], // Must match the plugin's "Name" in the Plugin Header.
			'plugin_author' => $wpe_plugin_to_handle['plugin_author'], // Must match the plugin's "Author" in the Plugin Header.
			'plugin_slug'   => $plugin_slug,
		);

		new \WpeSecureUpdater\PluginUpdaterClass\PluginUpdater( $properties );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\check_for_updates' );