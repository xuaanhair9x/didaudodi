<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (WP Edition)                                          |
 |                                                                     |
 | (c) NinTechNet - https://nintechnet.com/                            |
 +---------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or       |
 | modify it under the terms of the GNU General Public License as      |
 | published by the Free Software Foundation, either version 3 of      |
 | the License, or (at your option) any later version.                 |
 |                                                                     |
 | This program is distributed in the hope that it will be useful,     |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
 | GNU General Public License for more details.                        |
 +---------------------------------------------------------------------+ i18n+ / sa
*/

if (! defined( 'NFW_ENGINE_VERSION' ) ) { die( 'Forbidden' ); }

if (! empty($nfw_options['engine_version']) && version_compare($nfw_options['engine_version'], NFW_ENGINE_VERSION, '<') ) {

	// Starting from v3.8.2 and its new backup auto-restore feature,
	// we must prevent any potential race condition here:
	if ( get_transient( 'nfw_version_update' ) === false ) {
		set_transient( 'nfw_version_update', NFW_ENGINE_VERSION, 60 );

		// v3.1.2 update (file guard) ----------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.1.2', '<' ) ) {
			if (! empty( $nfw_options['fg_exclude'] ) ) {
				$nfw_options['fg_exclude'] = preg_quote( $nfw_options['fg_exclude'], '`');
			}
		}
		// v3.2.2 update -----------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.2.2', '<' ) ) {
			if ( is_multisite() ) {
				update_site_option('nfw_options', $nfw_options);
				update_site_option('nfw_rules', $nfw_rules_new);
			}
		}
		// v3.3 update ---------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.3', '<' ) ) {
			if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
				if (! empty( $nfw_options['response_headers'] ) && strlen( $nfw_options['response_headers'] ) == 6 ) {
					$nfw_options['response_headers'] .= '00';
				}
			}
		}
		// v3.4 update ---------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.4', '<' ) ) {
			$nfw_options['a_53'] = 1;
		}
		// v3.5.1 update -------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.5.1', '<' ) ) {
			// Create garbage collector's cron job:
			if ( wp_next_scheduled( 'nfwgccron' ) ) {
				wp_clear_scheduled_hook( 'nfwgccron' );
			}
			wp_schedule_event( time() + 60, 'hourly', 'nfwgccron' );
		}
		// v3.6.2 update -------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.6.2', '<' ) ) {
			$nfw_options['rate_notice'] = time() + 86400 * 15;
		}
		// v3.7.2 update -------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.7.2', '<' ) ) {
			if (! isset( $nfw_options['disallow_settings'] ) ) {
				$nfw_options['disallow_settings'] = 1;
			}
		}
		// v3.7.3 update -------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.7.3', '<' ) ) {
			// Clear old DB hash files:
			$path = NFW_LOG_DIR . '/nfwlog/cache/';
			$glob = glob( $path . "nfdbhash*.php" );
			if ( is_array( $glob ) ) {
				foreach( $glob as $file ) {
					unlink( $file );
				}
			}
			// Convert all backup file to json format:
			$path = NFW_LOG_DIR . '/nfwlog/cache/';
			$now = time();
			$glob = glob( $path .'backup_*.php' );
			if ( is_array( $glob ) && ! empty( $glob[0] ) ) {
				foreach( $glob as $file ) {
					$data = file_get_contents( $file );
					list ( $options, $rules, $bf ) = @explode("\n:-:\n", $data . "\n:-:\n");
					$array_options = @unserialize( $options );
					$array_rules = @unserialize( $rules );
					if (! empty( $bf ) ) {
						$bf_conf = @unserialize( $bf );
					} else {
						$bf_conf = '';
					}
					if ( $array_options !== false && $array_rules !== false ) {
						$data = json_encode( $array_options ) ."\n:-:\n". json_encode( $array_rules ) ."\n:-:\n". $bf_conf;
						file_put_contents( $file, $data );
					}
				}
			}
		}
		// v3.8.3 update -------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.8.3', '<' ) ) {
			if (! isset( $nfw_options['a_61'] ) ) {
				$nfw_options['a_61'] = 1;
			}
		}

		// ---------------------------------------------------------------
		// Delete old rules files (/updates/*):
		$update_dir = dirname( __DIR__ ) . '/updates';
		if ( is_dir( $update_dir ) ) {
			if ( file_exists( "{$update_dir}/.htaccess" ) ) {
				unlink( "{$update_dir}/.htaccess");
			}
			if ( file_exists( "{$update_dir}/rules3.txt" ) ) {
				unlink( "{$update_dir}/rules3.txt");
			}
			if ( file_exists( "{$update_dir}/version3.txt" ) ) {
				unlink( "{$update_dir}/version3.txt");
			}
			@rmdir( $update_dir );
		}
		// v4.0 update ---------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '3.9.2', '<' ) ) {
			if ( empty( $nfw_options['allow_local_ip'] ) ) {
				$nfw_options['allow_local_ip'] = 1;
			} else {
				$nfw_options['allow_local_ip'] = 0;
			}
		}
		// v4.0.6 update -------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '4.0.6', '<' ) ) {
			if (! isset( $nfw_options['disallow_privesc'] ) &&
				! defined('NFW_DISABLE_PRVESC2' ) &&
				! empty( $nfw_options['a_53'] ) ) {

				$nfw_options['disallow_privesc'] = 1;
			} else {
				$nfw_options['disallow_privesc'] = 0;
			}
			unset( $nfw_options['a_53'] );
		}
		// v4.1 update ---------------------------------------------------
		if ( version_compare( $nfw_options['engine_version'], '4.1', '<' ) ) {
			$nfw_options['secupdates'] = 1;
		}
		// -------------------------------------------------------------
		// All versions:
		// Old upgrades from 1.3.x to 1.4 didn't initialize the security
		// rules updates cron job; we attempt to fix this now:
		if (! isset( $nfw_options['enable_updates'] ) ) {
			$nfw_options['enable_updates'] = 1;
			$nfw_options['sched_updates'] = 1;
			$nfw_options['notify_updates'] = 0;
			// Clear scheduled scan (if any):
			if ( wp_next_scheduled('nfsecupdates') ) {
				wp_clear_scheduled_hook('nfsecupdates');
			}
			// Setup new cron:
			wp_schedule_event( time() + 600, 'hourly', 'nfsecupdates');
		}
		// -------------------------------------------------------------

		// Adjust current version :
		$nfw_options['engine_version'] = NFW_ENGINE_VERSION;

		// Update options:
		nfw_update_option( 'nfw_options', $nfw_options);
	}

	// ------------------------------------------------------------------
}

// ---------------------------------------------------------------------
// EOF
