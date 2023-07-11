import jQuery from 'jquery'; /* eslint-disable-line import/no-unresolved */

const { tenupWelcome, ajaxurl } = window;

const dismiss = document.querySelector('.notice-10up-experience-welcome');

if (dismiss) {
	const data = {
		action: 'tenup_dismiss_welcome',
		nonce: tenupWelcome.nonce,
	};

	jQuery(dismiss).on('click', 'button', () => {
		jQuery.ajax({
			method: 'post',
			data,
			url: ajaxurl,
		});
	});
}
