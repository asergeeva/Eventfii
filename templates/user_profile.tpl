<div id="user_profile">
<h2>{$userInfo['fname']} {$userInfo['lname']}</h2>
<table>
  <tr>
    <th>Paypal:</th>
    <td><input type="text" id="paypal_email" value="{$paypalEmail}" /></td>
  </tr>
</table>
<button id="update_profile" value="update_profile" onclick="CP_EVENT.updateProfile({$userInfo['id']})">Update</button>
</div>