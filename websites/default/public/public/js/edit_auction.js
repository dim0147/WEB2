var thumbDel = [];
function removeThumb(){
    thumbDel.push(this.getAttribute("imgID"));
    this.parentNode.removeChild(this);
}

window.onload = function(){
    var idProduct = document.getElementById("prodID").value;
    let img = document.getElementsByClassName("thumb_img");
    for(let i = 0; i < img.length; i++){
        img[i].addEventListener('click', removeThumb, false);
    }


    document.getElementById("form").addEventListener("submit", function(e){
        e.preventDefault();
        var form = new FormData(this);
        form.append("thumbnail_delete" , JSON.stringify(thumbDel));
        var xhr = new XMLHttpRequest();
                
        xhr.addEventListener('load', function(event) {
           alert(xhr.responseText);
           console.log(xhr.responseText);
        });

        xhr.open('POST', 'https://' + window.location.hostname + '/user/postEditAuction');

        xhr.send(form);
    });

    document.getElementById("Del").addEventListener("click", function(e){
        e.preventDefault();
        if(confirm("Are you sure want to remove this auction?")){
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "https://" + window.location.host + "/user/postRemoveAuction")
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.addEventListener('load', function(event){
                alert(xhr.responseText);
                console.log(xhr.responseText);
                location.reload();
            });
            xhr.send(("id=" + idProduct));
        }

    });
}