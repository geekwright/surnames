<!doctype html>
<html>
<head>
<title><{$smarty.const._MD_SURNAMES_PRINT}></title>

<link rel="stylesheet" type="text/css" href="assets/css/print.css" />

</head>
<body onload='window.print();'>

<h1><{$smarty.const._MD_SURNAMES_BC_ROOT}> &gt; <{$smarty.const._MD_SURNAMES_VIEW_SINGLE}></h1>

<div style="margin: 3px; padding: 3px;">
<table class="snouter" width="90%">
<tr><td class="snhead"><{$smarty.const._MD_SURNAMES_SURNAME}></td><td class="sndataem"><{$surname}></td></tr>

<{if isset($notes)}>
<tr><td class="snhead"><{$smarty.const._MD_SURNAMES_NOTES}></td><td class="sndata"><{$notes}></td></tr>
<{/if}>

<tr><td class="snhead"><{$smarty.const._MD_SURNAMES_USER}></td><td class="sndata"><{$name}></td></tr>

<{if isset($user_location)}>
<tr><td class="snhead">Location</td><td class="sndata"><{$user_location}></td></tr>
<{/if}>

<{if isset($user_email)}>
<tr><td class="snhead">Email</td><td class="sndata"><{$user_email}></td></tr>
<{/if}>

<{if isset($user_url)}>
<tr><td class="snhead">Homepage</td><td class="sndata"><{$user_url}></td></tr>
<{/if}>

<{if isset($user_sig)}>
<tr><td class="snhead">&nbsp;</td><td class="sndata"><{$user_sig}></td></tr>
<{/if}>

<{if is_array($surnames) && count($surnames) > 0 }>
<{assign var='colswide' value=$pref_cols}>
<{assign var='surname_count' value=$surnames|@count}>

<{math assign='ll' equation="floor((x + y - 1) / y)" x=$surname_count y=$colswide}>
<{math assign='lm' equation="floor(x * y)" x=$ll y=$colswide}>
<tr><td class="snhead">Other Surnames</td>
<td class="sndata">
<table width="100%">
<{section name=io start=0 loop=$ll step=1 }>
<tr>
<{section name=i start=$smarty.section.io.index loop=$lm max=$colswide step=$ll}>
<td>
<{if $smarty.section.i.index < $surname_count }>
<{$surnames[i]}>
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

</table>
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
</div>


</body>
</html>
