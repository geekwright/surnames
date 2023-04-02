<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_SURNAMES_BC_ROOT}></a> &nbsp;&gt; <{$smarty.const._MD_SURNAMES_LIST_ALL}></div>
<{if isset($pagenav)}>
<div align="left"><{$pagenav}></div>
<{/if}>

<{if is_array($surnames) && count($surnames) > 0 }>
<{assign var='colswide' value=$pref_cols}>
<{assign var='surname_count' value=$surnames|@count}>

<{math assign='ll' equation="floor((x + y - 1) / y)" x=$surname_count y=$colswide}>
<{math assign='lm' equation="floor(x * y)" x=$ll y=$colswide}>
<table width="100%">
<tr><th><{$smarty.const._MD_SURNAMES_LIST}></th></tr>
<tr><td>
<table width="100%">
<{section name=io start=0 loop=$ll step=1 }>
<tr class="<{cycle values="odd,even"}>">
<{section name=i start=$smarty.section.io.index loop=$lm max=$colswide step=$ll}>
<td>
<{if $smarty.section.i.index < $surname_count }>
<a href="list.php?surname=<{$qsurnames[i]}>"><{$surnames[i]}></a>
<{else}>
&nbsp;
<{/if}>
</td>
<{/section}>
</tr>
<{/section}>
</table>
</td></tr>
</table>
<{else}>
Nothing to display
<{/if}>
<hr>
<{if isset($pagenav)}>
<div align="right"><{$pagenav}></div>
<{/if}>
<{include file='db:system_notification_select.tpl'}>
<{if isset($debug)}>
<div><{$debug}></div>
<{/if}>
