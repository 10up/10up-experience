import { createInterpolateElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import AdminPageLayout from '../admin-shared/AdminPageLayout';
import './admin.css';

const CONFIG = window.tenupExperienceAboutData ?? {};

/* eslint-disable jsx-a11y/anchor-has-content, jsx-a11y/control-has-associated-label --
 * createInterpolateElement clones each anchor and fills it with the
 * translated text, so none of them are ever rendered empty. */

const AboutHero = () => (
	<section className="about-hero" aria-labelledby="about-fueled-heading">
		<div className="about-hero__content">
			<h2 id="about-fueled-heading" className="about-hero__headline">
				{__('Digital done right.', 'tenup')}
			</h2>

			<p className="about-hero__text">
				{createInterpolateElement(
					__(
						'Fueled (<a>previously 10up</a>) is a global digital agency that helps ambitious organizations build digital experiences that win users over and move their businesses forward.',
						'tenup',
					),
					{
						a: (
							<a
								href="https://10up.com/blog/2025/new-fueled-brand-10up-becomes-wordpress-practice/"
								target="_blank"
								rel="noopener noreferrer"
							/>
						),
					},
				)}
			</p>

			<p className="about-actions about-hero__actions">
				<a
					href="https://fueled.com"
					className="button button-primary"
					target="_blank"
					rel="noopener noreferrer"
				>
					{__('Visit Fueled', 'tenup')}
				</a>
				<a
					href="https://fueled.com/wordpress/"
					className="button"
					target="_blank"
					rel="noopener noreferrer"
				>
					{__('Explore our WordPress work', 'tenup')}
				</a>
			</p>
		</div>
	</section>
);

const CAPABILITIES = [
	__('Strategy', 'tenup'),
	__('Design', 'tenup'),
	__('Build', 'tenup'),
	__('Grow', 'tenup'),
	__('AI', 'tenup'),
];

const AboutCapabilities = () => (
	<section className="about-capabilities" aria-labelledby="about-capabilities-heading">
		<div className="about-capabilities__copy">
			<p className="about-eyebrow">{__('One team, end to end', 'tenup')}</p>
			<h2 id="about-capabilities-heading">
				{__(
					'From strategy through production and growth. Under one virtual roof.',
					'tenup',
				)}
			</h2>
			<p>
				{__(
					'We bring strategy, design, engineering, growth, and AI together with standout craftsmanship to create digital products and platforms built for impact.',
					'tenup',
				)}
			</p>
		</div>

		<ol className="about-capability-list" aria-label={__('Fueled capabilities', 'tenup')}>
			{CAPABILITIES.map((capability, index) => (
				<li key={capability}>
					<span>{String(index + 1).padStart(2, '0')}</span>
					{capability}
				</li>
			))}
		</ol>
	</section>
);

const AboutProofGrid = () => (
	<div className="about-proof-grid">
		<section
			className="about-proof-card about-proof-card--violet"
			aria-labelledby="about-team-heading"
		>
			<p className="about-eyebrow">{__('Our team', 'tenup')}</p>
			<h2 id="about-team-heading">{__('300+ experts', 'tenup')}</h2>
			<p>
				{__(
					'A globally distributed team bringing strategy, design, engineering, growth, and AI together.',
					'tenup',
				)}
			</p>
		</section>

		<section
			className="about-proof-card about-proof-card--dark"
			aria-labelledby="about-enterprise-heading"
		>
			<p className="about-eyebrow">{__('Platform expertise', 'tenup')}</p>
			<h2 id="about-enterprise-heading">{__('Enterprise-grade WordPress', 'tenup')}</h2>
			<p>
				{__(
					'Deep architecture and multisite expertise for component-based builds, editorial workflows, and hosting integrations.',
					'tenup',
				)}
			</p>
		</section>

		<section
			className="about-proof-card about-proof-card--clients"
			aria-labelledby="about-clients-heading"
		>
			<div className="about-proof-card__content">
				<p className="about-eyebrow">{__('Battle-tested experience', 'tenup')}</p>
				<h2 id="about-clients-heading">
					{__('Proven with the world’s most demanding brands.', 'tenup')}
				</h2>
				<p>
					{__(
						'Fueled teams have partnered with category-defining brands, global publishers, and public institutions to solve complex digital challenges at scale.',
						'tenup',
					)}
				</p>
			</div>

			<div
				className="about-client-logos"
				role="img"
				aria-label={__(
					'Selected clients, including Google, Apple, The Wall Street Journal, Campbell’s, Under Armour, The White House, Clinique, Salesforce, and Hilton.',
					'tenup',
				)}
			>
				{Array.from({ length: 18 }, (_, index) => (
					<span key={index} aria-hidden="true" />
				))}
			</div>
		</section>

		<section
			className="about-proof-card about-proof-card--open"
			aria-labelledby="about-open-heading"
		>
			<div className="about-proof-card__wordpress-mark" aria-hidden="true">
				<img src={CONFIG.wordpressLogoUrl} alt="" />
			</div>

			<div className="about-proof-card__content">
				<p className="about-eyebrow">
					{__('We don’t just make WordPress sites.', 'tenup')}
				</p>
				<h2 id="about-open-heading">{__('We make WordPress.', 'tenup')}</h2>

				<p>
					{createInterpolateElement(
						__(
							'Our team helps lead major WordPress releases and the official WordPress Core AI team, contributing code, tools, and ideas back to the project. We also build open-source products used across the ecosystem, including <a1>Distributor</a1>, <a2>ClassifAI</a2>, and <a3>ElasticPress</a3>.',
							'tenup',
						),
						{
							a1: (
								<a
									href="https://distributorplugin.com/"
									target="_blank"
									rel="noopener noreferrer"
								/>
							),
							a2: (
								<a
									href="https://classifaiplugin.com/"
									target="_blank"
									rel="noopener noreferrer"
								/>
							),
							a3: (
								<a
									href="https://www.elasticpress.io/"
									target="_blank"
									rel="noopener noreferrer"
								/>
							),
						},
					)}
				</p>
			</div>
		</section>
	</div>
);

const AboutStage = () => (
	<AdminPageLayout title={__('About', 'tenup')} logoUrl={CONFIG.logoUrl}>
		<div className="tenup-boot-page about-wrap about-fueled-wrap">
			<AboutHero />
			<AboutCapabilities />
			<AboutProofGrid />

			<p className="about-actions about-actions--footer">
				<a
					href="https://fueled.com"
					className="button button-primary"
					target="_blank"
					rel="noopener noreferrer"
				>
					{__('Visit Fueled', 'tenup')}
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
		</div>
	</AdminPageLayout>
);

/* eslint-enable jsx-a11y/anchor-has-content, jsx-a11y/control-has-associated-label */

export const stage = AboutStage;
