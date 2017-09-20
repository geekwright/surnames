<?php
// Module Info

// The name of this module
define('_MI_SURNAMES_NAME','Surname Registry');

// A brief description of this module
define('_MI_SURNAMES_DESC','Record genealogical researchers interested in specific surnames.');

// Text for menu
define('_MI_SURNAMES_MENU_ADD', 'Add Surname');
define('_MI_SURNAMES_MENU_REVIEW', 'Review');

// Text for admin area
define('_MI_SURNAMES_ADMAIN', 'Home');
define('_MI_SURNAMES_ABOUT', 'About');
define('_MI_SURNAMES_ADPERM', 'Permissions');

define('_MI_SURNAMES_ADMENU_INDEX', 'Permissions');
define('_MI_SURNAMES_AD_PERM_TITLE', 'Permissions for Add Surname');
define('_MI_SURNAMES_AD_PERM_1', 'Auto-Approve');
define('_MI_SURNAMES_AD_PERM_2', 'Add/Edit/Delete for Others');

// Text for config options

define('_MI_SURNAMES_PREF_ROWS', 'Display Rows?');
define('_MI_SURNAMES_PREF_ROWS_DSC', 'Maximum number of rows to display on a surname display.');

define('_MI_SURNAMES_PREF_COLS', 'Display Columns?');
define('_MI_SURNAMES_PREF_COLS_DSC', 'Number of columns to use in columar surname displays.');

define ('_MI_SURNAMES_POST_ANON', 'Anonymous Posting');
define ('_MI_SURNAMES_POST_ANON_DSC', 'Allow posting from Anonymous guests.');

define ('_MI_SURNAMES_CAPTCHA', 'Enable Posting Captcha');
define ('_MI_SURNAMES_CAPTCHA_DSC', 'Require Captcha on Query Posting.');

// notifications

define ('_MI_SURNAMES_GLOBAL_NOTIFY', 'Surnames');
define ('_MI_SURNAMES_GLOBAL_NOTIFY_DSC', 'Notification options that apply to surnames.');

define ('_MI_SURNAMES_SINGLE_NOTIFY', 'Single Item');
define ('_MI_SURNAMES_SINGLE_NOTIFY_DSC', 'Notification options that apply to a single surname by a single researcher.');

define ('_MI_SURNAMES_REG_NOTIFY', 'Same Surname');
define ('_MI_SURNAMES_REG_NOTIFYCAP', 'Notify me if someone else registers any of my surnames.');
define ('_MI_SURNAMES_REG_NOTIFYDSC', 'Receive notification when anyone registers a surname which matches one you have entered.');
define ('_MI_SURNAMES_REG_NOTIFYSBJ', '[{X_SITENAME}] auto-notify : {SURNAME} registered');

define ('_MI_SURNAMES_NEW_NOTIFY', 'New Surname');
define ('_MI_SURNAMES_NEW_NOTIFYCAP', 'Notify me whenever someone registers any surname.');
define ('_MI_SURNAMES_NEW_NOTIFYDSC', 'Receive notification when anyone registers any surname.');
define ('_MI_SURNAMES_NEW_NOTIFYSBJ', '[{X_SITENAME}] auto-notify : {SURNAME} registered');
define ('_MI_SURNAMES_NEW_NEED_APPROVAL', 'New Surname Needs Approval');
define ('_MI_SURNAMES_NEW_NEED_APPROVAL_CAP', 'Surname Approval Needed.');
define ('_MI_SURNAMES_NEW_NEED_APPROVAL_DSC', 'Receive notification when a registered surname needs approval.');
define ('_MI_SURNAMES_NEW_NEED_APPROVAL_SBJ', '[{X_SITENAME}] auto-notify : {SURNAME} Needs Approval');
