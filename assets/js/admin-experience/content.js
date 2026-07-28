import { createInterpolateElement, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { DataViews } from '@wordpress/dataviews';
import '@wordpress/dataviews/build-style/style.css';
import AdminPageLayout from '../admin-shared/AdminPageLayout';
import './admin.css';

const CONFIG = window.tenupExperiencePluginData ?? {};

const CONFIGURATION = (Array.isArray(CONFIG.configuration) ? CONFIG.configuration : []).map(
	(item, index) => ({ id: `configuration-${index}`, ...item }),
);
const FEATURES = (Array.isArray(CONFIG.features) ? CONFIG.features : []).map((item, index) => ({
	id: `feature-${index}`,
	...item,
}));

/* eslint-disable jsx-a11y/anchor-has-content, jsx-a11y/control-has-associated-label --
 * createInterpolateElement clones each anchor and fills it with the
 * translated text, so none of them are ever rendered empty. */

const ExperienceHero = () => (
	<section className="experience-hero" aria-labelledby="experience-hero-heading">
		<div className="experience-hero__content">
			<h2 id="experience-hero-heading" className="experience-hero__headline">
				{__('Experience Plugin', 'tenup')}
			</h2>

			<p className="experience-hero__text">
				{createInterpolateElement(
					__(
						'You’re seeing this page because the Fueled Experience Plugin is installed on this site. That usually means the site was built by or is supported by <a1>Fueled</a1> (<a2>previously 10up</a2>). It brings the safeguards, configuration choices, and support signals we use to help care for ambitious WordPress sites.',
						'tenup',
					),
					{
						a1: (
							<a
								href="https://fueled.com"
								target="_blank"
								rel="noopener noreferrer"
							/>
						),
						a2: (
							<a
								href="https://10up.com/blog/2025/new-fueled-brand-10up-becomes-wordpress-practice/"
								target="_blank"
								rel="noopener noreferrer"
							/>
						),
					},
				)}
			</p>
		</div>

		<div className="experience-hero__visual" aria-hidden="true">
			<img src={CONFIG.boltUrl} alt="" />
		</div>
	</section>
);

// ── Configuration & feature cards — DataViews grid layouts ──────────────
const CONFIGURATION_FIELDS = [
	{ id: 'label', label: __('Setting', 'tenup'), enableSorting: false },
	{
		id: 'value',
		label: __('Status', 'tenup'),
		enableSorting: false,
		render: ({ item }) => (
			<span className={`experience-status__value ${item.class ?? ''}`}>{item.value}</span>
		),
	},
	{
		id: 'description',
		label: __('Description', 'tenup'),
		enableSorting: false,
		render: ({ item }) => (
			<>
				{item.description}
				{(item.settings_url || item.learn_more_url) && (
					<span className="experience-status__action">
						{item.settings_url && (
							<a href={item.settings_url}>{__('Manage setting', 'tenup')}</a>
						)}
						{item.learn_more_url && (
							<a href={item.learn_more_url}>{__('Learn more', 'tenup')}</a>
						)}
					</span>
				)}
			</>
		),
	},
];

const FEATURE_FIELDS = [
	{ id: 'label', label: __('Feature', 'tenup'), enableSorting: false },
	{ id: 'description', label: __('Description', 'tenup'), enableSorting: false },
];

const CardsGrid = ({ data, fields, badgeFields }) => {
	// With badges, the description renders as a regular field so the card
	// reads title → status badge → description; the grid layout would place
	// a descriptionField above the badges otherwise.
	const [view, setView] = useState({
		type: 'grid',
		perPage: data.length,
		titleField: 'label',
		showMedia: false,
		...(badgeFields
			? { fields: [...badgeFields, 'description'], layout: { badgeFields } }
			: { descriptionField: 'description', fields: [] }),
	});

	return (
		<DataViews
			data={data}
			fields={fields}
			view={view}
			onChangeView={setView}
			search={false}
			defaultLayouts={{ grid: {} }}
			paginationInfo={{ totalItems: data.length, totalPages: 1 }}
			getItemId={(item) => item.id}
		/>
	);
};

const ExperienceConfiguration = () => (
	<section
		id="experience-configuration"
		className="experience-configuration"
		aria-labelledby="experience-configuration-heading"
	>
		<div className="experience-section-heading">
			<h2 id="experience-configuration-heading">{__('Configuration & features', 'tenup')}</h2>
		</div>

		<p className="experience-group-label">{__('Configured for this site', 'tenup')}</p>
		<CardsGrid data={CONFIGURATION} fields={CONFIGURATION_FIELDS} badgeFields={['value']} />

		<p className="experience-group-label experience-group-label--features">
			{__('Additional protection', 'tenup')}
		</p>
		<CardsGrid data={FEATURES} fields={FEATURE_FIELDS} />
	</section>
);

const ExperienceMonitor = () => (
	<section
		id="experience-monitor"
		className="experience-monitor"
		aria-labelledby="experience-monitor-heading"
	>
		<div className="experience-monitor__intro">
			<p className="about-eyebrow">{__('Proactive support', 'tenup')}</p>
			<h2 id="experience-monitor-heading">
				{__('Monitor helps us spot problems sooner.', 'tenup')}
			</h2>
			<p>
				{__(
					'When enabled, Support Monitor sends Fueled a daily technical snapshot. That gives our team a consistent view of site health and important administrative changes without collecting the content your organization publishes or receives.',
					'tenup',
				)}
			</p>
			<p className="experience-monitor__action">
				<a
					href="https://fueled.com/blog/proactive-site-support-fueled10ups-monitor/"
					className="button button-primary"
					target="_blank"
					rel="noopener noreferrer"
				>
					{__('How Monitor supports client sites', 'tenup')}
				</a>
			</p>
		</div>

		<div className="experience-monitor__details">
			<article>
				<p className="experience-monitor__label">{__('Shared when enabled', 'tenup')}</p>
				<h3>{__('Technical health signals', 'tenup')}</h3>
				<p>
					{__(
						'Site and hosting details, WordPress and PHP versions, plugin and theme status, selected system indicators, administrative events, and Fueled team accounts associated with the site.',
						'tenup',
					)}
				</p>
			</article>

			<article>
				<p className="experience-monitor__label">{__('Never shared', 'tenup')}</p>
				<h3>{__('Content & customer data', 'tenup')}</h3>
				<p>
					{__(
						'No post or page content, media, form submissions, or names and email addresses for non-Fueled customer accounts are included in the daily report. Administrative events may reference a site-local user ID.',
						'tenup',
					)}
				</p>
			</article>

			<p className="experience-monitor__note">
				{__(
					'Support monitoring is optional and is automatically disabled in local environments unless explicitly enabled in configuration.',
					'tenup',
				)}
			</p>
		</div>
	</section>
);

const ExperienceSource = () => (
	<section className="experience-source" aria-labelledby="experience-source-heading">
		<div>
			<p className="about-eyebrow">{__('Built for transparency', 'tenup')}</p>
			<h2 id="experience-source-heading">{__('Want the technical details?', 'tenup')}</h2>
			<p>
				{__(
					'Review the source code and technical documentation on GitHub, or talk with Fueled about how this site is supported.',
					'tenup',
				)}
			</p>
		</div>

		<p className="experience-actions">
			<a
				href="https://github.com/10up/10up-experience"
				className="button button-primary"
				target="_blank"
				rel="noopener noreferrer"
			>
				{__('View source on GitHub', 'tenup')}
			</a>
			<a
				href="https://fueled.com/contact/"
				className="button"
				target="_blank"
				rel="noopener noreferrer"
			>
				{__('Contact Fueled', 'tenup')}
			</a>
		</p>
	</section>
);

const ExperienceStage = () => (
	<AdminPageLayout title={__('Experience Plugin', 'tenup')} logoUrl={CONFIG.logoUrl}>
		<div className="tenup-boot-page about-wrap experience-wrap">
			<ExperienceHero />
			<ExperienceConfiguration />
			<ExperienceMonitor />
			<ExperienceSource />
		</div>
	</AdminPageLayout>
);

/* eslint-enable jsx-a11y/anchor-has-content, jsx-a11y/control-has-associated-label */

export const stage = ExperienceStage;
