<div id="contact_list">
<table>
	<tr>
  	<th></th>
  	<th>Email</th>
    <th>Name</th>
  </tr>
  {foreach from=$contactList key=email item=name}
  <tr>
  	<td><input type="checkbox" class="selected_contact" value="{$email}" /></td>
  	<td>{$email}</td>
    <td>{$name}</td>
  </tr>
  {/foreach}
</table>
</div>