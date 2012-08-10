$(function(){
	$("select#category").change(
		function() {
			if($(this).val()!="") { 
			    $.getJSON("/_gearoscope/hu/gearscategory/categorychange",{category: $(this).val(), ajax: 'true'}, 
			    	function(j) {
					      var options = '';
					      options += '<option value=""></option>';
					      for (var i = 0; i < j.length; i++) {
					    	  options += '<option value="' + j[i].key + '">' + j[i].value + '</option>';
					      }
					      $("select#subcategory").html(options);
			    })
			    $("div#div-subcategory").fadeIn();
			} else {
				$("div#div-subcategory").fadeOut();
			}
	  })
	  
	  $("select#subcategory").change(
		function() {
			if($(this).val()!="") {
			    $.getJSON("/_gearoscope/hu/gearssubcategory/categorychange",{category: $(this).val(), ajax: 'true'}, 
			    	function(j) {
					      var options = '';
					      options += '<option value=""></option>';
					      for (var i = 0; i < j.length; i++) {
					    	  options += '<option value="' + j[i].key + '">' + j[i].value + '</option>';
					      }
					      $("select#subsubcategory").html(options);
			    })
			    $("div#div-subsubcategory").fadeIn();
			    href = "/_gearoscope/hu/gearssubsubcategory/useradd/subcategoryid/" + $(this).val();
			    console.log(href);
			    $("a.addsubsubcategory").attr("href", href);
			} else {
				$("div#div-subsubcategory").fadeOut();
			}
	  })
});	  
