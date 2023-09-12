import './bootstrap';
import '../css/app.css';
import Swal from 'sweetalert2';

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


