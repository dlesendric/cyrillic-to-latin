<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h1><?php _e('welcome title', 'cyrillic-to-latin');?></h1>
<hr>
<p><?php _e('welcome description', 'cyrillic-to-latin');?></p>
<p style="color:orangered"><?php _e('welcome notice', 'cyrillic-to-latin');?></p>
<p><?php _e('plugin summary', 'cyrillic-to-latin');?> <a href="https://paypal.me/dlesendric" target="_blank"><?php _e('support developer', 'cyrillic-to-latin');?></a></p>
<table class="wp-list-table widefat stripped">
	<thead>
		<tr>
			<th><?php _e('file', 'cyrillic-to-latin');?></th>
			<th><?php _e('description', 'cyrillic-to-latin');?></th>
			<th><?php _e('action', 'cyrillic-to-latin');?></th>
		</tr>
	</thead>
	<tbody class="the-list">
	<?php
        $prefix = isset($_GET['folder']) ? $_GET['folder'].'/' : '';
        if(count($languages) == 0):?>
            <tr><td colspan="3">Nema ni jedne stavke ovde... <a href="<?php $_SERVER['PHP_SELF'];?>?page=cyrillic-to-latin">Idi na početak</a></td></tr>
        <?php endif;?>
    <?php
		foreach($languages as $lang):
	?>
		<tr>
			<td>
				<?php if($lang['type'] === 'folder'): ?>
					<span class="dashicons dashicons-portfolio" title="Folder"></span>
					<a href="<?php echo $_SERVER['PHP_SELF'].'?page=cyrillic-to-latin&folder='.$prefix.$lang['name'];?>"><?php echo $lang['name'];?></a>
					<?php else: ?>
					<span class="dashicons dashicons-clipboard" title="File"></span>
					<?php echo $lang['name'];?>
            </td>
				<?php endif;?>
			<td><?php  _e( $lang['name'], 'cyrillic-to-latin' );?></td>
			<td>
                <?php if($lang['type'] === 'file'):?>
                    <?php if($lang['revert']):?>
                        <a class="button button-primary" href="?page=cyrillic-to-latin&revert=<?php echo urlencode($lang['revert_path']);?>"><?php _e('rollback', 'cyrillic-to-latin');?></a>
                    <?php else: ?>
                        <a class="button button-primary" href="?page=cyrillic-to-latin&convert=<?php echo urlencode($lang['path']);?>"><?php _e('convert', 'cyrillic-to-latin');?></a>
                    <?php endif;?>
                <?php endif;?>
            </td>
		</tr>
	<?php
		endforeach;
	?>
	</tbody>
</table>