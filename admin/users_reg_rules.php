<?php####################################################												#### Author:       Andrey Brykin (Drunya)         #### Version:      1.0                            #### Project:      CMS                            #### package       CMS Fapos                      #### subpackege    Admin Panel module             #### copyright     ©Andrey Brykin 2010-2011       ########################################################################################################												#### any partial or not partial extension         #### CMS Fapos,without the consent of the         #### author, is illegal                           ###################################################### Любое распространение                        #### CMS Fapos или ее частей,                     #### без согласия автора, является не законным    ####################################################include_once '../sys/boot.php';include_once ROOT . '/admin/inc/adm_boot.php';$pageTitle = __('Registration rules');$pageNav = $pageTitle;$pageNavr = '';include_once ROOT . '/admin/template/header.php';	if (isset($_POST['send'])) {	if (!empty($_POST['message'])) {		$check = $FpsDB->select('users_settings', DB_COUNT, array('cond' => array('type' => 'reg_rules')));		if ($check > 0) {			$FpsDB->save('users_settings', 				array(					'values' => $_POST['message'],				), array(					'type' => 'reg_rules',				)			);		} else {			$FpsDB->save('users_settings', 				array(					'values' => $_POST['message'],					'type' => 'reg_rules',				)			);		}	} else {		echo '<span style="color:red;">' . __('Fill in rules') . '</span>';	}}$query = $FpsDB->select('users_settings', DB_FIRST, array('cond' => array('type' => 'reg_rules')));if (count($query) > 0) {	$current_rules = $query[0]['values'];}?><div class="warning">	<?php echo __('Fill in registration rules on the your site. Users will be duty to read their.') ?></div><div class="list">	<div class="title"><?php echo __('Registration rules') ?></div>	<table style="width:100%;" cellspacing="0" class="grid">		<form action="" method="POST">			<tr>				<td>					<textarea name="message" style="width:99%; height:400px;"><?php echo (!empty($current_rules)) ? $current_rules : ''; ?></textarea>				</td>			</tr>			<tr>				<td align="center">					<input  class="save-button" type="submit" name="send" value="<?php echo __('Save') ?>" />				</td>			</tr>		</form>	</table></div><?phpinclude_once 'template/footer.php';?>