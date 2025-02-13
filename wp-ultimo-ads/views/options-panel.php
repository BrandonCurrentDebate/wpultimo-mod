<style>
#wu-product-data ul.wc-tabs li.ads_options a:before, 
#wu-coupon-data ul.wc-tabs li.ads_options a:before, 
.wu ul.wc-tabs li.ads_options a:before {
  content: "\f488";
}
</style>

<div id="wu_ads" class="panel wu_options_panel">

  <div class="options_group">
    <p class="form-field">
      <label class="form-field-full"><strong><?php _e('Front-end Ads Settings', 'wu-ads'); ?></strong></label>
    </p>
  </div>

  <div class="options_group">
    <p class="form-field enable_front_end_ads_field">

      <label for="enable_front_end_ads">
        <?php _e('Front-end Ads', 'wu-ads'); ?>
      </label>

      <input v-model="enable_front_end_ads" type="checkbox" class="checkbox" style="" name="enable_front_end_ads" id="enable_front_end_ads" value="1"> 

      <span class="description"><?php _e('Check this option to display ads on the front-end side of sites on this plan.', 'wu-ads'); ?></span>

    </p>
  </div>

  <div class="options_group" v-if="enable_front_end_ads">
    <p class="form-field enable_before_content_ads_field">

      <label for="enable_before_content_ads">
        <?php _e('Before Post Content', 'wu-ads'); ?>
      </label>

      <input v-model="enable_before_content_ads" type="checkbox" class="checkbox" style="" name="enable_before_content_ads" id="enable_before_content_ads" value="1"> 

      <span class="description"><?php _e('Check this box if you want to display the ads BEFORE the post content. This apply to custom post types as well.', 'wu-ads'); ?></span>

    </p>

    <p class="form-field enable_after_content_ads_field">

      <label for="enable_after_content_ads">
        <?php _e('After Post Content', 'wu-ads'); ?>
      </label>

      <input v-model="enable_after_content_ads" checked="checked" type="checkbox" class="checkbox" style="" name="enable_after_content_ads" id="enable_after_content_ads" value="1"> 

      <span class="description"><?php _e('Check this box if you want to display the ads AFTER the post content. This apply to custom post types as well.', 'wu-ads'); ?></span>

    </p>

    <p class="form-field max_ads_field ">

      <label for="max_ads">
        <?php _e('Ads per Page', 'wu-ads'); ?>
      </label>

      <input type="number" class="short" style="" name="max_ads" id="max_ads" value="<?php echo $plan->max_ads; ?>" placeholder="0" min="0">

      <span class="description"><?php _e('Define a max number of Ads per page. Leave 0 for unlimited.', 'wu-ads'); ?></span>

    </p>

    <p class="form-field before_ad_code_field" v-if="enable_before_content_ads">

      <label for="before_ad_code">
        <?php _e('Before Ad Code', 'wu-ads'); ?>
      </label>

      <textarea name="before_ad_code" id="before_ad_code" class="short" rows="10" style="height: 120px;"><?php echo $plan->before_ad_code; ?></textarea>

      <span class="description"><?php _e('Inject the HTML code for the Ad in here', 'wu-ads'); ?></span>

    </p>

    <p class="form-field after_ad_code_field" v-if="enable_after_content_ads">

      <label for="after_ad_code">
        <?php _e('After Ad Code', 'wu-ads'); ?>
      </label>

      <textarea name="after_ad_code" id="after_ad_code" class="short" rows="10" style="height: 120px;"><?php echo $plan->after_ad_code; ?></textarea>

      <span class="description"><?php _e('Inject the HTML code for the Ad in here', 'wu-ads'); ?></span>

    </p>

  </div>

  <div class="options_group">
    <p class="form-field">
      <label class="form-field-full"><strong><?php _e('Admin Panel Ads Settings', 'wu-ads'); ?></strong></label>
    </p>
  </div>

  <div class="options_group">
    <p class="form-field enable_back_end_ads_field">

      <label for="enable_back_end_ads">
        <?php _e('Admin Panel Ads', 'wu-ads'); ?>
      </label>

      <input v-model="enable_back_end_ads" type="checkbox" class="checkbox" style="" name="enable_back_end_ads" id="enable_back_end_ads" value="1"> 

      <span class="description"><?php _e('Check this option to display ads on the admin panel of sites on this plan.', 'wu-ads'); ?></span>

    </p>
  </div>

  <div class="options_group" v-if="enable_back_end_ads">

    <p class="form-field back_end_ad_code_field">

      <label for="back_end_ad_code">
        <?php _e('Admin Panel Ad Code', 'wu-ads'); ?>
      </label>

      <textarea name="back_end_ad_code" id="back_end_ad_code" class="short" rows="10" style="height: 120px;"><?php echo $plan->back_end_ad_code; ?></textarea>

      <span class="description"><?php _e('Inject the HTML code for the Ad in here', 'wu-ads'); ?></span>

    </p>

  </div>
  
</div>

<input type="hidden" name="has_wu_ads" value="1">

<script type="text/javascript">
  var wu_options = new Vue({
    el: "#wu_ads",
    data: <?php echo json_encode(array(
      'enable_front_end_ads'      => $plan->enable_front_end_ads,
      'enable_back_end_ads'       => $plan->enable_back_end_ads,
      'enable_before_content_ads' => $plan->enable_before_content_ads,
      'enable_after_content_ads'  => $plan->enable_after_content_ads,
    )); ?>
  });
  
</script>