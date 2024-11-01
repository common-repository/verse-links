<?php

add_action('admin_menu', 'vl_create_menu');

function vl_create_menu() {
  add_options_page('Verse Links Settings', 'Verse Links', 8, basename(__FILE__), 'vl_options');
  add_action( 'admin_init', 'vl_register_settings' );
}

function vl_register_settings() {
  register_setting( 'vl-settings-group', 'vl_preferred_website' );
  register_setting( 'vl-settings-group', 'vl_new_window' );
  register_setting( 'vl-settings-group', 'vl_link_posts' );
  register_setting( 'vl-settings-group', 'vl_link_comments' );
  register_setting( 'vl-settings-group', 'vl_enable_tooltips' );
  register_setting( 'vl-settings-group', 'vl_tooltip_translation' );
}

function vl_options() {
  init_vl_sites();
  global $vl_sites;
?>

<div class="wrap">
  <h2>Verse Links Settings</h2>
  <form method="post" action="options.php"> 
    <?php
      settings_fields( 'vl-settings-group' );
      do_settings_fields( 'vl-settings-group' );
    ?>

    <table class="form-table">
      <tr><td colspan="2"><b>Link Options</b></td></tr>
      <tr valign="top">
        <th scope="row">Link Verses in Posts</th>
        <td><select name="vl_link_posts">
          <option value="true">Yes</option>
          <option value="false" <?php if (get_option('vl_link_posts')=="false") echo " selected=\"true\"";?> >No</option>
        </select></td>
      </tr>
      <tr valign="top">
        <th scope="row">Link Verses in Comments</th>
        <td><select name="vl_link_comments">
          <option value="true">Yes</option>
          <option value="false" <?php if (get_option('vl_link_comments')=="false") echo " selected=\"true\"";?> >No</option>
        </select></td>
      </tr>
      <tr valign="top">
        <th scope="row">Open Links in New Window</th>
        <td><select name="vl_new_window">
          <option value="true">Yes</option>
          <option value="false" <?php if (get_option('vl_new_window')=="false") echo " selected=\"true\"";?> >No</option>
        </select></td>
      </tr>
      <tr valign="top">
        <th scope="row">Preferred Website</th>
        <td><select name="vl_preferred_website">
          <?php
            $savedSite = get_option('vl_preferred_website');
            foreach (array_keys($vl_sites) as $vl_site)
            {
              echo "<option";
              if ($vl_site==$savedSite) echo " selected=\"true\"";
              echo ">" . $vl_site . "</option>";
            }
          ?>
	</select></td>
      </tr>


      <tr><td colspan="3"><br/>
        <b>Tooltip Options</b><br/>
        In addition to linking to a site of your choice, you can enable tooltips so<br/>the verse text can be viewed without having to leave your site.
      </td></tr>
      <tr valign="top">
        <th scope="row">Enable Tooltips</th>
        <td><select name="vl_enable_tooltips">
          <option value="true">Yes</option>
          <option value="false" <?php if (get_option('vl_enable_tooltips')=="false") echo " selected=\"true\"";?> >No</option>
        </select></td>
      </tr>
      <tr valign="top">
        <th scope="row">Tooltip Translation</th>
        <td><select name="vl_tooltip_translation">
          <option value="1">WEB - World English Bible</option>
          <option value="2" <?php if (get_option('vl_tooltip_translation')=="2") echo " selected=\"true\"";?> >KJV - King James Version</option>
          <option value="3" <?php if (get_option('vl_tooltip_translation')=="3") echo " selected=\"true\"";?> >ASV - American Standard Version</option>
        </select></td>
        <td><i>*Due to copyright restrictions we are not able to offer NIV and some other translations for the tooltips.</i></td>
      </tr>
    </table>

    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
</div>

<?php } ?>