$(document).ready(function() {
    var map = L.map('mapid').setView([8.176, 77.434], 13); // Set the initial view

    // Add a tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Function to add markers for specified areas
    function addMarkers(specifiedAreas) {
        // Clear existing markers
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                map.removeLayer(layer);
            }
        });

        // Add new markers based on the data
        specifiedAreas.forEach(function(area) {
            var markerColor = area.product_received === 'No' ? '#ff0000' : '#008000'; // Red if product not received, Green otherwise
            var marker = L.marker([parseFloat(area.latitude), parseFloat(area.longitude)], {
                icon: L.divIcon({
                    className: 'custom-icon',
                    html: '<div style="background-color: ' + markerColor + '; display: flex; justify-content: center; align-items: center; width: 30px; height: 30px; border-radius: 50%; border: 2px solid #000;">' + area.count + '</div>'
                })
            }).addTo(map);
            marker.bindPopup('<div class="custom-popup"><b>' + area.area + '</b><p>Non-Ration Buyers: ' + area.count + '</p></div>');
            marker.on('mouseover', function() {
                this.openPopup();
            });
            marker.on('mouseout', function() {
                this.closePopup();
            });
        });
    }

    // Fetch data from dashboard.php and add markers
    $.ajax({
        url: '../php/dashboard.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                addMarkers(response);
            } else {
                console.log('No data available');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
});
