
function setTimezoneCookie() {
    var now = new Date();
    var expire = new Date();

    expire.setTime( now.getTime() + 365*24*3600*1000 );

    var tzCookie = now.getTimezoneOffset() * 60;

    document.cookie = "timezoneOffset=" + tzCookie + ";expires=" + expire.toUTCString() + ";path=/";
}

setTimezoneCookie();