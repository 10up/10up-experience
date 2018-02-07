<?php
namespace tenup;

/**
 * Sanitize a checkbox boolean setting.
 *
 * @param  string $value
 * @return string
 */
function sanitize_checkbox_bool( $value ) {
	if ( ! empty( $value ) ) {
		return true;
	}

	return false;
}
