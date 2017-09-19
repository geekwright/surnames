<?php
/**
* English language constants used in the user side of the module
*
* @copyright	Copyright 2009-2010 geekwright, LLC
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Richard Griffith <richard@geekwright.com>
* @package		surnames
* @version		$Id$
*/

if (!defined("XOOPS_ROOT_PATH")) die("Root path not defined");

define("_MD_SURNAMES_LIST", "Surname List");

// Text for add a surname fields

define("_MD_SURNAMES_USER", "Researcher");
define("_MD_SURNAMES_USER_DSC", "Select the User this Surname applies to.");

define("_MD_SURNAMES_SURNAME", "Surname");
define("_MD_SURNAMES_SURNAME_DSC", "Enter one, and only one, surname that you are researching. You can add as many surnames as you would like, but only one at a time.");

define("_MD_SURNAMES_NOTES", "Notes");
define("_MD_SURNAMES_NOTES_DSC", 'Enter any extra detail which might help narrow someone else\'s search. For example, "from the John Smith family of the east district."');

define("_MD_SURNAMES_EDIT_BUTTON", "Submit");

define("_MD_SURNAMES_EDIT_DEL", "Check this box to delete this surname on submit.");
define("_MD_SURNAMES_CURRENT_LIST", "Currently Registered Surnames");

define("_MD_SURNAMES_PLUGIN_MSG_SINGLE", '<a href="%4$s">%1$s</a> occurs %2$d time in our <a href="%4$s">%3$s section</a>.');
define("_MD_SURNAMES_PLUGIN_MSG_PLURAL", '<a href="%4$s">%1$s</a> occurs %2$d times in our <a href="%4$s">%3$s section</a>.');

// fields for anonymous entries
define("_MD_SURNAMES_NAME", "Researcher Name");
define("_MD_SURNAMES_NAME_DSC", "Researcher's Name");

define("_MD_SURNAMES_EMAIL", "Researcher Email");
define("_MD_SURNAMES_EMAIL_DSC", "Researcher's Email Address");




// messages

define("_MD_SURNAMES_NO_ANON", "Anonymous posting is not allowed. Please log in.");
define("_MD_SURNAMES_TOKEN_ERR", "The security token check failed. Please try again.");
define("_MD_SURNAMES_DB_INS_ERR", "The insert failed. The error was: ");
define("_MD_SURNAMES_DB_UPD_ERR", "The update failed. The error was: ");
define("_MD_SURNAMES_DB_NOTFOUND", "The requested surname does not exist.");

define("_MD_SURNAMES_EDIT_MSG_ADD", "Surname posted.");
define("_MD_SURNAMES_EDIT_MSG_UPD", "Surname updated.");
define("_MD_SURNAMES_EDIT_MSG_DEL", "Surname deleted.");
define("_MD_SURNAMES_EDIT_MSG_PENDING", "Your changes were submitted for approval.");

define("_MD_SURNAMES_SURNAME_REQ", "The Surname may not be blank.");
define("_MD_SURNAMES_EDIT_ERR_4", "The Surname insert failed. The error was: ");

define("_MD_SURNAMES_EDIT_CAPTION", "Register this Surname");

define("_MD_SURNAMES_REVIEW_ERR_1", "You do not have the authority to perform this function.");
define("_MD_SURNAMES_REVIEW_ERR_2", "The security token check failed.");
define("_MD_SURNAMES_REVIEW_ERR_4", "The update failed. The error was: ");
define("_MD_SURNAMES_REVIEW_UPDMSG", '%d Records Processed');
define("_MD_SURNAMES_REVIEW_ANON", '(Anonymous)');
define("_MD_SURNAMES_VIEW_ACTIONS", "Actions");
define("_MD_SURNAMES_ACTIONS_APPROVE", "Approve");
define("_MD_SURNAMES_ACTIONS_CONFIRM", "Are you sure?");
define("_MD_SURNAMES_ACTIONS_DELETE", "Delete");
define("_MD_SURNAMES_REQUIRED_ERR", "%s is required. Please fill it in and try again.");

define("_MD_SURNAMES_VIEW_NO_NAME", "(not available)");
define("_MD_SURNAMES_VIEW_NO_EMAIL", "(not provided)");
define("_MD_SURNAMES_VIEW_HIDE_EMAIL", "(not shown)");

define("_MD_SURNAMES_PRINT", "Surname Print");
define("_MD_SURNAMES_PRINT_FOOTER", "<br /><br />[i]This surname was registered on:[/i]<br />");

// breadcrumb
define("_MD_SURNAMES_BC_ROOT", "Surnames");
define("_MD_SURNAMES_BC_EDIT", "Register Surname");
define("_MD_SURNAMES_LIST_BY_DATE", "By Date");
define("_MD_SURNAMES_LIST_REVIEW", "Review Unapproved");
define("_MD_SURNAMES_VIEW_SINGLE", "View Detail");
define("_MD_SURNAMES_LIST_ALL", "List All");
define("_MD_SURNAMES_BY_NEWEST", "Newest");
define("_MD_SURNAMES_BY_USER", "Single User");
define("_MD_SURNAMES_BY_SURNAME", "Single Surname");
