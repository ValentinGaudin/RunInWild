if (window.location.href === 'http://localhost:8000/') {
  localStorage.setItem('turn', 0);
}

const localTurn = +localStorage.getItem('turn');
let turn = localTurn;

const recordButton = document.getElementById('record-button');
const targetSpeedInput = document.getElementById('target-speed');

const min = 6000;
const date = new Date();
date1 = date.setMinutes(date.getMinutes() - 30);
date2 = date1 - 0.4 * min;
date3 = date2 - 0.55 * min;
date4 = date3 - 0.8 * min;
date5 = date4 - 0.6 * min;
date6 = date5 - 0.4 * min;
date7 = date6 - 0.4 * min;
date8 = date7 - 0.45 * min;
date9 = date8 - 0.5 * min;
date10 = date9 - 0.6 * min;
date11 = date10 - 0.55 * min;
date12 = date11 - 0.5 * min;
date13 = date12 - 0.45 * min;
date14 = date13 - 0.4 * min;
date15 = date14 - 0.35 * min;
const records = [
  [45.74884, 4.80716, date1],
  [45.75179, 4.808834, date2],
  [45.755323, 4.810808, date3],
  [45.751131, 4.817953, date4],
  [45.755323, 4.810808, date5],
  [45.75179, 4.808834, date6],
  [45.74884, 4.80716, date7],
  [45.75179, 4.808834, date8],
  [45.74884, 4.80716, date9],
  [45.75179, 4.808834, date10],
  [45.74884, 4.80716, date11],
  [45.75179, 4.808834, date12],
  [45.74884, 4.80716, date13],
  [45.75179, 4.808834, date14],
  [45.74884, 4.80716, date15],
];

let targetSpeed = targetSpeedInput.value ? targetSpeedInput.value : 6;
const acceptedValuesBpm = [4, 5, 6, 7, 8, 9, 10, 11];

document.addEventListener('DOMContentLoaded', (event) => {
  if ('geolocation' in navigator) {
    targetSpeedInput.addEventListener('change', (e) =>
      handleChangeTargetSpeed(e, acceptedValuesBpm)
    );

    recordButton.addEventListener('click', (e) =>
      handleClickRecordButton(e, records)
    );
  } else {
  }
});

function handleClickRecordButton(e, records) {
  e.preventDefault();
  navigator.geolocation.getCurrentPosition(retrievePosition);
}

function handleChangeTargetSpeed(e, acceptedValuesBpm) {
  const targetValue = +e.target.value;

  if (acceptedValuesBpm.includes(targetValue)) {
    targetSpeed = targetValue;
  } else if (targetValue < 4) {
    targetSpeed = 4;
  } else if (targetValue > 11) {
    targetSpeed = 11;
  }
}

function retrievePosition(position) {
  const latitude = position.coords.latitude;
  const longitude = position.coords.longitude;
  const now = new Date();

  records.push([latitude, longitude, now]);

  if (records.length > 1) {
    const lastSpeed = getLastSpeed(records);
    const speedInMinPerKm = getMinPerKm(lastSpeed);
    localStorage.setItem('turn', ++turn);

    let targetBpm = undefined;

    if (speedInMinPerKm > targetSpeed) {
      targetBpm = 120 + (speedInMinPerKm - targetSpeed) * 10;
    } else if (speedInMinPerKm < targetSpeed) {
      targetBpm = 120;
    } else {
      targetBpm = 120;
    }

    window.location.href = `/spotify/change?target=${targetSpeed}&bpm=${targetBpm}&actual=${speedInMinPerKm}`;
  }
}

function getSpeedFromTwoRecords(record1, record2) {
  let distance = getDistanceFromLatLonInKm(
    record1[0],
    record1[1],
    record2[0],
    record2[1]
  );
  distance = distance === 0 ? 1 : distance;

  const interval = (record1[2] - record2[2]) / (1000 * 60); // interval in hours

  return distance / interval;
}

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2 - lat1); // deg2rad below
  var dLon = deg2rad(lon2 - lon1);
  var a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(deg2rad(lat1)) *
      Math.cos(deg2rad(lat2)) *
      Math.sin(dLon / 2) *
      Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c; // Distance in km
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI / 180);
}

function getLastSpeed(records) {
  speedInKmPerHour = getSpeedFromTwoRecords(
    records[0 + turn],
    records[1 + turn]
  );
  return speedInKmPerHour.toFixed(3);
}

function getMinPerKm(kmPerHour) {
  const speed = 60 / kmPerHour;

  return Math.round(speed);
}
