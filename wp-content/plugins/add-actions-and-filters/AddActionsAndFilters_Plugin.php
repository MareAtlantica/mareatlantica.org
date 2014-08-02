<?php

/*
    "Add Actions and Filters" Copyright (C) 2013 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Add Actions and Filters for WordPress.

    Add Actions and Filters is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Add Actions and Filters is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

include_once('AddActionsAndFilters_LifeCycle.php');

class AddActionsAndFilters_Plugin extends AddActionsAndFilters_LifeCycle
{

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData()
    {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(//'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
        );
    }

    public function getPluginDisplayName()
    {
        return 'Add Actions And Filters';
    }

    protected function getMainPluginFileName()
    {
        return 'add-actions-and-filters.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables()
    {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables()
    {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade()
    {
    }


    public function addActionsAndFilters()
    {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
        add_action('admin_menu', array(&$this, 'addToolsAdminPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        if (strpos($_SERVER['REQUEST_URI'], $this->getCodePageSlug()) !== false) {
            add_action('admin_enqueue_scripts', array(&$this, 'enqueueAdminPageStylesAndScripts'));
        }

        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37
        $tmpCode = $this->getOption('tmp_code', '');
        $code = $this->getOption('code');
        if (!empty($tmpCode) && $tmpCode != $code) {
            // Test that the code works
            $this->updateOption('tmp_code', '');
            $this->updateOption('fatal_code', $tmpCode);
            eval($tmpCode); // Make raise FATAL error
            $this->updateOption('code', $tmpCode);
            $this->updateOption('fatal_code', '');
        } else {
            eval($code);
        }

        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
        add_action('wp_ajax_addactionsandfilters_save', array(&$this, 'ajaxSave'));


    }

    function enqueueAdminPageStylesAndScripts() {
        wp_enqueue_script('edit_area', plugins_url('/edit_area/edit_area_full.js', __FILE__));
        //wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
    }

    function getCodePageSlug()
    {
        return 'AddActionsAndFiltersCode';
    }

    function addToolsAdminPage()
    {
        if (current_user_can('manage_options')) {
            $this->requireExtraPluginFiles();
            $displayName = $this->getPluginDisplayName();
            add_submenu_page('tools.php',
                $displayName,
                $displayName,
                'manage_options',
                $this->getCodePageSlug(), // slug
                array(&$this, 'adminPage'));
        }
    }

    function adminPage()
    {
        ?>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="SSABNHHPSVWT6">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0"
                   name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>

        <form action='' method='post'>
            <input type="submit" id="savecode" value="Save"/>
            <p id="codesavestatus">
                <?php
                $fatalCode = $this->getOption('fatal_code', '');
                $code = $this->getOption('code');
                $displayCode = $code;
                if (!empty($fatalCode) && $fatalCode != $code) {
                    $displayCode = $fatalCode;
                    $this->updateOption('fatal_code', '');
                    ?><span style="font-weight: bold; background-color: yellow"><?php
                    _e('NOT SAVED: Code was not saved because it causes a PHP FATAL ERROR.', 'add-actions-and-filters');
                    ?></span><?php
                }
                ?>
            </p>
            <label for="code"><?php _e('Put PHP code here to define functions and add them as <a target="_addactions" href="http://codex.wordpress.org/Function_Reference/add_action">actions</a> or <a target="_addfilter" href="http://codex.wordpress.org/Function_Reference/add_filter">filters</a>. Also add <a target="_scripts" href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script">scripts</a> and <a target="_styles" href="http://codex.wordpress.org/Function_Reference/wp_enqueue_style">styles</a>.', 'add-actions-and-filters'); ?></label>
            <textarea id="code" style="height: 650px; width: 100%;"
                      name="test_1"><?php echo $displayCode; ?></textarea>
        </form>

        <script language="Javascript" type="text/javascript">
            // initialisation
            editAreaLoader.init({
                id: "code"	// id of the textarea to transform
                , start_highlight: true	// if start with highlight
                , allow_resize: "both", allow_toggle: true, word_wrap: true, language: "en", syntax: "php"
            });

            jQuery("#savecode").click(
                    function () {
                        jQuery.ajax(
                                {
                                    "url": "<?php echo admin_url('admin-ajax.php') ?>?action=addactionsandfilters_save",
                                    "type": "POST",
                                    "data": "code=" + encodeURIComponent(editAreaLoader.getValue("code")),
                                    "success": function (data, textStatus) {
                                        //jQuery("#codesavestatus").html(data);
                                        location.reload();
                                    },
                                    "error": function (textStatus, errorThrown) {
                                        jQuery("#codesavestatus").html(errorThrown);
                                    },
                                    "beforeSend": function () {
                                        jQuery("#codesavestatus").html('<img src="<?php echo plugins_url('img/load.gif', __FILE__); ?>">');
                                    }
                                }
                        );
                        return false;
                    });
        </script>
    <?php
    }

    public function ajaxSave()
    {
        if (current_user_can('manage_options')) {
            if (!headers_sent()) {
                // Don't let IE cache this request
                header("Pragma: no-cache");
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

                header("Content-type: text/plain");
            }

            $code = stripslashes($_REQUEST['code']);

            // Save it as temporarily, potentially fatal code
            $this->updateOption('tmp_code', $code);
            die();
        } else {
            die(-1);
        }
    }

}
