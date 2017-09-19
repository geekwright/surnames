<?php
function b_waiting_surnames()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
    $ret = array() ;

    $block = array();
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('surnames_register') . ' WHERE approved=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL."/modules/surnames/review.php" ;
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_SURNAMES_REVIEW ;
    }
    $ret[] = $block ;

    return $ret;
}
