//For the max value of the input in the age form
taseavCurrDate = new Date();
if(taseavData.debug == true){
  var taseavDebugLog = [];
}

function taseavDebug(log){
  if(taseavData.debug == true){
    console.log(log);
    taseavDebugLog.push(log);
  }
  return
}

//Initializes the age data object. This allows this data to pass into the pro plugin
var taseavAge = {};

//Gets the cookie that was just stored
function taseavGetCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return false;
}

//Stores the date of birth into the taseavAge object
function taseavStoreDOB(){
	taseavAge.month = jQuery('#taseav-age-verify input[name="month"]').val();
	taseavAge.day = jQuery('#taseav-age-verify input[name="day"]').val();
	taseavAge.year = jQuery('#taseav-age-verify input[name="year"]').val();
	if(taseavData.formType == 'eav_enter_age'){
		if(taseavAge.month < 10){
			taseavAge.month = "0" + taseavAge.month;
		}
		if(taseavAge.day < 10){
			taseavAge.day = "0" + taseavAge.day;
		}
		
	}
}

//Gets the date of birth
function taseavGetDOB(){
	var result = taseavAge.year + "-" + taseavAge.month + "-" + taseavAge.day;
	if(taseavData.formType == 'eav_confirm_age'){
		taseavAge.confirmAge = jQuery('#taseav-age-verify input[selected="selected"]').attr('name');
		var result = taseavAge.confirmAge;
	}
	return result;
}

//Stores the age into a cookie
function taseavStoreAge(){
	var result = taseavGetDOB();
	document.cookie = "taseavdob=" + result;
	taseavDebug('Age stored as a cookie. Value = ' + result);
}

function taseavGetAge() {
    taseavDebug('Evaluated Date of Birth: ' +taseavGetCookie('taseavdob'));
    var birthday = new Date(taseavGetCookie('taseavdob'));
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}

function taseavIsAboveAge(ageToCheck){
  if(!ageToCheck && taseavData.formType != 'eav_confirm_age'){
    taseavDebug('Getting minimum age to check...')
    ageToCheck = taseavData.minAge;
    taseavDebug('Age to check: ' + taseavData.minAge);
  };
  if(taseavData.formType == 'eav_confirm_age'){
    taseavDebug('Getting confirm age value...')
    var age = taseavGetCookie('taseavdob');
    taseavDebug('Confirm Age value = ' + age);
  }
  else{
    taseavDebug('Getting evaluated age to check against...')
    var age = taseavGetAge();
    taseavDebug('Age checked: ' + age);
  }
  if(age < ageToCheck || age == 'underAge'){
    taseavDebug('The user claimed their age as under age.');
    return false;
  }
  else{
    return true;
  }
}

function confirmAge(){
  if(taseavIsAboveAge() == false){
    taseavDebug('User is underage. Displaying message "' + taseavData.underageMessage + '"');
    alert(taseavData.underageMessage);
    if(taseavData.debug == true){
      alert(taseavDebugLog.join('\n \n'));
    }
    history.back();
  }
  else{
    taseavDebug('User is older than the min age of ' + taseavData.minAge +'. Removing age verify overlay.');
    jQuery('#taseav-age-verify').remove();
  }
}

jQuery(document).ready(function(){
  if(taseavData.isOfAge == false && taseavData.loggedIn != true){
    jQuery("body").append(taseavData.template);
    jQuery("#taseav-age-verify form").submit(function(e) {
      e.preventDefault();
    });
    //Disables mouse-wheel when gallery is open
    jQuery("#taseav-age-verify").bind("mousewheel", function() {
         return false;
    });
  if(taseavData.formType == 'eav_confirm_age'){
    jQuery("form input[type=submit]").click(function() {
      jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("selected");
      jQuery(this).attr("selected", "true");
    });
  }
    jQuery('#taseav-age-verify').submit(function(){
      taseavStoreAge();
      confirmAge();
      taseavDebug(taseavData);
    });
  };
})