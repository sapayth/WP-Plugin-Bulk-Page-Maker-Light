function init() {
	document.getElementById('cmb_post_type').onchange = typeChangeHandler;

	var pageCmb = document.getElementById('page_id');
	var opt = new Option('No Parent');
	opt.setAttribute('class', 'level-0');
	pageCmb.insertBefore(opt, pageCmb.firstChild);
	pageCmb.selectedIndex = 0;
}

function typeChangeHandler() {
	var postType = document.getElementById('cmb_post_type').value;
	var pageCmb = document.getElementById('page_id');
	if(postType == 'post') {		
	    pageCmb.setAttribute('disabled', true);
	    pageCmb.selectedIndex = 0;
	} else {
		pageCmb.removeAttribute('disabled');
	}
}

window.onload = init;