$(document).ready(function()
{
	loadUserProfile();
});

function loadUserProfile()
{
	var jsonToSend =
    {
      "action" : "LoadUser"
    };

	$.ajax({
      url: "./PHP/applicationLayer.php",
      type: "POST",
      data : jsonToSend,
      ContentType : "application/json",
      dataType: "json",
      success: function(dataReceived)
      {
   	    $("#registerFirstName").val(dataReceived[0].uFname) 
	    $("#registerLastName").val(dataReceived[0].uLname);    
	    $("#registerUsername").val(dataReceived[0].username);
	    $("#registerEmail").val(dataReceived[0].uEmail);
	    $("#registerOrganization").val(dataReceived[0].uOrganization);
	    $("#registerPassword").val(dataReceived[0].uPassword);
	    $("#registerPasswordConfirmation").val(dataReceived[0].uPassword);
	    $("#countrySelection").val(dataReceived[0].uCountry);

	    if(dataReceived[0].uGender == "M")
	    {
			$("#genderMasculine").prop('checked', true);
	    }
	    else if(dataReceived[0].uGender == "F")
	    {
	    	$("#genderFemenine").prop('checked', true);
	    }
      },
      error: function (errorMS)
      {
        alert("Could not load user profile");
      }
    });
}

$("#cancelUpdate").on("click", function()
{
	window.location.href = "userIndex.html";
});