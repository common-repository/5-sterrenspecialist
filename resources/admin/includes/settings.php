<div class="wrap">
    <h2><?php _e("Settings", FIVESTERPLUGIN_SLUG__);?></h2>

<?php
    if ($this->error) {
?>
    <div class="error"><?php echo $this->error;?></div>
<?php
    }
    if ($this->notice) {
?>
    <div class="updated"><?php echo $this->notice;?></div>
<?php
    }
?>

<?php
    $active_tab = isset( $_REQUEST[ 'tab' ] ) ? $_REQUEST[ 'tab' ] : "url";
?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=<?php echo FIVESTERPLUGIN_SLUG__;?>&tab=url" class="nav-tab <?php echo $active_tab == 'url' ? 'nav-tab-active' : ''; ?>"><?php _e("Configuration", FIVESTERPLUGIN_SLUG__);?></a>
    </h2>

    <form method="post" action="">
        <table class="form-table">
<?php
    if( $active_tab == 'url' ) {
?>
            <tr valign="top">
                <th scope="row"><?php _e("Feed URL", FIVESTERPLUGIN_SLUG__);?></th>
                <td>
                    <input type="text" name="url" id="url" value="<?php echo self::getOption("url");?>" class="large-text">
                    <p class="description">Shortcode: [5sterrenspecialist_snippet]</p>
                </td>
            </tr>
<?php
    }
?>
        </table>

        <input type="hidden" name="tab" value="<?php echo $active_tab;?>">
        <input type="hidden" name="action" value="<?php echo FIVESTERPLUGIN_SLUG__;?>">
        <?php wp_nonce_field(FIVESTERPLUGIN_SLUG__, "nonce");?>
        <?php submit_button(__("Save Changes", FIVESTERPLUGIN_SLUG__), "primary", "fivester-submit"); ?>
    </form>
