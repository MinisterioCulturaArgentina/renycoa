$(document).ready(function() {
	var iFrames = document.getElementsByTagName('iframe');

	// Check if browser is Safari or Opera.
	if ($.browser.safari || $.browser.opera)
	{
		// Start timer when loaded.
		$('iframe').load(function()
			{
				setTimeout(iResize, 0);
			}
		);

		// Safari and Opera need a kick-start.
		for (var i = 0, j = iFrames.length; i < j; i++)
		{
			var iSource = iFrames[i].src;
			iFrames[i].src = '';
			iFrames[i].src = iSource;
		}
	}
	else
	{
		// For other good browsers.
		$('iframe').load(function()
			{
				// Set inline style to equal the body height of the iframed content.
				this.style.height = this.contentWindow.document.body.offsetHeight  + 'px';
			}
		);
	}
});

// Resize heights.
function iResize()
{
	// Iterate through all iframes in the page.
	for (var i = 0, j = iFrames.length; i < j; i++)
	{
		// Set inline style to equal the body height of the iframed content.
		iFrames[i].style.height = iFrames[i].contentWindow.document.body.offsetHeight + 'px';
	}
}