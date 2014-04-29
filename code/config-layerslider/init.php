<?php
/**
 * Configure LayerSlider WordPress Plugin.
 *
 * @package StagFramework
 * @subpackage Crux
 */

/**
 * Disable LayerSlider.
 *
 * @return void
 */
function stag_layerslider_config() {

    // Disable auto-updates
    $GLOBALS['lsAutoUpdateBox'] = false;
}
add_action( 'layerslider_ready', 'stag_layerslider_config' );

if ( ! function_exists( 'stag_remove_default_import' ) ) {
	add_action( 'admin_menu', 'stag_remove_default_import' , 1 );

	function stag_remove_default_import() {
		if( isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'import_sample' ) {
			remove_action( 'admin_init', 'layerslider_import_sample_slider' );
			add_action( 'admin_init', 'stag_import_sample_slider2' );
		}
	}
}


if ( ! function_exists( 'stag_import_sample_slider' ) ) {
	/**
	 * Override Layerslider default import function
	 *
	 * @return void
	 */
	function stag_import_sample_slider() {

		// Base64 encoded, serialized slider export code

		$path        = "stag-samples/";
		$sample_file = "sample_sliders.txt";
		$the_path    = LS_ROOT_PATH . "/stag-samples/{$sample_file}";
		$import_file =  base64_decode( file_get_contents($the_path) );

		$s = 'http:\/\/demo.codestag.com\/crux\/wp-content\/uploads\/sites\/2\/2013\/11\/';
		$replace_with = LS_ROOT_PATH . "/stag-samples/";

		$sample_slider = json_decode(str_replace( $s, $replace_with, $import_file ));

		// Iterate over the sliders
		foreach($sample_slider as $sliderkey => $slider) {

			// Iterate over the layers
			foreach($sample_slider[$sliderkey]->layers as $layerkey => $layer) {

				// Change background images if any
				if(!empty($sample_slider[$sliderkey]->layers[$layerkey]->properties->background)) {
					$sample_slider[$sliderkey]->layers[$layerkey]->properties->background = $GLOBALS['lsPluginPath'].$path.basename($layer->properties->background);
				}

				// Change thumbnail images if any
				if(!empty($sample_slider[$sliderkey]->layers[$layerkey]->properties->thumbnail)) {
					$sample_slider[$sliderkey]->layers[$layerkey]->properties->thumbnail = $GLOBALS['lsPluginPath'].$path.basename($layer->properties->thumbnail);
				}

				// Iterate over the sublayers
				if(isset($layer->sublayers) && !empty($layer->sublayers)) {
					foreach($layer->sublayers as $sublayerkey => $sublayer) {

						// Only IMG sublayers
						if($sublayer->type == 'img') {
							$sample_slider[$sliderkey]->layers[$layerkey]->sublayers[$sublayerkey]->image = $GLOBALS['lsPluginPath'].$path.basename($sublayer->image);
						}
					}
				}
			}
		}

		// Get WPDB Object
		global $wpdb;

		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Append duplicate
		foreach($sample_slider as $key => $val) {

			// Insert the duplicate
			$wpdb->query(
				$wpdb->prepare("INSERT INTO $table_name
									(name, data, date_c, date_m)
								VALUES (%s, %s, %d, %d)",
								$val->properties->title,
								json_encode($val),
								time(),
								time()
				)
			);
		}

	}
}

function stag_import_sample_slider2() {

	// Base64 encoded, serialized slider export code
	$sample_slider = base64_decode(file_get_contents(LS_ROOT_PATH.'/stag-samples/sample_sliders.txt'));
	$s = 'http:\/\/demo.codestag.com\/crux\/wp-content\/uploads\/sites\/2\/2013\/11\/';
	$replace_with = LS_ROOT_PATH . "/stag-samples/";

	$sample_slider = json_decode(str_replace( $s, $replace_with, $sample_slider ), true);

	// Iterate over the sliders
	foreach($sample_slider as $sliderkey => $slider) {

		// Iterate over the layers
		foreach($sample_slider[$sliderkey]['layers'] as $layerkey => $layer) {

			// Change background images if any
			$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['backgroundId'] = '';
			if(!empty($sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'])) {
				$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['background'] = LS_ROOT_URL.'/stag-samples/'.basename($layer['properties']['background']);
			}

			// Change thumbnail images if any
			$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnailId'] = '';
			if(!empty($sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'])) {
				$sample_slider[$sliderkey]['layers'][$layerkey]['properties']['thumbnail'] = LS_ROOT_URL.'/stag-samples/'.basename($layer['properties']['thumbnail']);
			}

			// Iterate over the sublayers
			if(isset($layer['sublayers']) && !empty($layer['sublayers'])) {
				foreach($layer['sublayers'] as $sublayerkey => $sublayer) {

					// Only IMG sublayers
					$sample_slider[$sliderkey]['layers'][$layerkey]['sublayers'][$sublayerkey]['imageId'] = '';
					if($sublayer['type'] == 'img' || ( isset($sublayer['media']) && $sublayer['media'] == 'img')) {
						$sample_slider[$sliderkey]['layers'][$layerkey]['sublayers'][$sublayerkey]['image'] = LS_ROOT_URL.'/stag-samples/'.basename($sublayer['image']);
					}
				}
			}
		}
	}

	// Get WPDB Object
	global $wpdb;
	$table_name = $wpdb->prefix . "layerslider";

	// Append duplicate
	foreach($sample_slider as $key => $val) {
		$wpdb->query(
			$wpdb->prepare("INSERT INTO $table_name (name, data, date_c, date_m)
							VALUES (%s, %s, %d, %d)",
							$val['properties']['title'],
							json_encode($val),
							time(),
							time()
			)
		);
	}

	// Success
	header('Location: admin.php?page=layerslider');
	die();
}
