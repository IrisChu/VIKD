function clicked(){
	document.getElementById("hideMe").style.visibility = "hidden";
};


$(document).ready(function(){
   	$('.typebutton').click(function(){
   	    var clickBtnValue = $(this).val();
   	    var ajaxurl = 'pokemon.php',
   	    data =  {'typename': clickBtnValue};
   	    $.post(ajaxurl, data, function (response) {
   	        // Response div goes here.
   	    });
   	});
});