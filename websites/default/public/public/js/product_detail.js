
function loadReview(){
    var prodID = document.getElementById("getID").value;
    var url = "https://" + window.location.hostname + "/product/getReview?id=" + prodID;
    var xHttp = new XMLHttpRequest();
    xHttp.open('GET', url, true);

    xHttp.onload = function(){
        if(xHttp.status === 200){
            reviews = JSON.parse(xHttp.response);
            domHTML = document.getElementById("review-body");
            for(let key in reviews){
                let element = '<li><strong>' + reviews[key]['name'] +'</strong> ' + reviews[key]['comment'] + '<em>   ' + reviews[key]['created_at']  + '</em></li>';
                domHTML.innerHTML += element;
            }
        }
    }  
    xHttp.send(null);
    
}
window.onload = function(){
    loadReview();
}