<?php
use Xmf\Request;
use Xmf\Module\Helper;
use Xmf\Module\Helper\Permission;

require __DIR__ . '/../../mainfile.php';
$op = Request::getString('op', 'view');

if ($op=='print') {
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    $GLOBALS['xoopsOption']['template_main'] = 'surnames_print.tpl';
    $xoopsTpl = new XoopsTpl();
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'surnames_view.tpl';
    include(XOOPS_ROOT_PATH."/header.php");
}

$id='0';
$uid='0';
$name='';
$surname='';
$notes='';
$is_approved=0;
$approve_others=0;
$user_url='';
$user_email='';
$edit_rights=0;
$print_ok=0;

if(isset($_GET['id'])) $id = intval($_GET['id']);
if(isset($_POST['id'])) { $id = intval($_POST['id']); $_GET['id']=$id; }  // make comments happy

$sql="SELECT uid, name, email, surname, notes, approved ";
$sql.="FROM ".$xoopsDB->prefix('surnames_register');
$sql.=" WHERE id = $id ";
$result = $xoopsDB->query($sql);
if ($result) {
	$myrow=$xoopsDB->fetchArray($result);
	$uid=$myrow['uid'];
	if($uid==0) {
		$q_name = $xoopsDB->escape($myrow['name']);
		$name = htmlSpecialChars($myrow['name']);
		$user_url='';
		$user_email=$myrow['email'];
		$user_location='';
		$user_sig='';
	}
	else {
		$member_handler = xoops_gethandler('member');
		$thisUser = $member_handler->getUser($uid);
        	if (!is_object($thisUser) || !$thisUser->isActive() ) {
				redirect_header("index.php",3,'Error');
				exit();
			}
		$q_name = '';
		$name = htmlSpecialChars($thisUser->getVar('name'));
		if($name=='') $name = htmlSpecialChars($thisUser->getVar('uname'));
		$user_url=$thisUser->getVar('url');
		if($thisUser->getVar('user_viewemail')) $user_email=$thisUser->getVar('email');
		else $user_email='';
		$user_location=$thisUser->getVar('user_from');
		$user_sig=$thisUser->getVar('user_sig');
	}

	$surname=$myrow['surname'];
	$qsurname=htmlspecialchars($surname, ENT_QUOTES);
	$surname=htmlspecialchars($surname, ENT_QUOTES);
	$notes=$myrow['notes'];
	$notes=stripslashes($notes);
	$notes=htmlspecialchars($notes, ENT_QUOTES);
	$is_approved=$myrow['approved'];

	$print_ok=true;
	$edit_rights=false;
	$permissionHelper = new Permission('surnames');
	$approve_others = $permissionHelper->checkPermission('surnames_approve', 2);
	if (is_object($xoopsUser)) {
		$testuid = $xoopsUser->getVar('uid');
        $edit_rights = ($testuid==$uid) ? true : $edit_rights;
        $edit_rights = ($approve_others) ? true : $edit_rights;
	}

	$myuid='0';
	$whereclause="name = '$q_name'";
	if(is_object($xoopsUser)) {
		$myuid=$xoopsUser->getVar('uid');
	}
	if($uid!=0) $whereclause="uid = $uid";

	$sql="SELECT id, surname FROM ".$xoopsDB->prefix('surnames_register');
	$sql.=" WHERE $whereclause AND surname!='$surname' AND (approved=1 OR uid=$myuid) ";
	$result = $xoopsDB->query($sql);
	$surname_list = array();
	if ($result) {
		while($myrow=$xoopsDB->fetchArray($result)) {
			$surname_list_id[]=$myrow['id'];
			$surname_list[]=$myrow['surname'];
		}
	}

}

if(isset($actions)) unset($actions);
if($print_ok) {
	$actions[] = array('action' => "view.php?id=$id&op=print", 'button' => 'fa fa-print', 'alt' => 'Print', 'extra' => ' target="_blank" ');
}

if($edit_rights) {
	if(!$is_approved && $approve_others) {
		$actions[] = array('action' => "edit.php?id=$id", 'button' => 'fa fa-check-square-o', 'alt' => 'Approve', 'extra' => '');
	}
	$actions[] = array('action' => "edit.php?id=$id", 'button' => 'fa fa-edit', 'alt' => 'Edit', 'extra' => '');
}

$xoopsTpl->assign('pref_cols', 3);
if(is_array($surname_list) && count($surname_list)) {
	$xoopsTpl->assign('surnames', $surname_list);
	$xoopsTpl->assign('surnames_ids', $surname_list_id);
}
if(is_array($actions)) $xoopsTpl->assign('actions', $actions);

$xoopsTpl->assign('op', $op);
$xoopsTpl->assign('id', $id);
$xoopsTpl->assign('uid', $uid);
$xoopsTpl->assign('name', $name);
$xoopsTpl->assign('surname', $surname);
$xoopsTpl->assign('qsurname', $qsurname);

if($notes!='') $xoopsTpl->assign('notes', $notes);
if($user_url!='') $xoopsTpl->assign('user_url', $user_url);
if($user_email!='') $xoopsTpl->assign('user_email', $user_email);
if($user_location!='') $xoopsTpl->assign('user_location', $user_location);
if($user_sig!='') $xoopsTpl->assign('user_sig', $user_sig);

include XOOPS_ROOT_PATH.'/include/comment_view.php';
if($op=='print') {
    $xoopsLogger->activated = false;
    $xoopsTpl->display("db:surnames_print.tpl");
} else {
include(XOOPS_ROOT_PATH."/footer.php");
}
