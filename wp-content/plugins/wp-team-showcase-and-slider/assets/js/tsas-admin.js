jQuery( document ).ready(function($) {

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', wp_tsas_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wp_tsas_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', wp_tsas_fl_render_preview );
});

/* Function to render shortcode preview for Beaver Builder */
function wp_tsas_fl_render_preview() {
	wp_tsas_team_slider_init();
	wp_tsas_popup_gallery_init();
}