<?php
use Xmf\Request;
use Xmf\Module\Helper;

//  ------------------------------------------------------------------------ //

require __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'surnames_list.tpl';
include(XOOPS_ROOT_PATH."/header.php");
$moduleHelper = Helper::getHelper('surnames');

function myGetUnameFromId($uid)
{
    static $memberHandler=false;

    if (!is_object($memberHandler)) {
        /** @var \XoopsMemberHandler $member_handler */
        $memberHandler = xoops_gethandler('member');
    }
    /** @var \XoopsUser $thisUser */
    $thisUser = $memberHandler->getUser($uid);
    $name = $thisUser->getVar('name', 's');
    if ($name=='') {
        $name = $thisUser->getVar('uname', 's');
    }
    return($name);
}

// get our parameters
$myuid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

$start = Request::getInt('start', 0, 'get');
$userid = Request::getInt('uid', 0, 'get');
$surname = Request::getString('surname', '', 'get');
//$surname = stripcslashes($surname);

$op='display';
$bc_level = '';

// get our preferences and apply some sanity checks
$pref_rows = $moduleHelper->getConfig('pref_rows', 50);
$pref_rows = ($pref_rows<1) ? 10 : (($pref_rows>1000) ? 1000 : $pref_rows);
$xoopsTpl->assign('pref_rows', $pref_rows);
$limit=$pref_rows;
$total=0;

// get the data
if ($op=='display') {
    $orderby_clause = 'ORDER BY changed_ts DESC';
    $where_clause='';
    $search_target='';
    $bc_level = _MD_SURNAMES_BY_NEWEST;
    if ($userid) {
        $where_clause .= "AND uid=$userid ";
        $search_target .= _MD_SURNAMES_USER.": ".myGetUnameFromId($userid)."  ";
        $orderby_clause = 'ORDER BY surname ';
        $bc_level = _MD_SURNAMES_BY_USER;
    }
    if ($surname!='') {
        $q_surname=$xoopsDB->escape($surname);
        $where_clause .= "AND surname='$q_surname' ";
        $search_target .= _MD_SURNAMES_SURNAME.":  ".$surname."  ";
        $orderby_clause = 'ORDER BY surname, name, uid';
        $bc_level = _MD_SURNAMES_BY_SURNAME;
    }


    $sql="SELECT COUNT(*) FROM ".$xoopsDB->prefix('surnames_register');
    $sql.=" WHERE (approved=1 OR (uid=$myuid and uid<>0)) $where_clause";

    $total=0;
    $result = $xoopsDB->query($sql);
    if ($result) {
        $myrow=$xoopsDB->fetchRow($result);
        $total=$myrow[0];
    }

    unset($id_list, $uid_list, $surname_list, $notes_list, $name_list, $comcnt_list);

    $sql="SELECT id, uid, surname, notes, comment_count, changed_ts, name ";
    $sql.="FROM ".$xoopsDB->prefix('surnames_register');
    $sql.=" WHERE (approved=1 OR (uid=$myuid and uid<>0)) $where_clause ";
    $sql.=" $orderby_clause ";

    $result = $xoopsDB->query($sql, $limit, $start);
    if ($result) {
        while ($myrow=$xoopsDB->fetchArray($result)) {
            $id_list[]=$myrow['id'];
            $uid_list[]=$myrow['uid'];
            if ($myrow['uid']==0) {
                $temp_name=$myrow['name'];
            } else {
                $temp_name=myGetUnameFromId($myrow['uid']);
            }
            $name_list[]=$temp_name;
            $surname_list[]=htmlSpecialChars($myrow['surname']);
            $qsurname_list[]=htmlSpecialChars($myrow['surname'], ENT_QUOTES);
            $notes_list[]=$myrow['notes'];
            $comcnt_list[]=$myrow['comment_count'];
        }
    }

    $xoopsTpl->assign('search_target', $search_target);

    $xoopsTpl->assign('ids', $id_list);
    $xoopsTpl->assign('uids', $uid_list);
    $xoopsTpl->assign('names', $name_list);
    $xoopsTpl->assign('surnames', $surname_list);
    $xoopsTpl->assign('qsurnames', $qsurname_list);
    $xoopsTpl->assign('notes', $notes_list);
    $xoopsTpl->assign('comcnt', $comcnt_list);
    $xoopsTpl->assign('bc_level', $bc_level);
}

// tng plugin
if ($surname!='') {
    $pluginpath=XOOPS_ROOT_PATH.'/modules/tng/include/surnames.plugin.php';
    if (file_exists($pluginpath)) {
        include $pluginpath;
        $plugin_values=tng_surname_plugin($surname);
        if (is_array($plugin_values)) {
            if ($plugin_values['count'] > 1) {
                $plugin_message = sprintf(_MD_SURNAMES_PLUGIN_MSG_PLURAL, $plugin_values['surname'], $plugin_values['count'], $plugin_values['section'], $plugin_values['link']);
            } else {
                $plugin_message = sprintf(_MD_SURNAMES_PLUGIN_MSG_SINGLE, $plugin_values['surname'], $plugin_values['count'], $plugin_values['section'], $plugin_values['link']);
            }
            $xoopsTpl->assign('plugin_message', $plugin_message);
        }
    }
}

// set up pagenav
if ($total > $limit) {
    include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
    if ($surname!='') {
        $nav = new xoopsPageNav($total, $limit, $start, 'start', 'surname='.$surname);
    } elseif ($userid!=0) {
        $nav = new xoopsPageNav($total, $limit, $start, 'start', 'uid='.$userid);
    } else {
        $nav = new xoopsPageNav($total, $limit, $start, 'start');
    }
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

include(XOOPS_ROOT_PATH."/footer.php");
