<?php
/**
 * Grid view.
 *
 * @since 2.0.0
 */
?>

<?php $table->display_tablenav('top'); ?>

<div class="wp_ultimo_ptm_mt-8 theme-browser content-filterable <?php echo implode( ' ', $table->get_table_classes() ); ?>">

  <div id="the-list" class="themes wp-clearfix">

    <?php $table->display_rows_or_placeholder(); ?>

  </div>
</div>
