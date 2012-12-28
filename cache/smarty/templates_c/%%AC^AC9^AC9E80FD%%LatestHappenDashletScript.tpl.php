<?php /* Smarty version 2.6.11, created on 2012-04-09 02:47:57
         compiled from modules/AccountObjective/Dashlets/LatestHappenDashlet/LatestHappenDashletScript.tpl */ ?>

<?php echo '<script>
if(typeof JotPad == \'undefined\') { // since the dashlet can be included multiple times a page, don\'t redefine these functions
	JotPad = function() {
	    return {
	    	/**
	    	 * Called when the textarea is blurred
	    	 */
	        blur: function(ta, id) {
	        
	        	if(\'';  echo $this->_tpl_vars['owner'];  echo '\' == false)
	        	{
	        		alert("You are not authorised to perform this action");
	        		return;
	        	}
	        	
	        	ajaxStatus.showStatus(\'';  echo $this->_tpl_vars['saving'];  echo '\'); // show that AJAX call is happening
	        	// what data to post to the dashlet

    	    		postData = \'to_pdf=1&module=AccountObjective&action=CallMethodDashlet&method=saveText&record=';  echo $this->_tpl_vars['record'];  echo '&return_module=';  echo $this->_tpl_vars['parent_type'];  echo '&id=\' + id + \'&savedText=\' + ta.value;
    	    	
    	    	//alert("In blur");
				var cObj = YAHOO.util.Connect.asyncRequest(\'POST\',\'index.php\', 
								  {success: JotPad.saved, failure: JotPad.saved}, postData);

//alert("OM "+postData);
	        },
		    /**
	    	 * Called when the textarea is double clicked on
	    	 */
			edit: function(divObj, id) {
		        	
		        	//alert("In edit :"+divObj);
		        	
		        	if(\'';  echo $this->_tpl_vars['owner'];  echo '\' == false)
		        	{
		        		alert("You are not authorised to perform this action");
		        		return;
		        	}
				
				//alert("Getting id :"+\'jotpad_\'+getId(divObj));
				ta = document.getElementById(\'jotpad_textarea_\' + id);
				//alert("Ta :"+ta);
				if(isIE) ta.value = divObj.innerHTML.replace(/<br>/gi, "\\n");
				else ta.value = divObj.innerHTML.replace(/<br>/gi, \'\');
				
				divObj.style.display = \'none\';
				ta.style.display = \'\';
				ta.focus();
			},
			
			getId:function(divObj)
			{
				return divObj.id.substring(7,divObj.id.length);//format jotpad_id
			},
		    /**
	    	 * handle the response of the saveText method
	    	 */
	        saved: function(data) {
	        	//alert("Result :"+data.responseText);
	        	var result = eval(data.responseText);
			//alert("Result :"+result);
	           	ajaxStatus.showStatus(\'';  echo $this->_tpl_vars['saved'];  echo '\');
	           	if(typeof result != \'undefined\') 
	           	{
				ta = document.getElementById(\'jotpad_textarea_\' + result[\'id\']);
				theDiv = document.getElementById(\'jotpad_\' + result[\'id\']);
				theDiv.innerHTML = result[\'savedText\'];
			}
			ta.style.display = \'none\';
			theDiv.style.display = \'\';
	           	window.setTimeout(\'ajaxStatus.hideStatus()\', 2000);
	        }
	    };
	}();
}
</script>'; ?>
