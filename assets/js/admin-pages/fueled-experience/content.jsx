import { useState, useCallback, useMemo } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { Button, Spinner, Modal } from '@wordpress/components';
import { EntitiesSavedStates } from '@wordpress/editor';
import { __, sprintf } from '@wordpress/i18n';
import { DataForm } from '@wordpress/dataviews';
import { Card, Notice } from '@wordpress/ui';
import '@wordpress/dataviews/build-style/style.css';
import AdminPageLayout from '../shared/AdminPageLayout';
import './admin.css';

const CONFIG = window.tenupExperienceSettingsData ?? {};

// ── Option keys (must match PHP register_setting keys) ──────────────────
const OPT_ALLOW_SSO = 'tenup_allow_sso';
const OPT_STRONG_PASSWORDS = 'tenup_require_strong_passwords';
const OPT_RESTRICT_REST_API = 'tenup_restrict_rest_api';
const OPT_DISABLE_COMMENTS = 'tenup_disable_comments';
const OPT_DISABLE_GUTENBERG = 'tenup_disable_gutenberg';
const OPT_PASSWORD_PROTECT = 'tenup_password_protect';
const OPT_MONITOR = 'tenup_support_monitor_settings';

// ── Shared layout primitives ────────────────────────────────────────────
// Mirrors the WP-boot admin page layout (header / scrollable content /
// save footer) used by other boot-based settings screens.

const SaveButton = () => {
	const [isSaveOpen, setIsSaveOpen] = useState(false);

	const { dirtyEntityRecords } = useSelect(
		(select) => ({
			dirtyEntityRecords: select('core').__experimentalGetDirtyEntityRecords(),
		}),
		[],
	);

	const isDirty = dirtyEntityRecords.length > 0;

	return (
		<>
			<Button
				variant="primary"
				disabled={!isDirty}
				accessibleWhenDisabled
				onClick={() => setIsSaveOpen(true)}
			>
				{isDirty
					? sprintf(
							/* translators: %d: number of unsaved changes */
							__('Review %d changes…', 'tenup'),
							dirtyEntityRecords.length,
						)
					: __('Review changes', 'tenup')}
			</Button>
			<p className="tenup-boot-footer__status" aria-live="polite">
				{isDirty ? __('Unsaved changes', 'tenup') : __('All changes saved', 'tenup')}
			</p>
			{isSaveOpen && (
				<Modal
					className="edit-site-save-panel__modal"
					onRequestClose={() => setIsSaveOpen(false)}
					title={__('Review changes', 'tenup')}
					size="small"
				>
					<EntitiesSavedStates close={() => setIsSaveOpen(false)} />
				</Modal>
			)}
		</>
	);
};

// ── Card primitive ──────────────────────────────────────────────────────
// The same @wordpress/ui Card (and classnames) DataForm's card layout
// renders, so these cards match DataForm's padding, border and title.
const SettingsCard = ({ title, children }) => (
	<Card.Root className="dataforms-layouts-card__field tenup-boot-card">
		<Card.Header>
			<div className="dataforms-layouts-card__field-header-content">
				<Card.Title>{title}</Card.Title>
			</div>
		</Card.Header>
		<Card.Content>
			<div className="tenup-boot-card__body">{children}</div>
		</Card.Content>
	</Card.Root>
);

const Note = ({ intent = 'info', children }) => (
	<Notice.Root intent={intent}>
		<Notice.Description>{children}</Notice.Description>
	</Notice.Root>
);

// ── Field definitions ───────────────────────────────────────────────────
// Binary settings are booleans in form data (DataForm toggles) and mapped
// to the option's stored type ("yes"/"no" or 1/0) in onChange.

const SECURITY_FIELDS = [
	{
		id: 'allowSso',
		label: __('Allow Fueled SSO', 'tenup'),
		description: __(
			'Allows members of Fueled on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.',
			'tenup',
		),
		type: 'boolean',
		Edit: 'toggle',
	},
	{
		id: 'strongPasswords',
		label: __('Require strong passwords', 'tenup'),
		description: __(
			'Requires stronger passwords and checks them against breach data during sign-in or password changes.',
			'tenup',
		),
		type: 'boolean',
		Edit: 'toggle',
	},
	{
		id: 'restrictRestApi',
		label: __('REST API availability', 'tenup'),
		description: __(
			'Restricting REST API access helps prevent unintended exposure of potentially sensitive information.',
			'tenup',
		),
		type: 'text',
		Edit: 'radio',
		elements: [
			{ value: 'all', label: __('Restrict all access to authenticated users', 'tenup') },
			{
				value: 'users',
				label: __('Restrict access to the users endpoint to authenticated users', 'tenup'),
			},
			{ value: 'none', label: __('Publicly accessible', 'tenup') },
		],
	},
];

const EDITORIAL_FIELDS = [
	{
		id: 'disableComments',
		label: __('Disable comments', 'tenup'),
		description: __(
			'Removes all comment-related UI from the admin and front end. Block Notes remain available.',
			'tenup',
		),
		type: 'boolean',
		Edit: 'toggle',
	},
	{
		id: 'disableGutenberg',
		label: __('Use the Classic Editor', 'tenup'),
		description: __(
			'Disables the block editor in favor of the prior writing experience.',
			'tenup',
		),
		type: 'boolean',
		Edit: 'toggle',
	},
	{
		id: 'passwordProtect',
		label: __('Allow password-protected content', 'tenup'),
		description: __(
			'WordPress default password protected post functionality is insecure and does not work with page caching.',
			'tenup',
		),
		type: 'boolean',
		Edit: 'toggle',
	},
];

const MONITOR_FIELDS = [
	{
		id: 'monitorEnable',
		label: __('Enable Support Monitor', 'tenup'),
		type: 'boolean',
		Edit: 'toggle',
	},
	{
		id: 'monitorApiKey',
		label: __('API key', 'tenup'),
		type: 'text',
		Edit: 'text',
	},
	{
		id: 'monitorServerUrl',
		label: __('API Server URL', 'tenup'),
		description: __('Only shown while Support Monitor debugging is enabled.', 'tenup'),
		type: 'text',
		Edit: 'text',
	},
];

/**
 * Build the visible-field list for a card, honoring lock flags supplied by
 * PHP (settings forced by constants or filters are hidden from the form and
 * replaced with an explanatory note).
 *
 * @param {Array}    fields Field definitions.
 * @param {string[]} hidden Field ids to hide.
 * @returns {{fields: string[]}} DataForm form config.
 */
const visibleForm = (fields, hidden = []) => ({
	fields: fields.map((f) => f.id).filter((id) => !hidden.includes(id)),
});

// ── Cards ────────────────────────────────────────────────────────────────

const SecurityCard = ({ formData, onChange }) => {
	const hidden = [];
	if (!CONFIG.sso?.available) {
		hidden.push('allowSso');
	}
	if (!CONFIG.strongPasswords?.available) {
		hidden.push('strongPasswords');
	}
	if (!CONFIG.restApi?.available) {
		hidden.push('restrictRestApi');
	}

	return (
		<SettingsCard title={__('Security and access', 'tenup')}>
			{!CONFIG.sso?.available && (
				<Note>{__('Fueled SSO has been disabled in code for this site.', 'tenup')}</Note>
			)}
			<DataForm
				data={formData}
				fields={SECURITY_FIELDS}
				form={visibleForm(SECURITY_FIELDS, hidden)}
				onChange={onChange}
			/>
		</SettingsCard>
	);
};

const EditorialCard = ({ formData, onChange }) => {
	const hidden = [];
	if (CONFIG.comments?.locked) {
		hidden.push('disableComments');
	}

	return (
		<SettingsCard title={__('Editorial', 'tenup')}>
			{CONFIG.comments?.locked && (
				<Note>
					{__(
						'The comments setting is controlled in code for this site and cannot be changed here.',
						'tenup',
					)}
				</Note>
			)}
			<DataForm
				data={formData}
				fields={EDITORIAL_FIELDS}
				form={visibleForm(EDITORIAL_FIELDS, hidden)}
				onChange={onChange}
			/>
		</SettingsCard>
	);
};

const MonitorCard = ({ formData, onChange }) => {
	const hidden = [];
	if (CONFIG.monitor?.enableLocked) {
		hidden.push('monitorEnable');
	}
	if (CONFIG.monitor?.apiKeyLocked) {
		hidden.push('monitorApiKey');
	}
	if (!CONFIG.monitor?.serverUrlVisible) {
		hidden.push('monitorServerUrl');
	}

	return (
		<SettingsCard title={__('Support Monitor', 'tenup')}>
			<p>
				{__(
					'Fueled collects site-health information, including plugin, WordPress, and system versions, general site issues, and Fueled team accounts associated with the site, to provide proactive support. It does not send proprietary site content or customer account information. Although recommended, this functionality is optional and can be disabled.',
					'tenup',
				)}
			</p>
			{CONFIG.monitor?.isLocalEnvironment && (
				<Note>
					{__(
						'Local environment detected: support monitoring is automatically disabled in local environments to prevent test/development data from being sent to the monitoring service.',
						'tenup',
					)}
				</Note>
			)}
			{(CONFIG.monitor?.enableLocked || CONFIG.monitor?.apiKeyLocked) && (
				<Note>
					{__(
						'Some Support Monitor settings are defined in code for this site and cannot be changed here.',
						'tenup',
					)}
				</Note>
			)}
			<DataForm
				data={formData}
				fields={MONITOR_FIELDS}
				form={visibleForm(MONITOR_FIELDS, hidden)}
				onChange={onChange}
			/>
		</SettingsCard>
	);
};

// ── Stage ────────────────────────────────────────────────────────────────
const SettingsStage = () => {
	const siteSettings = useSelect(
		(select) => select('core').getEditedEntityRecord('root', 'site'),
		[],
	);

	const hasResolved = useSelect(
		(select) => select('core').hasFinishedResolution('getEntityRecord', ['root', 'site']),
		[],
	);

	const { editEntityRecord } = useDispatch('core');

	const monitorSettings = useMemo(
		() => ({
			enable_support_monitor: 'no',
			api_key: '',
			server_url: '',
			...(siteSettings?.[OPT_MONITOR] ?? {}),
		}),
		[siteSettings],
	);

	const formData = useMemo(
		() => ({
			allowSso: (siteSettings?.[OPT_ALLOW_SSO] ?? 'yes') === 'yes',
			strongPasswords: Number(siteSettings?.[OPT_STRONG_PASSWORDS] ?? 1) === 1,
			restrictRestApi: siteSettings?.[OPT_RESTRICT_REST_API] ?? 'users',
			disableComments: siteSettings?.[OPT_DISABLE_COMMENTS] === 'yes',
			disableGutenberg: Number(siteSettings?.[OPT_DISABLE_GUTENBERG] ?? 0) === 1,
			passwordProtect: Number(siteSettings?.[OPT_PASSWORD_PROTECT] ?? 0) === 1,
			monitorEnable: monitorSettings.enable_support_monitor === 'yes',
			monitorApiKey: monitorSettings.api_key ?? '',
			monitorServerUrl: monitorSettings.server_url ?? '',
		}),
		[siteSettings, monitorSettings],
	);

	const onChange = useCallback(
		(edits) => {
			const mapped = {};
			if ('allowSso' in edits) {
				mapped[OPT_ALLOW_SSO] = edits.allowSso ? 'yes' : 'no';
			}
			if ('strongPasswords' in edits) {
				mapped[OPT_STRONG_PASSWORDS] = edits.strongPasswords ? 1 : 0;
			}
			if ('restrictRestApi' in edits) {
				mapped[OPT_RESTRICT_REST_API] = edits.restrictRestApi;
			}
			if ('disableComments' in edits) {
				mapped[OPT_DISABLE_COMMENTS] = edits.disableComments ? 'yes' : 'no';
			}
			if ('disableGutenberg' in edits) {
				mapped[OPT_DISABLE_GUTENBERG] = edits.disableGutenberg ? 1 : 0;
			}
			if ('passwordProtect' in edits) {
				mapped[OPT_PASSWORD_PROTECT] = edits.passwordProtect ? 1 : 0;
			}

			const monitorEdits = {};
			if ('monitorEnable' in edits) {
				monitorEdits.enable_support_monitor = edits.monitorEnable ? 'yes' : 'no';
			}
			if ('monitorApiKey' in edits) {
				monitorEdits.api_key = edits.monitorApiKey;
			}
			if ('monitorServerUrl' in edits) {
				monitorEdits.server_url = edits.monitorServerUrl;
			}
			if (Object.keys(monitorEdits).length) {
				const merged = { ...monitorSettings, ...monitorEdits };
				// An empty server URL means "use the default" — leave the key
				// out so Monitor's own fallback applies.
				if (!merged.server_url) {
					delete merged.server_url;
				}
				mapped[OPT_MONITOR] = merged;
			}

			editEntityRecord('root', 'site', undefined, mapped);
		},
		[editEntityRecord, monitorSettings],
	);

	if (!hasResolved) {
		return (
			<AdminPageLayout
				title={__('Experience settings', 'tenup')}
				logoUrl={CONFIG.logoUrl}
				footer={<SaveButton />}
			>
				<Spinner />
			</AdminPageLayout>
		);
	}

	return (
		<AdminPageLayout
			title={__('Experience settings', 'tenup')}
			logoUrl={CONFIG.logoUrl}
			footer={<SaveButton />}
		>
			<section className="tenup-boot-settings">
				<p className="tenup-boot-section-description">
					{__(
						'The Fueled Experience plugin configures WordPress to help protect and support this site according to Fueled’s best practices. These settings are also available on the classic General, Writing, and Reading settings screens.',
						'tenup',
					)}
				</p>
				<div className="tenup-boot-cards">
					<SecurityCard formData={formData} onChange={onChange} />
					<EditorialCard formData={formData} onChange={onChange} />
					<MonitorCard formData={formData} onChange={onChange} />
				</div>
			</section>
		</AdminPageLayout>
	);
};

export const stage = SettingsStage;
