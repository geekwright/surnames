<?php

$modversion['dirname'] = basename(__DIR__);

$modversion['name']                = _MI_SURNAMES_NAME;
$modversion['version']             = '1.1.0';
$modversion['description']         = _MI_SURNAMES_DESC;
$modversion['credits']             = "Richard Griffith";
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.5.9';
$modversion['system_menu']         = 1;
$modversion['help']                = 'page=help';
$modversion['license']             = "GNU GPL v2 or higher";
$modversion['license_url']         = XOOPS_URL . '/modules/' . $modversion['dirname'] . '/docs/license.txt';
//$modversion['license_url']         = substr($modversion['license_url'], strpos($modversion['license_url'], '//') + 2);
$modversion['official']            = 0;
$modversion['image']               = "assets/images/icon.png";

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "surnames_search";


// comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'view.php';

$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['update'] = 'surnames_com_update';

// notification
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'surnames_notify_iteminfo';

$modversion['notification']['category'][1] = array(
    'name'           => 'global',
    'title'          => _MI_SURNAMES_GLOBAL_NOTIFY,
    'description'    => _MI_SURNAMES_GLOBAL_NOTIFY_DSC,
    'subscribe_from' => array('edit.php', 'index.php', 'review.php', 'view.php'),
);

$modversion['notification']['category'][2] = array(
    'name'           => 'surname',
    'title'          => _MI_SURNAMES_SINGLE_NOTIFY,
    'description'    => _MI_SURNAMES_SINGLE_NOTIFY_DSC,
    'subscribe_from' => array('view.php'),
    'item_name'      => 'id',
    'allow_bookmark' => 0,
);

$modversion['notification']['event'][1] = array(
    'name'          => 'same_surname',
    'category'      => 'global',
    'title'         => _MI_SURNAMES_REG_NOTIFY,
    'caption'       => _MI_SURNAMES_REG_NOTIFYCAP,
    'description'   => _MI_SURNAMES_REG_NOTIFYDSC,
    'mail_template' => 'same_surname_notify',
    'mail_subject'  => _MI_SURNAMES_REG_NOTIFYSBJ,
);

$modversion['notification']['event'][2] = array(
    'name'          => 'new_surname',
    'category'      => 'global',
    'title'         => _MI_SURNAMES_NEW_NOTIFY,
    'caption'       => _MI_SURNAMES_NEW_NOTIFYCAP,
    'description'   => _MI_SURNAMES_NEW_NOTIFYDSC,
    'mail_template' => 'new_surname_notify',
    'mail_subject'  => _MI_SURNAMES_NEW_NOTIFYSBJ,
);

$modversion['notification']['event'][3] = array(
    'name'          => 'approval_needed',
    'category'      => 'global',
    'title'         => _MI_SURNAMES_NEW_NEED_APPROVAL,
    'caption'       => _MI_SURNAMES_NEW_NEED_APPROVAL_CAP,
    'description'   => _MI_SURNAMES_NEW_NEED_APPROVAL_DSC,
    'mail_template' => 'need_approval_notify',
    'mail_subject'  => _MI_SURNAMES_NEW_NEED_APPROVAL_SBJ,
    'admin_only'    => 1,
);

// Config

$modversion['config'][1]['name'] = 'pref_cols';
$modversion['config'][1]['title'] = '_MI_SURNAMES_PREF_COLS';
$modversion['config'][1]['description'] = '_MI_SURNAMES_PREF_COLS_DSC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 3;

$modversion['config'][2]['name'] = 'pref_rows';
$modversion['config'][2]['title'] = '_MI_SURNAMES_PREF_ROWS';
$modversion['config'][2]['description'] = '_MI_SURNAMES_PREF_ROWS_DSC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 50;

$modversion['config'][3]['name'] = 'postanon';
$modversion['config'][3]['title'] = '_MI_SURNAMES_POST_ANON';
$modversion['config'][3]['description'] = '_MI_SURNAMES_POST_ANON_DSC';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;

$modversion['config'][4]['name'] = 'captcha';
$modversion['config'][4]['title'] = '_MI_SURNAMES_CAPTCHA';
$modversion['config'][4]['description'] = '_MI_SURNAMES_CAPTCHA_DSC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "surnames_register";

global $xoopsUser;
$modHelper  = Xmf\Module\Helper::getHelper($modversion['dirname']);
$permHelper = new Xmf\Module\Helper\Permission($modversion['dirname']);

if (is_object($xoopsUser) || $modHelper->getConfig('postanon', false)) {
    $modversion['sub'][] = array(
        'name' => _MI_SURNAMES_MENU_ADD,
        'url' => "edit.php",
    );
}
if ($permHelper->checkPermission('surnames_approve', 2)) {
    $modversion['sub'][] = array(
        'name' => _MI_SURNAMES_MENU_REVIEW,
        'url' => "review.php",
    );
}

// Templates
$modversion['templates'][1]['file'] = 'surnames_index.tpl';
$modversion['templates'][1]['description'] = 'Module Index';

$modversion['templates'][2]['file'] = 'surnames_edit.tpl';
$modversion['templates'][2]['description'] = 'Edit Surname';

$modversion['templates'][3]['file'] = 'surnames_view.tpl';
$modversion['templates'][3]['description'] = 'Show Surname';

$modversion['templates'][4]['file'] = 'surnames_list.tpl';
$modversion['templates'][4]['description'] = 'List Surnames';

$modversion['templates'][5]['file'] = 'surnames_review.tpl';
$modversion['templates'][5]['description'] = 'Approval Reviews';

$modversion['templates'][6]['file'] = 'surnames_print.tpl';
$modversion['templates'][6]['description'] = 'Print Surname';
