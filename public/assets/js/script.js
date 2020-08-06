function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function success(position) {
    const latitude  = position.coords.latitude;
    const longitude = position.coords.longitude;

    client_coordinate = [latitude, longitude];
    console.log(client_coordinate);
    mymap.setView(client_coordinate, 13);
    var circle = L.circle(client_coordinate, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 150
    }).addTo(mymap);
    circle.bindPopup("Itt vagyok!");
}

function error() {
    status.textContent = 'Unable to retrieve your location';
}

(function() {

    if (!navigator.geolocation) {
        //status.textContent = 'Geolocation is not supported by your browser';
    } else {
        //status.textContent = 'Locating…';
        navigator.geolocation.getCurrentPosition(success, error);
    }



    var search_button = document.querySelector("#search");
    if(search_button != null) {

        search_button.addEventListener("click", function (e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            var data = new FormData();
            data.name = document.querySelector(".filter.name").value.trim();
            data.district = document.querySelector(".filter.district").value.trim();

            document.querySelector(".home .modal-search").classList.add("is-active");
            document.querySelector("html").classList.add("is-clipped");


            fetch("/search_eating_houses", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                if(json.success == 1) {

                    document.querySelector(".home .modal-search").classList.remove("is-active");
                    document.querySelector("html").classList.remove("is-clipped");

                    //document.querySelector(".eating-houses-list").innerHTML = "";

                    var html =``;

                    if(json.list.length == 0) {

                        html += `<div class="animated fadeIn has-text-centered">Sajnos nincs találat.</div>`;

                    } else {



                        for(var i = 0; i < json.list.length; i++) {

                            html += `<div class="level animated fadeIn">
                            <div class="level-left">
                                <div class="eating-house-title level-item"><i class="fas fa-home"></i>&nbsp;${json.list[i].name}</div>
                            </div>
                            <div class="level-right">
                                <div class="eating-house-contact"><i class="fas fa-phone"></i>&nbsp;${json.list[i].phone} / <i class="fas fa-globe"></i>&nbsp;<a href="${json.list[i].website}" target="_blank">${json.list[i].website}</a></div>
                            </div>
                        </div>
                        <div>
                            ${json.list[i].introduction}
                        </div>
                        <hr>`

                        }
                    }
console.log(app.houses);
                    app.houses = json.list;
                    console.log(app.houses);

                    //document.querySelector(".eating-houses-list").innerHTML = html;
                }
            });

        });
    }

    var google_input = document.querySelector("#google_input");
    if(google_input != null) {
        google_input.focus();
        document.querySelector(".google-input-container").classList.add("shadow");
        var texts = [
            "kifőzde zugló",
            "kifőzde terézváros",
            "kifőzde x kerület",
            "kifőzde budapest"
        ];

        var texts_length = texts.length;
        var current_text = 0;
        var time = 300;
        var loop = 0;

        var random;
        var random_text;
        var random_text_length;
        var string;
        var built_string = "";


        var write_text = function() {

            if(built_string == random_text || loop == 0) {


                random = randomIntFromInterval(0, texts_length - 1);
                //console.log(random);
                random_text = texts[random];
                random_text_length = random_text.length;
                built_string = "";
                loop++;
                current_text = 0;
                google_input.value = "";
            }

            var character = random_text[current_text];
            built_string += character;

            if (character == "x") {
                character = randomIntFromInterval(1, 23) + ".";
            }

            google_input.value += character;

            current_text++;
            google_input.setAttribute('value', google_input.value);
            setTimeout(function(){write_text()}, time);

        };

        setTimeout(function(){write_text()}, time);
    }


    var subscriptions = [];


    document.addEventListener("click", function(event) {

        if(event.target.classList.contains("subscribe")) {

            if (event.target.closest(".eating-house-details").classList.contains("selected")) {
                event.target.closest(".eating-house-details").classList.remove("selected");

                if (subscriptions.indexOf(event.target.dataset.id) >= 0) {
                    subscriptions[subscriptions.indexOf(event.target.dataset.id)] = null;
                    if (subscriptions.length == 1) {
                        subscriptions = [];
                    }

                }

            } else {
                event.target.closest(".eating-house-details").classList.add("selected");
                subscriptions.push(event.target.dataset.id);
            }

            //console.log(subscriptions);

            if (subscriptions.length > 0) {
                document.querySelector(".subscription-box").style.display = "block";
            } else {
                document.querySelector(".subscription-box").style.display = "none";
            }
        }

    });


    var subscribe_btn = document.getElementById("subscribe");
    if(subscribe_btn != null) {
        subscribe_btn.addEventListener("click", function(event) {

            var email = document.getElementById("subscriber_email").value.trim();
            var data = new FormData();
            data.email = email;
            data.subscribed_houses = subscriptions;

            fetch("/subscribe", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                if(json.success == 1) {

                    document.querySelector(".home .modal-subscribed").classList.add("is-active");
                    document.querySelector("html").classList.add("is-clipped");

                    setTimeout(function() {

                        document.getElementById("subscriber_email").value = "";

                        document.querySelector(".home .modal-subscribed").classList.remove("is-active");
                        document.querySelector("html").classList.remove("is-clipped");

                    }, 2000);
                }

            });
        });
    }


})();