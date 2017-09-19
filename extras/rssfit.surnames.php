<?php
// $Id: rssfit.myalbum.php 244 2006-07-20 08:41:42Z tuff $
###############################################################################
##                RSSFit - Extendable XML news feed generator                ##
##                Copyright (c) 2004 - 2006 NS Tai (aka tuff)                ##
##                       <http://www.brandycoke.com/>                        ##
###############################################################################
##                    XOOPS - PHP Content Management System                  ##
##                       Copyright (c) 2000 XOOPS.org                        ##
##                          <http://www.xoops.org/>                          ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
/*
* This file is a dummy for making a RSSFit plug-in, follow the following steps
* if you really want to do so.
* Step 0:	Stop here if you are not sure what you are doing, it's no fun at all
* Step 1:	Clone this file and rename as something like rssfit.[mod_dir].php
* Step 2:	Replace the text "RssfitMyalbum" with "Rssfit[mod_dir]" at line 59 and
* 			line 65, i.e. "RssfitNews" for the module "News"
* Step 3:	Modify the word in line 60 from 'Myalbum' to [mod_dir]
* Step 4:	Modify the function "grabEntries" to satisfy your needs
* Step 5:	Move your new plug-in file to the RSSFit plugins folder,
* 			i.e. your-xoops-root/modules/rss/plugins
* Step 6:	Install your plug-in by pointing your browser to
* 			your-xoops-url/modules/rss/admin/?do=plugins
* Step 7:	Finally, tell us about yourself and this file by modifying the
* 			"About this RSSFit plug-in" section which is located... somewhere.
*
* [mod_dir]: Name of the driectory of your module, i.e. 'news'
*
* About this RSSFit plug-in
* Author: John Doe <http://www.your.site/>
* Requirements (or Tested with):
*  Module: Blah <http://www.where.to.find.it/>
*  Version: 1.0
*  RSSFit verision: 1.2 / 1.5
*  XOOPS version: 2.0.13.2 / 2.2.3
*/

if( !defined('RSSFIT_ROOT_PATH') ){ exit(); }
class RssfitSurnames {
	var $dirname = 'surnames';
	var $modname;
	var $grab;
	var $module;	// optional, see line 74

	function RssfitSurnames(){
	}

	function loadModule(){
		$mod = $GLOBALS['module_handler']->getByDirname($this->dirname);
		if( !$mod || !$mod->getVar('isactive') ){
			return false;
		}
		$this->modname = $mod->getVar('name');
		$this->module = $mod;	// optional, remove this line if there is nothing
								// to do with module info when grabbing entries
		return $mod;
	}

	function myGetUnameFromId($uid) {
		static $thisUser=false;
		static $lastUid=false;
		static $lastName='';

		if($lastUid==$uid) return $lastName;

		if (!is_object($thisUser)) {
			$member_handler = xoops_gethandler('member');
			$thisUser = $member_handler->getUser($uid);
		}
		$name = htmlSpecialChars($thisUser->getVar('name'));
		if($name=='') $name = htmlSpecialChars($thisUser->getVar('uname'));
		$lastUid=$uid;
		$lastName=$name;
		return $name;
	}

	function &grabEntries(&$obj){
		global $xoopsDB;
		$myts = MyTextSanitizer::getInstance();
		$ret = false;

		$i = -1;
		$lasttime=false;
		$lastuser=false;
		$lastname='';
		$limit=10*$this->grab;

		$sql = "SELECT uid, id, name, surname, notes, DATE_FORMAT(changed_ts,'%Y-%m-%d') as changedate FROM ".$xoopsDB->prefix("surnames_register");
		$sql .=" WHERE approved=1 ORDER BY changedate DESC, uid, name, surname ";
		$result = $xoopsDB->query($sql, $limit, 0);
		while( $row = $xoopsDB->fetchArray($result) ){
			$changedate=strtotime($row['changedate']);
			$uid=$row['uid'];
			$name=$row['name'];
			if($lasttime==$changedate && $lastuser==$uid && $lastname==$name) {
				$link = XOOPS_URL.'/modules/surnames/view.php?id='.$row['id'];
				$surname=$row['surname'];
				$desc .= "<a href=\"$link\">$surname</a><br />";
			}
			else {
				if($i>=0) {
					$ret[$i]['description'] = $desc;
				}
				++$i;
				$lasttime=$changedate;
				$lastuser=$uid;
				$lastname=$name;
				if ($i<=$this->grab) {
					$desc="";
					if($uid==0) htmlSpecialChars($dname=$name);
					else $dname = $this->myGetUnameFromId($uid);

					$ret[$i]['title'] = ($this->modname).': by '.$dname;
					$ret[$i]['link'] = XOOPS_URL.'/modules/surnames/list.php?uid='.$row['uid'];
					$ret[$i]['timestamp'] = $changedate;

					$link = XOOPS_URL.'/modules/surnames/view.php?id='.$row['id'];
					$ret[$i]['guid'] = $link;
					$ret[$i]['category'] = $this->modname;

					$surname=$row['surname'];
					$desc .= "<a href=\"$link\">$surname</a><br />";
				}

			}
			if ($i>$this->grab) break;
		}
		if ($i<$this->grab) {
			$ret[$i]['description'] = $desc;
		}
		return $ret;
	}
}
?>