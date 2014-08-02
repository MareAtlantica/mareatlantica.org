<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterSubscription::instance();

if (!$controls->is_action()) {
    $controls->data = get_option('newsletter_profile');
} else {
    if ($controls->is_action('save')) {
        update_option('newsletter_profile', $controls->data);
    }

    if ($controls->is_action('reset')) {
        // TODO: Move this inside the module
        @include NEWSLETTER_DIR . '/subscription/languages/profile-en_US.php';
        @include NEWSLETTER_DIR . '/subscription/languages/profile-' . WPLANG . '.php';
        update_option('newsletter_profile', array_merge(get_option('newsletter_profile', array()), $options));
        $controls->data = get_option('newsletter_profile');
    }
}

$status = array(0 => 'Disabled/Private use', 1 => 'Only on profile page', 2 => 'Even on subscription forms');
$rules = array(0 => 'Optional', 1 => 'Required');
?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "specific_textareas",
        editor_selector: "visual",
        theme: "advanced",
        theme_advanced_disable: "styleselect",
        relative_urls: false,
        remove_script_host: false,
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_resizing: true,
        theme_advanced_statusbar_location: "bottom",
        document_base_url: "<?php echo get_option('home'); ?>/",
        content_css: "<?php echo get_option('blogurl'); ?>/wp-content/plugins/newsletter/editor.css?" + new Date().getTime()
    });
</script>

<div class="wrap">
    <?php $help_url = 'http://www.satollo.net/plugins/newsletter/subscription-module'; ?>
    <?php include NEWSLETTER_DIR . '/header-new.php'; ?>

    <div id="newsletter-title">
        <?php include NEWSLETTER_DIR . '/subscription/menu.inc.php'; ?>

        <h2>Subscription Form Fields and Layout</h2>
        <p>
            This panel contains the configuration of the subscription and profile editing forms which collect the subscriber data you want to have.<br>
            And let you to <strong>translate</strong> every single button and label.<br>
            <strong>Preferences</strong> can be an important setting for your newsletter: <a href="http://www.satollo.net/plugins/newsletter/newsletter-preferences" target="_blank">here you can read more about them</a>.
        </p>

    </div>
    <div class="newsletter-separator"></div>


    <?php $controls->show(); ?>
    <form action="" method="post">
        <?php $controls->init(); ?>

        <div id="tabs">

            <ul>
                <li><a href="#tabs-2">Main profile fields</a></li>
                <li><a href="#tabs-3">Extra profile fields</a></li>
                <li><a href="#tabs-4">Preferences</a></li>
                <li><a href="#tabs-5">Form code</a></li>
                <li><a href="#tabs-6">Form style</a></li>
            </ul>

            <div id="tabs-2">
                <div class="tab-preamble">
                    <p>The main subscriber fields. Only the email field is, of course, mandatory.</p>
                </div>
                <table class="form-table">
                    <tr>
                        <th>Email</th>
                        <td>
                            <table class="newsletter-option-grid">
                                <tr><th>Field label</th><td><?php $controls->text('email', 50); ?></td></tr>
                                <tr><th>Error message</th><td><?php $controls->text('email_error', 50); ?></td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Name or first name</th>
                        <td>
                            <table class="newsletter-option-grid">
                                <tr><th>Field label</th><td><?php $controls->text('name', 50); ?></td></tr>
                                <tr><th>When to show</th><td><?php $controls->select('name_status', $status); ?></td></tr>
                                <tr><th>Rules</th><td><?php $controls->select('name_rules', $rules); ?></td></tr>
                                <tr><th>Error message</th><td><?php $controls->text('name_error', 50); ?></td></tr>
                            </table>
                            <div class="hints">
                                If you want to collect only a generic "name", use only this field and not the
                                last name field.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Last name</th>
                        <td>
                            <table class="newsletter-option-grid">
                                <tr><th>Field label</th><td><?php $controls->text('surname', 50); ?></td></tr>
                                <tr><th>When to show</th><td><?php $controls->select('surname_status', $status); ?></td></tr>
                                <tr><th>Rules</th><td><?php $controls->select('surname_rules', $rules); ?></td></tr>
                                <tr><th>Error message</th><td><?php $controls->text('surname_error', 50); ?></td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Sex/Gender</th>
                        <td>
                            <table class="newsletter-option-grid">
                                <tr><th>Field label</th><td><?php $controls->text('sex', 50); ?></td></tr>
                                <tr><th>When to show</th><td><?php $controls->select('sex_status', $status); ?></td></tr>
                                <tr><th>Value labels</th><td>
                                        female: <?php $controls->text('sex_female'); ?>
                                        male: <?php $controls->text('sex_male'); ?>
                                        not specified: <?php $controls->text('sex_none'); ?>
                                    </td></tr>

                                <tr><th>Salutation titles</th><td>

                                        for males: <?php $controls->text('title_male'); ?> (ex. "Mr")<br>
                                        for females: <?php $controls->text('title_female'); ?> (ex. "Mrs")<br>
                                        for others: <?php $controls->text('title_none'); ?>
                                    </td></tr>
                            </table>
                            <div class="hints">
                                Salutation titles are inserted in emails message when the tag {title} is used. For example
                                "Good morning {title} {surname} {name}".
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Button labels</th>
                        <td>
                            <table class="newsletter-option-grid">
                                <tr><th>Subscribe button</th><td><?php $controls->text('subscribe'); ?></td></tr>
                                <tr><th>Save button</th><td><?php $controls->text('save'); ?> (on profile page)</td></tr>
                            </table>
                            <div class="hints">
                                For "subscribe" insert an URL to an image (http://...) to use it as a graphical button.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Privacy check box</th>
                        <td>
                            <table class="newsletter-option-grid">
                                <tr><th>Enabled?</th><?php $controls->yesno('privacy_status'); ?></td></tr>
                                <tr><th>Label</th><td><?php $controls->text('privacy', 50); ?></td></tr>
                                <tr><th>Error message</th><td><?php $controls->text('privacy_error', 50); ?></td></tr>
                                <tr><th>Privacy URL</th><td><?php $controls->text('privacy_url', 50); ?></td></tr>
                            </table>
                        </td>
                    </tr>


                </table>
            </div>


            <div id="tabs-3">
                <table class="form-table">
                    <tr>
                        <th>Generic profile fields</th>
                        <td>
                            <div class="hints">Fields of type "list" must be configured with a set of options, comma separated
                                like: "first option, second option, third option".
                            </div>
                            <table class="widefat">
                                <thead>
                                    <tr>
                                        <th>Field</th><th>Label</th><th>When/Where</th><th>Type</th><th>Configuration</th>
                                    </tr>
                                </thead>
                                <?php for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) { ?>
                                    <tr>
                                        <td>Profile <?php echo $i; ?></td>
                                        <td><?php $controls->text('profile_' . $i); ?></td>
                                        <td><?php $controls->select('profile_' . $i . '_status', $status); ?></td>
                                        <td><?php $controls->select('profile_' . $i . '_type', array('text' => 'Text', 'select' => 'List')); ?></td>
                                        <td>
                                            <?php $controls->textarea_fixed('profile_' . $i . '_options', '300px', '50px'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                            <div class="hints">
                                Those fields are collected as texts, Newsletter Pro does not give meaning to them, it just stores them.
                            </div>
                        </td>
                    </tr>
                </table>
            </div>


            <div id="tabs-4">
                <p>
                    Preferences are on/off choices users can change on their profile. Those preferences are then
                    used by you to target emails you create.
                </p>
                <table class="form-table">
                    <tr>
                        <th>Preferences</th>
                        <td>
                            <table class="widefat">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>When/Where</th>
                                        <th>Name</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <?php for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) { ?>
                                    <tr>
                                        <td>Preference <?php echo $i; ?></td>
                                        <td><?php $controls->select('list_' . $i . '_status', $status); ?></td>
                                        <td><?php $controls->text('list_' . $i); ?></td>
                                        <td><?php $controls->select('list_' . $i . '_checked', array(0 => 'Uncheked', 1 => 'Checked')); ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                            <div class="hints">
                                Disabled preferences are not selectable by users but they can be assigned from admin panels, so they can be
                                considered as private user preferences.
                            </div>
                        </td>
                    </tr>
                </table>
            </div>


            <div id="tabs-5">
                <div class="tab-preamble">
                    <p>This panel shows the form HTML code generated by Newsletter if you want to copy it as starting point for a custom form.</p>
                </div>

                <h3>Standard form code</h3>
                <textarea readonly style="width: 100%; height: 500px; font-family: monospace"><?php echo htmlspecialchars(NewsletterSubscription::instance()->get_subscription_form()); ?></textarea>

                <h3>Widget form code</h3>
                <textarea readonly style="width: 100%; height: 500px; font-family: monospace"><?php echo htmlspecialchars(NewsletterSubscription::instance()->get_subscription_form()); ?></textarea>

            </div>

            <div id="tabs-6">
                <div class="tab-preamble">
                    <p></p>
                </div>
                <table class="form-table">
                    <tr>
                        <th>Subscription form style</th>
                        <td>
                            <?php $controls->select('style', $module->get_styles()); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Widget style</th>
                        <td>
                            <?php $controls->select('widget_style', $module->get_styles()); ?>
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <p class="submit">
            <?php $controls->button('save', 'Save'); ?>
            <?php $controls->button_confirm('reset', 'Reset all', 'Are you sure you want to reset all?'); ?>
        </p>

    </form>
</div>