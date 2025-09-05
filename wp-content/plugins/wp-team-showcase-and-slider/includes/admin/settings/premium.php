<?php
/**
 * Plugin Premium Offer Page
 *
 * @package wp-team-showcase-and-slider
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<h2 style="text-align: center;"><?php esc_html_e( 'WP Team Showcase and Slider with ', 'wp-team-showcase-and-slider' ); ?><span class="wpos-epb"><?php esc_html_e( 'Essential Plugin Bundle', 'wp-team-showcase-and-slider' ); ?></h2><br />	

	<style>
		.wpos-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #2ECC71;}
		.wpos-plugin-pricing-table thead th h2 + p{font-size: 1.25em; line-height: 1.4; color: #999; margin:5px 0 5px 0;}

		table.wpos-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

		.wpos-plugin-pricing-table th, .wpos-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}

		.wpos-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.wpos-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.wpos-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #2ECC71; }

		/* Tablehead */
		.wpos-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.wpos-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.wpos-plugin-pricing-table thead th:nth-child(3) p{color:#000;}

		/* Tablebody */
		.wpos-plugin-pricing-table tbody th{background: #fff; border-left: 1px solid #ccc; font-weight: 600;}
		.wpos-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}

		.wpos-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.wpos-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.wpos-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.wpos-plugin-pricing-table tbody td .dashicons-yes{color: #2ECC71;}

		.wpos-plugin-pricing-table tbody tr:nth-child(even) th,
		.wpos-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		.wpos-plugin-pricing-table tbody tr:last-child td {border-bottom: 0 none;}

		/* Table Footer */
		.wpos-plugin-pricing-table tfoot th, .wpos-plugin-pricing-table tfoot td{text-align: center; border-top: 1px solid #ccc;}
		.wpos-plugin-pricing-table tfoot a, .wpos-plugin-pricing-table thead a{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}
		
		.wpos-epb{color:#ff2700 !important;}
		
		/* welcome-screen-css start -M */
		.wp-tsas-sf-btn{display: inline-block; font-size: 18px; padding: 10px 25px; border-radius: 100px;  background-color: #ff5d52; border-color: #ff5d52; color: #fff !important; font-weight: 600; text-decoration: none;}
		.wp-tsas-sf-btn:hover,
		.wp-tsas-sf-btn:focus{background-color: #ff5d52; border-color: #ff5d52;}
		.wp-tsas-inner-Bonus-class{background: #46b450;
		  border-radius: 20px;
		  font-weight: 700;
		  padding: 5px 10px;
		  color: #fff;
		    line-height: 1;
		  font-size: 12px;}

		.wp-tsas-black-friday-feature{padding: 30px 40px;
		  background: #fafafa;
		  border-radius: 20px 20px 0 0;
		  gap: 60px;
		  align-items: center;
		  flex-direction: row;
		  display: flex;}
		.wp-tsas-black-friday-feature .wp-tsas-inner-deal-class{flex-direction: column;
		  gap: 15px;
		  display: flex;
		  align-items: flex-start;}
		.wp-tsas-black-friday-feature ul li{text-align: left;}
		.wp-tsas-black-friday-feature .wp-tsas-inner-list-class {
		  display: grid;
		  grid-template-columns: repeat(4,1fr);
		  gap: 10px;
		}
		.wp-tsas-black-friday-feature .wp-tsas-list-img-class {
		  min-height: 95px;
		  display: flex;
		  align-items: center;
		  background: #fff;
		  border-radius: 20px;
		  flex-direction: column;
		  gap: 10px;
		  justify-content: center;
		  padding: 10px;color: #000;
		  font-size: 12px;
		}
		.wp-tsas-black-friday-banner-wrp .wp-tsas-list-img-class img {
		  width: 100%;
		  flex: 0 0 40px;
		  font-size: 20px;
		  height: 40px;
		  width: 40px;
		  box-shadow: inset 0px 0px 15px 2px #c4f2ac;
		  border-radius: 14px;
		  display: flex;
		  justify-content: center;
		  align-items: center;
		  padding: 10px;
		}

		.wp-tsas-main-feature-item{background: #fafafa;
		  padding: 20px 15px 40px;
		  border-radius: 0 0 20px 20px;margin-bottom: 40px;}
		.wp-tsas-inner-feature-item{display: flex;
		  gap: 30px;
		  padding: 0 15px;}
		.wp-tsas-list-feature-item {
		  border: 1px solid #ddd;
		  padding: 10px 15px;
		  border-radius: 8px;text-align: left;
		}
		.wp-tsas-list-feature-item img {
		  width: 36px !important;
		  padding: 5px;
		  border: 1px solid #ccc;
		  border-radius: 50%;margin-bottom: 5px;
		}
		.wp-tsas-list-feature-item h5{margin: 0;
		  font-weight: bold;font-size: 16px;
		  text-decoration: underline;
		  text-underline-position: under;
		  color: #000;}
		.wp-tsas-list-feature-item p {
		  color: #505050;
		  font-size: 12px;
		  margin-bottom: 0;
		}
		/* welcome-screen-css end -M */

		/* SideBar */
		.wpos-sidebar .wpos-epb-wrap{background:#0055fb; color:#fff; padding:15px;}
		.wpos-sidebar .wpos-epb-wrap  h2{font-size:24px !important; color:#fff; margin:0 0 15px 0; padding:0px !important;}
		.wpos-sidebar .wpos-epb-wrap  h2 span{font-size:20px !important; color:#ffff00 !important;}
		.wpos-sidebar .wpos-epb-wrap ul li{font-size:16px; margin-bottom:8px;}
		.wpos-sidebar .wpos-epb-wrap ul li span{color:#ffff00 !important;}
		.wpos-sidebar .wpos-epb-wrap ul{list-style: decimal inside none;}
		.wpos-sidebar .wpos-epb-wrap b{font-weight:bold !important;}
		.wpos-sidebar .wpos-epb-wrap p{font-size:16px;}
		.wpos-sidebar .wpos-epb-wrap .button-yellow{font-weight: 600;color: #000; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ffff00;border-radius: .2em;}
		.wpos-sidebar .wpos-epb-wrap .button-orange{font-weight: 600;color: #fff; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ff2700;border-radius: .2em;}
	</style>
	
	<!-- <div class="wp-tsas-black-friday-banner-wrp">
		<a href="<?php // echo esc_url( WP_TSAS_PLUGIN_LINK_UPGRADE ); ?>" target="_blank"><img style="width: 100%;" src="<?php // echo esc_url( WP_TSAS_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
	</div> -->

	<div class="wp-tsas-black-friday-banner-wrp" style="background:#e1ecc8;padding: 20px 20px 40px; border-radius:5px; text-align:center;margin-bottom: 40px;">
		<h2 style="font-size:30px; margin-bottom:10px;"><span style="color:#0055fb;">WP Team Showcase and Slider</span> is included in <span style="color:#0055fb;">Essential Plugin Bundle</span> </h2> 
		<h4 style="font-size: 18px;margin-top: 0px;color: #ff5d52;margin-bottom: 24px;">Now get Designs, Optimization, Security, Backup, Migration Solutions @ one stop. </h4>

		<div class="wp-tsas-black-friday-feature">

			<div class="wp-tsas-inner-deal-class" style="width:40%;">
				<div class="wp-tsas-inner-Bonus-class">Bonus</div>
				<div class="wp-tsas-image-logo" style="font-weight: bold;font-size: 26px;color: #222;"><img style="width: 34px; height:34px;vertical-align: middle;margin-right: 5px;" class="wp-tsas-img-logo" src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/essential-logo-small.png" alt="essential-logo" /><span class="wp-tsas-esstial-name" style="color:#0055fb;">Essential </span>Plugin</div>
				<div class="wp-tsas-sub-heading" style="font-size: 16px;text-align: left;font-weight: bold;color: #222;margin-bottom: 10px;">Includes All premium plugins at no extra cost.</div>
				<a class="wp-tsas-sf-btn" href="<?php echo esc_url( WP_TSAS_PLUGIN_LINK_UPGRADE ); ?>" target="_blank">Grab The Deal</a>
			</div>

			<div class="wp-tsas-main-list-class" style="width:60%;">
				<div class="wp-tsas-inner-list-class">
					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/img-slider.png" alt="essential-logo" /> Image Slider</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/advertising.png" alt="essential-logo" /> Publication</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/marketing.png" alt="essential-logo" /> Marketing</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/photo-album.png" alt="essential-logo" /> Photo album</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/showcase.png" alt="essential-logo" /> Showcase</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/shopping-bag.png" alt="essential-logo" /> WooCommerce</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/performance.png" alt="essential-logo" /> Performance</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/security.png" alt="essential-logo" /> Security</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/forms.png" alt="essential-logo" /> Pro Forms</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/seo.png" alt="essential-logo" /> SEO</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/backup.png" alt="essential-logo" /> Backups</li></div>

					<div class="wp-tsas-list-img-class"><img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/White-labeling.png" alt="essential-logo" /> Migration</li></div>
				</div>
			</div>
		</div>
		<div class="wp-tsas-main-feature-item">
			<div class="wp-tsas-inner-feature-item">
				<div class="wp-tsas-list-feature-item">
					<img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/layers.png" alt="layer" />
					<h5>Site management</h5>
					<p>Manage, update, secure & optimize unlimited sites.</p>
				</div>
				<div class="wp-tsas-list-feature-item">
					<img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/risk.png" alt="backup" />
					<h5>Backup storage</h5>
					<p>Secure sites with auto backups and easy restore.</p>
				</div>
				<div class="wp-tsas-list-feature-item">
					<img src="<?php echo esc_url( WP_TSAS_URL ); ?>assets/images/logo-image/support.png" alt="support" />
					<h5>Support</h5>
					<p>Get answers on everything WordPress at anytime.</p>
				</div>
			</div>
		</div>
		<a class="wp-tsas-sf-btn" href="<?php echo esc_url( WP_TSAS_PLUGIN_LINK_UPGRADE ); ?>" target="_blank">Grab The Deal</a>
	</div>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">

				<table class="wpos-plugin-pricing-table">
					<colgroup></colgroup>
					<colgroup></colgroup>
					<colgroup></colgroup>
					 <thead>
						<tr>
							<th></th>
							<th>
								<h2>Free</h2>
							</th>
							<th>
								<h2 class="wpos-epb">Premium</h2>
								<p style="margin-bottom: 10px;">Gain access to <strong>WP Team Showcase</strong> included in <br /><strong class="wpos-epb">Essential Plugin Bundle</strong></p>
								<a href="<?php echo esc_url(WP_TSAS_PLUGIN_LINK_UPGRADE); ?>" target="_blank">Upgrade To PRO</a>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Designs <span>Designs that make your website better</span></th>
							<td>3</td>
							<td>25+</td>
						</tr>
						<tr>
							<th>Shortcodes <span>Shortcode provide output to the front-end side</span></th>
							<td>2 (Grid , Slider)</td>
							<td>2 (Grid , Slider)</td>
						</tr>
						<tr>
							<th>Shortcode Parameters <span>Add extra power to the shortcode</span></th>
							<td>9</td>
							<td>29+</td>
						</tr>
						<tr>
							<th>Center Mode <span>Display slider with center mode.</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Shortcode Generator <span>Play with all shortcode parameters with preview panel. No documentation required!!</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>WP Templating Features <span class="subtext">You can modify plugin html/designs in your current theme.</span></th>
							<td><i class="dashicons dashicons-no-alt"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Option Show/Hide content  <span>Display video title</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Display Full Content <span>Display full content or not</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Offset <span>Distance between two team columns. You can set any numeric value. Leave empty for default.</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Content Words Limit <span>Controls Word limit for content. Default value is 40</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Social Service Limit <span>Limit the number of social icons. Default value is 6. In popup all social icon will be displayed.</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Drag & Drop Slide Order Change <span>Arrange your desired slides with your desired order and display</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Popup Theme Design  <span>Choose Design for you popup. values are design-1, desing-2 </span></th>
							<td>1 (Light)</td>
							<td>2 (Light and Dark)</td>
						</tr>
						<tr>
							<th>Gutenberg Block Supports <span>Use this plugin with Gutenberg easily</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
				    		<th>Elementor Page Builder Support <em class="wpos-new-feature">New</em> <span>Use this plugin with Elementor easily</span></th>
				    		<td><i class="dashicons dashicons-no-alt"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
				    	<tr>
				    		<th>Beaver Builder Support <em class="wpos-new-feature">New</em> <span>Use this plugin with Beaver Builder easily</span></th>
				    		<td><i class="dashicons dashicons-no-alt"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
				    	<tr>
				    		<th>SiteOrigin Page Builder Support <em class="wpos-new-feature">New</em> <span>Use this plugin with SiteOrigin easily</span></th>
				    		<td><i class="dashicons dashicons-no-alt"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
				    	<tr>
				    		<th>Divi Page Builder Native Support <em class="wpos-new-feature">New</em> <span>Use this plugin with Divi Builder easily</span></th>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
				    	<tr>
							<th>Fusion Page Builder (Avada) native support <em class="wpos-new-feature">New</em> <span>Use this plugin with Fusion( Avada ) Builder easily</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
				    		<th>WPBakery Page Builder Support <span>Use this plugin with Visual Composer easily</span></th>
				    		<td><i class="dashicons dashicons-no-alt"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
						<tr>
							<th>Display Team Member for Particular Categories <span>Display team member by their category ID. You can pass multiple ids by comma separated.</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Exclude Team Member <span>Do not display the team member you want</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Exclude Some Categories <span>Do not display the team member for particular categories</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Team Member Order / Order By Parameters <span>Display Team Member according to date, title and etc</span></th>
								<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Multiple Slider Parameters <span>Slider parameters like autoplay, number of slide, sider dots and etc.</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Slider RTL Support <span>Slider supports for RTL website</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Automatic Update <span>Get automatic  plugin updates </span></th>
							<td>Lifetime</td>
							<td>Lifetime</td>
						</tr>
						<tr>
							<th>Support <span>Get support for plugin</span></th>
							<td>Limited</td>
							<td>1 Year</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<td></td>
							<td><p>Gain access to <strong>WP Team Showcase and Slider</strong> included in <br /><strong>Essential Plugin Bundle</strong></p>
							<a href="<?php echo esc_url(WP_TSAS_PLUGIN_LINK_UPGRADE); ?>" target="_blank">Upgrade To PRO</a>
						</tr>
					</tfoot>
				</table>
			</div>	
		</div>
	</div>			
</div>