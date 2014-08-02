<?php
$help_shortcodes_text=<<<EOD

<p>
	<strong>Create link</strong> - <code>[ps2id url='#id' offset='0']link[/ps2id]</code>
</p>

<p>
	<strong>Create link shrotcode attributes</strong> 
</p>

<ul>
	<li>
		<code>url</code> - target element id <br /> 
		Examples: <br /> 
		<code>[ps2id url='#some-id']link[/ps2id]</code> <br />
		<code>[ps2id url='http://somesite.com/somepage/#some-id']link[/ps2id]</code>
	</li>
	<li>
			<code>offset</code> - link specific offset <br /> 
			Examples: <br /> 
			<code>[ps2id url='#id' offset='100']link[/ps2id]</code> - offset by 100 pixels <br />
			<code>[ps2id url='#id' offset='#navigation-menu']link[/ps2id]</code> - offset by element's height/width/position <br />
			<code>[ps2id url='#id' offset='{"y":"100","x":"50"}']link[/ps2id]</code> - offset vertically by 100 pixels and horizontally by 50 
	</li>
</ul>

EOD;
?>

<?php _e($help_shortcodes_text, $this->plugin_slug); ?>