<?php
/**
 * Plugin Template view.
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

    <div class="plugin-card plugin-card-<?php echo $plugin['id']; ?>">

      <div class="plugin-card-top">

        <div class="name column-name">

          <h3>

            <span>

              <?php echo $plugin['name']; ?>

              <img src="<?php echo $plugin['screenshot'][0]; ?>" class="plugin-icon" alt="">

            </span>

          </h3>

        </div>

        <div class="action-links">

          <ul class="plugin-action-buttons">

            <li>

            <?php if ($plugin['active']) { ?>

              <?php if (!$plugin['network']) { ?>

                <a class="button button-primary" href="<?php echo $plugin['actions']['deactivate']; ?>">

                  <?php _e( 'Deactivate' ); ?>

                </a>

              <?php } ?>

            <?php } else { ?>

              <?php
              /* translators: %s: Theme name */
              $aria_label = sprintf(_x('Activate %s', 'theme' ), '{{ data.name }}');

              ?>

              <a class="button button-primary activate" href="<?php echo $plugin['actions']['activate']; ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>">

                <?php _e('Activate', 'wp-ultimo-plugin-and-theme-manager'); ?>

              </a>

            <?php } ?>

            </li>

          </ul>

        </div>

        <div class="desc column-description">

          <p><?php echo $plugin['description']; ?></p>

          <?php if ($plugin['author']) : ?>

            <p class="authors">

              <cite><?php printf( __( 'By %s' ), $plugin['author'] ); ?></cite>

            </p>

          <?php endif; ?>

        </div>

      </div>

      <div class="plugin-card-bottom">

        <div class="vers column-rating">

          <div class="star-rating"><span class="screen-reader-text"><?php _e('5 stars extension', 'wp-ultimo-plugin-and-theme-manager'); ?></span>

            <div class="star star-full" aria-hidden="true"></div>

            <div class="star star-full" aria-hidden="true"></div>

            <div class="star star-full" aria-hidden="true"></div>

            <div class="star star-full" aria-hidden="true"></div>

            <div class="star star-full" aria-hidden="true"></div>

          </div>

        </div>

        <div class="column-updated">

          <strong><?php _e('Categories', 'wp-ultimo-plugin-and-theme-manager'); ?>: </strong><?php echo $plugin['tags'];?></div>

        <div class="column-downloaded">

        </div>

        <div class="column-compatibility"></div>

      </div>

    </div>

    <?php endforeach; ?>

  </div>

</div>

<?php
/*
 * The tmpl-theme template is synchronized with PHP above!
 */
?>

<script id="tmpl-theme" type="text/template">

<div class="plugin-card-top">
  <div class="name column-name">
    <h3>
      <span>
      {{ data.name }}
      <img src="{{ data.screenshot[0] }}" class="plugin-icon wu-bg-gray-100" alt="">
      </span>
    </h3>
  </div>
  <div class="action-links">
    <ul class="plugin-action-buttons">

      <li>
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
    </li>

    </ul>
  </div>
  <div class="desc column-description">
    <p>{{ data.description }}</p>
    <# if ( data.author.length ) { #>
      <p class="authors">
        <cite><?php printf( __( 'By %s' ), '{{ data.author }}' ); ?></cite>
      </p>
    <# } #>
  </div>
</div>
<div class="plugin-card-bottom">
  <div class="vers column-rating">
    <div class="star-rating"><span class="screen-reader-text"><?php _e('5 stars extension', 'wp-ultimo-plugin-and-theme-manager'); ?></span>
      <div class="star star-full" aria-hidden="true"></div>
      <div class="star star-full" aria-hidden="true"></div>
      <div class="star star-full" aria-hidden="true"></div>
      <div class="star star-full" aria-hidden="true"></div>
      <div class="star star-full" aria-hidden="true"></div>
    </div>
  </div>
  <div class="column-updated">
    <strong><?php _e('Categories'); ?>:</strong> {{ data.tags }} </div>
  <div class="column-downloaded">
  </div>
  <div class="column-compatibility"></div>
</div>

</script>
