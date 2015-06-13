function toggleInfo() {
  var pan_info = jQuery('.info');
	if (pan_info.css('display') == 'block') {
		pan_info.hide(200);
	} else {
		pan_info.show(200);
	}
}