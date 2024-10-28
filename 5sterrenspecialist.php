<?php
/**
* Plugin Name: 5-sterrenspecialist
* Plugin URI: https://www.5sterrenspecialist.nl/paginas/40-google-rich-snippets-plugin-handleiding.html
* Description: Plugin voor rich snippets
* Version: 1.2
* Author: 5-sterrenspecialist
* Author URI: http://www.5sterrengroeipromotor.nl/
* Tested up to: 5.5
* License: GPL2
* Text-Domain: __fivesterrenspecialist_
* Domain Path: /languages
*/
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define("FIVESTERPLUGIN_NAME__", "5sterrenspecialist");
define("FIVESTERPLUGIN_SLUG__", "__fivesterrenspecialist_");
define("FIVESTERDIR__", trailingslashit(plugin_dir_path(__FILE__)));
define("FIVESTERROOT__", trailingslashit(plugins_url("", __FILE__)));
define("FIVESTERRESOURCES__", FIVESTERROOT__ . "resources/");
define("FIVESTERDEBUG__", false);

if (FIVESTERDEBUG__) {
    @error_reporting(E_ALL);
    @ini_set("display_errors", "1");
}

/**
 * Abort loading if WordPress is upgrading
 */
if (defined("WP_INSTALLING") && WP_INSTALLING) return;

class FiveSter
{

    private $error;
    private $notice;

    public function __construct()
    {
        @mkdir(FIVESTERDIR__ . "tmp");

        register_activation_hook(__FILE__ , array($this, "fivesterrenspecialist_activate"));

        $this->loadHooks();
    }

    private function loadHooks()
    {
        add_action("wp_enqueue_scripts", array($this, "fivesterrenspecialist_includeResources"));
        add_action("plugins_loaded", array($this, "fivesterrenspecialist_i18n"));
        add_action("admin_menu", array($this, "fivesterrenspecialist_add_menu"));

        add_shortcode("5sterrenspecialist_snippet", array($this, "fivesterrenspecialist_shortcode"));

        add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($this, "fivesterrenspecialist_action_links") );
    }

    function fivesterrenspecialist_action_links($links)
    {
        $links[] = '<a href="' . admin_url( 'options-general.php?page=' . FIVESTERPLUGIN_SLUG__) . '">' . __("Settings", FIVESTERPLUGIN_SLUG__) . '</a>';
        return $links;
    }

    function fivesterrenspecialist_i18n()
    {
        $pluginDirName  = dirname(plugin_basename(__FILE__));
        $domain         = FIVESTERPLUGIN_SLUG__;
        $locale         = apply_filters("plugin_locale", get_locale(), $domain);
        load_textdomain($domain, WP_LANG_DIR . "/" . $pluginDirName . "/" . $domain . "-" . $locale . ".mo");
        load_plugin_textdomain($domain, "", $pluginDirName . "/resources/lang/");
    }

    function fivesterrenspecialist_add_menu()
    {
        add_options_page("5-sterrenspecialist", "5-sterrenspecialist", "manage_options", FIVESTERPLUGIN_SLUG__, array($this, "fivesterrenspecialist_settings"));
    }

    function fivesterrenspecialist_settings()
    {
        if (isset($_POST["fivester-submit"]) && wp_verify_nonce($_POST["nonce"], $_POST["action"])) {
            self::saveSettings();
        }

        include_once FIVESTERDIR__ . "resources/admin/includes/settings.php";
    }

    private function saveSettings()
    {
        $url    = $_POST["url"];
        if (empty($url)) {
            $this->error    = __("Empty URL provided", FIVESTERPLUGIN_SLUG__);
        } else {
            $api            = self::callAPI($url, array("json" => true));
            if ($api["error"] != 200) {
                $this->error    = __("Error while validating the URL", FIVESTERPLUGIN_SLUG__);
                $url            = "";
            } else {
                $this->notice   = __("URL updated", FIVESTERPLUGIN_SLUG__);
            }
        }
        self::setOption("url", $url);
    }

    function fivesterrenspecialist_includeResources()
    {
        wp_register_style("5sterrenspecialist", FIVESTERRESOURCES__ . "public/css/5sterrenspecialist.css");
        wp_enqueue_style("5sterrenspecialist");
    }

    function fivesterrenspecialist_activate()
    {
        @unlink(FIVESTERDIR__ . "tmp/log.log");
    }

	public function fivesterrenspecialist_shortcode($atts, $content="No ID")
    {
        $url    = self::getOption("url");
        if (empty($url)) {
            return "";
        }

        $api            = self::callAPI($url, array("json" => true));
        if ($api["error"] != 200) {
            return "";
        }

        $data       = $api["response"];
        $rating     = round((float) $data['ratingValue'], 1);
        $plural     = (int) $data['ratingCount'] === 1 ? 'beoordeling' : 'beoordelingen';
        ob_start();
        include_once FIVESTERDIR__ . "resources/public/includes/shortcode.php";
        $html   = ob_get_clean();
        return $html;
	}

    /****************************************** Util functions ******************************************/

    /**
     * Writes to the file /tmp/log.log if DEBUG is on
     */
    public static function writeDebug($msg)
    {
        if (FIVESTERDEBUG__) file_put_contents(FIVESTERDIR__ . "tmp/log.log", date("F j, Y H:i:s", current_time("timestamp")) . " - " . $msg."\n", FILE_APPEND);
    }

    /**
     * Custom wrapper for the get_option function
     *
     * @return string
     */
    public static function getOption($field, $clean=false)
    {
        $val = get_option(FIVESTERPLUGIN_SLUG__ . $field);
        return $clean ? htmlspecialchars($val) : $val;
    }

    /**
     * Custom wrapper for the update_option function
     *
     * @return mixed
     */
    public static function setOption($field, $value)
    {
        return update_option(FIVESTERPLUGIN_SLUG__ . $field, $value);
    }

    public static function callAPI($url)
    {
        $response   = wp_remote_get($url);
        $error      = wp_remote_retrieve_response_code($response);
        $body       = null;
        if (200 == $error) {
            $body       = wp_remote_retrieve_body($response);
            $body       = json_decode($body, true);
        }

        $array          = array(
            "response"  => $body,
            "error"     => $error,
        );

        self::writeDebug("Calling ". $url. " returning raw response " . print_r($response,true) . " and finally returning " . print_r($array,true));

        return $array;
    }
}

$fiveSter = new FiveSter();
