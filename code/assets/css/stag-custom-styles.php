<?php
header("Content-type: text/css");

// Enable caching
header('Cache-Control: public');

// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

if(file_exists('../../../../wp-load.php')) :
  include '../../../../wp-load.php';
else:
  include '../../../../../wp-load.php';
endif;

@ob_flush();

$output = '';
$stag_values = get_option( 'stag_framework_values' );
if( array_key_exists( 'style_custom_css', $stag_values ) && $stag_values['style_custom_css'] != '' ) {
	$output .= stripslashes($stag_values['style_custom_css']);
	$output .= "\n\n";
}
echo apply_filters( 'stag_custom_css_output', $output );
@ob_end_flush();
?>
