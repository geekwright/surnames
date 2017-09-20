<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                         <https://xoops.org/>                              //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

function surnames_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $ret = array();

    $sql = 'SELECT id, uid, surname, notes, UNIX_TIMESTAMP(changed_ts) as updtime FROM '
        . $xoopsDB->prefix('surnames_register') . ' WHERE approved=1';
    if ($userid != 0) {
        $sql .= " AND uid = $userid ";
    }
    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((surname LIKE '%$queryarray[0]%' OR notes LIKE '%$queryarray[0]%')";
        for ($i=1; $i<$count; $i++) {
            $sql .= " $andor ";
            $sql .= "(surname LIKE '%$queryarray[0]%' OR notes LIKE '%$queryarray[0]%')";
        }
        $sql .= ") ";
    }

    $sql .= "ORDER BY changed_ts DESC, surname";
    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $display=true;
//		if($i>=$limit) $display=false;
        if ($display) {
            $ret[$i]['image'] = "assets/images/search-result-icon.png";
            if ($myrow['notes']=='') {
                $ret[$i]['title'] = htmlspecialchars($myrow['surname'], ENT_QUOTES);
            } else {
                $ret[$i]['title'] = htmlspecialchars($myrow['surname'], ENT_QUOTES)
                    . ', ' . htmlspecialchars($myrow['notes'], ENT_QUOTES);
            }
            $ret[$i]['link'] = "view.php?id=".$myrow['id'];
            $ret[$i]['time'] = $myrow['updtime'];
            $ret[$i]['uid'] = $myrow['uid'];
            $i++;
        }
    }

    return $ret;
}
