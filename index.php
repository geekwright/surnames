<?php
use Xmf\Request;

require __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'surnames_index.tpl';
include(XOOPS_ROOT_PATH."/header.php");
// include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/calendar.php";

// get our parameters
$start = Request::getInt('start', 0, 'GET');

// get our preferences and apply some sanity checks
$pref_cols=$xoopsModuleConfig['pref_cols'];
if ($pref_cols<1) {
    $pref_cols=1;
}
if ($pref_cols>10) {
    $pref_cols=10;
}
$xoopsTpl->assign('pref_cols', $pref_cols);

$pref_rows=$xoopsModuleConfig['pref_rows'];
if ($pref_rows<1) {
    $pref_rows=10;
}
if ($pref_rows>1000) {
    $pref_rows=1000;
}
$xoopsTpl->assign('pref_rows', $pref_rows);

$limit = $pref_rows * $pref_cols;

// get the data
$myuserid = '0';
if (is_object($xoopsUser)) {
    $myuserid = $xoopsUser->getVar('uid');
}
// first a count
$sql="SELECT COUNT(DISTINCT surname) FROM ".$xoopsDB->prefix('surnames_register');
$sql.=" WHERE approved=1 OR (uid=$myuserid and uid!=0) ";

$total=0;
$result = $xoopsDB->query($sql);
if ($result) {
    $myrow=$xoopsDB->fetchRow($result);
    $total=$myrow[0];
}

if (isset($surname_list)) {
    unset($surname_list);
}

$sql="SELECT DISTINCT surname FROM ".$xoopsDB->prefix('surnames_register');
$sql.=" WHERE approved=1 OR (uid=$myuserid and uid!=0) ORDER BY surname";

$result = $xoopsDB->query($sql, $limit, $start);
if ($result) {
    while ($myrow=$xoopsDB->fetchArray($result)) {
        $surname_list[]=htmlSpecialChars($myrow['surname']);
        $qsurname_list[]=htmlSpecialChars($myrow['surname'], ENT_QUOTES);
    }
}

$xoopsTpl->assign('surnames', $surname_list);
$xoopsTpl->assign('qsurnames', $qsurname_list);

// set up pagenav
if ($total > $limit) {
    include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
    $nav = new xoopsPageNav($total, $limit, $start, 'start', '');
    $xoopsTpl->assign('pagenav', $nav->renderNav());
}

include XOOPS_ROOT_PATH . '/footer.php';
