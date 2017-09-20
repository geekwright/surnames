<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Manage migrations file
 *
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2017 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */

/**
 * @param  XoopsModule $module
 * @return bool
 */
function xoops_module_pre_update_surnames(XoopsModule $module)
{
    XoopsLoad::load('migrate', 'surnames');
    $migrate = new SurnamesMigrate();
    $migrate->synchronizeSchema();

    return true;
}
