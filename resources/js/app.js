import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

let channel = Echo.private(`App.Models.User.${userID}`);
channel.notification(function (data) {
    console.log(data);
    alert(data.body);
    // alert(JSON.stringify(data));
});
