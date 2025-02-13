<?php
/**
 * Filters view
 *
 * @since 2.0.0
 */
?>
<div id="<?php echo esc_attr($filters_el_id); ?>" class="wp-filter wp_ultimo_ptm_filter">

	<ul class="filter-links">

		<li>

			<a href="#" data-sort="featured" class="current" aria-current="page">

				<?php _e('Filtered', 'wp-ultimo-plugin-and-theme-manager'); ?>

					<span class="wp_ultimo_ptm_p-1 wp_ultimo_ptm_bg-gray-200 wp_ultimo_ptm_ml-1 wp_ultimo_ptm_rounded-sm wp_ultimo_ptm_text-gray-600 wp_ultimo_ptm_text-xs">20</span>

			</a>

		</li>

		<li>

			<a href="#" data-sort="popular">

				<?php _e('Popular', 'wp-ultimo-plugin-and-theme-manager'); ?>

			</a>

		</li>

	</ul>

	<?php if (isset($filters) && !empty($filters)) : ?>

		<button v-on:click="toggle_filters" type="button" class="button drawer-toggle" v-bind:aria-expanded="drawer_open ? 'true' : 'false'">

			<?php _e('Advanced Filters', 'wp-ultimo-plugin-and-theme-manager'); ?>

		</button>

	<?php endif; ?>

	<form class="search-form">

		<?php if (isset($has_search) && $has_search) : ?>

			<label class="screen-reader-text" for="wp-filter-search-input">

				<?php echo esc_html($search_label); ?>

			</label>

			<input name='s' id="s" value="<?php echo esc_attr(isset($_REQUEST['s']) ? $_REQUEST['s'] : ''); ?>" placeholder="<?php echo esc_attr($search_label); ?>" type="search" aria-describedby="live-search-desc" id="wp-filter-search-input" class="wp-filter-search">

		<?php endif; ?>

	</form>

	<?php if (isset($has_view_switch) && $has_view_switch) : ?>

		<?php $table->view_switcher($table->current_mode); ?>

	<?php endif; ?>

	<div class="filter-drawer" v-show="drawer_open">

		<div class="buttons">

				<button type="button" v-on:click="apply_filters" class="apply-filters button">

					<?php _e('Apply Filters', 'wp-ultimo-plugin-and-theme-manager'); ?><span v-text="filter_count ? filter_count : ''"></span>

				</button>

				<button v-on:click="reset_filters" v-bind:style="filter_count ? 'display: inline-block !important;' : ''" type="button" class="clear-filters button" aria-label="<?php echo esc_attr(__('Clear current filters', 'wp-ultimo-plugin-and-theme-manager')); ?>">

					<?php _e('Clear', 'wp-ultimo-plugin-and-theme-manager'); ?>

				</button>

				</div>

				<?php if (isset($filters) && !empty($filters)) : ?>

					<?php foreach ($filters as $filter_id => $filter) : ?>

						<fieldset class="filter-group">

							<legend><?php echo esc_html($filter['label']); ?></legend>

							<div class="filter-group-feature">

								<?php foreach ($filter['options'] as $option_value => $option_label) : $count = count($filter['options']); ?>

									<input data-item="<?php echo esc_attr($filter_id.'-'.$option_value); ?>" data-label="<?php echo esc_attr($option_label); ?>" name="<?php echo esc_attr($filter_id); ?>" v-model="filters.<?php echo esc_attr($filter_id); ?>" type="<?php echo $count === 2 ? 'radio' : 'checkbox'; ?>" id="filter-id-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr($option_value); ?>" value="<?php echo esc_attr($option_value); ?>">

									<label for="filter-id-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr($option_value); ?>"><?php echo esc_html($option_label); ?></label>

								<?php endforeach; ?>

							</div>

						</fieldset>

					<?php endforeach; ?>

				<?php endif; ?>

				<?php if (isset($date_filters) && !empty($date_filters)) : ?>

					<?php foreach ($date_filters as $filter_id => $filter) :  ?>

						<fieldset class="filter-group">

							<legend><?php echo esc_html($filter['label']); ?></legend>

							<div class="filter-group-feature">

								<?php foreach ($filter['options'] as $option_value => $option) : ?>

									<input data-item="<?php echo esc_attr($filter_id.'-'.$option_value); ?>" data-label="<?php echo esc_attr(sprintf('%s: %s', $filter['label'], $option['label'])); ?>" v-on:click="set_dates" data-item="<?php echo esc_attr($option_value); ?>" data-label="<?php echo esc_attr($option['label']); ?>" data-name="<?php echo esc_attr($filter_id); ?>" name="filter_<?php echo esc_attr($filter_id); ?>" v-model="date_filters.<?php echo esc_attr($filter_id); ?>.type" type="radio" id="date-filter-id-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr($option_value); ?>" value="<?php echo esc_attr($option_value); ?>" data-after="<?php echo esc_attr($option['after']); ?>" data-before="<?php echo esc_attr($option['before']); ?>">

									<label for="date-filter-id-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr($option_value); ?>"><?php echo esc_html($option['label']); ?></label>

								<?php endforeach; ?>

								<div class="wp_ultimo_ptm_custom-date-selector" v-show="date_filters.<?php echo esc_attr($filter_id); ?>.type == 'custom'">

									<span><?php _e('Between', 'wp-ultimo-plugin-and-theme-manager'); ?></span>

									<input id="date-filter-id-<?php echo esc_attr($filter_id); ?>-after" value="<?php echo esc_attr(isset($_REQUEST[$filter_id]['after']) ? $_REQUEST[$filter_id]['after'] : ''); ?>" type="text" name="<?php echo esc_attr($filter_id); ?>[after]" value="" data-format="Y-m-d H:i:S" class="wp_ultimo_ptm_filter-date-inputs wp_ultimo_ptm_datepicker" placeholder="<?php _e('Click to edit', 'wp-ultimo-plugin-and-theme-manager'); ?>">

									<span><?php _e('and', 'wp-ultimo-plugin-and-theme-manager'); ?></span>

									<input id="date-filter-id-<?php echo esc_attr($filter_id); ?>-before" value="<?php echo esc_attr(isset($_REQUEST[$filter_id]['before']) ? $_REQUEST[$filter_id]['before'] : ''); ?>" type="text" name="<?php echo esc_attr($filter_id); ?>[before]" value="" data-format="Y-m-d H:i:S" class="wp_ultimo_ptm_filter-date-inputs wp_ultimo_ptm_datepicker" placeholder="<?php _e('Click to edit', 'wp-ultimo-plugin-and-theme-manager'); ?>">

								</div>

							</div>

						</fieldset>

				<?php endforeach; ?>

			<?php endif; ?>

			<div class="buttons">

				<button type="button" v-on:click="apply_filters" class="apply-filters button">

					<?php _e('Apply Filters', 'wp-ultimo-plugin-and-theme-manager'); ?><span v-text="filter_count ? filter_count : ''"></span>

				</button>

				<button v-on:click="reset_filters" v-bind:style="filter_count ? 'display: inline-block !important;' : ''" type="button" class="clear-filters button" aria-label="<?php echo esc_attr(__('Clear current filters', 'wp-ultimo-plugin-and-theme-manager')); ?>"><?php _e('Clear', 'wp-ultimo-plugin-and-theme-manager'); ?></button>

			</div>

			<div class="filtered-by">

				<span><?php _e('Filtering by:', 'wp-ultimo-plugin-and-theme-manager'); ?></span>

				<div class="tags">

					<span v-for="filter in cleaned_filters" class="tag" v-html="get_label(filter)"></span>

				</div>

				<button v-on:click="open_filters" type="button" class="button-link edit-filters"><?php _e('Edit Filters', 'wp-ultimo-plugin-and-theme-manager'); ?></button>

			</div>

		</div>

</div>

<style>
.wp_ultimo_ptm_custom-date-selector {
		padding: 12px 12px 18px 12px;
		background: #f9f9f9;
		border-top: 1px solid #eee;
		margin: 0 -10px -10px -10px;
}
.wp_ultimo_ptm_custom-date-selector > span {
		display: block;
		margin: 5px 0;
		/* text-transform: uppercase; */
		letter-spacing: 0.3px;
		/* font-weight: bold; */
		font-size: 11px;
}
.wp_ultimo_ptm_custom-date-selector .wp_ultimo_ptm_filter-date-inputs {
		position: relative !important;
		margin: 0;
		width: 100% !important;
}

.filter-drawer {
		width: 100%;
}
</style>
