import { __ } from '@wordpress/i18n';
import './boot-layout.css';

/**
 * Shared page shell for the plugin's boot admin screens: a fixed header with
 * the Fueled logo and page title, a scrollable content region, and an
 * optional footer (used by the settings screen for its save bar).
 *
 * @param {object}  props          Component props.
 * @param {string}  props.title    Page title shown in the header.
 * @param {string}  [props.logoUrl] Fueled logo URL for the header.
 * @param {object}  [props.footer]  Footer content (e.g. a save button).
 * @param {object}  props.children Page content.
 * @returns {object} The layout element.
 */
const AdminPageLayout = ({ title, logoUrl, footer, children }) => (
	<div style={{ display: 'flex', flexDirection: 'column', height: '100%' }}>
		<header className="tenup-boot-header">
			{logoUrl && (
				<img
					className="tenup-boot-header__logo"
					src={logoUrl}
					alt={__('Fueled', 'tenup')}
					width={107}
					height={20}
				/>
			)}
			<h1>{title}</h1>
		</header>
		<div className="tenup-boot-content">{children}</div>
		{footer && <footer className="tenup-boot-footer">{footer}</footer>}
	</div>
);

export default AdminPageLayout;
