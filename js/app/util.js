function array_move(arr, old_index, new_index) {
    if (new_index >= arr.length) {
        var k = new_index - arr.length + 1;
        while (k--) {
            arr.push(undefined);
        }
    }
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
    return arr; // for testing
};

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};
String.prototype.toTitleCase = function() {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

function removeDuplicates(originalArray, objKey) {
    var trimmedArray = [];
    var values = [];
    var value;
  
    for(var i = 0; i < originalArray.length; i++) {
      value = originalArray[i][objKey];
  
      if(values.indexOf(value) === -1) {
        trimmedArray.push(originalArray[i]);
        values.push(value);
      }
    }
  
    return trimmedArray;
}

function parseTimestamp(timestamp){
    if (timestamp === null){return;}
    var t = timestamp.split(/[- :]/);
    var date = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
    return date;
}

function getYear(date){
    return date.getFullYear();
}
    
function getMonth(date,abbrev){
    abbrev = abbrev||false;
    var monthNames = (abbrev)?["Jan", "Feb", "Mar", "Apr", "May", "June",
    "July", "Aug", "Sept", "Oct", "Nov", "Dec"
    ]:["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
    ];
    return monthNames[date.getMonth()];
}

function getDay(date,abbrev){
    abbrev = abbrev || false;
    var dayNames = (abbrev)?(["Sun", "Mon", "Tues", "Wed", "Thurs", "Fri", "Sat"]):(["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]);
    return dayNames[date.getDay()];
}

function adjustForTimezone(date){
    var timeOffsetInMS = date.getTimezoneOffset() * 60000;
    date.setTime(date.getTime() - timeOffsetInMS);
    return date;
}

function revertDateObject(dateline, timezoneOffset){
    return (new Date( dateline.getTime() - timezoneOffset * 3600 * 1000));
}

var inputValidation = {
    scheduledcontact:function(vals){
        return (vals.message!=='' && (vals.target==='followers'||(vals.targetid>0)||(typeof vals.targetid.length !== "undefined" && vals.targetid.length > 0)) && (vals.ongoing || vals.enddate !== null));
    },
    contact:function(vals){
        return (vals.message!=='' && (vals.target==='followers'||(vals.targetid>0)||(typeof vals.targetid.length !== "undefined" && vals.targetid.length > 0)));
    },
    group:function(vals){
        return (vals.name!=='');
    },
    ticket:function(vals){
        return (typeof vals.category!== 'undefined' && vals.category!=='' && typeof vals.subcategory!== 'undefined' && vals.subcategory!=='' && vals.subject !=='' && vals.message !=='');
    }
};

function addMinutes(date, minutes) {
    var result = new Date(date);
    result.setMinutes(result.getMinutes() + minutes);
    return result;
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function addMonths(date, months) {
    var result = new Date(date);
    result.setMonth(result.getMonth() + months);
    return result;
}

var mouseDown = [0, 0, 0];
document.body.onmousedown = function(evt) {
    if (evt.button > 2){return;}
    mouseDown[evt.button]=1;
};
document.body.onmouseup = function(evt) {
    if (evt.button > 2){return;}
    mouseDown[evt.button]=0;
};