import { createInterpolateElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import AdminPageLayout from '../shared/AdminPageLayout';

const CONFIG = window.tenupExperiencePluginData ?? {};
const CONFIGURATION = Array.isArray(CONFIG.configuration) ? CONFIG.configuration : [];
const FEATURES = Array.isArray(CONFIG.features) ? CONFIG.features : [];

/* eslint-disable jsx-a11y/anchor-has-content, jsx-a11y/control-has-associated-label --
 * createInterpolateElement clones each anchor and fills it with the
 * translated text, so none of them are ever rendered empty. */

const ExperienceHero = () => (
	<section className="experience-hero" aria-labelledby="experience-hero-heading">
		<div className="experience-hero__content">
			<h2 id="experience-hero-heading" className="experience-hero__headline">
				{__('Safeguards and support for this site.', 'tenup')}
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
	</section>
);

// Eight fixed facts are a list, not a data set: name and what it does on
// the left, the current state and its setting on the right.
const ExperienceConfiguration = () => (
	<section
		id="experience-configuration"
		className="experience-configuration"
		aria-labelledby="experience-configuration-heading"
	>
		<h2 id="experience-configuration-heading">{__('How this site is configured', 'tenup')}</h2>

		<ul className="experience-list">
			{CONFIGURATION.map((item) => (
				<li key={item.label} className="experience-list__row">
					<div className="experience-list__text">
						<h3>{item.label}</h3>
						<p>{item.description}</p>
					</div>
					<p className={`experience-status__value ${item.class ?? ''}`}>{item.value}</p>
					{item.settings_url && (
						<a className="experience-list__action" href={item.settings_url}>
							{__('Manage', 'tenup')}
							<span className="screen-reader-text"> {item.label}</span>
						</a>
					)}
				</li>
			))}
		</ul>

		<h3 className="experience-features__heading">{__('Always on', 'tenup')}</h3>
		<dl className="experience-features">
			{FEATURES.map((feature) => (
				<div key={feature.label}>
					<dt>{feature.label}</dt>
					<dd>{feature.description}</dd>
				</div>
			))}
		</dl>
	</section>
);

const ExperienceMonitor = () => (
	<section
		id="experience-monitor"
		className="experience-monitor"
		aria-labelledby="experience-monitor-heading"
	>
		<div className="experience-monitor__intro">
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
