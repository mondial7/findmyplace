"use strict";

function FindMyPlace(){

    // Redirect to logout page
    this.logout = function() {
        window.location = "../logout/";
    }

    /*
    this.askForHelp = function(){
        FMP.layout.showLoading();
        var i = document.getElementById("askforhelp_id").value;
        var e = document.getElementById("askforhelp_email").value;
        var m = document.getElementById("askforhelp_message").value;
        if (i==""||e==""||m==""){alert("Please fill all inputs"+i+e+m);}
        else {
            OldWheel.get({url : "app/controllers/askforhelp.php?i="+i+"&e="+e+"&m="+m}, function(response){
                if(response == "ok"){
                    alert("Request successfully sent!");
                    FMP.layout.hideAskForHelp();
                } else {
                    alert(response);
                }
                FMP.layout.hideLoading();
            });
        }
        return false;
    }*/

}

FindMyPlace.prototype.layout = {

    loading_screen : document.getElementById("loading_screen"),
    askforhelp : document.getElementById("askforhelp"),
    askforhelp_opt : { use_style : true, 
                       extra_style : { show : "top:0px", hide : "" } }

};

// Loading screen modal
FindMyPlace.prototype.layout.showLoading = function(){
    OldWheel.show( this.loading_screen, { use_style : true } );
}
FindMyPlace.prototype.layout.hideLoading = function(){
    OldWheel.hide( this.loading_screen, { use_style : true } );
}

// Ask for help modal
FindMyPlace.prototype.layout.showAskForHelp = function(){
    OldWheel.show( this.askforhelp, this.askforhelp_opt );
}
FindMyPlace.prototype.layout.hideAskForHelp = function(){
    OldWheel.hide( this.askforhelp, this.askforhelp_opt );
}

// Navigation menu
FindMyPlace.prototype.layout.toggleMobileMenu = function(){
    
    var menu_list = document.getElementById("menu_list"),
        is_visible = menu_list.getAttribute("data-mobile"),
        menu_button = document.getElementById("mobile_menu__button");

    if(is_visible === "0"){
        menu_list.className = "menu_list";
        menu_list.setAttribute("data-mobile","1");
        menu_button.className = "mobile_menu__button mobile_menu__button--close";
    } else {
        menu_list.className = "menu_list menu_list--hide";
        menu_list.setAttribute("data-mobile","0");
        menu_button.className = "mobile_menu__button";
    }

}

// Hide modal
FindMyPlace.prototype.layout.hideModal = function(modal_name){ 
    document.getElementById(modal_name).className = modal_name; 
}

// Show modal
FindMyPlace.prototype.layout.showModal = function(modal_name) {
    document.getElementById(modal_name).className = modal_name + " " + modal_name + "--visible";
}

FindMyPlace.prototype.layout.showList = function() {

    var node = document.getElementById("places__container"),
        classname_ = "places__container places__container--switch places__container--list_visible";

    node.className = classname_;

}

FindMyPlace.prototype.layout.showMap = function() {

    var node = document.getElementById("places__container"),
        classname_ = "places__container";

    node.className = classname_;

}

// Filert and hide (css) cards
FindMyPlace.prototype.layout.filter = function(filter_key){/* ... */}


FindMyPlace.prototype.places = { 
    result_limit : 100,
    latitude : 0,
    longitude : 0,
    my_latitude : 0,
    my_longitude : 0,
    zoom : 14,
    max_zoom : 20,
    min_zoom : 3
};

FindMyPlace.prototype.places.refreshLocation = function(init) {
    
    var map = document.querySelector('google-map'),
        lon = 0, lat = 0;

    if (typeof init !== "undefined" && init) {
        lon = this.my_longitude;
        lat = this.my_latitude;
    } else {
        lon = this.longitude;
        lat = this.latitude; 
    }

    map.latitude = lat;
    map.longitude = lon;
    map.zoom = this.zoom;

}

FindMyPlace.prototype.places.zoomIn = function() {

    if (FMP.places.zoom < FMP.places.max_zoom) {

        FMP.places.zoom++;
        FMP.places.refreshZoom();

    }

}

FindMyPlace.prototype.places.zoomOut = function() {

    if (FMP.places.zoom > FMP.places.min_zoom) {

        FMP.places.zoom--;
        FMP.places.refreshZoom();

    }

}

FindMyPlace.prototype.places.refreshZoom = function() {

    document.querySelector('google-map').zoom = FMP.places.zoom;

}

FindMyPlace.prototype.places.updateLocation = function(position){

    var latitude = position.coords.latitude,
        longitude = position.coords.longitude;

    console.log(latitude + " " + longitude);

    if (typeof latitude == "undefined" ||
        typeof longitude == "undefined") {
        return;
    }

    FMP.places.latitude = latitude;
    FMP.places.longitude = longitude;

    FMP.places.refreshLocation();

}

FindMyPlace.prototype.places.initLocation = function(position) {
    
    FMP.places.my_latitude = position.coords.latitude;
    FMP.places.my_longitude = position.coords.longitude;

    FMP.places.updateLocation(position);

};

FindMyPlace.prototype.places.localize = function() {

    /* Geolocation */
    if (navigator.geolocation) {
        console.log("geolocation");
        navigator.geolocation.getCurrentPosition(FMP.places.initLocation);
    } else { 
        console.log('Geolocation is not supported by this browser. IP location is used instead.');
    }

}

FindMyPlace.prototype.places.showPlace = function(id){

    FMP.layout.showModal("place__modal");

    OldWheel.get({ url : "app/controllers/places.php?id=" + id }, function(response){

        var data;

        response = JSON.parse(response);
        data = response.data;

        // fill modal with data
        document.getElementById("place_date").innerHTML = data.created_at;
        document.getElementById("place_address").innerHTML = data.address;
        document.getElementById("place_about").innerHTML = data.about;
        document.getElementById("place_ownership").innerHTML = data.ownership;
        document.getElementById("place_status").innerHTML = data.status;
        if (typeof Page !== "undefined" && Page.userLogged) {
            document.getElementById("project_place_id").value = data.id;
        }

        // save current place_id
        FMP.projects.place_id = data.id;
        
        // fill with projects
        FMP.projects.refresh(data.id);

        // hide loader
        // ...

    });

}

FindMyPlace.prototype.places.updatePlaces = function(response_type, located) {

    var url_ = "app/controllers/places.php?limit=" + this.result_limit + "&" + response_type;

    if (typeof located !== "undefined") {
    
        url_ += "&locate&lat=" + located.lat + "&lon=" + located.lon + "&zoom=" + located.zoom;
    
    }

    OldWheel.get({ url : url_ }, function(response){

        var position = { coords : {} };

        if (response_type == "html") {

            document.getElementById("place_cards").innerHTML = response;

        } else if (response_type == "html_map") {

            document.getElementById("map_wrapper").innerHTML = "";
            document.getElementById("map_wrapper").innerHTML = response;

        } else {

            // json response is default
            console.log(JSON.parse(response));

        }

        //position.coords.latitude = data.latitude;
        //position.coords.longitude = data.longitude;

        //FMP.places.updateLocation(position);

    });

}

FindMyPlace.prototype.places.addMarker = function(place) {

    var map = document.querySelector('google-map'),
        marker = document.createElement('google-map-marker'),
        marker_img = document.createElement('img'),
        marker_info = document.createElement('div'),
        marker_address = document.createElement('p'),
        marker_more = document.createElement('p'),
        marker_more_span = document.createElement('span'),
        position = { coords : {} };

    if (typeof place.latitude == "undefined" ||
        typeof place.longitude == "undefined") {
        console.log("Place coords not defined");
        return;
    }

    OldWheel.setAttributes(marker_img, {
        alt : "place photo",
        class : "marker__pic",
        src : "public/home.svg"
    });

    marker_info.className = "marker__address";
    marker_address.innerHTML = place.address;
    marker_more.className = "marker__more";
    marker_more_span.innerHTML = "More info...";
    marker_more_span.setAttribute("onclick", "FMP.places.showPlace(" + place.id + ")");

    OldWheel.setAttributes(marker, {
        latitude : place.latitude,
        longitude : place.longitude,
        icon : "public/pin_map.svg",
        title : place.address
    });

    marker_more.appendChild(marker_more_span);
    marker_info.appendChild(marker_address);
    marker_info.appendChild(marker_more);
    marker.appendChild(marker_img);
    marker.appendChild(marker_info);

    Polymer.dom(map).appendChild(marker);

    position.coords.latitude = place.latitude;
    position.coords.longitude = place.longitude;

    FMP.places.updateLocation(position);

}

FindMyPlace.prototype.places.search = function(address) {

    var addr = address;
    
    if (typeof address == "undefined" || address == null || address == "") {
        return ;
    }

    OldWheel.get({url : "app/controllers/location.php?address=" + address}, function(response) {
        
        var coords = {};

        console.log(response);

        response = JSON.parse(response);

        if (response.status == "OK") {

            coords.longitude = response.data.lon;
            coords.latitude = response.data.lat;

            FMP.places.updateLocation({ coords : coords });

            var markers = document.getElementsByTagName("google-map-marker"),
                markers_l = markers.length, i, title, 
                address = addr.toLowerCase();

            for (i = 0; i < markers_l; i++) {
                title = markers[i].getAttribute("title");
                if ( title.toLowerCase().search(address) != -1 ) {
                    markers[i].setAttribute("icon", "public/pin_map_orange.svg");
                } else {
                    markers[i].setAttribute("icon", "public/pin_map.svg");
                }
            }

        }

    })

}

FindMyPlace.prototype.places.delete = function(place_id, address) {

    var url_ = "app/controllers/places_logged.php?key=remove&id=" + place_id;

    if (typeof address == "undefined") {
        address = "";
    }

    if (!confirm("You are about to delete this place \n\"" + address + "\"\nAnd all the related projects.")) {
        return ;
    }

    OldWheel.get({url : url_}, function(response){

        console.log(response);

        response = JSON.parse(response);

        if (response.status == "OK") {

            var place_card = document.getElementById("card__" + place_id);
            
            // remove project
            OldWheel.deleteNode(place_card);
        }

    });

}

FindMyPlace.prototype.places.enableMarkersToggle = function() {

    var markers = document.getElementsByTagName("google-map-marker");

    for (var i = 0; i < markers.length; i++) {
        markers[i].addEventListener("click", function(e){

            var button = e.target || e.srcElement;

            FMP.places.closeMarkers();

            button.setAttribute("open","");

        });
    }

}

FindMyPlace.prototype.places.closeMarkers = function() {

    var open_markers = document.getElementsByClassName("iron-selected"),
        open_markers_l = open_markers.length;
    
    for (var i = 0; i < open_markers_l; i++) {
        open_markers[i].removeAttribute("open");
    }

}

FindMyPlace.prototype.projects = {
    container : document.getElementsByClassName("place_projects__list")[0],
    place_id : null
}

FindMyPlace.prototype.projects.render = function(data) {

    // Create project dom element
    var project = document.createElement("div"),
        project_p = document.createElement("p"),
        project_span = document.createElement("span"),
        project_delete = document.createElement("span"),
        project_about = document.createTextNode( OldWheel.decodeHTML(data.about) );

    project.className = "place_project";
    project.setAttribute("id", "project__" + data.id);
    if (data.account == Page.USER_ID) {
        project.className = "place_project my_project";
    }
    project_span.innerHTML = OldWheel.decodeHTML(data.title);
    project_p.appendChild(project_span);
    project_p.appendChild(project_about);
    project_p.setAttribute("id", "project_cont__" + data.id);
    project_delete.innerHTML = "delete";
    project_delete.className = "project_delete__btn";
    project_delete.setAttribute("onclick", "FMP.projects.delete(" + data.id + ")");
    project_delete.setAttribute("data-project", data.id);
    project.appendChild(project_p);
    project.appendChild(project_delete);

    // Append to page dom ( on top of the projects list )
    this.container.insertBefore(project, this.container.childNodes[0]);

}

FindMyPlace.prototype.projects.refresh = function(place_id) {

    var target = this.container;

    OldWheel.get({url : "app/controllers/projects.php?html&place_id=" + place_id}, function(response){

        target.innerHTML = "";
        target.insertAdjacentHTML("beforeend", response);

        FMP.projects.addDeleteListener();

    });

}

FindMyPlace.prototype.projects.submit = function(e) {
    
    var project_title = document.getElementById("project_title"),
        project_about = document.getElementById("project_about"),
        project_place = document.getElementById("project_place_id"),
        title = project_title.value,
        about = project_about.value.replace(/\n/g, "<br />"),
        place_id = project_place.value,
        url_ = "app/controllers/projects_logged.php?key=add&title=" + title +
               "&about=" + about +
               "&place_id=" + place_id;

    if (typeof about == "undefined" ||  about == null || about.trim() == "") {
        return ;
    }

    OldWheel.get({url : url_}, function(response){

        console.log(response);

        response = JSON.parse(response);

        if (response.status == "OK") {

            FMP.projects.render(response.data);

            // reset inputs
            project_title.value = "";
            project_about.value = "";

        }


    });

}

FindMyPlace.prototype.projects.addDeleteListener = function() {

    var delete_buttons = document.getElementsByClassName("project_delete__btn"),
        delete_buttons_l = delete_buttons.length, i;

    for (i = 0; i < delete_buttons_l; i++) {
        
        delete_buttons[i].addEventListener("click", function(e){

            var button = e.target || e.srcElement,
                project_id = button.getAttribute("data-project");

            FMP.projects.delete(project_id);

        });

    }

}

FindMyPlace.prototype.projects.delete = function(id) {

    var project_content_node = document.getElementById("project_cont__" + id),
        place_id = FMP.projects.place_id,
        project_content = project_content_node.textContent.substring(0, 50) + " ...",
        url_ = "app/controllers/projects_logged.php?key=remove&id=" + id + "&place_id=" + place_id;

    if (!confirm("You are about to delete this project \n\"" + project_content + "\"")) {
        return ;
    }

    OldWheel.get({url : url_}, function(response){

        console.log(response);

        response = JSON.parse(response);

        if (response.status == "OK") {

            var project = document.getElementById("project__" + id);
            
            // remove project
            OldWheel.deleteNode(project);
        }

    });

}


/* Initialize FindMyPlace */

if(typeof FMP === "undefined" || FMP === null){ 

    var FMP = new FindMyPlace();

}


window.addEventListener("load", function(){  

    /* Prevent page loading on anchor links */
    OldWheel.lockAnchors();

    /* Funciton only for map interface */
    if (typeof Page !== "undefined" &&
        typeof Page.isMap !== "undefined" && 
        Page.isMap) {

        var place__modal_closer = document.getElementsByClassName("place__modal_close")[0];

        place__modal_closer.addEventListener("click", function(){
            FMP.layout.hideModal("place__modal");
        });

        FMP.places.localize();

        document.querySelector('google-map').removeAttribute("fit-to-markers");

        /* Search event listener */

        // search bar
        document.getElementById("search__input").addEventListener("keydown", function(e){
            var code = (e.keyCode ? e.keyCode : e.which),
                input = document.getElementById("search__input");;
            if(code == 13) {
                FMP.places.search(input.value);
            }
        });
        document.getElementById("search__trigger").addEventListener("click", function(e){ 
            FMP.places.search( document.getElementById("search__input").value );
        });

        // Enable toogle map makers
        FMP.places.enableMarkersToggle();

        // User logged functions
        if (Page.userLogged) {

            console.log("user logged : " + Page.userLogged);

            var project_form = document.getElementById("project_form");
            
            project_form.onsubmit = function(e){

                e.preventDefault();
                
                FMP.projects.submit(e);

                return false;
                
            };

        }

    }

});