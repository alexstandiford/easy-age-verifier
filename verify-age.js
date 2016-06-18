tasCurrDate = new Date();
function tasGetCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return false;
}
function tasHasAge(){
  if(tasGetCookie('dob')){
    return true;
  }
  else{
    return false;
  }
}
function tasGetAge() {
    var birthday = new Date(tasGetCookie('dob'));
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}
function tasIsAboveAge(ageToCheck){
  if(!ageToCheck){
    ageToCheck = 21;
  };
  var age = tasGetAge();
  if(age < ageToCheck){
    return false;
  }
  else{
    return true;
  }
}
function ageForm(){
  var result;
  result =  "<div class='age-verify'>";
  result +=   "<form>";
  result +=   "<h2>Verify Your Age to Continue</h2>";
  result +=     "<div class='month'>";
  result +=     "<label>Month</label>";
  result +=     "<input name='month' type='number' min='1' max='12' required>";
  result +=     "</div>";
  result +=     "<div class='day'>";
  result +=     "<label>Day</label>";
  result +=     "<input name='day' type='number' min='1' max='31' required>";
  result +=     "</div>";
  result +=     "<div class='year'>";
  result +=     "<label>Year</label>";
  result +=     "<input name='year' type='number' min='1900' max='"+ tasCurrDate.getFullYear() +"' required>";
  result +=     "</div>";
  result +=     "<input type='submit'>";
  result +=   "</form>";
  result +=  "</div>";
  return result;
}
function storeAge(){
  var month = jQuery('.age-verify input[name="month"]').val();
  var day = jQuery('.age-verify input[name="day"]').val();
  var year = jQuery('.age-verify input[name="year"]').val();
  if(month < 10){
    month = "0" + month;
  }
  if(day < 10){
    day = "0" + day;
  }
  document.cookie="dob=" + year + "-" + month + "-" + day;
}
function confirmAge(){
  if(tasIsAboveAge() == false){
    history.back();
  }
  else{
    jQuery('.age-verify').remove();
  }
}

jQuery(document).ready(function(){
  if(!tasHasAge() || tasIsAboveAge() === false){
    jQuery("html").append(ageForm());
    jQuery(".age-verify form").submit(function(e) {
      e.preventDefault();
    });
    //Disables mouse-wheel when gallery is open
    jQuery(".age-verify").bind("mousewheel", function() {
         return false;
    });
    jQuery('.age-verify').submit(function(){
      storeAge();
      confirmAge();
  });
  }
})