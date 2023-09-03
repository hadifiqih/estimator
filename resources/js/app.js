import './bootstrap';
import '../css/app.css';

import * as PusherPushNotifications from "@pusher/push-notifications-web";

    const beamsClient = new PusherPushNotifications.Client({
      instanceId: '0958376f-0b36-4f59-adae-c1e55ff3b848',
    });

    beamsClient.start()
        .then((beamsClient) => beamsClient.getDeviceId())
        .then((deviceId) => console.log("Successfully registered with Beams. Device ID:", deviceId))

        .then(() => beamsClient.addDeviceInterest("hello"))
        .then(() => beamsClient.getDeviceInterests())
        .then((interests) => console.log("Current interests:", interests))
        .catch(console.error);

// const channel = window.Echo.channel('global');

// channel.subscribed(() => {
//     console.log('Subscribed to channel: global');
// }).listen('.global', (e) => {
//     console.log(e);
// });

// //Initialize Firebase
// import { initializeApp } from 'firebase/app';
// import { getMessaging } from "firebase/messaging";

// // TODO: Replace the following with your app's Firebase project configuration
// const firebaseConfig = {
//     apiKey: "AIzaSyD-hClzHRiMyWjWdn-xoHXQYk4BGJYqiCM",
//     authDomain: "antree-apps.firebaseapp.com",
//     projectId: "antree-apps",
//     storageBucket: "antree-apps.appspot.com",
//     messagingSenderId: "689952355755",
//     appId: "1:689952355755:web:61f40fd484d1a1dbb54eee",
//     measurementId: "G-G9QKV6Q8JQ"
// };

// const app = initializeApp(firebaseConfig);
// const messaging = getMessaging(app);

// const form = document.getElementById('myForm');
// const inputMessage = document.getElementById('input-messages');
// const listMessage = document.getElementById('list-messages');
// form.addEventListener('submit', (e) => {
//     e.preventDefault();
//     const message = inputMessage.value;
//     axios.post('/messages', {
//         message : message
//     }).then((response) => {
//         console.log(response);
//         inputMessage.value = '';
//     }).catch((error) => {
//         console.log(error);
//     });
// });

// const channel = window.Echo.channel('messages');

// channel.subscribed(() => {
//     console.log('Subscribed to channel: messages');
// }).listen('.pesan', (e) => {
//     console.log(e);
//     // //menampilkan li pada listMessage
//     // const li = document.createElement('li');
//     // li.classList.add('list-group-item');
//     // li.innerHTML = `
//     //     <p class="text-primary">${e.message}</p>
//     // `;
//     // listMessage.append(li);

//     //Menampilkan notifikasi sweetalert2 toastr
//     const Toast = Swal.mixin({
//         toast: true,
//         position: 'top-end',
//         showConfirmButton: false,
//         timer: 6000,
//         timerProgressBar: true,
//     })
//     Toast.fire({
//         icon: 'info',
//         title: 'Antrian baru telah ditambahkan, Cek Sekarang!'
//     });
// });
