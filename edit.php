<?php

use Xmf\Request;
use Xmf\Module\Helper;
use Xmf\Module\Helper\Permission;

//  ------------------------------------------------------------------------ //

require __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'surnames_edit.tpl';
include XOOPS_ROOT_PATH.'/header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$moduleHelper = Helper::getHelper('surnames');
$permHelper = new Permission('surnames');

/** @var XoopsTpl $xoopsTpl */
global $xoopsTpl;

function notificationTrigger($uid, $surname, $notes, $approved)
{
    global $xoopsModuleConfig, $xoopsDB, $xoopsUser;

    if (empty($xoopsModuleConfig['notification_enabled'])) {
        return;
    }

    $uids=array();
    $q_surname=$xoopsDB->escape($surname);
    $sql="SELECT uid FROM " . $xoopsDB->prefix('surnames_register') . " WHERE surname = '$q_surname' ";
    $result = $xoopsDB->query($sql);
    while ($row=$xoopsDB->fetchRow($result)) {
        $uids[] = $row[0];
    }

    $regname = $xoopsUser->getUnameFromId($uid, 1);
    if ($regname=='') {
        $regname = $xoopsUser->getUnameFromId($uid, 0);
    }

    $tags = array();
    $tags['SURNAME'] = $surname;
    $tags['RESEARCHER'] = $regname;
    $tags['NOTES'] = $notes;
    $tags['SURNAME_URL'] = XOOPS_URL . '/modules/surnames/list.php?surname='.$surname;
    $tags['RECENT_URL'] = XOOPS_URL . '/modules/surnames/list.php';

    /** @var \XoopsNotificationHandler $notification_handler */
    $notification_handler = xoops_gethandler('notification');

    if (!$approved) {
        $notification_handler->triggerEvent('global', 0, 'approval_needed', $tags);
        return;
    }

    if (count($uids)) {
        $notification_handler->triggerEvent('global', 0, 'same_surname', $tags, $uids);
    }
    $notification_handler->triggerEvent('global', 0, 'new_surname', $tags);
}


// get our preferences and apply some sanity checks
$postanon = $moduleHelper->getConfig('postanon', false);
$useCaptcha = $moduleHelper->getConfig('captcha', false);

$pref_cols = $moduleHelper->getConfig('pref_cols', 3);
$pref_cols = ($pref_cols<1) ? 1 : (($pref_cols>10) ? 10 : $pref_cols);
$xoopsTpl->assign('pref_cols', $pref_cols);

$pref_rows = $moduleHelper->getConfig('pref_rows', 50);
$pref_rows = ($pref_rows<1) ? 10 : (($pref_rows>1000) ? 1000 : $pref_rows);
$xoopsTpl->assign('pref_rows', $pref_rows);

unset($err_message, $message, $body);

// get our parameters
global $xoopsUser, $xoopsDB;

$op = Request::getString('submit', 'display', 'post');
$op = ($op === _MD_SURNAMES_EDIT_BUTTON) ? 'update' : 'display';

$myuserid=0;
if (is_object($xoopsUser)) {
    $myuserid = (int) $xoopsUser->getVar('uid');
}

$userid = Request::getInt('userid', $myuserid, 'post');

$surname = Request::getString('surname', '', 'post');
$notes = Request::getString('notes', '', 'post');
$anon_name = Request::getString('name', '', 'post');
$anon_email = Request::getEmail('email', '', 'post');

$approved=0;
$id_approved=0;

// passed in id, set all values from database, overrides post data
$id = Request::getInt('id', 0);

$delete_request = false;
if ($id !== 0) {
    $sql="SELECT uid, name, email, surname, notes, approved ";
    $sql.="FROM ".$xoopsDB->prefix('surnames_register');
    $sql.=" WHERE id = $id ";
    $result = $xoopsDB->query($sql);
    if ($result) {
        $myrow=$xoopsDB->fetchArray($result);
        $userid=(int) $myrow['uid'];
        $anon_name = $myrow['name'];
        $anon_email=$myrow['email'];
        $surname=$myrow['surname'];
        $notes=$myrow['notes'];
        $id_approved=$myrow['approved'];
    }
    if ('POST' == Request::getMethod()) {
        $delete_request = Request::getBool('delete_box', false, 'post');
        if (!$delete_request) {
            $userid = Request::getInt('userid', $userid, 'post');
            $surname = Request::getString('surname', $surname, 'post');
            $notes = Request::getString('notes', $notes, 'post');
            $anon_name = Request::getString('name', $anon_name, 'post');
            $anon_email = Request::getEmail('email', $anon_email, 'post');
        }
    }
}

$q_surname='';
$q_notes='';

$approve_own = $permHelper->checkPermission('surnames_approve', 1);
$approve_others = $permHelper->checkPermission('surnames_approve', 2);

// leave if trying anonymous edit
if ($myuserid === 0 && $id !== 0) {
    redirect_header(XOOPS_URL.'/modules/surnames/index.php', 3, _NOPERM);
}

// leave if anonymous posting is not allowed
if ($myuserid === 0 && !$postanon) {
    redirect_header(XOOPS_URL.'/user.php', 3, _MD_SURNAMES_NO_ANON);
}

// leave if no permission to edit others and id differs
if (!$approve_others && $myuserid !== $userid && $id) {
    redirect_header(XOOPS_URL.'/modules/surnames/index.php', 3, _NOPERM);
}

$user_mode='anon';
if ($myuserid !== 0) {
    $user_mode='reg';
    if ($approve_others) {
        $user_mode='editor';
    }
    if ($xoopsUser->isAdmin()) {
        $user_mode='admin';
    }
    $approved=$xoopsUser->isAdmin();
}

if ($myuserid === $userid && $approve_own) {
    $approved=1;
}
if ($myuserid !== $userid && $approve_others) {
    $approved=1;
}

if ($myuserid !== $userid && !$approve_others && $id == 0) {
    $op='display';
}


if ($op=='update') {
    $check=$GLOBALS['xoopsSecurity']->check();

    if (!$check) {
        $op='display';
        $err_message = _MD_SURNAMES_TOKEN_ERR;
    }
}

// check to see if a delete was requested
// if we have made it this far, we have the authority to issue the delete.
if ($op=='update') {
    if ($delete_request) {
        $op='delete';
    }
}

if ($op=='delete') {
    if ($delete_request) {
        $sql="DELETE FROM ".$xoopsDB->prefix('surnames_register')." WHERE id = $id ";
        $result = $xoopsDB->queryF($sql);
        if ($result) {
            $message = _MD_SURNAMES_EDIT_MSG_DEL;
            xoops_comment_delete($moduleHelper->getModule()->getVar('mid'), $id);
        } else {
            $err_message = _MD_SURNAMES_EDIT_ERR_4 .' '.$xoopsDB->errno() . ' ' . $xoopsDB->error();
        }
        $id=0;
        $surname='';
        $notes='';
        $op='display';
    }
}

if ($op=='update') {
    $surname=strtoupper($surname);
    if ($surname=='') {
        $message=_MD_SURNAMES_SURNAME_REQ;
        $op='display';
    }
}

if ($op=='update' && $useCaptcha) {
    xoops_load('XoopsCaptcha');
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if (!$xoopsCaptcha->verify()) {
        $op = 'display';
        $err_message = $xoopsCaptcha->getMessage();
    }
}

if ($op=='update') {
    $q_surname = $xoopsDB->escape($surname);
    $q_notes   = $xoopsDB->escape($notes);
    $q_name    = $xoopsDB->escape($anon_name);
    $q_email   = $xoopsDB->escape($anon_email);

    $approved = $approved==0 ? '0' : $approved;

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('surnames_register')
        . ' (uid, name, email, surname, notes, approved, changed_ts)'
        . " VALUES ($userid, '$q_name', '$q_email', '$q_surname', '$q_notes', $approved, NOW() )";

    $result = $xoopsDB->queryF($sql);
    if ($result) {
        notificationTrigger($userid, $surname, $notes, $approved);
        $message = _MD_SURNAMES_EDIT_MSG_ADD;
        if (!$approved) {
            $message .= ' '._MD_SURNAMES_EDIT_MSG_PENDING;
        }
        $surname='';
        $notes='';
    } else {
        if ($xoopsDB->errno()==1062) { // record exists, try an update instead. We only change notes and timestamp
            $sql = 'UPDATE ' . $xoopsDB->prefix('surnames_register')
                . " SET notes='$q_notes', approved=$approved, name='$q_name', email='$q_email', changed_ts=NOW()"
                . " WHERE id = $id ";
            $result = $xoopsDB->queryF($sql);
            if ($result) {
                notificationTrigger($userid, $surname, $notes, $approved);
                $message = _MD_SURNAMES_EDIT_MSG_UPD;
                if (!$approved) {
                    $message .= ' '._MD_SURNAMES_EDIT_MSG_PENDING;
                }
                $surname='';
                $notes='';
            } else {
                $err_message = _MD_SURNAMES_EDIT_ERR_4 .' '.$xoopsDB->errno() . ' ' . $xoopsDB->error();
            }
        } else {
            $err_message = _MD_SURNAMES_EDIT_ERR_4 .' '.$xoopsDB->errno() . ' ' . $xoopsDB->error();
        }
    }
    $op='display';
}

if ($op=='display') {
    $token=1;
    $joiner1='<br /><span style="font-weight:normal;">';
    $joiner2='</span>';
    $form = new XoopsThemeForm('Add New Surname', 'form1', 'edit.php', 'POST', $token);

    if ($approve_others) {
        // caption, name, include_annon, size (1 for dropdown), multiple
        $caption = _MD_SURNAMES_USER . $joiner1 . _MD_SURNAMES_USER_DSC . $joiner2;
        $form->addElement(new XoopsFormSelectUser($caption, 'uid', true, $userid, 1, false));
    }
    if ($user_mode=='reg') {
        $caption = _MD_SURNAMES_USER;
        $myname = $xoopsUser->getVar('name');
        if ($myname=='') {
            $myname = $xoopsUser->getVar('uname');
        }
        $form->addElement(new XoopsFormLabel($caption, $myname));
        $form->addElement(new XoopsFormHidden('uid', $myuserid));
    }
    if ($user_mode!='reg') {
        $caption = _MD_SURNAMES_NAME . $joiner1 . _MD_SURNAMES_NAME_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'name', 20, 30, htmlspecialchars($anon_name, ENT_QUOTES)));
        $caption = _MD_SURNAMES_EMAIL . $joiner1 . _MD_SURNAMES_EMAIL_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'email', 20, 30, htmlspecialchars($anon_email, ENT_QUOTES)));
    }

    $caption = _MD_SURNAMES_SURNAME . $joiner1 . _MD_SURNAMES_SURNAME_DSC . $joiner2;
    $form->addElement(new XoopsFormText($caption, 'surname', 20, 30, htmlspecialchars($surname, ENT_QUOTES)));
    $caption = _MD_SURNAMES_NOTES . $joiner1 . _MD_SURNAMES_NOTES_DSC . $joiner2;
    $form->addElement(new XoopsFormText($caption, 'notes', 40, 120, htmlspecialchars($notes, ENT_QUOTES)));

    if ($id!=0) {
        $caption = '';
        $checked_value = 1;
        $checkbox = new XoopsFormCheckBox($caption, 'delete_box', !$checked_value);
        $checkbox->addOption($checked_value, _MD_SURNAMES_EDIT_DEL);
        $form->addElement($checkbox);
        $form->addElement(new XoopsFormHidden('id', $id));
    }

    if ($useCaptcha) {
        $form->addElement(new XoopsFormCaptcha());
    }

    $form->addElement(new XoopsFormButton('Add this Surname', 'submit', _MD_SURNAMES_EDIT_BUTTON, 'submit'));

    // XoopsFormText( string $caption, string $name, int $size, int $maxlength, string $value = "" )
    // XoopsFormHidden($name, $value)

    //$form->display();
    $body=$form->render();
}

if ($op!='' && $userid!=0) {
    if (isset($surname_list)) {
        unset($surname_list);
    }

    $sql="SELECT id, surname FROM ".$xoopsDB->prefix('surnames_register');
    $sql.=" WHERE uid=$userid ORDER BY surname";

    $result = $xoopsDB->query($sql);
    if ($result) {
        while ($myrow=$xoopsDB->fetchArray($result)) {
            $id_list[]=$myrow['id'];
            $surname_list[]=$myrow['surname'];
        }
        if (isset($surname_list)) {
            $xoopsTpl->assign('surnames', $surname_list);
        }
        if (isset($id_list)) {
            $xoopsTpl->assign('ids', $id_list);
        }
    }
}

if (isset($body)) {
    $xoopsTpl->assign('body', $body);
}

if (isset($message)) {
    $xoopsTpl->assign('message', $message);
}
if (isset($err_message)) {
    $xoopsTpl->assign('err_message', $err_message);
}
if (isset($debug)) {
    $xoopsTpl->assign('debug', $debug);
}

include XOOPS_ROOT_PATH . '/footer.php';
