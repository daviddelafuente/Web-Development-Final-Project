$(document).ready(function()
{
  // Action when clicking the submit button
  $("#submitLogin").on("click", function()
  { 
    var flagFieldsAreFilled = true;

    // Validate inputs of type text/password
    validateTextInput($("#loginUserName"), $("#errorLoginUsername"));
    validateTextInput($("#loginPassword"), $("#errorLoginPassword"));

    // Check error spans to see if it possible to load the next page
    validateNextPageLogin($("span[class=loginErrorSpan]"));
  });

  $("#submitLoginAdmin").on("click", function()
  { 
    var flagFieldsAreFilled = true;

    // Validate inputs of type text/password
    validateTextInput($("#loginUserName"), $("#errorLoginUsername"));
    validateTextInput($("#loginPassword"), $("#errorLoginPassword"));

    // Check error spans to see if it possible to load the next page
    validateNextPageLoginAdmin($("span[class=loginErrorSpan]"));
  });

  // Action when clicking the submit button
  $("#submitRegister").on("click", function()
  { 
    // Validate inputs of type text/password
    validateTextInput($("#registerFirstName"), $("#errorRegisterFirstName"));
    validateTextInput($("#registerLastName"), $("#errorRegisterLastName"));
    validateTextInput($("#registerUsername"), $("#errorRegisterUsername"));
    validateTextInput($("#registerEmail"), $("#errorRegisterEmail"));
    validateTextInput($("#registerOrganization"), $("#errorRegisterOrganization"));
    validateTextInput($("#registerPassword"), $("#errorRegisterPassword"));
    validateTextInput($("#registerPasswordConfirmation"), $("#errorRegisterPasswordConfirmation"));

    // Validate the radio buttons of gender
    validateRadio($("input[name=gender]"), $("#errorGender"));

    // Validate the dropdown menu of countries
    validateDropdownMenu($("#countrySelection"), $("#errorCountrySelection"));

    // Validate password inputs to see if they match
    validateMatchingPasswords($("#registerPassword"), $("#registerPasswordConfirmation"), $("#errorRegisterPasswordsDoNotMatch"));

    // Check error spans to see if it possible to load the next page
    validateNextPageRegister($("span[class=registerErrorSpan]"));
  });

    // Action when clicking the submit button
  $("#submitUpdateUser").on("click", function()
  { 
    // Validate inputs of type text/password
    validateTextInput($("#registerFirstName"), $("#errorRegisterFirstName"));
    validateTextInput($("#registerLastName"), $("#errorRegisterLastName"));
    validateTextInput($("#registerUsername"), $("#errorRegisterUsername"));
    validateTextInput($("#registerEmail"), $("#errorRegisterEmail"));
    validateTextInput($("#registerOrganization"), $("#errorRegisterOrganization"));
    validateTextInput($("#registerPassword"), $("#errorRegisterPassword"));
    validateTextInput($("#registerPasswordConfirmation"), $("#errorRegisterPasswordConfirmation"));

    // Validate the radio buttons of gender
    validateRadio($("input[name=gender]"), $("#errorGender"));

    // Validate the dropdown menu of countries
    validateDropdownMenu($("#countrySelection"), $("#errorCountrySelection"));

    // Validate password inputs to see if they match
    validateMatchingPasswords($("#registerPassword"), $("#registerPasswordConfirmation"), $("#errorRegisterPasswordsDoNotMatch"));

    // Check error spans to see if it possible to load the next page
    validateUpdateUser($("span[class=registerErrorSpan]"));
  });
});

// Generic function to validate an input of type text/password 
// Requires the element to be validated and the element of the error span to display the message
function validateTextInput($elementToValidate, $errorElement)
{
  if ($elementToValidate.val() == "")
  {
    $errorElement.text("Please fill up this input.");
  }
  else
  {
    $errorElement.text("");
  }
}

// Generic function to validate a group of radio buttons
// Requires the group of radio buttons and the element of the error span to display the message
function validateRadio($radioElements, $errorElement)
{
  if (!$radioElements.is(":checked"))
  {
    $errorElement.text("Please select an option.");
  }
  else
  {
    $errorElement.text("");
  }
}

// Generic function to validate a the options in a dropdown menu
// Requires the element of the dropdown menu and the element of the error span to display the message
function validateDropdownMenu($dropdownElement, $errorElement)
{
  if ($dropdownElement.val() == "0")
  {
    $errorElement.text("Please select an option.");
  }
  else
  {
    $errorElement.text("");
  }
}

// Generic function to validate two password inputs
// Requires both of the password inputs and the element of the error span to display the message
function validateMatchingPasswords($inputOne, $inputTwo, $errorElement)
{
  if ($inputOne.val() != $inputTwo.val())
  {
    $errorElement.text("Passwords do not match.");
  }
  else
  {
    $errorElement.text("");
  }
}


// Check that all of the spans in the given section are empty, which means
// that validations were correct. In order for this function to work, it needs
// to be called after calling the functions that validate the given section.
function validateNextPageLogin($errorElements)
{
  var flagFieldsAreFilled = true;

  $errorElements.each(function (index, element) 
  {
    if(element.textContent != "")
    {
      flagFieldsAreFilled = false;
    }
  });

  // If validation was successful, it loads the home page
  if(flagFieldsAreFilled)
  {
    var username = $("#loginUserName").val();
    var password = $("#loginPassword").val();
    var remember = $("#rememberCheckbox").is(":checked");

    var jsonToSend = {
              "username" : username,
              "uPass" : password,
              "action" : "logUser"
             };
    
    $.ajax({
      url: "./PHP/applicationLayer.php",
      type: "POST",
      data : jsonToSend,
      ContentType : "application/json",
      dataType: "json",
      success: function(dataReceived){
        alert("Sucessful Login");
        window.location.href = "userIndex.html";
      },
      error: function (errorMS){
        alert("Wrong credentials " + errorMS.statusText);
      }
    });
  }
}

function validateNextPageLoginAdmin($errorElements)
{
  var flagFieldsAreFilled = true;

  $errorElements.each(function (index, element) 
  {
    if(element.textContent != "")
    {
      flagFieldsAreFilled = false;
    }
  });

  // If validation was successful, it loads the home page
  if(flagFieldsAreFilled)
  {
    var username = $("#loginUserName").val();
    var password = $("#loginPassword").val();

    var jsonToSend = 
    {
      "uID" : username,
      "uPass" : password,
      "action" : "logAdmin"
    };
    
    $.ajax({
      url: "./PHP/applicationLayer.php",
      type: "POST",
      data : jsonToSend,
      ContentType : "application/json",
      dataType: "json",
      success: function(dataReceived){
        alert("Sucessful Login");
        window.location.href = "adminindex.html";
      },
      error: function (errorMS){
        alert("Wrong credentials " + errorMS.statusText);
      }
    });
  }
}

function validateNextPageRegister($errorElements)
{
  var flagFieldsAreFilled = true;

  $errorElements.each(function (index, element) 
  {
    if(element.textContent != "")
    {
      flagFieldsAreFilled = false;
    }
  });

  // If validation was successful, it loads the home page
  if(flagFieldsAreFilled)
  {
    var firstName = $("#registerFirstName").val();
    var lastName = $("#registerLastName").val();    
    var username = $("#registerUsername").val();
    var email = $("#registerEmail").val();
    var organization = $("#registerOrganization").val();
    var password = $("#registerPassword").val();
    var gender = $("input[name=gender]:checked").val();
    var country = $("#countrySelection").val();
    var remember = $("#rememberCheckbox").is(":checked");

    var jsonToSend = 
    {
      "username" : username,
      "uPass" : password,
      "uFname" : firstName,
      "uLname" : lastName,
      "uEmail" : email,
      "uOrganization" : organization,
      "uGender" : gender,
      "uCountry" : country,
      "action" : "RegUser"
    };
    
    $.ajax({
      url: "./PHP/applicationLayer.php",
      type: "POST",
      data : jsonToSend,
      ContentType : "application/json",
      dataType: "json",
      success: function(dataReceived)
      {
        alert("User registered");
        window.location.href = "userIndex.html";
      },
      error: function (errorMS)
      {
        alert("Could not register user");
      }
    });
  }
}

function validateUpdateUser($errorElements)
{
  var flagFieldsAreFilled = true;

  $errorElements.each(function (index, element) 
  {
    if(element.textContent != "")
    {
      flagFieldsAreFilled = false;
    }
  });

  // If validation was successful, it loads the home page
  if(flagFieldsAreFilled)
  {
    var firstName = $("#registerFirstName").val();
    var lastName = $("#registerLastName").val();    
    var username = $("#registerUsername").val();
    var email = $("#registerEmail").val();
    var organization = $("#registerOrganization").val();
    var password = $("#registerPassword").val();
    var gender = $("input[name=gender]:checked").val();
    var country = $("#countrySelection").val();
    var remember = $("#rememberCheckbox").is(":checked");

    var jsonToSend = 
    {
      "username" : username,
      "uPass" : password,
      "uFname" : firstName,
      "uLname" : lastName,
      "uEmail" : email,
      "uOrganization" : organization,
      "uGender" : gender,
      "uCountry" : country,
      "action" : "UpdateUser"
    };
    
    $.ajax({
      url: "./PHP/applicationLayer.php",
      type: "POST",
      data : jsonToSend,
      ContentType : "application/json",
      dataType: "json",
      success: function(dataReceived)
      {
        alert("User information updated");
        window.location.href = "userIndex.html";
      },
      error: function (errorMS)
      {
        alert("Could not register user");
      }
    });
  }
}