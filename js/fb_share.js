function streamPublish(ILInk, ITitle, ImLink)
{
	FB.ui(
	  {
		method: 'feed',
		name: ITitle,
		link: ILInk,
		picture: ImLink,
		caption: ILInk,
		description: ''
	  },
	  function(response) {
		if (response && response.post_id) {
		  //alert('Post was published.');
		} else {
		  //alert('Post was not published.');
		}
	  }
	);
}