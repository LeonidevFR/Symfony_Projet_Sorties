const apiUrl = 'https://geo.api.gouv.fr/communes';
const Http = new XMLHttpRequest();
Http.open("GET", apiUrl);
Http.responseType = 'json';
Http.send();
Http.onreadystatechange = function(){
    var response = Http.response;
    for(let i = 0; i < response.length; i++){
        if(response[i]["codesPostaux"].length != 0)
        {
            const HttpResponse = new XMLHttpRequest();
        }
    }
}