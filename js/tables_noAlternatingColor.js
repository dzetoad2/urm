$(document).ready(function() {
// to selecd an id:   $("#orderedlist").addClass("red");
// select a class:     $(".myClass").css("border","3px solid red");<
	   //$('table tbody  tr:nth-child(even)').addClass('alt');
	   $('table tbody tr.clickable').mouseover(function(){
		 $(this).addClass('over');
	   });
	   $('table tbody tr.clickable').mouseout(function(){
			 $(this).removeClass('over');
		   });
	   $(this).addClass('mouseover');
});