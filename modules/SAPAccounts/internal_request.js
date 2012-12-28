function createRequestObject()
{
	var request_o; //declare the variable to hold the object.
	var browser = navigator.appName; //find the browser name
	if(browser == "Microsoft Internet Explorer"){
		/* Create the object using MSIE's method */
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		/* Create the object using other browser's method */
		request_o = new XMLHttpRequest();
	}
	return request_o; //return the object
}

/* The variable http will hold our new XMLHttpRequest object. */
var http = createRequestObject();
var fieldName;

function getCityDetails(field){
	//alert("In getCityDetails "+field);
	fieldName = field;
//	http.open('get', '/crm/getCityDetails.php?city_id='+ document.getElementById(fieldName).value);
	http.open('post','/crm/getCityDetails.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleCityDetails;
	http.send('city_id='+document.getElementById(fieldName).value);
	//http.send(null);
}

function handleCityDetails(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ //Finished loading the response
		var response = http.responseXML;
		var xmlObj = response.documentElement.selectSingleNode("state");
		if(xmlObj)
		{
			var newField = fieldName.substring(0,fieldName.lastIndexOf("_"))+"_state";
			//alert("New field name :"+newField +" state id :"+xmlObj.getAttribute("id"));
			document.getElementById(newField+'_desc').value = xmlObj.getAttribute("description");
			document.getElementById(newField).value = xmlObj.getAttribute("id");
		}

		xmlObj = response.documentElement.selectSingleNode("country");
		if(xmlObj)
		{
			var newField = fieldName.substring(0,fieldName.lastIndexOf("_"))+"_country";
			//alert("New field name :"+newField +" state id :"+stateObj.getAttribute("id"));
			document.getElementById(newField+'_desc').value = xmlObj.getAttribute("description");
			document.getElementById(newField).value = xmlObj.getAttribute("id");
		}
	}
}

function getStateDetails(field){
	//alert("In getCityDetails "+field);
	fieldName = field;
//	http.open('get', '/crm/getStateDetails.php?state_id='+ document.getElementById(fieldName).value);
	http.open('post','/crm/getStateDetails.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleStateDetails;
	http.send('state_id='+document.getElementById(fieldName).value);
	//http.send(null);
}

function handleStateDetails(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ //Finished loading the response
		var response = http.responseXML;
		var xmlObj = response.documentElement.selectSingleNode("country");
		if(xmlObj)
		{
			var newField = fieldName.substring(0,fieldName.lastIndexOf("_"))+"_country";
			document.getElementById(newField+'_desc').value = xmlObj.getAttribute("description");
			document.getElementById(newField).value = xmlObj.getAttribute("id");
		}
	}
}

function getAccountObjParentData(field,target){
	//alert("In getAccountObjParentData "+field+" "+target);
//	http.open('get', '/crm/getCityDetails.php?city_id='+ document.getElementById(fieldName).value);
	http.open('post','/crm/getAccountObjParentData.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleAccountObjParentData;
	http.send('account_id='+field+'&target='+target);
	//http.send(null);
}

function handleAccountObjParentData(){
	/* Make sure that the transaction has finished. The XMLHttpRequest object
		has a property called readyState with several states:
		0: Uninitialized
		1: Loading
		2: Loaded
		3: Interactive
		4: Finished */
	if(http.readyState == 4){ //Finished loading the response
		var response = http.responseXML;
		var temp = null;

		if(isDataPresent(response))
		{
			//alert("Response xml :"+response.xml);

			temp = "mkt_obj";
			setData(response,temp,temp);
		
			temp = "comm_obj";
			setData(response,temp,temp);

			temp = "mkt_pri";
			setData(response,temp,temp);
		
			temp = "latest_happen";
			setData(response,temp,temp);
		}	
	}
}

function getAccountObjParentDataJSON(field,target){
	//alert("In getAccountObjParentData "+field+" target :"+target);
	http.open('post','/sfa/getAccountObjParentDataJSON.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleAccountObjParentDataJSON;
	http.send('account_id='+field+'&target='+target);
}

function handleAccountObjParentDataJSON(){
	if(http.readyState == 4){
		var response = eval('('+http.responseText+')');
		//alert("Got some response :"+response);
		if(!response)
		{
			alert("No data found");
			return;
		}

		var temp = "mkt_obj";
		setDataJSON(response,temp,temp);
		
		temp = "comm_obj";
		setDataJSON(response,temp,temp);

		temp = "mkt_pri";
		setDataJSON(response,temp,temp);
		
		temp = "latest_happen";
		setDataJSON(response,temp,temp);
	}
}

function getAccountMktParentDataJSON(field,target){
	http.open('post','/sfa/getAccountMktParentDataJSON.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleAccountMktParentDataJSON;
	http.send('account_id='+field+'&target='+target);
}

function handleAccountMktParentDataJSON(){
	if(http.readyState == 4){ 
		var response = eval('('+http.responseText+')');

		if(!response)
		{
			alert("No data found");
			return;
		}
		
		var temp = "mkt_size";
		setDataJSON(response,temp,temp);
		
		temp = "mkt_share";
		setDataJSON(response,temp,temp);

		temp = "comp_info";
		setDataJSON(response,temp,temp);
		
		temp = "season_info";
		setDataJSON(response,temp,temp);		
		
		temp = "industry_info";
		setDataJSON(response,temp,temp);		
		
		temp = "annual_info";
		setDataJSON(response,temp,temp);				
	}
}

function getAccountTGParentDataJSON(field,target){
	http.open('post','/sfa/getAccountTGParentDataJSON.php');
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleAccountTGParentDataJSON;
	http.send('account_id='+field+'&target='+target);
}

function handleAccountTGParentDataJSON(){
	if(http.readyState == 4){ 
		var response = eval('('+http.responseText+')');

		if(!response)
		{
			alert("No data found");
			return;
		}

		var temp = "geographic";
		setDataJSON(response,temp,temp);
		
		temp = "demographic";
		setDataJSON(response,temp,temp);
		
		temp = "psychographic";
		setDataJSON(response,temp,temp);
		
		temp = "media_habits";
		setDataJSON(response,temp,temp);	
	}
}

function isDataPresent(responseXml)
{
	var nodeObj = responseXml.documentElement.selectSingleNode("nodata");
	if(nodeObj)
	{
		alert(nodeObj.text);
		return false;
	}
	return true;
}


function setData(responseXml,element,htmlfield)
{
	var nodeObj = responseXml.documentElement.selectSingleNode(element);
	if(nodeObj)
	{
		document.getElementById(htmlfield).value = nodeObj.text;
	}
}

function setDataJSON(responseArray,element,htmlfield)
{
	var nodeObj = responseArray[element];
	//alert("Node obj "+nodeObj);
	if(nodeObj)
	{
		document.getElementById(htmlfield).value = nodeObj;
	}
}

function getSAPAccountDetails()
{
	//alert("In getSAPAccountDetails() "+document.getElementById("name").value);
	//sapaccounts.innerHTML = "OM";
	var sapform = document.getElementById("sapform");
	var name1 = document.getElementById("name").value;
	var ispadrbsnd = document.getElementById("address").value;
	//alert("Name :"+name1+" address :"+ispadrbsnd);
	var url = "/sfa/getSAPAccounts.php";
	//alert("Url :"+url);
	//http.open('post','http://10.100.109.253/crm/servlet/crm.SAPAccountSearch');
	http.open('post',url);
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = handleSAPAccountSearchDataJSON;
	http.send('NAME1='+name1+'&ISPADRBSND='+ispadrbsnd+'&source=CompanyDetailsSAP');
	//http.send();
}

var sapaccounts = "";

function handleSAPAccountSearchDataJSON(){
	if(http.readyState == 4){ 
		//alert("Got a response "+http.responseText);
		var response = eval('('+http.responseText+')');

		if(!response)
		{
			alert("No data found");
			return;
		}
		createSAPAccountSearchResultsTable(response);	
		//alert("Got SAP account :"+response);
	}
}

function createSAPAccountSearchResultsTable(response)
{
	var output = "<table id='myTable1' width='100%' border='1' class='tabForm'><tbody id='myTbody'>";
	output += "<tr><th>Code</th><th>Account Name</th><th>Address</th><th>Telephone</th><th>&nbsp;</th></tr>";
	
	var node = null;
	if(response.ZCRMCOMP_DETAILS_STR4SAP)

	node = response.ZCRMCOMP_DETAILS_STR4SAP.item;
	
	if(!node || node.length == 0)
	   output += "<tr><td colspan='6'>No Data Found</td></tr>";
	else
	{
	//alert("Node :"+node[0].GP_REF);
	//alert("Nodes length :"+node.length);
		for (var j = 0; j < node.length; j++) {
		   output += "<tr class='odd_bg'>";
		   output += "<td>" + node[j].GP_REF + "</td>";
		   output += "<td>" + node[j].NAME1 + "</td>";
		   output += "<td>" + node[j].HAUSN +","+ node[j].STREET2+"</td>";
		   //output += "<td>" + node[j].TELFX + "</td>";
		   output += "<td>" + node[j].ISPTELD + "</td>";
		   output += "<td><input type='button' class='button' onclick='setSAPAccountValues("+j+");this.disabled=true;' value='Set'/></td>";
		   output += "</tr>";
		}
	}
	
	output += "</tbody></table>";
	document.getElementById("sapaccounts").innerHTML = output;
	sapaccounts = node;
}

function setSAPAccountValues(index)
{
	//alert("In setSAPAccountValues :"+index);
	document.getElementById("GP_REF").value = sapaccounts[index].GP_REF;
	document.getElementById("NAME1").value = sapaccounts[index].NAME1;
	document.getElementById("ISPEMAIL").value = sapaccounts[index].ISPEMAIL;
	document.getElementById("TELFX").value = sapaccounts[index].TELFX;
	document.getElementById("ISPTELD").value = sapaccounts[index].ISPTELD;
	document.getElementById("ISPHANDY").value = sapaccounts[index].ISPHANDY;
	//document.getElementById("ISPADRBSND").value = sapaccounts[index].ISPADRBSND;
	document.getElementById("HAUSN").value = sapaccounts[index].HAUSN;
	document.getElementById("STRAS").value = sapaccounts[index].STRAS;
	document.getElementById("STREET2").value = sapaccounts[index].STREET2;

	document.getElementById("ORT01").value = sapaccounts[index].ORT01;
	document.getElementById("PSTLZ").value = sapaccounts[index].PSTLZ;
}
