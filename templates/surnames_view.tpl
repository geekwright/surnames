<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_SURNAMES_BC_ROOT}></a> &gt; <{$smarty.const._MD_SURNAMES_VIEW_SINGLE}></div>
<br />
<table>
<tr><td width="20%"><{$smarty.const._MD_SURNAMES_SURNAME}></td><td><a href="list.php?surname=<{$qsurname}>"><{$surname}></a></td></tr>

<{if isset($notes)}>
<tr><td><{$smarty.const._MD_SURNAMES_NOTES}></td><td><{$notes}></td></tr>
<{/if}>

<{if ($uid==0)}>
<tr><td><{$smarty.const._MD_SURNAMES_USER}></td><td><{$name}></td></tr>
<{else}>
<tr><td><{$smarty.const._MD_SURNAMES_USER}></td><td><a href="<{$smarty.const.XOOPS_URL}>/userinfo.php?uid=<{$uid}>"><{$name}></a></td></tr>
<{/if}>


<{if isset($user_location)}>
<tr><td>Location</td><td><{$user_location}></td></tr>
<{/if}>

<{if isset($user_email)}>
<tr><td>Email</td><td><{$user_email}></td></tr>
<{/if}>

<{if isset($user_url)}>
<tr><td>Homepage</td><td><a href="<{$user_url}>"><{$user_url}></a></td></tr>
<{/if}>

<{if isset($user_sig)}>
<tr><td>&nbsp;</td><td><{$user_sig}></td></tr>
<{/if}>

<{if is_array($surnames) && count($surnames) > 0 }>
<{assign var='colswide' value=$pref_cols}>
<{assign var='surname_count' value=$surnames|@count}>

<{math assign='ll' equation="floor((x + y - 1) / y)" x=$surname_count y=$colswide}>
<{math assign='lm' equation="floor(x * y)" x=$ll y=$colswide}>
<tr><td><{$smarty.const._MD_SURNAMES_CURRENT_LIST}></td>
<td>
<table width="100%">
<{section name=io start=0 loop=$ll step=1 }>
<tr>
<{section name=i start=$smarty.section.io.index loop=$lm max=$colswide step=$ll}>
<td>
<{if $smarty.section.i.index < $surname_count }>
<a href="view.php?id=<{$surnames_ids[i]}>"><{$surnames[i]}></a>
<{else}>
&nbsp;
<{/if}>
</td>
<{/section}>
</tr>
<{/section}>
</table>
</tr>
<{/if}>

<{if is_array($actions)}>
<tr><td><{$smarty.const._MD_SURNAMES_VIEW_ACTIONS}></td><td>
<table style="width: 10%; margin: 0; padding: 0;"><tr>
<{section name=i loop=$actions }>
<td>
  <a href="<{$actions[i].action}>" <{$actions[i].extra}> title="<{$actions[i].alt}>"><button class="btn btn-default"><span class="<{$actions[i].button}>"></span></button></a>
</td>
<{/section}>
</tr></table>
</td>
</tr>
<{/if}>

</table>
<hr />
<div style="text-align: center; padding: 3px; margin: 3px;">
  <{$commentsnav}>
  <{$lang_notice}>
</div>

<div style="margin: 3px; padding: 3px;">
<!-- start comments loop -->
<{if $comment_mode == "flat"}>
  <{include file="db:system_comments_flat.tpl"}>
<{elseif $comment_mode == "thread"}>
  <{include file="db:system_comments_thread.tpl"}>
<{elseif $comment_mode == "nest"}>
  <{include file="db:system_comments_nest.tpl"}>
<{/if}>
<!-- end comments loop -->
<{include file='db:system_notification_select.tpl'}>
</div>
