<?php
/**
 * Designs and Plugins Feed
 *
 * @package WP Team Showcase and Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wrap pap-wrap">
	<style type="text/css">
		.wpos-box{box-shadow: 0 5px 30px 0 rgba(214,215,216,.57);background: #fff; padding-bottom:10px; position:relative;}
		.wpos-box ul{padding: 15px;}
		.wpos-box h5{background:#555; color:#fff; padding:15px; text-align:center;}
		.wpos-box h4{ padding:0 15px; margin:5px 0; font-size:18px;}
		.wpos-box .button{margin:0px 15px 15px 15px; text-align:center; padding:7px 15px; font-size:15px;display:inline-block;}
		.wpos-box .wpos-list{list-style:square; margin:10px 0 0 20px;}
		.wpos-clearfix:before, .wpos-clearfix:after{content: "";display: table;}
		.wpos-clearfix::after{clear: both;}
		.wpos-clearfix{clear: both;}
		.wpos-col{width: 47%; float: left; margin-right:10px; margin-bottom:10px;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.pap-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.pap-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.wpos-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
	</style>
	<h2><?php esc_html_e( 'How It Works - Display and Shortcode', 'wp-team-showcase-and-slider' ); ?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<!--How it workd HTML -->
			<div id="post-body-content">
				<div class="meta-box-sortables">

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'How It Works - Display and Shortcode', 'wp-team-showcase-and-slider' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('Getting Started', 'wp-team-showcase-and-slider'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1: This plugin create a Team Showcase tab in WordPress menu section', 'wp-team-showcase-and-slider'); ?></li>
												<li><?php esc_html_e('Step-2: Now, paste below shortcode in any post or page and your WP Team Showcase and Slider is ready !!!', 'wp-team-showcase-and-slider'); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('All Shortcodes', 'wp-team-showcase-and-slider'); ?>:</label>
										</th>
										<td>
											<span class="wpos-copy-clipboard pap-shortcode-preview">[wp-team]</span> – <?php esc_html_e('Team Showcase Grid', 'wp-team-showcase-and-slider'); ?> <br />
											<span class="wpos-copy-clipboard pap-shortcode-preview">[wp-team-slider]</span> – <?php esc_html_e('Team Showcase Slider', 'wp-team-showcase-and-slider'); ?> <br />
										</td>
									</tr>
									<tr>
										<th>
											<label><?php esc_html_e('Documentation', 'countdown-timer-ultimate'); ?>:</label>
										</th>
										<td>
											<a class="button button-primary" href="https://docs.essentialplugin.com/wp-team-showcase-and-slider/" target="_blank"><?php esc_html_e('Check Documentation', 'countdown-timer-ultimate'); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Gutenberg Support', 'wp-team-showcase-and-slider' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('How it Work', 'wp-team-showcase-and-slider'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Go to the Gutenberg editor of your page.', 'wp-team-showcase-and-slider'); ?></li>
												<li><?php esc_html_e('Step-2. Search "Team" keyword in the Gutenberg block list.', 'wp-team-showcase-and-slider'); ?></li>
												<li><?php esc_html_e('Step-3. Add any block of Team and you will find its relative options on the right end side.', 'wp-team-showcase-and-slider'); ?></li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Help to improve this plugin!', 'wp-team-showcase-and-slider' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<p>Enjoyed this plugin? You can help by rate this plugin <a href="https://wordpress.org/support/plugin/wp-team-showcase-and-slider/reviews/" target="_blank">5 stars!</a></p>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-body-content -->

			<!--Upgrad to Pro HTML -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">

						<h3 class="hndle">
							<span><?php esc_html_e( 'Upgrate to Pro', 'wp-team-showcase-and-slider' ); ?></span>
						</h3>
						<div class="inside">
							<ul class="wpos-list">
								<li>Added 2 shortcodes with various parameters. [wp-team] – Grid Shortcode [wp-team-slider] – Slider Shortcode</li>
								<li>25 stunning and cool designs.</li>
								<li>Display team member in a grid view.</li>
								<li>Display team member in a slider view.</li>
								<li>Display team member details in a popup.</li>
								<li>2 popup designs for team members.</li>
								<li>2 popup theme (Light – Dark) for team members.</li>
								<li>WPBakery Page Builder Supports</li>
								<li>Gutenberg, Elementor, Beaver and SiteOrigin Page Builder Support. <span class="wpos-new-feature">New</span></li>
								<li>Divi Page Builder Native Support. <span class="wpos-new-feature">New</span></li>
								<li>Fusion Page Builder (Avada) native support.<span class="wpos-new-feature">New</span></li>
								<li>WP Templating Features</li>
								<li>Slider RTL support.</li>
								<li>Display team showcase categories wise.</li>
								<li>Drag & Drop team members to display in your desired order.</li>
								<li>Strong shortcode parameters.</li>
								<li>Fully Responsive.</li>
								<li>100% Multilanguage.</li>
							</ul>
							<div class="upgrade-to-pro">Gain access to <strong>WP Team Showcase and Slider</strong> included in <br /><strong>Essential Plugin Bundle</div>
							<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url(WP_TSAS_PLUGIN_LINK_UNLOCK); ?>" target="_blank"><?php esc_html_e('Upgrade To PRO', 'wp-team-showcase-and-slider'); ?></a>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-container-1 -->
		</div><!-- #post-body -->
	</div><!-- #poststuff -->
</div>