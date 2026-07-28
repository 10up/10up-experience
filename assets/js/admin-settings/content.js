import { useState, useCallback, useMemo } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { Button, Spinner, Modal, Card, CardHeader, CardBody } from '@wordpress/components';
import { EntitiesSavedStates } from '@wordpress/editor';
import { __, sprintf } from '@wordpress/i18n';
import { DataForm } from '@wordpress/dataviews';
import '@wordpress/dataviews/build-style/style.css';
import AdminPageLayout from '../admin-shared/AdminPageLayout';
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
				aria-disabled={!isDirty}
				onClick={() => setIsSaveOpen(true)}
			>
				{isDirty
					? sprintf(
							/* translators: %d: number of unsaved changes */
							__('Review %d changes…', 'tenup'),
							dirtyEntityRecords.length,
						)
					: __('Saved', 'tenup')}
			</Button>
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
// Wraps content in @wordpress/components <Card> with the dataforms-layouts
// classes so it picks up the same surface chrome (white bg, rounded corners,
// 1px box-shadow border) DataForm uses for its card-layout sections, and
// drops cleanly into the same grid.
const SettingsCard = ({ title, children }) => (
	<div className="dataforms-layouts__wrapper">
		<Card className="dataforms-layouts-card__field">
			<CardHeader className="dataforms-layouts-card__field-header">
				<span className="dataforms-layouts-card__field-header-label">{title}</span>
			</CardHeader>
			<CardBody className="dataforms-layouts-card__field-control">{children}</CardBody>
		</Card>
	</div>
);

const Note = ({ tone = 'info', children }) => (
	<div className={`tenup-boot-note is-${tone}`}>
		<p>{children}</p>
	</div>
);

// ── Field definitions ───────────────────────────────────────────────────
// Everything is kept as strings in form data ("yes"/"no", "1"/"0") and
// mapped back to the option's native type in onChange, so DataForm radio
// controls can be used consistently for all binary settings.

const YES_NO = [
	{ value: 'yes', label: __('Yes', 'tenup') },
	{ value: 'no', label: __('No', 'tenup') },
];

const ON_OFF = [
	{ value: '1', label: __('Yes', 'tenup') },
	{ value: '0', label: __('No', 'tenup') },
];

const SECURITY_FIELDS = [
	{
		id: 'allowSso',
		label: __('Allow Fueled SSO', 'tenup'),
		description: __(
			'Allows members of Fueled on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.',
			'tenup',
		),
		type: 'string',
		Edit: 'radio',
		elements: YES_NO,
	},
	{
		id: 'strongPasswords',
		label: __('Require Strong Passwords', 'tenup'),
		description: __(
			'Requires stronger passwords and checks them against breach data during sign-in or password changes.',
			'tenup',
		),
		type: 'string',
		Edit: 'radio',
		elements: ON_OFF,
	},
	{
		id: 'restrictRestApi',
		label: __('REST API Availability', 'tenup'),
		description: __(
			'Restricting REST API access helps prevent unintended exposure of potentially sensitive information.',
			'tenup',
		),
		type: 'string',
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
		label: __('Disable Comments', 'tenup'),
		description: __(
			'Removes all comment-related UI from the admin and front end. Block Notes remain available.',
			'tenup',
		),
		type: 'string',
		Edit: 'radio',
		elements: YES_NO,
	},
	{
		id: 'disableGutenberg',
		label: __('Use Classic Editor', 'tenup'),
		description: __(
			'Disables the block editor in favor of the prior writing experience.',
			'tenup',
		),
		type: 'string',
		Edit: 'radio',
		elements: ON_OFF,
	},
	{
		id: 'passwordProtect',
		label: __('Enable Password Protected Content', 'tenup'),
		description: __(
			'WordPress default password protected post functionality is insecure and does not work with page caching.',
			'tenup',
		),
		type: 'string',
		Edit: 'radio',
		elements: ON_OFF,
	},
];

const MONITOR_FIELDS = [
	{
		id: 'monitorEnable',
		label: __('Enable Support Monitor', 'tenup'),
		type: 'string',
		Edit: 'radio',
		elements: YES_NO,
	},
	{
		id: 'monitorApiKey',
		label: __('API Key', 'tenup'),
		type: 'string',
		Edit: 'text',
	},
	{
		id: 'monitorServerUrl',
		label: __('API Server URL', 'tenup'),
		description: __('Only shown while Support Monitor debugging is enabled.', 'tenup'),
		type: 'string',
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
		<SettingsCard title={__('Security & Access', 'tenup')}>
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
			<p style={{ marginTop: 0 }}>
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
			allowSso: siteSettings?.[OPT_ALLOW_SSO] ?? 'yes',
			strongPasswords: String(siteSettings?.[OPT_STRONG_PASSWORDS] ?? 1),
			restrictRestApi: siteSettings?.[OPT_RESTRICT_REST_API] ?? 'users',
			disableComments: siteSettings?.[OPT_DISABLE_COMMENTS] || 'no',
			disableGutenberg: String(siteSettings?.[OPT_DISABLE_GUTENBERG] ?? 0),
			passwordProtect: String(siteSettings?.[OPT_PASSWORD_PROTECT] ?? 0),
			monitorEnable: monitorSettings.enable_support_monitor || 'no',
			monitorApiKey: monitorSettings.api_key ?? '',
			monitorServerUrl: monitorSettings.server_url ?? '',
		}),
		[siteSettings, monitorSettings],
	);

	const onChange = useCallback(
		(edits) => {
			const mapped = {};
			if ('allowSso' in edits) {
				mapped[OPT_ALLOW_SSO] = edits.allowSso;
			}
			if ('strongPasswords' in edits) {
				mapped[OPT_STRONG_PASSWORDS] = parseInt(edits.strongPasswords, 10);
			}
			if ('restrictRestApi' in edits) {
				mapped[OPT_RESTRICT_REST_API] = edits.restrictRestApi;
			}
			if ('disableComments' in edits) {
				mapped[OPT_DISABLE_COMMENTS] = edits.disableComments;
			}
			if ('disableGutenberg' in edits) {
				mapped[OPT_DISABLE_GUTENBERG] = parseInt(edits.disableGutenberg, 10);
			}
			if ('passwordProtect' in edits) {
				mapped[OPT_PASSWORD_PROTECT] = parseInt(edits.passwordProtect, 10);
			}

			const monitorEdits = {};
			if ('monitorEnable' in edits) {
				monitorEdits.enable_support_monitor = edits.monitorEnable;
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
				title={__('Experience', 'tenup')}
				logoUrl={CONFIG.logoUrl}
				footer={<SaveButton />}
			>
				<Spinner />
			</AdminPageLayout>
		);
	}

	return (
		<AdminPageLayout
			title={__('Experience', 'tenup')}
			logoUrl={CONFIG.logoUrl}
			footer={<SaveButton />}
		>
			<section>
				<h2 className="tenup-boot-section-title">{__('Plugin settings', 'tenup')}</h2>
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
