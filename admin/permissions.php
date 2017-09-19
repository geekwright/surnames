<?php
// ------------------------------------------------------------------------- //
//                Surnames - XOOPS surname researchers                       //
// ------------------------------------------------------------------------- //

use Xmf\Module\Admin;

//
// Form Part
//
require dirname(__FILE__) . '/admin_header.php';

/** @var Admin $moduleAdmin */
$moduleAdmin = Admin::getInstance();
$moduleAdmin->displayNavigation('permissions.php');

global $xoopsModule, $xoopsConfig;

include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
$module_id = $xoopsModule->getVar('mid');

$item_list = array('1' => _MI_SURNAMES_AD_PERM_1, '2' => _MI_SURNAMES_AD_PERM_2);

$title_of_form = _MI_SURNAMES_AD_PERM_TITLE;
$perm_name = 'surnames_approve';
$perm_desc = '';

$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
foreach ($item_list as $item_id => $item_name) {
	$form->addItem($item_id, $item_name);
}

$moduleAdmin->addItemButton(_MI_SURNAMES_MENU_REVIEW, XOOPS_URL.'/modules/surnames/review.php', 'button_ok');
$moduleAdmin->displayButton();

echo "<div>";
echo $form->render();
echo "</div>";

require dirname(__FILE__) . '/admin_footer.php';
