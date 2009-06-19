//
// inc/rackDHTML.js
//
// JavaScript/DHTML functions for rack
//
// Time-stamp: "2009-06-19 02:01:56 jantman"
// $Id$
// $Source$

var http = createRequestObject(); 

function createRequestObject()
{
	var request_o;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer")
	{
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else
	{
		request_o = new XMLHttpRequest();
	}
	return request_o;
}

//
// viewRack Move Form
//

function showMoveForm($hostID)
{
  // shows the form to move a device within a rack

  // DEBUG
  var ffxVer = getFirefoxVersion();
  if(ffxVer > 0 && ffxVer < 3)
  {
    alert("These functions will throw an unhandled JS exception in Firefox " + ffxVer);
  }
  // END DEBUG

  document.getElementById("popuptitle").innerHTML = "Move Device " + $hostID;
  
  newMoveFormRequest($hostID);
}

function newMoveFormRequest($id)
{
  doHTTPrequest(('dhtmlMoveForm.php?id=' + $id), handleNewMoveFormRequest);
	// TODO: add an error var to reload the form if we have errors
}

function handleNewMoveFormRequest()
{
  if(http.readyState == 4)
  {
    var response = http.responseText;
    document.getElementById('popupbody').innerHTML = response;
    showPopup();
  }
}

function submitForm()
{
  // radio buttons
  var postData = "";
  postData = "newTopU="+document.getElementById("newTopU").value+"&id="+document.getElementById("id").value;

  var url = "handlers/moveDevice.php";
  http.open("POST", url, true);
  
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.setRequestHeader("Content-length", postData.length);
  http.setRequestHeader("Connection", "close");  
  http.onreadystatechange = handleSubmitURL; 
  http.send(postData);
}

function handleSubmitURL()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		if(response.substr(0, 6) == "ERROR:")
		{
			// TODO: handle error condition by triggering a popup or changing content of existing one
			var errorMessage = response.substr(6, (response.length - 6));
			hidePopup();
			document.getElementById("popuptitle").innerHTML = "ERROR: ";
			document.getElementById("popupbody").innerHTML = errorMessage;
			showPopup();
		}
		else
		{
		  updateCommitDiv();
		  setTimeout("reloadRackTable()",100);
		  //reloadRackTable();
		}
	}
}

function reloadRackTable()
{
	http.open('get', "showRackTable.php?rack_id="+document.getElementById("rackID").value);
	http.onreadystatechange = handleReloadRackTable; 
	http.send(null);
}

function handleReloadRackTable()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
	        document.getElementById("rackTableDiv").innerHTML = response;
		hidePopup();
	}
}

//
// END Move Form
//

//
// BEGIN update commit div
//
function updateCommitDiv()
{
  //var id = document.getElementById("rackID").value;
  //alert(id);
  http.open('get', "inc/getCommitDiv.php?rack_id="+document.getElementById("rackID").value);
  http.onreadystatechange = handleUpdateCommitDiv; 
  http.send(null);
}

function handleUpdateCommitDiv()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		document.getElementById("commitForm").innerHTML = response;
	}
}
//
// END update commit div
//

//
// Update U options
//

function updateUoptions(rack_id)
{
  if(document.getElementById("device_id").value != -1)
    {
      //alert("updateUoptions: "+rack_id); // DEBUG
      reloadUoptions(rack_id, document.getElementById("device_id").value, 0, 0);
    }
  //reloadUoptions(height);
}

function reloadUoptions(rack_id, device_id, height, isPart)
{
  if(isPart == 1)
    {
      var side = document.getElementById("partSide").value;
    }
  else
    {
      var side = document.getElementById("deviceSide").value;
    }
  
    if(height == 0)
    {
      http.open('get', "getEmptyUoptions.php?rack_id="+rack_id+"&device_id="+device_id+"&side="+side);
    }
    else
    {
      http.open('get', "../getEmptyUoptions.php?rack_id="+document.getElementById("rack").value+"&height="+height+"&side="+side);
    }
  http.onreadystatechange = handleReloadUoptions; 
  http.send(null);
}

function handleReloadUoptions()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		document.getElementById("topUspan").innerHTML = response;
	}
}

//
// END Update U Options
//

//
// Update Side options
//

function updateSideOptions(rack_id)
{
  if(document.getElementById("device_id").value != -1)
    {
      reloadSideOptions(rack_id, document.getElementById("device_id").value);
    }
}

function reloadSideOptions(rack_id, device_id)
{
  http.open('get', "getEmptySideOptions.php?device_id="+device_id+"&rack_id="+rack_id);
  //alert("getEmptySideOptions.php?device_id="+device_id+"&rack_id="+rack_id);
  http.onreadystatechange = handleReloadSideOptions; 
  http.send(null);
}

function handleReloadSideOptions()
{
	if(http.readyState == 4)
	{
	  var response = http.responseText;
	  document.getElementById("deviceSideSpan").innerHTML = response;
	  var rack_id = document.getElementById("rackID").value;
	  if(response.indexOf('<option value="0">Full Depth</option></select>') != -1)
	    {
	      //alert("CALLING updateUoptions: "+rack_id); // DEBUG
	      updateUoptions(rack_id);
	    }
	}
}

//
// END Update Side Options
//

//
// AddRackPart U options
//

function updateRackPart(rack_id)
{
  var height = document.getElementById("heightU").value;
  var side = document.getElementById("partSide").value;
  
  if(height == 0)
    {
      alert("Please select a height for the rack part.")
    }
  else
    {
      http.open('get', "getEmptyUoptions.php?rack_id="+rack_id+"&partheight="+height+"&side="+side);
      http.onreadystatechange =  handleReloadRackPartOptions; 
      http.send(null);
    }
}

function handleReloadRackPartOptions()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		document.getElementById("rackPartTopUspan").innerHTML = response;
	}
}

//
// END AddRackPart U Options
//

//
// viewDevice add Patch Form
//

function addPatch($ifID)
{
  // shows the form to add a patch to a device

  // DEBUG
  var ffxVer = getFirefoxVersion();
  if(ffxVer > 0 && ffxVer < 3)
  {
    alert("These functions will throw an unhandled JS exception in Firefox " + ffxVer);
  }
  // END DEBUG

  document.getElementById("popuptitle").innerHTML = "Add Patch to Interface " + $ifID;
  
  newPatchFormRequest($ifID);
}

function newPatchFormRequest($id)
{
  doHTTPrequest(('dhtmlPatchForm.php?id=' + $id), handleNewPatchFormRequest);
	// TODO: add an error var to reload the form if we have errors
}

function handleNewPatchFormRequest()
{
  if(http.readyState == 4)
  {
    var response = http.responseText;
    document.getElementById('popupbody').innerHTML = response;
    showPopup();
  }
}

function submitPatchForm()
{
  var postData = "";
  postData = "patchToID="+document.getElementById("patchToID").value+"&id="+document.getElementById("id").value;

  var url = "handlers/patchInterface.php";
  http.open("POST", url, true);
  
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.setRequestHeader("Content-length", postData.length);
  http.setRequestHeader("Connection", "close");  
  http.onreadystatechange = handleSubmitPatch; 
  http.send(postData);
}

function handleSubmitPatch()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		if(response.substr(0, 6) == "ERROR:")
		{
			// TODO: handle error condition by triggering a popup or changing content of existing one
			var errorMessage = response.substr(6, (response.length - 6));
			hidePopup();
			document.getElementById("popuptitle").innerHTML = "ERROR: ";
			document.getElementById("popupbody").innerHTML = errorMessage;
			showPopup();
		}
		else
		{
		        reloadInterfaceTable();
		}
	}
}

function reloadInterfaceTable()
{
	http.open('get', "showInterfaceTable.php?id="+document.getElementById("device_id").value);
	http.onreadystatechange = handleReloadInterfaceTable; 
	http.send(null);
}

function handleReloadInterfaceTable()
{
	if(http.readyState == 4)
	{
		var response = http.responseText;
		document.getElementById("ifTableDiv").innerHTML = response;
		hidePopup();
	}
}

//
// END viewDevice add patch form
//


//
// HTTPrequest stuff
//

function doHTTPrequest($url, $handler)
{
  // TODO - get this working with older Firefox, using abort()
  http.open('get', $url);
  http.onreadystatechange = $handler;
  http.send(null);
}

//
// POPUP STUFF
//

function showPopup()
{
        grayOut(true);
        document.getElementById("popup").style.display = 'block';
}

function hidePopup()
{
        grayOut(false);
        document.getElementById("popup").style.display = 'none';
}

// UTIITY FUNCTIONS

// DEVELOPMENT ONLY???
function getFirefoxVersion()
{
  var userAgentStr = navigator.userAgent;
  // returns the version number for firefox (as an integer)
  var startIndex = userAgentStr.indexOf("Firefox/");
  if(startIndex < 0)
  {
    // not Firefox
    return 0;    
  }
  
  var version = userAgentStr.substring(startIndex); // start from / next
  version = userAgentStr.substring(userAgentStr.lastIndexOf("/")+1);
  version = parseFloat(version);
  
  return version;
  
}
 
