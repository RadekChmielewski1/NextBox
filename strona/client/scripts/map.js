var map = L.map('map').setView([50.28850, 18.67775], 18);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var marker = L.marker([50.28856954857069, 18.677403656507277]).addTo(map);
marker.bindPopup("<h4>NextBox GLI0123</h4></br><img src='https://euslugi.polsl.pl/Dokument/Find/52b053b4-31bf-4fbd-975e-2e98d489196f/System-identyfikacji-wizualnej--SIW-PS-/Logo/Wersja-polska--kolorowe-/politechnika_sl_logo_pion_inwersja_pl_rgb.jpg' height='64px' width='64px'><p>Akademicka 16</p>");