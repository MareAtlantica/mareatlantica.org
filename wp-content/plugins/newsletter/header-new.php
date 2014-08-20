<?php
$dismissed = get_option('newsletter_dismissed', array());

if (isset($_REQUEST['dismiss']) && check_admin_referer()) {
    $dismissed[$_REQUEST['dismiss']] = 1;
    update_option('newsletter_dismissed', $dismissed);
}

?>

<?php if (isset($dismissed['rate']) && $dismissed['rate'] != 1) { ?>
<div class="newsletter-notice">
    I never asked before and I'm curious: <a href="http://wordpress.org/extend/plugins/newsletter/" target="_blank">would you rate this plugin</a>?
    (few seconds required). (account on WordPress.org required, every blog owner should have one...). <strong>Really appreciated, Stefano</strong>.
    <div class="newsletter-dismiss"><a href="<?php echo wp_nonce_url($_SERVER['REQUEST_URI'] . '&dismiss=rate')?>">Dismiss</a></div>
    <div style="clear: both"></div>
</div>
<?php } ?>

<?php if (isset($dismissed['newsletter-page']) && $dismissed['newsletter-page'] != 1 && empty(NewsletterSubscription::instance()->options['url'])) { ?>
<div class="newsletter-notice">
    Create a page with your blog style to show the subscription form and the subscription messages. Go to the
    <a href="?page=newsletter_subscription_options">subscription panel</a> to
    configure it.
    <div class="newsletter-dismiss"><a href="<?php echo wp_nonce_url($_SERVER['REQUEST_URI'] . '&dismiss=newsletter-page')?>">Dismiss</a></div>
    <div style="clear: both"></div>
</div>
<?php } ?>


<?php $newsletter->warnings(); ?>


<?php if (NEWSLETTER_HEADER) { ?>
<div id="newsletter-header-new">
    <div style="text-align: center">
        <a href="http://www.satollo.net/plugins/newsletter" target="_blank" style="font-weight: bold; font-size: 13px; text-transform: uppercase">Get the Professional Extensions!</a>
    </div>

    <div style="text-align: center; margin-top: 5px;">
    <a href="http://www.satollo.net/plugins/newsletter/newsletter-documentation" target="_blank"><img style="vertical-align: bottom" src="<?php echo plugins_url('newsletter'); ?>/images/header/documentation.png"> Documentation</a>
    <a href="http://www.satollo.net/forums" target="_blank"><img style="vertical-align: bottom" src="<?php echo plugins_url('newsletter'); ?>/images/header/forum.png"> Forum</a>
    <a href="https://www.facebook.com/satollo.net" target="_blank"><img style="vertical-align: bottom" src="<?php echo plugins_url('newsletter'); ?>/images/header/facebook.png"> Facebook</a>

    <!--<a href="http://www.satollo.net/plugins/newsletter/newsletter-collaboration" target="_blank">Collaboration</a>-->
    </div>

    <div style="text-align: center; margin-top: 5px;">
    <form style="margin: 0;" action="http://www.satollo.net/wp-content/plugins/newsletter/do/subscribe.php" method="post" target="_blank">
        My Newsletter<!-- to satollo.net--> <input type="email" name="ne" required placeholder="Your email" style="padding: 2px">
        <input type="submit" value="Go" style="padding: 2px">
    </form>
    </div>

    <div style="text-align: center; margin-top: 5px;">
        <table style="margin: 0 auto">
            <tr>
                <td style="padding: 2px; border: 0; margin: 0; vertical-align: middle">
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5Y6JXSA7BSU2L" target="_blank"><img style="vertical-align: bottom" src="<?php echo plugins_url('newsletter'); ?>/images/donate.png"></a>
                </td>
                <td style="padding: 2px; border: 0; margin: 0; vertical-align: middle">
    <a href="http://www.satollo.net/donations" target="_blank">Even <b>2$</b> really help: why?</a>                </td>
            </tr>
        </table>


    </div>
    <!--
    <a href="http://www.satollo.net/plugins/newsletter/newsletter-delivery-engine" target="_blank">Engine next run in <?php echo wp_next_scheduled('newsletter') - time(); ?> s</a>
    -->
</div>
<?php } ?>


