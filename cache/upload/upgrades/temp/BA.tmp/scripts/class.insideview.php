<?PHP
/* This Class contains all the methods to install and uninstall InsideView */
class InsideView 
{
	var $module;
	var $pattern;
	var $replacement;
	var $view;
	var $template;
	var $cache;
	var $custom;
	var $uninstallContent;
	var $installed;
	
	//This Function Initializes all the variables with the files to be updated for each module
	function initialize($moduleName)
	{
		$modules = array( "Accounts"=>array("pattern" =>"#(\{NAME\}.*)(</span|</div)#",
		"replacement"=>"$1&nbsp; (<a href=\"http://my.insideview.com/iv/callback.do?clb=launchCompany&company_name={NAME}&ticker={TICKER_SYMBOL}\" target=\"_blank\" class=\"tabDetailViewDFLink\">Find Account in InsideView</a>)$2"
		),
		"Contacts"=>array("pattern" =>"#(\{NAME\}.*)(</span|</div)#",
		"replacement"=>"$1&nbsp; (<a href=\"http://my.insideview.com/iv/callback.do?clb=launchPerson&first_name={URLENCODED_FIRST_NAME}&last_name={URLENCODED_LAST_NAME}&company_name={ACCOUNT_NAME}\" target=\"_blank\" class=\"tabDetailViewDFLink\">Find Contact in InsideView</a>)$2"
		),
		"Leads"=>array("pattern" =>"#(\{FORMATTED_NAME\}.*)(</span|</div)#",
		"replacement" =>"$1&nbsp; (<a href=\"http://my.insideview.com/iv/callback.do?clb=launchPerson&first_name={FIRST_NAME}&last_name={LAST_NAME}&company_name={ACCOUNT_NAME}\" target=\"_blank\" class=\"tabDetailViewDFLink\">Find Lead in InsideView</a>)$2"								  )
		);	
		$this->module =$moduleName;
		$this->pattern = $modules[$moduleName]["pattern"];
		$this->replacement = $modules[$moduleName]["replacement"];
		$this->getFiles($moduleName);
	}
		//This function creates the file names
	function getFiles($moduleName)
	{
		$this->view = "modules/".$moduleName."/DetailView.html";
		//Templates
		$this->template = "modules/".$moduleName."/tpls/DetailView.html";
		//Cache
		$this->cache ="cache/studio/custom/working/modules/".$moduleName."/DetailView.html";
		//Custom Working Modules
		$this->custom = "custom/working/modules/".$moduleName."/DetailView.html";
	}
	
	function removeFiles()
	{
		$ivClassFile="include/InsideView/class.insideview.php";
		$success = ( unlink($ivClassFile) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Removed the files from $ivDir : <font color=$color><b>$success </b></font><br>";	
	}
	
	function createIvDir()
	{
		$ivDir ="include/InsideView";
		$success = ( mkdir($ivDir) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Created the $ivDir Directory  : <font color=$color><b>$success </b></font><br>";
	}
	function removeIvDir()
	{
		$ivDir ="include/InsideView";
		$success = ( rmdir($ivDir) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Removed the $ivDir Directory  : <font color=$color><b>$success </b></font><br>";
	}

	//This function creates the patterns and replacements needed to update files needed for InsideView and Backs up the module and template 
	function backupModule($moduleName) 
	{
		//Backup the OldView n Template
		$oldView ="modules/".$moduleName."/Old.DetailView.beforeInstallingInsideView.html";
		$oldTemplate = "modules/".$moduleName."/tpls/Old.DetailView.beforeInstallingInsideView.html";
		
		$success = ( copy($this->view, $oldView) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Backed up MODULE    &nbsp;&nbsp;&nbsp;&nbsp;:<font color=$color><b> $success </b></font>";
		
		$success = ( copy($this->template, $oldView) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Backed up TEMPLATE   &nbsp;:<font color=$color><b> $success </b></font><br>";
	}
	//Updates the Modules
	function updateModule()
	{
		$success = ( $this->updateView($this->view) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Updated the MODULE  : <font color=$color><b> $success </b></font><br>";
	}
	//Updates Templates
	function updateTemplate()
	{
		$success = ( $this->updateView($this->template) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Updated TEMPLATE&nbsp;&nbsp;: <font color=$color><b>$success </b></font><br>";
	}
	//Updates Studio Cache
	function updateCache()
	{
		$success = ( $this->updateView($this->cache) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Successfully Updated the CACHE   &nbsp;: <font color=$color><b>$success </b></font><br>";
	}
	//Updates Custom Working Modules
	function updateCustomWorkingModule()
	{
		$success = ( $this->updateView($this->custom) ) ? "YES" : "NO";
		$color = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Updated the WORKING MODULE&nbsp;&nbsp;&nbsp;: <font color=$color><b>$success </b></font><br>";
	}
	//Checks if InsideView is already Installed
	function checkInstalled($view)
	{
		if(!$handle = fopen($view,'r'))
		echo "<br><br> The file $view couldnot be opened for reading<br>"; //For debugging
		//Get the contents of the file
		$content = fread($handle,filesize($view));
		//Close the file
		fclose($handle);
		//Check if the InsideView link already exists
		$insideview = "#(\(<a[^)]*InsideView</a>\))#";
		$nothing ="";
		$noIvContent = preg_replace($insideview, $nothing, $content);
		//Compare the strings before and after the replace to see if the Links have been removed and to know if InsideView is already installed or not.
		$count= strcmp($content, $noIvContent);
		//echo "<br> Count =".$count;
		if($count<0)
		{
			$this->uninstallContent = $noIvContent;
			$this->installed ="YES";
			return 1; //return 1 on success
		}
		$this->installed ="NO";
		return 0;
	}
	
	//Function to Update the files ... this is where the real work happens :)
	function updateView($view)
	{
		$pattern = $this->pattern;
		$replacement = $this->replacement;
		
		if(file_exists($view))
		{
			echo "<br>Updating ".$view."<br>";
			//Yes it does, Open it.
			$installed = ( $this->checkInstalled($view) ) ? "YES" : "NO";
			$color = ($installed == "YES") ?"BLUE" : "GREEN";
			echo "<br>Is Insideview already installed? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <font color=$color><b> $installed </b></font>";
			if($installed =="NO")
			{
				if(!$handle = fopen($view,'r'))
				echo "<br><br> The file $view couldnot be opened for reading<br>"; //For debugging
				//Get the contents of the file
				$content = fread($handle,filesize($view));
				//Close the file
				fclose($handle);
				//Check if the InsideView link already exists
				$newContent = preg_replace($pattern, $replacement, $content);
				//Create the new file for writing
				
				if(!$handle = fopen($view,'w'))
				{
					echo "<br><br> The file $view couldnot be created."; //For debugging
					return 0;
				}
				//Write to the New DetailView.html file
				if(fwrite($handle,$newContent))
				return 1; //return 1 on success
				else
				{	
					echo "<br> New File ".$view." created but Couldnot write to the new file $view";
					return 0;
				}
			}
			return 1;
		}
		else
		{	//The file doesnot exits 
			echo "<br><br>The file $view doesnot exit."; //For debugging 
			return 0;
		}
	}
	
	/* Ikkada nunchi anthaa Uninstallation functions*/
	
	//Uninstalls the links from the Views
	function uninstallModule()
	{
		$success = ($this->uninstallIv($this->view))    ? "YES" : "NO";
		$color   = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Uninstalled from MODULE  :<font color=$color><b>$success </b></font><br>";
	}
	
	function uninstallTemplate()
	{
		$success = ($this->uninstallIv($this->template))    ? "YES" : "NO";
		$color   = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Uninstalled from TEMPLATE  :<font color=$color><b>$success </b></font><br>";
	}
	
	function uninstallCache()
	{
		$success = ($this->uninstallIv($this->cache))    ? "YES" : "NO";
		$color   = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Uninstalled from CACHE  :<font color=$color><b>$success </b></font><br>";
	}
	
	function uninstallCutomWorkingModule()
	{
		$success = ($this->uninstallIv($this->custom))    ? "YES" : "NO";
		$color   = ($success == "YES") ?"GREEN" : "RED";
		echo "<br>Uninstalled from WORKING MODULE  :<font color=$color><b>$success </b></font><br>";
	}
	
	function uninstallIv($view)
	{
		if(file_exists($view))
		{
			$installed = ( $this->checkInstalled($view) ) ? "YES" : "NO";
			$color = ($installed == "YES") ?"GREEN" : "RED";
			echo "<br>Is Insideview installed? &nbsp;&nbsp;&nbsp;: <font color=$color><b>: $installed </b></font>";
			if($installed =="YES")
			{
				//Now, replace the {NAME} field with our custom links
				if(!$handle = fopen($view,'w'))
				{
					echo "<br><br>The file $view couldnot be created."; //For debugging
					return 0;
				}
				//Write to the New DetailView.html file
				if(fwrite($handle,$this->uninstallContent))
				{
					$this->uninstallContent =""; //Make this empty
					return 1; //return 1 on success
				}
				else
				{	echo "<br>File ".$view." created but Couldnot write to the new file $view";
					return 0;
				}
			}
			return 1;
		}
		else
		{	//The file doesnot exits 
			echo "<br><br>The file $view doesnot exit."; //For debugging 
			return 0;
		}
	}
}
?>