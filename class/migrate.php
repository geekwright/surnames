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
 * Class SurnamesMigrate synchronize existing tables with target schema
 *
 * @category  SurnamesMigrate
 * @package   Surnames
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2017 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class SurnamesMigrate extends Xmf\Database\Migrate
{
    private $renameTables = array(
        'surnames'     => 'surnames_register',
    );

    /**
     * SurnamesMigrate constructor.
     */
    public function __construct()
    {
        parent::__construct('surnames');
    }

    /**
     * change table prefix if needed
     */
    private function doRename()
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }

    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     *
     * @return void
     */
    protected function preSyncActions()
    {
        // fix table name
        $this->doRename();
    }
}
