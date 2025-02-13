<?php
/**
 * Theme Template.
 *
 * @since 2.0.0
 */
?>
<div class="theme-browser">
    <div class="themes wp-clearfix">

      <?php

        /*
        * This PHP is synchronized with the tmpl-theme template below!
        */

        foreach ($plugins as $plugin) :

          $aria_action = esc_attr($plugin['id'].'-action');

          $aria_name = esc_attr($plugin['id'].'-name');

        ?>

        <div class="theme<?php if ($plugin['active']) echo ' active'; ?>" tabindex="0" aria-describedby="<?php echo $aria_action.' '.$aria_name; ?>">

          <?php if (!empty($plugin['screenshot'][0])) { ?>

            <div class="theme-screenshot wu-styling">

              <img class="wu-bg-gray-100" src="<?php echo $plugin['screenshot'][0]; ?>" alt="" />

            </div>

          <?php } else { ?>

            <div class="theme-screenshot blank"></div>

          <?php } ?>

          <span class="more-details" id="<?php echo $aria_action; ?>">

            <?php _e('Plugin Details'); ?>

          </span>

          <div class="theme-author">

            <?php printf(__( 'By %s' ), $plugin['author']); ?>

          </div>

          <?php if ($plugin['active']) { ?>

            <h2 class="theme-name" id="<?php echo $aria_name; ?>">

                <?php
                /* translators: %s: theme name */
                printf(__('<span>Active:</span> %s'), $plugin['name']);
                ?>

            </h2>

          <?php } else { ?>

            <h2 class="theme-name" id="<?php echo $aria_name; ?>"><?php echo $plugin['name']; ?></h2>

          <?php } ?>

          <div class="theme-actions">

            <?php if ($plugin['active']) { ?>

              <?php if (!$plugin['network']) { ?>

              <a class="button button-primary" href="<?php echo $plugin['actions']['deactivate']; ?>">

                <?php _e('Deactivate', 'wp-ultimo-plugin-and-theme-manager'); ?>

              </a>

              <?php } ?>

            <?php } else { ?>

            <?php

            /* translators: %s: Theme name */
            $aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );

            ?>

              <a class="button button-primary activate" href="<?php echo $plugin['actions']['activate']; ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>">

                <?php _e('Activate', 'wp-ultimo-plugin-and-theme-manager'); ?>

              </a>

            <?php } ?>

          </div>

        </div>

        <?php endforeach; ?>

    </div>

  </div>

  <div class="theme-overlay"></div>

  <p class="no-themes">

    <?php _e( 'No Plugins found.', 'wp-ultimo-plugin-and-theme-manager' ); ?>

  </p>

</div>
<!-- .wrap -->

<?php
/*
 * The tmpl-theme template is synchronized with PHP above!
 */
?>

<script id="tmpl-theme" type="text/template">
  <# if ( data.screenshot[0] ) { #>
    <div class="theme-screenshot wu-styling">
      <img class="wu-bg-gray-100" src="{{ data.screenshot[0] }}" alt="" />
    </div>
  <# } else { #>
    <div class="theme-screenshot blank"></div>
  <# } #>

  <span class="more-details" id="{{ data.id }}-action"><?php _e( 'Plugin Details' ); ?></span>

  <div class="theme-author">
    <?php
    /* translators: %s: Theme author name */
    printf( __( 'By %s' ), '{{{ data.author }}}' );
    ?>
  </div>

  <# if ( data.active ) { #>
    <h2 class="theme-name" id="{{ data.id }}-name">
      <?php
      /* translators: %s: Theme name */
      printf( __( '<span>Active:</span> %s' ), '{{{ data.name }}}' );
      ?>
    </h2>
  <# } else { #>
    <h2 class="theme-name" id="{{ data.id }}-name">{{{ data.name }}}</h2>
  <# } #>

  <div class="theme-actions">
    <# if ( data.active ) { #>
      <# if ( !data.network ) { #>
        <a class="button button-primary" href="{{{ data.actions.deactivate }}}">
          <?php _e( 'Deactivate' ); ?>
        </a>
      <# } else { #>
        <a class="button" disabled="disabled" href="#">
          <?php _e( 'Network Active' ); ?>
        </a>
      <# } #>
    <# } else { #>
    <?php
    /* translators: %s: Theme name */
    $aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
    ?>
        <a class="button activate button-primary" href="{{{ data.actions.activate }}}" aria-label="<?php echo $aria_label; ?>">
         <?php _e( 'Activate' ); ?>
        </a>

    <# } #>

  </div>
</script>
