(function() {


    /*
        ADMIN LOGIN
    */
    var admin_login = document.querySelector(".admin-login");
    if(admin_login != null) {

        admin_login.addEventListener("click", function(e) {

            e.stopImmediatePropagation();
            e.preventDefault();

            var email = document.querySelector("#admin_login_email").value.trim();
            var password = document.querySelector("#admin_login_password").value.trim();

            var data = new FormData();
            data.email = email;
            data.password = password;

            fetch("/admin/login", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                document.querySelector(".message").classList.remove("is-warning");
                document.querySelector(".message").classList.remove("is-success");

                document.querySelector(".message").classList.add("is-" + json.class);
                document.querySelector(".message-header p").innerText = json.title;
                document.querySelector(".message-body").innerText = json.message;
                document.querySelector(".message").style.display = "block";

                if(json.success == 1) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                }
            });
        });
    }

    /*
     ADMIN SIGNUP
     */
    var admin_signup = document.querySelector(".admin-signup");
    if(admin_signup != null) {

        admin_signup.addEventListener("click", function(e) {

            e.stopImmediatePropagation();
            e.preventDefault();


            var email = document.querySelector("#admin_login_email").value.trim();
            var password = document.querySelector("#admin_login_password").value.trim();
            var admin_pw_again = document.getElementById("admin_signup_password");
            var password_again = admin_pw_again.value.trim();
            var username = document.getElementById("admin_signup_username").value.trim();

            if(document.querySelector(".field.signup").style.display != "none" && document.querySelector(".field.signup").style.display != "") {

                var data = new FormData();
                data.email = email;
                data.password = password;
                data.password_again = password_again;
                data.username = username;

                fetch("/admin/signup", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then( (response) => { return response.json() } ).then( json => {

                    document.querySelector(".message").classList.remove("is-warning");
                    document.querySelector(".message").classList.remove("is-success");

                    document.querySelector(".message").classList.add("is-" + json.class);
                    document.querySelector(".message-header p").innerText = json.title;
                    document.querySelector(".message-body").innerText = json.message;
                    document.querySelector(".message").style.display = "block";

                    if(json.success == 1) {
                        setTimeout(function() {
                            window.location.assign("https://localhost:8000/admin");
                        }, 1500);
                    }
                });

            } else {

                var signup_fields = document.querySelectorAll(".field.signup");
                for(var x = 0; x < signup_fields.length; x++) {
                    signup_fields[x].style.display = "block";
                    signup_fields[x].classList.add("animated", "fadeIn");
                }

                admin_signup.innerText = "Regisztrálás";
            }



        });
    }

    /*
     ADMIN EATING HOUSE DATA SAVE
     */
    var data_save = document.querySelector(".eating-house-data .data-save");
    if(data_save != null) {

        data_save.addEventListener("click", function(e) {

            e.stopImmediatePropagation();
            e.preventDefault();

            var container = ".eating-house-data";
            var inputs_error = document.querySelectorAll(container + " .is-danger");
            for(var y = 0; y < inputs_error.length; y++) {
                inputs_error[y].classList.remove("is-danger");
            }


            var name = document.querySelector(container + " #name").value.trim();
            var contact = document.querySelector(container + " #contact").value.trim();
            var website = document.querySelector(container + " #website").value.trim();
            var phone = document.querySelector(container + " #phone").value.trim();
            var zip = document.querySelector(container + " #zip").value.trim();
            var district = document.querySelector(container + " #district").value.trim();
            var address = document.querySelector(container + " #address").value.trim();
            var introduction = document.querySelector(container + " #introduction").value.trim();

            if(name.length == 0) {
                document.querySelector(container + " #name").classList.add("is-danger");
                return false;
            }

            if(contact.length == 0) {
                document.querySelector(container + " #contact").classList.add("is-danger");
                return false;
            }

            if(zip.length == 0) {
                document.querySelector(container + " #zip").classList.add("is-danger");
                return false;
            }

            if(district < 1 || district > 23) {
                document.querySelector(container + " #district").classList.add("is-danger");
                return false;
            }

            if(address.length == 0) {
                document.querySelector(container + " #address").classList.add("is-danger");
                return false;
            }

            if(phone.length == 0) {
                document.querySelector(container + " #phone").classList.add("is-danger");
                return false;
            }

            var data = new FormData();
            data.name = name;
            data.contact = contact;
            data.website = website;
            data.zip = zip;
            data.district = district;
            data.address = address;
            data.introduction = introduction;
            data.phone = phone;

            fetch("/admin/eating_house", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                document.querySelector(".message").classList.remove("is-warning");
                document.querySelector(".message").classList.remove("is-success");

                document.querySelector(".message").classList.add("is-" + json.class);
                document.querySelector(".message-header p").innerText = json.title;
                document.querySelector(".message-body").innerText = json.message;
                document.querySelector(".message").style.display = "block";

                if(json.success == 1) {
                    setTimeout(function() {
                        window.location.assign("https://localhost:8000/admin");
                    }, 1500);
                } else {

                    for(var x = 0; x < json.info.length; x++) {
                        document.querySelector(container + " #" + json.info[x]).classList.add("is-danger");
                    }
                }
            });
        });
    }

    /*
     ADMIN EATING HOUSE DATA SAVE ***NEW***
     */
    var data_save_new = document.querySelector(".eating-house-data .data-save-new");
    if(data_save_new != null) {

        data_save_new.addEventListener("click", function(e) {

            e.stopImmediatePropagation();
            e.preventDefault();

            var container = ".eating-house-data";
            var inputs_error = document.querySelectorAll(container + " .is-danger");
            for(var y = 0; y < inputs_error.length; y++) {
                inputs_error[y].classList.remove("is-danger");
            }


            var name = document.querySelector(container + " #name").value.trim();
            var contact = document.querySelector(container + " #contact").value.trim();
            var website = document.querySelector(container + " #website").value.trim();
            var phone = document.querySelector(container + " #phone").value.trim();
            var zip = document.querySelector(container + " #zip").value.trim();
            var district = document.querySelector(container + " #district").value.trim();
            var address = document.querySelector(container + " #address").value.trim();
            var introduction = document.querySelector(container + " #introduction").value.trim();

            if(name.length == 0) {
                document.querySelector(container + " #name").classList.add("is-danger");
                return false;
            }

            if(contact.length == 0) {
                document.querySelector(container + " #contact").classList.add("is-danger");
                return false;
            }

            if(zip.length == 0) {
                document.querySelector(container + " #zip").classList.add("is-danger");
                return false;
            }

            if(district < 1 || district > 23) {
                document.querySelector(container + " #district").classList.add("is-danger");
                return false;
            }

            if(address.length == 0) {
                document.querySelector(container + " #address").classList.add("is-danger");
                return false;
            }

            if(phone.length == 0) {
                document.querySelector(container + " #phone").classList.add("is-danger");
                return false;
            }

            var data = new FormData();
            data.name = name;
            data.contact = contact;
            data.website = website;
            data.zip = zip;
            data.district = district;
            data.address = address;
            data.introduction = introduction;
            data.phone = phone;

            fetch("/admin/new_eating_house", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                document.querySelector(".message").classList.remove("is-warning");
                document.querySelector(".message").classList.remove("is-success");

                document.querySelector(".message").classList.add("is-" + json.class);
                document.querySelector(".message-header p").innerText = json.title;
                document.querySelector(".message-body").innerText = json.message;
                document.querySelector(".message").style.display = "block";

                if(json.success == 1) {
                    setTimeout(function() {
                        window.location.assign("https://localhost:8000/admin");
                    }, 1500);
                } else {

                    for(var x = 0; x < json.info.length; x++) {
                        document.querySelector(container + " #" + json.info[x]).classList.add("is-danger");
                    }
                }
            });
        });
    }

    var menu_day_save = document.querySelector(".save-menu-day");

    if(menu_day_save != null) {
        menu_day_save.addEventListener("click", function(e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            document.querySelector(".day-menu-change #soup").classList.remove("is-danger");
            document.querySelector(".day-menu-change #main_dish").classList.remove("is-danger");

            var date = this.dataset.date;
            var soup = document.querySelector("#soup").value.trim();
            var main_dish = document.querySelector("#main_dish").value.trim();
            var dessert = document.querySelector("#dessert").value.trim();

            if(soup.length == 0 && main_dish.length == 0) {
                document.querySelector(".day-menu-change #soup").classList.add("is-danger");
                document.querySelector(".day-menu-change #main_dish").classList.add("is-danger");
                return false;
            }

            var data = new FormData();
            data.date = date;
            data.soup = soup;
            data.main_dish = main_dish;
            data.dessert = dessert;

            fetch("/admin/menu/" + date, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                document.querySelector(".message").classList.remove("is-warning");
                document.querySelector(".message").classList.remove("is-success");

                document.querySelector(".message").classList.add("is-" + json.class);
                document.querySelector(".message-header p").innerText = json.title;
                document.querySelector(".message-body").innerText = json.message;
                document.querySelector(".message").style.display = "block";

                if(json.success == 1) {
                    setTimeout(function() {
                        window.location.assign("https://localhost:8000/admin/menu");
                    }, 1500);
                } else {

                }
            });
        });
    }

    var save_menu_new_week = document.querySelector("#save-new-menu");

    if(save_menu_new_week != null) {
        save_menu_new_week.addEventListener("click", function (e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            var soups = document.querySelectorAll('.admin-new-menu .input.soup');
            var dishes = document.querySelectorAll('.admin-new-menu .input.maindish');

            var soups_count = 0;
            var dishes_count = 0;

            for(var k = 0; k < soups.length; k++) {
                if(soups[k].value.length > 0) {
                    soups_count++;
                }
            }

            for(var k = 0; k < dishes.length; k++) {
                if(dishes[k].value.length > 0) {
                    dishes_count++;
                }
            }

            if(soups_count < 5 && dishes_count < 5) {

                document.querySelector(".admin-new-menu .modal").classList.add("is-active");
                document.querySelector("html").classList.add("is-clipped");
                return false;
            }

            var soups_temp = [];
            var soups_temp_obj = {};
            var dishes_temp = [];
            var desserts_temp = [];

            var data = {};

            var soups = document.querySelectorAll(".admin-new-menu .input.soup");

            for(var i = 0; i < soups.length; i++) {

                var temp = {};
                temp.date = soups[i].dataset.date;
                temp.name = soups[i].value.trim();

                soups_temp.push(temp);
            }

            data.soups = soups_temp;

            var dishes = document.querySelectorAll(".admin-new-menu .input.maindish");

            for(var i = 0; i < dishes.length; i++) {
                //dishes_temp.push(dishes[i]);
                //dishes_temp[dishes[i].dataset.date] = dishes[i].value.trim();

                var temp = {};
                temp.date = dishes[i].dataset.date;
                temp.name = dishes[i].value.trim();

                dishes_temp.push(temp);
            }
            data.maindishes = dishes_temp;

            var desserts = document.querySelectorAll(".admin-new-menu .input.dessert");

            for(var i = 0; i < desserts.length; i++) {
                //desserts_temp[desserts[i].dataset.date] = desserts[i].value.trim();
                //desserts_temp.push(desserts[i]);

                var temp = {};
                temp.date = desserts[i].dataset.date;
                temp.name = desserts[i].value.trim();

                desserts_temp.push(temp);
            }
            data.desserts = desserts_temp;

            console.log(data);
            console.log(JSON.stringify(data));

            //return false;

            fetch("/admin/new_menu", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                document.querySelector(".message").classList.remove("is-warning");
                document.querySelector(".message").classList.remove("is-success");

                document.querySelector(".message").classList.add("is-" + json.class);
                document.querySelector(".message-header p").innerText = json.title;
                document.querySelector(".message-body").innerText = json.message;
                document.querySelector(".message").style.display = "block";

                if(json.success == 1) {
                    setTimeout(function() {
                        window.location.assign("https://localhost:8000/admin/menu");
                    }, 1500);
                } else {

                }
            });
        });
    }


    var save_new_menu_confirm = document.querySelector("#save_new_menu_confirm");

    if(save_new_menu_confirm != null) {
        save_new_menu_confirm.addEventListener("click", function (e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            var soup = document.querySelector("#soup").value.trim();
            var main_dish = document.querySelector("#main_dish").value.trim();
            var dessert = document.querySelector("#dessert").value.trim();

            if(soup.length == 0 && main_dish.length == 0) {
                document.querySelector(".day-menu-change #soup").classList.add("is-danger");
                document.querySelector(".day-menu-change #main_dish").classList.add("is-danger");
                return false;
            }

            var soups_temp = [];
            var dishes_temp = [];
            var desserts_temp = [];

            var data = new FormData();

            var soups = document.querySelectorAll(".admin-new-menu .input.soup");

            for(var i = 0; i < soups.length; i++) {
                soups_temp[soups[i].getAttribute("name")] = soups[i].value.trim();
            }
            data.soups = soups_temp;

            var dishes = document.querySelectorAll(".admin-new-menu .input.maindish");

            for(var i = 0; i < dishes.length; i++) {
                //dishes_temp.push(dishes[i]);
                dishes_temp[dishes[i].getAttribute("name")] = dishes[i].value.trim();
            }
            data.maindishes = dishes_temp;

            var desserts = document.querySelectorAll(".admin-new-menu .input.dessert");

            for(var i = 0; i < desserts.length; i++) {
                desserts_temp[desserts[i].getAttribute("name")] = desserts[i].value.trim();
                //desserts_temp.push(desserts[i]);
            }
            data.desserts = desserts_temp;

            fetch("/admin/new_menu", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then( (response) => { return response.json() } ).then( json => {

                document.querySelector(".message").classList.remove("is-warning");
                document.querySelector(".message").classList.remove("is-success");

                document.querySelector(".message").classList.add("is-" + json.class);
                document.querySelector(".message-header p").innerText = json.title;
                document.querySelector(".message-body").innerText = json.message;
                document.querySelector(".message").style.display = "block";

                if(json.success == 1) {
                    setTimeout(function() {
                        window.location.assign("https://localhost:8000/admin/menu");
                    }, 1500);
                } else {

                }
            });
        });
    }


    var save_new_menu_cancel = document.querySelector("#save_new_menu_cancel");

    if(save_new_menu_cancel != null) {
        save_new_menu_cancel.addEventListener("click", function (e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            document.querySelector(".admin-new-menu .modal").classList.remove("is-active");
            document.querySelector("html").classList.remove("is-clipped");
        });
    }
})();