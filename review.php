<?php
use Xmf\Request;
use Xmf\Module\Helper;
use Xmf\Module\Helper\Permission;

//  ------------------------------------------------------------------------ //

require __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'surnames_review.tpl';
include XOOPS_ROOT_PATH.'/header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$moduleHelper = Helper::getHelper('surnames');
$permHelper = new Permission('surnames');

/** @var XoopsTpl $xoopsTpl */
global $xoopsTpl;

// get our parameters
$start = Request::getInt('start', 0, 'get');
$opset = Request::getArray('opset', null, 'post');

// get our preferences and apply some sanity checks
$pref_rows = $moduleHelper->getConfig('pref_rows', 50);
$pref_rows = ($pref_rows<1) ? 10 : (($pref_rows>1000) ? 1000 : $pref_rows);
$xoopsTpl->assign('pref_rows', $pref_rows);
$limit=$pref_rows;
$total=0;

function myGetUnameFromId($uid)
{
    static $thisUser=false;
    if ($uid!=0) {
        if (!is_object($thisUser)) {
            /** @var \XoopsMemberHandler $member_handler */
            $member_handler = xoops_gethandler('member');
            /** @var \XoopsUser $thisUser */
            $thisUser = $member_handler->getUser($uid);
        }
        $name = htmlSpecialChars($thisUser->getVar('name'));
        if ($name=='') {
            $name = htmlSpecialChars($thisUser->getVar('uname'));
        }
    } else {
        $name=_MD_SURNAMES_REVIEW_ANON;
    }
    return $name;
}

function notificationTrigger($uid, $surname, $notes, $approved)
{
    global $xoopsModuleConfig, $xoopsDB, $xoopsUser;

    if ($approved && !empty($xoopsModuleConfig['notification_enabled'])) {
        $uids=array();
        $q_surname=$xoopsDB->escape($surname);
        $sql="SELECT uid FROM ".$xoopsDB->prefix('surnames_register'). " WHERE surname = '$q_surname' ";
        $result = $xoopsDB->query($sql);
        while ($row=$xoopsDB->fetchRow($result)) {
            $uids[]=$row[0];
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
        if (count($uids)) {
            $notification_handler->triggerEvent('global', 0, 'same_surname', $tags, $uids);
        }
        $notification_handler->triggerEvent('global', 0, 'new_surname', $tags);
    }
}

function notificationTriggerById($opid)
{
    global $xoopsDB;

    $uid=false;
    $sql="SELECT * FROM ".$xoopsDB->prefix('surnames_register'). " WHERE id=$opid ";
    $result = $xoopsDB->query($sql);
    if ($row=$xoopsDB->fetchArray($result)) {
        $uid=$row['uid'];
        $surname=$row['surname'];
        $notes=$row['notes'];
        $approved=$row['approved'];
    }
    if ($uid) {
        notificationTrigger($uid, $surname, $notes, $approved);
    }
}

unset($err_message, $message, $body);

// get our parameters
global $xoopsUser, $xoopsDB;

$approval_authority=$permHelper->checkPermission('surnames_approve', 2);
$op='display';
if (!$approval_authority) {
    redirect_header("index.php", 2, _MD_SURNAMES_REVIEW_ERR_1);
    exit();
}

if ($op!='') {
    if (isset($opset) && is_array($opset)) {
        $op='update';
        $check = $GLOBALS['xoopsSecurity']->check();
        if (!$check) {
            $op='display';
            $err_message = _MD_SURNAMES_TOKEN_ERR;
        }
    }
}

if ($op=='update') {
    $delcnt=0;
    $updcnt=0;
    foreach ($opset as $opid => $act) {
        switch ($act) {
            case 'approve':
                $sql = "UPDATE " . $xoopsDB->prefix('surnames_register') . " SET APPROVED=1 WHERE id=$opid ";
                $result = $xoopsDB->queryF($sql);
                if ($result) {
                    ++$updcnt;
                    notificationTriggerById($opid);
                } else {
                    $err_message = _MD_SURNAMES_EDIT_ERR_4 . ' ' . $xoopsDB->errno() . ' ' . $xoopsDB->error();
                }
                break;
            case 'delete':
                $sql = "DELETE FROM " . $xoopsDB->prefix('surnames_register') . " WHERE id=$opid ";
                $result = $xoopsDB->queryF($sql);
                if ($result) {
                    ++$delcnt;
                    xoops_comment_delete($moduleHelper->getModule()->getVar('mid'), $opid);
                } else {
                    $err_message = _MD_SURNAMES_EDIT_ERR_4 . ' ' . $xoopsDB->errno() . ' ' . $xoopsDB->error();
                }
                break;
            default:
                $err_message = "bad action '$act' for id '$opid'.";
                break;
        }
        if (isset($err_message)) {
            break;
        }
    }
    if ($delcnt || $updcnt) {
        $message = sprintf(_MD_SURNAMES_REVIEW_UPDMSG, intval($updcnt+$delcnt));
    }

    $op='display';
}

// first a count
if ($op=='display') {
    $sql="SELECT COUNT(DISTINCT surname) FROM ".$xoopsDB->prefix('surnames_register');
    $sql.=" WHERE approved=0 ";

    $total=0;
    $result = $xoopsDB->query($sql);
    if ($result) {
        $myrow=$xoopsDB->fetchRow($result);
        $total=$myrow[0];
    }

    $id_list = array();
    $uid_list = array();
    $surname_list = array();
    $notes_list = array();
    $name_list = array();

    $sql="SELECT id, uid, surname, notes FROM ".$xoopsDB->prefix('surnames_register');
    $sql.=" WHERE approved=0 ORDER BY uid, surname";

    $result = $xoopsDB->query($sql, $limit, $start);
    if ($result) {
        while ($myrow=$xoopsDB->fetchArray($result)) {
            $id_list[]=$myrow['id'];
            $temp_uid=$myrow['uid'];
            $uid_list[]=$temp_uid;
            $name_list[]=myGetUnameFromId($temp_uid);
            $surname_list[]=$myrow['surname'];
            $notes_list[]=$myrow['notes'];
        }
    }

    $xoopsTpl->assign('ids', $id_list);
    $xoopsTpl->assign('uids', $uid_list);
    $xoopsTpl->assign('names', $name_list);
    $xoopsTpl->assign('surnames', $surname_list);
    $xoopsTpl->assign('notes', $notes_list);
    $xoopsTpl->assign('formtoken', $GLOBALS['xoopsSecurity']->getTokenHTML());
}

// set up pagenav
if ($total > $limit) {
    include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
    $nav = new xoopsPageNav($total, $limit, $start, 'start', '');
    $xoopsTpl->assign('pagenav', $nav->renderNav());
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

include XOOPS_ROOT_PATH . '/footer.php';
