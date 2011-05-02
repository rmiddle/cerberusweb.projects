<div style="float:right;">
<form action="{devblocks_url}{/devblocks_url}" method="post">
<input type="hidden" name="c" value="projects">
<input type="hidden" name="a" value="doProblemsQuickSearch">
<b>{$translate->_('common.quick_search')}</b> <select name="type">
	<option value="title" {if $quick_search_type eq 'title'}selected{/if}>{$translate->_('common.title')|capitalize}</option>
</select><input type="text" name="query" class="input_search" size="16"><button type="submit">{$translate->_('common.search_go')|lower}</button>
</form>
</div>

<form action="{devblocks_url}{/devblocks_url}" style="margin-bottom:5px;">
{if 1 || $active_worker->hasPriv('xxx')}<button type="button" onclick="genericAjaxPopup('peek','c=projects&a=showProblemPeek&id=0&view_id={$view->id}',null,false,'500');"><span class="cerb-sprite sprite-add"></span> {$translate->_('common.add')|capitalize}</button>{/if}
</form>

{include file="devblocks:cerberusweb.core::internal/views/search_and_view.tpl" view=$view}