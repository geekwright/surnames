<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_SURNAMES_BC_ROOT}></a> &gt; <{$smarty.const._MD_SURNAMES_BC_EDIT}></div>
<br>
<{if isset($err_message)}>
<hr>
<div class="errorMsg"><{$err_message}></div>
<hr>
<{/if}>
<{if isset($message)}>
<hr>
<div class="resultMsg"><{$message}></div>
<hr>
<{/if}>
<{if isset($body)}>
<div><{$body}></div>
<{/if}>

<{if is_array($surnames) && count($surnames) > 0 }>
<{assign var='colswide' value=$pref_cols}>
<{assign var='surname_count' value=$surnames|@count}>

<{math assign='ll' equation="floor((x + y - 1) / y)" x=$surname_count y=$colswide}>
<{math assign='lm' equation="floor(x * y)" x=$ll y=$colswide}>
<div class="blockContent">
<table width="100%">
<th colspan="<{$pref_cols}>"><{$smarty.const._MD_SURNAMES_CURRENT_LIST}></th>
<{section name=io start=0 loop=$ll step=1 }>
<tr>
<{section name=i start=$smarty.section.io.index loop=$lm max=$colswide step=$ll}>
<td>
<{if $smarty.section.i.index < $surname_count }>
<a href="edit.php?id=<{$ids[i]}>"><{$surnames[i]}></a>
<{else}>
&nbsp;
<{/if}>
</td>
<{/section}>
</tr>
<{/section}>
</table>
</div>
<{/if}>
<{include file='db:system_notification_select.tpl'}>
<{if isset($debug)}>
<hr>
<div><{$debug}></div>
<{/if}>