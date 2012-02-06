function streamPublish(ILInk, ITitle)
{
	FB.ui(
	  {
		method: 'feed',
		name: ITitle,
		link: ILInk,
		picture: ILInk,
		caption: ITitle,
		description: ITitle
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