import L from 'leaflet';

const map = L.map('map').setView([14.6349, -90.5069], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

L.marker([14.6349, -90.5069]).addTo(map)
    .bindPopup('Brigada de Comunicaciones')
    .openPopup();