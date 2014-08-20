<table class="form-table">
    <tr>
        <th>Max posts</th>
        <td><?php $controls->text('theme_max_posts', 5); ?></td>
    </tr>
    <tr>
        <th>Categories</th>
        <td><?php $controls->categories_group('theme_categories'); ?></td>
    </tr>
</table>
<?php include WP_PLUGIN_DIR . '/newsletter/emails/themes/default/social-options.php'; ?>