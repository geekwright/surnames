<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_SURNAMES_BC_ROOT}></a> &gt; <{$bc_level}></div>

<br />
<{if isset($err_message)}>
<div class="errorMsg"><{$err_message}></div>
<hr /><br />
<{/if}>
<{if isset($message)}>
<br /><hr />
<div style="font-weight: bold;"><{$message}></div>
<hr /><br />
<{/if}>
<div class="item">
<table width="100%">
<tr>
<th><{$smarty.const._MD_SURNAMES_USER}></th>
<th><{$smarty.const._MD_SURNAMES_SURNAME}></th>
<th><{$smarty.const._MD_SURNAMES_VIEW_ACTIONS}></td>
<th><{$smarty.const._MD_SURNAMES_NOTES}></th>
</tr>
<{section name=i loop=$ids }>
<tr class="<{cycle values="odd,even"}>">
<{if $uids[i]}>
<td><a href="list.php?uid=<{$uids[i]}>"><{$names[i]}></a></td>
<{else}>
<td><{$names[i]}></td>
<{/if}>
<td><a href="list.php?surname=<{$qsurnames[i]}>"><{$surnames[i]}></a></td>
<td><a href="view.php?id=<{$ids[i]}>">
<{if $comcnt[i]}>
See Comments
<{else}>
View
<{/if}>
</a></td>
<td><{$notes[i]}>&nbsp;</td>
</tr>
<{/section}>
</table>
</div>
<{if isset($pagenav)}>
<hr />
<div align="right"><{$pagenav}></div>
<{/if}>
<{if isset($plugin_message)}>
<br />
<div><{$plugin_message}></div>
<{/if}>

<{if isset($body)}>
<div><{$body}></div>
<{/if}>
<{if isset($debug)}>
<hr />
<div><{$debug}></div>
<{/if}>