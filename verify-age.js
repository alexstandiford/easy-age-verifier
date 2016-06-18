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

//The actual form
function taseavAgeForm(){
  var result;
  result =  "<div class='" + taseavData.wrapperClass + "'>";
  result +=   "<form class='" + taseavData.formClass + "'>";
  result +=   "<h2>" + taseavData.formTitle + "</h2>";
  result +=     "<div class='taseav-month'>";
  result +=     "<label>Month</label>";
  result +=     "<input name='month' type='number' min='1' max='12' required>";
  result +=     "</div>";
  result +=     "<div class='taseav-day'>";
  result +=     "<label>Day</label>";
  result +=     "<input name='day' type='number' min='1' max='31' required>";
  result +=     "</div>";
  result +=     "<div class='taseav-year'>";
  result +=     "<label>Year</label>";
  result +=     "<input name='year' type='number' min='1900' max='"+ taseavCurrDate.getFullYear() +"' required>";
  result +=     "</div>";
  result +=     "<input type='submit'>";
  result +=   "</form>";
  result +=  "</div>";
  return result;
}

//Stores the age into a cookie
function taseavStoreAge(){
  var month = jQuery('.taseav-age-verify input[name="month"]').val();
  var day = jQuery('.taseav-age-verify input[name="day"]').val();
  var year = jQuery('.taseav-age-verify input[name="year"]').val();
  if(month < 10){
    month = "0" + month;
  }
  if(day < 10){
    day = "0" + day;
  }
  var result = "taseavdob=" + year + "-" + month + "-" + day;
  document.cookie = result;
}
function confirmAge(){
  if(taseavIsAboveAge() == false){
    history.back();
  }
  else{
    jQuery('.taseav-age-verify').remove();
  }
}

jQuery(document).ready(function(){
    jQuery("html").append(taseavAgeForm());
    jQuery(".taseav-age-verify form").submit(function(e) {
      e.preventDefault();
    });
    //Disables mouse-wheel when gallery is open
    jQuery(".taseav-age-verify").bind("mousewheel", function() {
         return false;
    });
    jQuery('.taseav-age-verify').submit(function(){
      taseavStoreAge();
      confirmAge();
  });
})