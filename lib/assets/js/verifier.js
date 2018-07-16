//For the max value of the input in the age form
const taseavWrapper = jQuery('#taseav-age-verify');
const verifier = new EavVerifier();

/**
 * Gets the verifier form when the site loads
 */
jQuery(document).ready(function(){
  if(/bot|googlebot|crawler|spider|robot|crawling/i.test(navigator.userAgent)) return; //Bail early if this is a bot

  let cookie = document.cookie.replace(/(?:(?:^|.*;\s*)eav_age\s*\=\s*([^;]*).*$)|^.*$/, "$1");
  cookie = cookie === "" ? "underAge" : cookie;
  if((cookie < eav.verification.minAge || cookie === "underAge") && eav.debugModeEnabled !== "1"){
    verifier.getForm();
  }
  if(eav.debugModeEnabled === "1") console.log('Easy Age Verifier Debug Mode Is Enabled.');
});

/**
 * Parses and grabs the submitted age from the visitor
 *
 * @constructor
 */
function EavAge(){

  /**
   * Gets the the age of a value from a specified date of birth
   *
   * @returns Age|False
   */
  this.getAge = function(dob){
    let result;
    let submittedDob = new Date(dob).getTime();
    if(submittedDob){
      let ageDifMs = Date.now() - submittedDob;
      let ageDate = new Date(ageDifMs);
      result = Math.abs(ageDate.getUTCFullYear() - 1970);
    }
    else{
      result = false;
    }
    return result;
  },

    /**
     * Stores the submitted age as a cookie
     *
     * @returns the cookie value
     */
    this.storeAsCookie = function(){
      let result = this.getSubmittedValue();
      if(!eav.isCustomizer){
        document.cookie = "eav_age=" + result + ";" + eav.cookieParameters;
      }
      else{
        console.log("Since the age confirmation was executed in the WordPress customizer, the cookie was not saved");
      }
      return result;
    },

    /**
     * gets the date of birth, or age confirmation from the submitted form
     *
     * @returns the submitted form value
     */
    this.getSubmittedValue = function(){
      let wrapper = jQuery('#taseav-age-verify');
      let result;
      let dob;
      if(eav.formType === 'eav_enter_age'){
        dob = this.parseDob();
        result = this.getAge(dob);
      }
      if(eav.formType === 'eav_confirm_age'){
        result = wrapper.find('input[selected="selected"]').attr('name');
      }
      return result;
    },

    /**
     * Parses the date of birth value from the enter age verifier form
     *
     * @returns {string}
     */
    this.parseDob = function(){
      let wrapper = jQuery('#taseav-age-verify');
      let month = parseInt(wrapper.find('input[name="month"]').val());
      let day = parseInt(wrapper.find('input[name="day"]').val());
      let year = parseInt(wrapper.find('input[name="year"]').val());
      if(month < 10){
        month = "0" + month;
      }
      if(day < 10){
        day = "0" + day;
      }
      return year + "-" + month + "-" + day;
    }
}

/**
 * Checks the submitted age against the specified verifications
 *
 * @param age
 * @constructor
 */
function EavVerification(age){
  this.age = age;

  /**
   * Checks if the verification failed. Returns true if so
   *
   * @returns boolean
   */
  this.failed = function(){
    let visitorAge = this.age.getSubmittedValue();
    let ageToCheck;
    if(eav.formType === 'eav_enter_age'){
      ageToCheck = eav.verification.minAge;
      return (visitorAge < ageToCheck);
    }
    else{
      return (visitorAge === 'underAge');
    }
  }
}

/**
 * Builds the form, and executes actions
 *
 * @constructor
 */
function EavVerifier(){
  this.age = new EavAge();
  this.verification = new EavVerification(this.age);

  this.doFailedAction = function(){
    alert(eav.underageMessage);
    if(!eav.isCustomizer){
      history.back();
    }
  };

  this.doPassedAction = function(){
    jQuery('#taseav-age-verify').remove();
    jQuery(document.body).toggleClass('taseav-verify-failed taseav-verify-success');
  };

  this.doActions = function(){
    if(this.verification.failed() === true){
      this.doFailedAction();
    }
    else{
      verifier.age.storeAsCookie();
      this.doPassedAction();
    }
  };

  this.buildForm = function(wrapper){
    if(!wrapper){
      wrapper = taseavWrapper;
    }
    wrapper.find('form').submit(function(e){
      e.preventDefault();
    });
    //Disables mouse-wheel when gallery is open
    wrapper.bind("mousewheel", function(){
      return false;
    });

    if(eav.formType === 'eav_confirm_age'){
      wrapper.find('input[type=submit]').click(function(){
        jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("selected");
        jQuery(this).attr("selected", "true");
      });
    }

    wrapper.submit(function(){
      verifier.doActions();
    });
  };

  /**
   * Gets the form
   * @returns {boolean}
   */
  this.getForm = function(){
    jQuery("body").prepend(eav.template);
    jQuery(document.body).addClass('taseav-verify-failed');
    jQuery(document.body).removeClass('taseav-verify-success');
    let taseavWrapper = jQuery('#taseav-age-verify');
    verifier.buildForm(taseavWrapper);
    return true;
  }
}