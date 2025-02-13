<?php
/**
 * Details Template.
 *
 * @since 2.0.0
 */
?>
<script id="tmpl-theme-single" type="text/template">
  <div class="theme-backdrop"></div>
  <div class="theme-wrap wp-clearfix">
    <div class="theme-header">
      <button class="left dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show previous theme' ); ?></span></button>
      <button class="right dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show next theme' ); ?></span></button>
      <button class="close dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Close details dialog' ); ?></span></button>
    </div>
    <div class="theme-about wp-clearfix">
      <div class="theme-screenshots">
        <# if ( data.screenshot[0] ) { #>
          <div class="screenshot"><img src="{{ data.screenshot[0] }}" alt="" /></div>
          <# } else { #>
            <div class="screenshot blank"></div>
            <# } #>
      </div>
      <div class="theme-info">
        <# if ( data.active ) { #>
          <span class="current-label"><?php _e( 'Active' ); ?></span>
          <# } #>
            <h2 class="theme-name">{{{ data.name }}}<span class="theme-version"><?php printf( __( '%s' ), '{{ data.version }}' ); ?></span></h2>
            <p class="theme-author">
              <?php printf( __( '%s' ), '{{{ data.authorAndUri }}}' ); ?>
            </p>
            <# if ( data.hasUpdate ) { #>
              <div class="notice notice-warning notice-alt notice-large">
                <h3 class="notice-title"><?php _e( 'Update Available' ); ?></h3> {{{ data.update }}}
              </div>
              <# } #>
                <p class="theme-description">{{{ data.description }}}</p>
                <# if ( data.parent ) { #>
                  <p class="parent-theme">
                    <?php printf( __( 'This is a child theme of %s.' ), '<strong>{{{ data.parent }}}</strong>' ); ?></p>
                  <# } #>
                    <# if ( data.tags ) { #>
                      <p class="theme-tags"><span><?php _e( 'Categories:' ); ?></span> {{{ data.tags }}}</p>
                      <# } #>
      </div>
    </div>
    <div class="theme-actions">
      <div class="active-theme">
        <# if ( !data.network ) { #>
          <a href="{{{ data.actions.deactivate }}}" class="button button-primary">
            <?php _e( 'Deactivate' ); ?>
          </a>
        <# } #>
        <?php echo implode( ' ', $current_theme_actions ); ?>
      </div>
      <div class="inactive-theme">
        <?php
            /* translators: %s: Theme name */
            $aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
            ?>
          <# if ( data.actions.activate ) { #>
            <a href="{{{ data.actions.activate }}}" class="button button-primary activate" aria-label="<?php echo $aria_label; ?>">
              <?php _e( 'Activate' ); ?>
            </a>
            <# } #>
      </div>
      <# if ( ! data.active && data.actions[ 'delete'] ) { #>
        <a href="{{{ data.actions['delete'] }}}" class="button delete-theme">
          <?php _e( 'Delete' ); ?>
        </a>
        <# } #>
    </div>
  </div>
</script>
