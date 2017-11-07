var apiPath = '/api/';

function createLoginCookie(token) {
    var diff = 30;
    var oldDateObj = new Date();
    var newDateObj = new Date(oldDateObj.getTime() + diff * 60000);
    document.cookie = "accessToken=" + token + "; expires=" + newDateObj.toISOString();
}


function getCurrentToken() {
    var accessTokenString = getCookie('accessToken');
    return accessTokenString;
}

function createLoggedInUser(userObj) {
    document.cookie = "name=" + userObj.firstName;
    document.cookie = "lastName=" + userObj.lastName;
}

function destroyLoginCookie() {
    document.cookie = "accessToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}


function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2)
        return parts.pop().split(";").shift();
}


function getParameterByName(name, url) {
    if (!url)
        url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
            results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}


