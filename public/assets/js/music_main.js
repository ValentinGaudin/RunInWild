const recordButton = document.getElementById('record-button');
const targetSpeedInput = document.getElementById('target-speed');

const date = new Date();
date.setMinutes(date.getMinutes() - 30);
const records = [[45.74884, 4.80716, date]];

let targetSpeed = targetSpeedInput.value ? targetSpeedInput.value : 120;
const acceptedValuesBpm = [120, 130, 140, 150, 160, 170, 180, 190];

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

  console.log(records);
}

function handleChangeTargetSpeed(e, acceptedValuesBpm) {
  const targetValue = +e.target.value;

  if (acceptedValuesBpm.includes(targetValue)) {
    targetSpeed = targetValue;
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

    const lastSpeedDisplay = document.getElementById('last-speed');
    lastSpeedDisplay.innerHTML = `${speedInMinPerKm} min/km`;

    const currentBpm = Math.round(speedInMinPerKm * 20);

    let targetBpm = undefined;

    if (currentBpm < 130) {
      targetBpm = 120;
    } else if (currentBpm > 180) {
      targetBpm = 190;
    } else if (currentBpm < targetSpeed) {
      targetBpm = currentBpm + 10;
    } else if (currentBpm > targetSpeed) {
      targetBpm = currentBpm - 10;
    } else {
      targetBpm = currentBpm;
    }

    window.location.href = `/spotify/change?target=${targetSpeed}&bpm=${targetBpm}&actual=${currentBpm}`;
  }
}

function getSpeedFromTwoRecords(record1, record2) {
  let distance = getDistanceFromLatLonInKm(
    record1[0],
    record1[1],
    record2[0],
    record2[1]
  );
  distance = distance === 0 ? 0.1 : distance;

  const interval = (record2[2] - record1[2]) / (1000 * 60 * 60); // interval in hours

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
    records[records.length - 2],
    records[records.length - 1]
  );

  return Math.round(speedInKmPerHour);
}

function getMinPerKm(kmPerHour) {
  const speed = 60 / kmPerHour;
  return Math.ceil(speed);
}
