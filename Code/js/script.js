// Skript

// myoffers: add another text field for entering multiple modules
function addModule(){
    var segm = document.getElementById("input_module");
    // Find the current id
    var id = 0;
    var el = "module0";
    while (document.getElementById(el) != null){
        id += 1;
        el = "module"+id;
    }
    var newMod = "<br><input type='text' name='"+el+"' id='"+el+"'>";
    segm.innerHTML += newMod;
}

// myoffers: add another text field for entering multiple courses of studies
function addCourse(){
    var segm = document.getElementById("input_course");
    // Find the current id
    var id = 0;
    var el = "course0";
    while (document.getElementById(el) != null){
        id += 1;
        el = "course"+id;
    }
    var newCourse = "<br><input type='text' name='"+el+"' id='"+el+"'>";
    segm.innerHTML += newCourse;
}