<?php
header('Content-Type: text/html;charset=UTF-8');
header('X-Robots-Tag: noindex,nofollow,noarchive');
header('Cache-Control: no-cache,no-store,private');
// Patch to avoid "na" parameter to disturb the call
unset($_REQUEST['na']);
unset($_POST['na']);
unset($_GET['na']);

require_once '../../../../wp-load.php';

$module = NewsletterSubscription::instance();
if (!isset($module->options['antibot'])) $module->options['antibot'] = 0;

if ($module->options['antibot'] == 0 || $module->options['antibot'] == 1 && isset($_GET['ts']) && time() - $_GET['ts'] < 30) {

    $user = NewsletterSubscription::instance()->subscribe();
    if ($user->status == 'E')
        NewsletterSubscription::instance()->show_message('error', $user->id);
    if ($user->status == 'C')
        NewsletterSubscription::instance()->show_message('confirmed', $user->id);
    if ($user->status == 'A')
        NewsletterSubscription::instance()->show_message('already_confirmed', $user->id);
    if ($user->status == 'S')
        NewsletterSubscription::instance()->show_message('confirmation', $user->id);
}
else {
    ?><!DOCTYPE html>
    <html>
        <head>

        </head>
        <body onload="document.getElementById('form').action = '?ts=<?php echo time(); ?>';document.getElementById('form').submit()">
            <form id="form" action="<?php echo plugins_url('newsletter'); ?>/do/dummy.php" method="post">
                <?php foreach ($_REQUEST as $name => $value) { ?>
                    <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>">
                <?php } ?>
            </form>
        </body>
    </html>
    <?php
    return;
}
?>
Uncorrect status: <?php echo $user->status; ?>