const table_language = {
    processing: `Processing the content   <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
    searchPlaceholder: "",
    search: "Search",
    lengthMenu: "Show _MENU_  peer page",
    zeroRecords: "Nothing found",
    info: "Page _PAGE_ of _PAGES_  (filtered of _MAX_ records totals)",
    infoEmpty: "There are no records to show.",
    infoFiltered: ""
};

function formatDate(d){
    if(!d||d=='') return '';
    const date = new Date(d);
    const day = date.getDate().toString().padStart(2, "0");
    const month = (date.getMonth() + 1).toString().padStart(2, "0");
    const year = date.getFullYear().toString();

    return `${month}/${day}/${year}`;
};

function formatDateOther(d){
  if (!d || d === '') return '';
  
  const dateTime = new Date(d);
  
  const month = (dateTime.getMonth() + 1).toString().padStart(2, "0");
  const day = dateTime.getDate().toString().padStart(2, "0");
  const year = dateTime.getFullYear().toString();
  
  let hour = dateTime.getHours().toString();
  let minute = dateTime.getMinutes().toString();
  let second = dateTime.getSeconds().toString();
  const amPM = hour >= 12 ? "PM" : "AM";
  
  hour = hour % 12;
  hour = hour === 0 ? "12" : hour.toString().padStart(2, "0");
  minute = minute.padStart(2, "0");
  second = second.padStart(2, "0");
  
  const formattedDateTime = `${month}/${day}/${year} ${hour}:${minute}:${second} ${amPM}`;

  return formattedDateTime;
};

function _formatDateOther(d){
    //const originalDateTime = "20024-05-24T16:59:06.000000Z";
    if(!d||d=='') return '';
    const options = {
      month: "2-digit",
      day: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    };
    
    const formattedDateTime = new Date(d)
      .toLocaleString(navigator.language, options);
    
    return formattedDateTime;
};

function formatDecodeDate(d){
  if(!d||d=="")return '';
  var parts = d.split("/");
  var formattedDate = parts[2] + "-" + parts[0] + "-" + parts[1];
  return formattedDate;
}

function frendlyPastDate(d){
  //d='2024-06-11 05:27:07' for example
  // need to convert follows:
  // for example: "30 sec ago" or "12 min ago", or "2 hours ago", or "1 hour ago", or "a half of hour ago", "2 days ago", or "yesterday", or "Mon", or "Tue", or "a week ago", or "2 weeks ago", or "last month", or "2 months ago", or "last year",  "2 years ago" etc
  const currentDate = new Date();
  const pastDate = new Date(d);
  const timeDifference = Math.abs(currentDate - pastDate);
  const seconds = Math.floor(timeDifference / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);
  const months = Math.floor(days / 30);
  const years = Math.floor(months / 12);
  if (seconds < 60) {
    return `${seconds} sec ago`;
  } else if (minutes < 60) {
    return `${minutes} min ago`;
  } else if (hours < 24) {
    if (hours === 1) {
      return `1 hour ago`;
    } else {
      return `${hours} hours ago`;
    }
  } else if (days === 1) {
    return `yesterday`;
  } else if (days < 7) {
    return `${days} days ago`;
  } else if (days < 14) {
    return `last week`;
  } else if (months === 1) {
    return `last month`;
  } else if (months < 12) {
    return `${months} months ago`;
  } else if (years === 1) {
    return `last year`;
  } else {
    return `${years} years ago`;
  }
}

function checkP(___p, _p, _m=0){
  var res = false;
  ___p.forEach(p => {
    if(p.id==_p){
      if(_m==0) return res = p.read>0;
      else if(_m==1) return res = p.write>0;
      else if(_m==2) return res = p.create>0;
      else if(_m==3) return res = p.delete>0;
    }
  });
  return res;
}