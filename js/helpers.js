function parseDate(dateline,timezoneOffset){
	var date1 = new Date( new Date().getTime() + timezoneOffset * 3600 * 1000);
	var timeDiff = Math.abs(date1.getTime() - dateline.getTime());

	var diffMinutes = (timeDiff / (1000 * 60));
	if (diffMinutes < 1){
		return "Just now";
	}
	var diffMinutes = Math.ceil(diffMinutes);
	if (diffMinutes<60){
		return diffMinutes + " minutes ago";
	}
	var diffHours = Math.round(diffMinutes/60);
	if (diffHours<24){
		return diffHours + " hours ago";
	}
	
	var monthNames = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"
	];
	var dayNum = dateline.getDate();
	var monthName = monthNames[dateline.getMonth()];
	var yearNow = date1.getFullYear();
	var yearThen = dateline.getFullYear();
	var totalDate = monthName + " " + dayNum;
	if (yearNow != yearThen){
		totalDate = totalDate + ", "+yearThen;
	}
	
	return totalDate;
}

function parseFundraiserDateDifference(datetarg,timezoneOffset){
	var dateref = new Date( new Date().getTime() + timezoneOffset * 3600 * 1000);
	var timeDiff = datetarg.getTime() - dateref.getTime();

	var diffMinutes = (timeDiff / (1000 * 60));
	
	if (diffMinutes < 0){
		return "Ended";
	}
	
	if (diffMinutes < 1){
		return "Ending soon!";
	}
	var diffMinutes = Math.ceil(diffMinutes);
	if (diffMinutes<60){
		return diffMinutes + " minutes to go";
	}
	var diffHours = Math.round(diffMinutes/60);
	if (diffHours<24){
		return diffHours + " hours to go";
	}
	
	var diffDays = Math.round(diffMinutes/(60*24));
	if (diffDays < 7){
		return diffDays + " days to go";
	}
	
	var diffWeeks = Math.round(diffMinutes/(60*24*7));
	if (diffWeeks < 4){
		return diffWeeks + " weeks to go";
	}
	
	var monthNames = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"
	];
	var dayNum = datetarg.getDate();
	var monthName = monthNames[datetarg.getMonth()];
	var yearNow = dateref.getFullYear();
	var yearThen = datetarg.getFullYear();
	var totalDate = "Ends "+monthName + " " + dayNum;
	if (yearNow != yearThen){
		totalDate = totalDate + ", "+yearThen;
	}
	
	return totalDate;
}