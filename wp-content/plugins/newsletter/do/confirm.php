<?php
header('Content-Type: text/html;charset=UTF-8');
header('X-Robots-Tag: noindex,nofollow,noarchive');
header('Cache-Control: no-cache,no-store,private');
if (isset($_GET['ts']) && time() - $_GET['ts'] < 30) {
// Patch to avoid "na" parameter to disturb the call
    unset($_REQUEST['na']);
    unset($_POST['na']);
    unset($_GET['na']);

// This page is linked to {subscription_confirm_url} tag.

    include '../../../../wp-load.php';

    $user = NewsletterSubscription::instance()->confirm();
    if ($user->status == 'E') {
        NewsletterSubscription::instance()->show_message('error', $user->id);
    } else {
        NewsletterSubscription::instance()->show_message('confirmed', $user);
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <script>
                location.href = location.href + "&ts=<?php echo time(); ?>";
            </script>
        </head>
        <body>
            If you're not redirect in few seconds, <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&ts=<?php echo time(); ?>">click here</a>, thank you.
        </body>
    </html>
    <?php
}
?>
