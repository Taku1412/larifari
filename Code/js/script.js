// Script

// myoffers: add another text field for entering multiple modules
function addModule(el_name){
    var segm = document.getElementById("input_module");
    // Find the current id
    var id = 0;
    var el = el_name+id;
    while (document.getElementById(el) != null){
        id += 1;
        el = el_name+id;
    }
    var newMod = "<br><input type='text' name='"+el+"' id='"+el+"'>";
    segm.innerHTML += newMod;
}

// myoffers: add another text field for entering multiple courses of studies
function addCourse(el_name){
    var segm = document.getElementById("input_course");
    // Find the current id
    var id = 0;
    var el = el_name+id;
    while (document.getElementById(el) != null){
        id += 1;
        el = el_name+id;
    }
    var newCourse = "<br><input type='text' name='"+el+"' id='"+el+"'>";
    segm.innerHTML += newCourse;
}

//offer: add an offer as favorite 
function favoriteOffer(username, offerID) {
    //TODO
    console.log("Favorite: " + username + " wants " + offerID);
    
}

//offer: remove an offer from favorites 
function unfavoriteOffer(username, offerID) {
    //TODO
    console.log("Unfavorite: " + username + " does not want " + offerID + " anymore");
}