$(function() {
	var p=$('.tableExp');
	var divh=$('tr').height();
	while ($(p).outerHeight()>divh) {
	    $(p).text(function (index, text) {
	        return text.replace(/\W*\s(\S)*$/, '...');
	    });
	}
});