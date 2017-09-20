<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_SURNAMES_BC_ROOT}></a> &gt; <{$smarty.const._MD_SURNAMES_LIST_REVIEW}></div>
<br />
<{if isset($err_message)}>
<div class="errorMsg"><{$err_message}></div>
<hr /><br />
<{/if}>
<{if isset($message)}>
<br /><hr />
<div class="resultMsg"><{$message}></div>
<hr /><br />
<{/if}>
<script LANGUAGE="JavaScript">
function confirmSubmit()
{
return confirm("<{$smarty.const._MD_SURNAMES_ACTIONS_CONFIRM}>");
}
</script>
<div class="content">
<form action="review.php" method="POST" onSubmit="return confirmSubmit();">
<table width="100%">
<tr>
<th>Id</td>
<th><{$smarty.const._MD_SURNAMES_USER}></th>
<th><{$smarty.const._MD_SURNAMES_SURNAME}></th>
<th><{$smarty.const._MD_SURNAMES_NOTES}></th>
<th><{$smarty.const._MD_SURNAMES_ACTIONS_APPROVE}></th>
<th><{$smarty.const._MD_SURNAMES_ACTIONS_DELETE}></th>
</tr>
<{section name=i loop=$surnames }>
<tr class="<{cycle values="odd,even"}>">
<td><a href="edit.php?id=<{$ids[i]}>"><{$ids[i]}></a></td>
<{if $uids[i]}>
<td><a href="<{$smarty.const.XOOPS_URL}>/userinfo.php?uid=<{$uids[i]}>"><{$names[i]}></a></td>
<{else}>
<td><{$names[i]}></td>
<{/if}>
<td><{$surnames[i]}></td>
<td><{$notes[i]}>&nbsp;</td>
<td><input type="radio" name="opset[<{$ids[i]}>]" value="approve"></td>
<td><input type="radio" name="opset[<{$ids[i]}>]" value="delete"></td>
</tr>
<{/section}>
</table>
<{$formtoken}>
<input class="btn btn-default" type="submit" value="submit"><input class="btn btn-default" type="reset" value="reset">
</div>
<{if isset($pagenav)}>
<hr />
<div align="right"><{$pagenav}></div>
<{/if}>
<{if isset($body)}>
<div><{$body}></div>
<{/if}>
<{include file='db:system_notification_select.tpl'}>
<{if isset($debug)}>
<hr />
<div><{$debug}></div>
<{/if}>