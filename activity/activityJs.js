/**
 * 
 */

$(document).ready(function() {
		//	   $('table th:even').addClass('alt');

	   $("a").click(function(e){
//		     alert("clicked a link");
		   });
	   $('input').keypress(function(event) {
			if(event.keyCode == 13){
				event.preventDefault();
			}
	   });
	   $('#skipButton, #skipButton2').click(function(e) {
		   $('#isPerformedAdultYes').removeAttr('checked');
		   $('#isPerformedPediatricYes').removeAttr('checked');
		   $('#isPerformedNatalYes').removeAttr('checked');
   		   $('#isPerformedAdultNo').attr('checked','checked');
   		   $('#isPerformedPediatricNo').attr('checked','checked');
		   $('#isPerformedNatalNo').attr('checked','checked');
		   document.forms["submitSurveyForm"].submit();
		   e.preventDefault(); 
	   });
		// jquery stuff for hiding irrelevant questions
		 
		// notes: 
		//  class 'startHidden'.
		//  divs:    ids doYouPerformAdult -> timeStandardAdult,  durVolAdult
		// logic:  
		//   1. if the doyouperf 'yes' (isPerformedAdultYes) is clicked, show timeStandardAdult.  
		//   2. if the doyouper 'no' is clicked, hide  timestandardAdult and the divs durVolAdult and methodologyAdult. 
		//   3. if hastimeStandardAdult Yes is clicked, show the divs durVolAdult and methodologyAdult.  
		//   4. if hastimesTandardAdult No is clicked, hide the divs durVolAdult and methodologyAdult. ( and clear their ticks!)
		
	   //================== ALL ADULT ANIMATION HERE ==================================	
		
	   // 	if the input id='' has an attributed 'checked', then show the  ....
	   if ($('#isPerformedAdultYes').is(':checked')){
		   $('#timeStandardAdult').show();
	   }		
	   if ($('#hasTimestandardAdultYes').is(':checked')){
		   $('#durVolAdult').show();
		   $('#methodologyAdult').show();
	   }
	   
	   
	   //-----------the is performed adult   yes and no clicks. ------------------------
	   $('#isPerformedAdultYes').click(function(e) {  // the yes radio click
		   $('#timeStandardAdult').show(300);
		   
	   });
	   $('#isPerformedAdultNo').click(function(e) {          //  the no radio click
		   $('#timeStandardAdult').hide(300);
		   $('#hasTimestandardAdultYes').removeAttr('checked');
   		   $('#hasTimestandardAdultNo').removeAttr('checked');
		   																	//hide  and clear everything below it.
   		   $('#durVolAdult').hide(300);  //inputbox to clear
		   $('#durationAdult').attr('value','');   //this clears the input value.
		   $('#volumeAdult').attr('value','');   //this clears the input value.
		   $('#methodologyAdult').hide(300);  //3 radios to clear
		   $('#methodologyAdultMeasured').removeAttr('checked');
   		   $('#methodologyAdultExpert_Opinion').removeAttr('checked');
   		   $('#methodologyAdultUnknown').removeAttr('checked');
			
   		   
	   });

	   //--------------- the timestandard adult radio Yes and No clicks.---------------------
	   $('#hasTimestandardAdultYes').click(function(e) {  //    the yes click
		   $('#durVolAdult').show(300); // inputbox to clear
		   //$('#durationAdult').attr('value','');      //this clears the input value.
		   $('#methodologyAdult').show(300); //3 radios to clear
		   
   		   
	   });
	   $('#hasTimestandardAdultNo').click(function(e) {          //  the no click.
		   $('#durVolAdult').hide(300);  //inputbox to clear
		   $('#durationAdult').attr('value','');   //this clears the input value.
		   $('#volumeAdult').attr('value','');   //this clears the input value.
		   $('#methodologyAdult').hide(300);  //3 radios to clear
		   $('#methodologyAdultMeasured').removeAttr('checked');
   		   $('#methodologyAdultExpert_Opinion').removeAttr('checked');
   		   $('#methodologyAdultUnknown').removeAttr('checked');
			
	   });
	   //================== END ADULT ANIMATION HERE ==================================
	   //================== ALL PEDIATRIC ANIMATION HERE ==================================	
	   
		//	 	if the input id='' has an attributed 'checked', then show the  ....
	   if ($('#isPerformedPediatricYes').is(':checked')){
		   $('#timeStandardPediatric').show();
	   }	
	   if ($('#hasTimestandardPediatricYes').is(':checked')){
		   $('#durVolPediatric').show();
		   $('#methodologyPediatric').show();
	   }
	   //-----------the is performed pediatric   yes and no clicks. ------------------------
	   $('#isPerformedPediatricYes').click(function(e) {  // the yes radio
		   $('#timeStandardPediatric').show(300);
		      });
	   $('#isPerformedPediatricNo').click(function(e) {          //  the no radio
		   $('#timeStandardPediatric').hide(300);
		   $('#hasTimestandardPediatricYes').removeAttr('checked');
   		   $('#hasTimestandardPediatricNo').removeAttr('checked');
   		   $('#durVolPediatric').hide(300);  //inputbox to clear
		   $('#durationPediatric').attr('value','');   //this clears the input value.
		   $('#volumePediatric').attr('value','');   //this clears the input value.
		   $('#methodologyPediatric').hide(300);  //3 radios to clear
		   $('#methodologyPediatricMeasured').removeAttr('checked');
   		   $('#methodologyPediatricExpert_Opinion').removeAttr('checked');
   		   $('#methodologyPediatricUnknown').removeAttr('checked');
		
	   });
	   //--------------- the timestandard pediatric radio Yes and No clicks.---------------------
	   $('#hasTimestandardPediatricYes').click(function(e) {  //    yes
		   $('#durVolPediatric').show(300); // inputbox to clear
		   $('#methodologyPediatric').show(300); //3 radios to clear
	   });
	   $('#hasTimestandardPediatricNo').click(function(e) {          //  no
		   $('#durVolPediatric').hide(300);  //inputbox to clear
		   $('#durationPediatric').attr('value','');   //this clears the input value.
		   $('#volumePediatric').attr('value','');
		   $('#methodologyPediatric').hide(300);  //3 radios to clear
		   $('#methodologyPediatricMeasured').removeAttr('checked');
   		   $('#methodologyPediatricExpert_Opinion').removeAttr('checked');
   		   $('#methodologyPediatricUnknown').removeAttr('checked');
	   });
	   
	   
	   
	   //================== END PEDIATRIC ANIMATION HERE ==================================	
	   //================== ALL NATAL ANIMATION HERE ==================================	
		//	 	if the input id='' has an attributed 'checked', then show the  ....
	   if ($('#isPerformedNatalYes').is(':checked')){
		   $('#timeStandardNatal').show();
	   }	
	   if ($('#hasTimestandardNatal1').is(':checked')){
		   $('#durVolNatal').show();
		   $('#methodologyNatal').show();
	   }
	   //-----------the is performed Natal   yes and no clicks. ------------------------
	   $('#isPerformedNatalYes').click(function(e) {  // the yes radio click
		   $('#timeStandardNatal').show(300);
	   });
	   $('#isPerformedNatalNo').click(function(e) {          //  the no radio click
		   $('#timeStandardNatal').hide(300);
		   $('#hasTimestandardNatal1').removeAttr('checked');
   		   $('#hasTimestandardNatal2').removeAttr('checked');
   		   $('#durVolNatal').hide(300);  //hides the div.
		   $('#durationNatal').attr('value','');   //this clears the duration input value.
		   $('#volumeNatal').attr('value','');
		   $('#methodologyNatal').hide(300);  //3 radios to clear:
		   $('#methodologyNatal1').removeAttr('checked');
		   $('#methodologyNatal2').removeAttr('checked');
		   $('#methodologyNatal3').removeAttr('checked');
	   });
	   //--------------- the timestandard Natal radio Yes and No clicks.---------------------
	   $('#hasTimestandardNatal1').click(function(e) {  //    yes
		   $('#durVolNatal').show(300); // inputbox to clear
		   $('#methodologyNatal').show(300); //3 radios to clear
   		   
	   });
	   $('#hasTimestandardNatal2').click(function(e) {          //  no
		   $('#durVolNatal').hide(300);  //inputbox to clear
		   $('#durationNatal').attr('value','');   //this clears the input value.
		   $('#volumeNatal').attr('value','');
		   $('#methodologyNatal').hide(300);  //3 radios to clear
   		   $('#methodologyNatal1').removeAttr('checked');
   		   $('#methodologyNatal2').removeAttr('checked');
   		   $('#methodologyNatal3').removeAttr('checked');
			
	   });
	   //================== END NATAL ANIMATION HERE ==================================	
	   
});