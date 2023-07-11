const { tenupPasswords } = window;

const passwordWrapper = document.querySelectorAll('#your-profile .wp-pwd, #resetpassform .wp-pwd');

if (passwordWrapper.length) {
	const passwordMessage = document.createElement('span');

	passwordMessage.classList.add('pw-message');
	passwordMessage.innerText = tenupPasswords.message;

	passwordWrapper[0].appendChild(passwordMessage);
}
