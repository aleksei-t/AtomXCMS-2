<?php

##################################################
##												##
## Author:       Andrey Brykin (Drunya)         ##
## Version:      0.9                            ##
## Project:      CMS                            ##
## package       CMS Fapos                      ##
## subpackege    Admin Panel module             ##
## copyright     ©Andrey Brykin 2010-2011       ##
##################################################


##################################################
##												##
## any partial or not partial extension         ##
## CMS Fapos,without the consent of the         ##
## author, is illegal                           ##
##################################################
## Любое распространение                        ##
## CMS Fapos или ее частей,                     ##
## без согласия автора, является не законным    ##
##################################################


include_once '../sys/boot.php';
include_once ROOT . '/admin/inc/adm_boot.php';

$Register = Register::getInstance();
$FpsDB = $Register['DB'];
$api_url = 'http://home.develdo.com/';



// download & install plugin
if (!empty($_GET['download'])) {
	$set_plugin = str_replace(array('.', '/'), '', $_GET['download']);

	$set_url = $api_url . 'plugins/' . $set_plugin . '.zip';
	$new_path_z = ROOT . '/sys/plugins/' . $set_plugin . '.zip';
	$new_path = ROOT . '/sys/plugins/' . $set_plugin . '/';
	copy($set_url, $new_path_z);
	
	
	if (file_exists($new_path_z)) {
		Zip::extractZip($new_path_z, ROOT . '/sys/plugins/');
		
		
		if (file_exists($new_path . 'config.dat')) {
			$config = json_decode(file_get_contents($new_path . 'config.dat'), true);
			
			$obj = new $config['className'];
			if (method_exists($obj, 'install')) {
				$obj->install();
			}
		}
		
		$_SESSION['message'] = __('Plugin is saved');
		redirect('/admin/get_plugins.php');
	}
}




$pageTitle = __('Admin Panel');
$pageNav = $pageTitle . __(' - General information');
$pageNavl = '';

// get our plugins
$pl_url = ROOT . '/sys/plugins/*';
$our_plugins = glob($pl_url, GLOB_ONLYDIR);

foreach ($our_plugins as &$pl) {
	if (file_exists($pl . '/config.dat')) {
		$pl_conf = json_decode(get_cont($pl . '/config.dat'), true);
		if (!empty($pl_conf['title'])) {
			$pl = $pl_conf['title'];
		}
	}
}



// get foreign plugins
$url = 'http://home.develdo.com/plugins_api.php';
$data = json_decode(file_get_contents($url), true);


//echo $header;
include 'template/header.php';
?>


<?php
if (!empty($_SESSION['message'])):
?>
<script type="text/javascript">showHelpWin('<?php echo h($_SESSION['message']) ?>', '<?php echo __('Message') ?>');</script>
<?php
	unset($_SESSION['message']);
endif;
?>


<div class="warning">
<?php echo __('Plugins instruction') ?>
</div>



<!-- Download foreign plugins -->
<div id="sec" class="popup">
	<div class="top">
		<div class="title"><?php echo __('Download plugin') ?></div>
		<div onClick="closePopup('sec');" class="close"></div>
	</div>
	<form action="get_plugins.php?ac=download" method="POST" enctype="multipart/form-data">
	<div class="items">
		<div class="clear">&nbsp;</div>
		<div class="item">
			<div class="left">
				URL:
				<span class="comment"><?php echo __('Download plugin from remote server') ?></span>
			</div>
			<div class="right"><input type="text" name="pl_url" placeholder="http://site.com/path/to/plugin" /></div>
			<div class="clear"></div>
		</div>
		<div class="clear">&nbsp;</div>
		<div class="item">
			<div class="left">
				<?php echo __('Upload file') ?>:
				<span class="comment"><?php echo __('Upload as local file') ?></span>
			</div>
			<div class="right"><input type="file" accept="application/zip" name="pl_file" onChange="if (pl_file.value.substring(pl_file.value.lastIndexOf('.')+1, pl_file.value.length).toLowerCase() != 'zip')
{ alert('<?php echo __('File type should be ZIP') ?>!'); return; };" /></div>
			<div class="clear"></div>
		</div>
		<div class="clear">&nbsp;</div>
		<div class="item submit">
			<div class="left"></div>
			<div style="float:left;" class="right">
				<input type="submit" class="save-button" name="send" value="<?php echo __('Save') ?>">
			</div>
			<div class="clear"></div>
		</div>
	</div>
	</form>
</div>


<!-- Download from official server -->							
<div class="list">
	<div class="title"><?php echo __('Plugins download') ?></div>
	<div class="add-cat-butt" onClick="openPopup('sec');"><div class="add"></div><?php echo __('Download plugin') ?></div>
	<div class="level1">
		<div class="items" id="plugins">
		<?php foreach ($data as $row): ?>
			<div class="setting-item">
				<div class="left">
				<?php if (!empty($row['img']) && @fopen($row['img'], 'r')): ?>
					<img class="pl-preview" src="<?php echo h($row['img']) ?>" />
				<?php else: ?>
					NO IMAGE
				<?php endif; ?>
				</div>
				<div class="right">
					<h3><?php echo $row['title'] ?></h3>
					<?php echo $row['description'] ?><br /><br />
					<div class="r-but-container">
					<?php if (in_array($row['title'], $our_plugins)): ?>
						<strong class="green"><?php echo __('Plugin is saved') ?></strong>
					<?php else: ?>
						<a href="<?php echo WWW_ROOT ?>/admin/get_plugins.php?set_plugin=<?php echo $row['url'] ?>"><?php echo __('Install') ?></a>
					<?php endif; ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		<?php endforeach; ?>	
		</div>
	</div>
</div>
							




<?php
include_once 'template/footer.php';
