<?php
/**
 * Function Custom meta box for Premium
 * 
 * @package WP Team Showcase and Slider
 * @since 1.4.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<!-- <div class="pro-notice"><strong><?php // echo sprintf( __( 'Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'wp-team-showcase-and-slider'), WP_TSAS_PLUGIN_LINK_UNLOCK); ?></strong></div> -->

<!-- <div class="pro-notice">
	<strong>
		<?php // echo sprintf( __( 'Try All These <a href="%s" target="_blank">PRO Features in Essential Bundle Free For 5 Days.</a>', 'wp-team-showcase-and-slider'), WP_TSAS_PLUGIN_LINK_UNLOCK); ?>
	</strong>
</div> -->

<!-- <div class="wp-tsas-black-friday-banner-wrp">
	<a href="<?php // echo esc_url( WP_TSAS_PLUGIN_LINK_UNLOCK ); ?>" target="_blank"><img style="width: 100%;" src="<?php // echo esc_url( WP_TSAS_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
</div> -->

<strong style="color:#2ECC71; font-weight: 700;"><?php echo sprintf( __( ' <a href="%s" target="_blank" style="color:#2ECC71;">Upgrade To Pro</a> and Get Designs, Optimization, Security, Backup, Migration Solutions @ one stop.', 'wp-team-showcase-and-slider'), WP_TSAS_PLUGIN_LINK_UNLOCK); ?></strong>

<table class="form-table tsas-metabox-table">
	<tbody>

		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Layouts', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('2 (Grid, Slider).', 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Designs', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('25+. In lite version only 3 design.', 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Popup Theme Design ', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('2. In lite version only 1 popup design.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('WP Templating Features ', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('You can modify plugin html/designs in your current theme.', 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Shortcode Generator ', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Play with all shortcode parameters with preview panel. No documentation required.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Offset ', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Distance between two team columns.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Social Service Limit ', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Limit the number of social icons. Default value is 6. In popup all social icon will be displayed.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>

		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Drag & Drop Social Link ', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Arrange your desired Social with your desired order and display.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>

		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Drag & Drop Slide Order Change', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Arrange your desired slides with your desired order and display.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Page Builder Support', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Gutenberg Block, Elementor, Bevear Builder, SiteOrigin, Divi, Visual Composer and Fusion Page Builder Support', 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="tsas-pro-feature">
			<th>
				<?php esc_html_e('Exclude Team and Exclude Some Categories', 'wp-team-showcase-and-slider'); ?><span class="tsas-pro-tag"><?php esc_html_e('PRO','wp-team-showcase-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php esc_html_e('Do not display the team & Do not display the team for particular categories.' , 'wp-team-showcase-and-slider'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .tsas-metabox-table -->

