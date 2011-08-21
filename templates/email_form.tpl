<section class="block" id="manage-email">
				<fieldset>
					<label for="mail_to">
						<strong>To:</strong>
						<div>
							<select class="autowidth" id="email-to">
								<option>All Attendees</option>
								<option>Absolutely Attending</option>
								<option>Maybe Attending</option>
								<option>Not Attending</option>
								<option>No Response Yet</option>
							</select>
						</div>
					</label>
					<label>
						<strong>Subject:</strong>
						<div>Update about {$smarty.session.manage_event->title}!</div>
					</label>
					<label for="message">
						<strong>Message:</strong>
						<div>
							<p>Hey {literal}{Guest Name}{/literal},</p>
							<textarea class="autowidth" name="message" id="message"></textarea>
	 						<p>Thanks!<br />{$smarty.session.user->fname}</p>
						</div>
					</label>
					<footer class="buttons buttons-send">
						<label for="automatic_email_send_cb"><input type="checkbox" name="automatically"{if isset($eventReminder['isAuto'])} checked="checked"{/if} id="automatic_email_send_cb" /> Send automatically on</label> <input type="text" name="date" class="inputbox datebox" id="automatic_email_event_date" value="{$smarty.session.manage_event->date}" /> at <select name="time">{include file="timeselect.tpl"} <p class="btn"><input type="submit" name="submit" value="Send" /></p></select>
					</footer>
				</fieldset>
			</section> 
