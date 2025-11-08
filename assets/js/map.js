/**
 * LEAFLET MAP IMPLEMENTATION - AUTO SERVIS
 * Interaktivna mapa sa lokacijama servisa
 */

// Provera da li Leaflet postoji
if (typeof L === 'undefined') {
  console.error('Leaflet library nije učitana!');
}

// Čekaj da se DOM učita
document.addEventListener('DOMContentLoaded', () => {
  
  // Proveri da li postoji map container
  const mapContainer = document.getElementById('map');
  if (!mapContainer) {
    console.warn('Map container (#map) nije pronađen');
    return;
  }

  // ========== LOKACIJE AUTO SERVISA ==========
  const locations = [
    // Beograd - 3 lokacije
    {
      name: "Auto Servis Premium Centar",
      address: "Knez Mihailova 15",
      city: "Beograd",
      phone: "+381 11 123 4567",
      email: "beograd.centar@autoservis.rs",
      lat: 44.8176,
      lng: 20.4564,
      services: ["Servis", "Tuning", "Dijagnostika", "Karoserija"]
    },
    {
      name: "Auto Servis Novi Beograd",
      address: "Bulevar Mihajla Pupina 115",
      city: "Beograd",
      phone: "+381 11 234 5678",
      email: "novi.beograd@autoservis.rs",
      lat: 44.8125,
      lng: 20.4145,
      services: ["Servis", "Gume", "Klima", "Auto Elektrika"]
    },
    {
      name: "Auto Servis Zemun",
      address: "Glavna 45",
      city: "Beograd - Zemun",
      phone: "+381 11 345 6789",
      email: "zemun@autoservis.rs",
      lat: 44.8473,
      lng: 20.4037,
      services: ["Servis", "Dijagnostika", "Limarija"]
    },
    
    // Novi Sad - 2 lokacije
    {
      name: "Auto Servis Novi Sad",
      address: "Bulevar Oslobođenja 100",
      city: "Novi Sad",
      phone: "+381 21 123 4567",
      email: "novisad@autoservis.rs",
      lat: 45.2671,
      lng: 19.8335,
      services: ["Servis", "Tuning", "Dijagnostika"]
    },
    {
      name: "Auto Servis Petrovaradin",
      address: "Preradovićeva 25",
      city: "Novi Sad - Petrovaradin",
      phone: "+381 21 234 5678",
      email: "petrovaradin@autoservis.rs",
      lat: 45.2514,
      lng: 19.8659,
      services: ["Servis", "Gume", "Ulja"]
    },
    
    // Niš - 2 lokacije
    {
      name: "Auto Servis Niš",
      address: "Obrenovićeva 25",
      city: "Niš",
      phone: "+381 18 123 4567",
      email: "nis@autoservis.rs",
      lat: 43.3209,
      lng: 21.8954,
      services: ["Servis", "Tuning", "Auto Stakla"]
    },
    {
      name: "Auto Servis Niška Banja",
      address: "Sindjelićeva 10",
      city: "Niška Banja",
      phone: "+381 18 234 5678",
      email: "niska.banja@autoservis.rs",
      lat: 43.2833,
      lng: 21.9500,
      services: ["Servis", "Dijagnostika"]
    },
    
    // Ostali gradovi
    {
      name: "Auto Servis Kragujevac",
      address: "King Cross Shopping Centar",
      city: "Kragujevac",
      phone: "+381 34 123 4567",
      email: "kragujevac@autoservis.rs",
      lat: 44.0122,
      lng: 20.9394,
      services: ["Servis", "Tuning", "Dijagnostika", "Karoserija"]
    },
    {
      name: "Auto Servis Subotica",
      address: "Korzo 15",
      city: "Subotica",
      phone: "+381 24 123 4567",
      email: "subotica@autoservis.rs",
      lat: 46.1005,
      lng: 19.6672,
      services: ["Servis", "Gume", "Klima"]
    },
    {
      name: "Auto Servis Zrenjanin",
      address: "Trg Slobode 3",
      city: "Zrenjanin",
      phone: "+381 23 123 4567",
      email: "zrenjanin@autoservis.rs",
      lat: 45.3833,
      lng: 20.3833,
      services: ["Servis", "Dijagnostika"]
    },
    {
      name: "Auto Servis Pančevo",
      address: "Njegoševa 20",
      city: "Pančevo",
      phone: "+381 13 123 4567",
      email: "pancevo@autoservis.rs",
      lat: 44.8704,
      lng: 20.6509,
      services: ["Servis", "Auto Elektrika"]
    },
    {
      name: "Auto Servis Čačak",
      address: "Gradsko šetalište 10",
      city: "Čačak",
      phone: "+381 32 123 4567",
      email: "cacak@autoservis.rs",
      lat: 43.8911,
      lng: 20.3497,
      services: ["Servis", "Tuning", "Limarija"]
    },
    {
      name: "Auto Servis Kraljevo",
      address: "Trg Srpskih Ratnika 5",
      city: "Kraljevo",
      phone: "+381 36 123 4567",
      email: "kraljevo@autoservis.rs",
      lat: 43.7250,
      lng: 20.6869,
      services: ["Servis", "Dijagnostika"]
    },
    {
      name: "Auto Servis Leskovac",
      address: "Carigradska 50",
      city: "Leskovac",
      phone: "+381 16 123 4567",
      email: "leskovac@autoservis.rs",
      lat: 42.9981,
      lng: 21.9461,
      services: ["Servis", "Gume"]
    },
    {
      name: "Auto Servis Smederevo",
      address: "Karađorđeva 25",
      city: "Smederevo",
      phone: "+381 26 123 4567",
      email: "smederevo@autoservis.rs",
      lat: 44.6631,
      lng: 20.9300,
      services: ["Servis", "Auto Stakla"]
    },
    {
      name: "Auto Servis Valjevo",
      address: "Karađorđeva 1",
      city: "Valjevo",
      phone: "+381 14 123 4567",
      email: "valjevo@autoservis.rs",
      lat: 44.2750,
      lng: 19.8900,
      services: ["Servis", "Tuning"]
    },
    {
      name: "Auto Servis Užice",
      address: "Dimitrija Tucovića 52",
      city: "Užice",
      phone: "+381 31 123 4567",
      email: "uzice@autoservis.rs",
      lat: 43.8597,
      lng: 19.8489,
      services: ["Servis", "Dijagnostika", "Karoserija"]
    },
    {
      name: "Auto Servis Šabac",
      address: "Masarikova 10",
      city: "Šabac",
      phone: "+381 15 123 4567",
      email: "sabac@autoservis.rs",
      lat: 44.7566,
      lng: 19.6908,
      services: ["Servis", "Gume", "Klima"]
    },
    {
      name: "Auto Servis Požarevac",
      address: "Dušanova 5",
      city: "Požarevac",
      phone: "+381 12 123 4567",
      email: "pozarevac@autoservis.rs",
      lat: 44.6220,
      lng: 21.1858,
      services: ["Servis", "Auto Elektrika"]
    },
    {
      name: "Auto Servis Pirot",
      address: "Srpskih Vladara 25",
      city: "Pirot",
      phone: "+381 10 123 4567",
      email: "pirot@autoservis.rs",
      lat: 43.1531,
      lng: 22.5881,
      services: ["Servis", "Dijagnostika"]
    }
  ];

  // ========== INICIJALIZACIJA MAPE ==========
  
  // Kreiraj mapu centriranu na Srbiju
  const map = L.map('map', {
    center: [44.0, 21.0], // Centar Srbije
    zoom: 7,
    zoomControl: true,
    scrollWheelZoom: true,
    doubleClickZoom: true,
    dragging: true
  });

  // Dodaj dark tile layer
  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 19,
    minZoom: 6
  }).addTo(map);

  // ========== CUSTOM GOLD MARKER ICON ==========
  const goldIcon = L.divIcon({
    className: 'custom-marker',
    html: `
      <div class="gold-marker" style="
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #f0ad4e, #d98c00);
        border: 3px solid #fff;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        position: relative;
      ">
        <i class="fas fa-wrench" style="
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%) rotate(45deg);
          color: #111;
          font-size: 14px;
        "></i>
      </div>
    `,
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
  });

  // ========== MARKER CLUSTER GROUP ==========
  const markers = L.markerClusterGroup({
    spiderfyOnMaxZoom: true,
    showCoverageOnHover: false,
    zoomToBoundsOnClick: true,
    maxClusterRadius: 50,
    iconCreateFunction: function(cluster) {
      const count = cluster.getChildCount();
      return L.divIcon({
        html: '<div>' + count + '</div>',
        className: 'marker-cluster marker-cluster-small',
        iconSize: L.point(40, 40)
      });
    }
  });

  // ========== DODAVANJE MARKERA ==========
  locations.forEach(location => {
    // Kreiraj marker
    const marker = L.marker([location.lat, location.lng], {
      icon: goldIcon,
      title: location.name
    });

    // Kreiraj popup sadržaj
    const popupContent = `
      <div class="popup-title">
        <i class="fas fa-map-marker-alt"></i>
        ${location.name}
      </div>
      <div class="popup-info">
        <div class="popup-info-item">
          <i class="fas fa-map-pin"></i>
          <span>${location.address}, ${location.city}</span>
        </div>
        <div class="popup-info-item">
          <i class="fas fa-phone"></i>
          <a href="tel:${location.phone}" style="color: var(--text-muted);">${location.phone}</a>
        </div>
        <div class="popup-info-item">
          <i class="fas fa-envelope"></i>
          <a href="mailto:${location.email}" style="color: var(--text-muted);">${location.email}</a>
        </div>
      </div>
      <div class="popup-services">
        ${location.services.map(service => 
          `<span class="popup-service-tag">${service}</span>`
        ).join('')}
      </div>
      <div class="popup-cta">
        <a href="views/register.php" class="popup-btn">
          <i class="fas fa-calendar-check me-1"></i>
          Zakaži Termin
        </a>
      </div>
    `;

    // Dodaj popup markeru
    marker.bindPopup(popupContent, {
      maxWidth: 300,
      className: 'custom-popup'
    });

    // Dodaj marker u cluster grupu
    markers.addLayer(marker);
  });

  // Dodaj cluster grupu na mapu
  map.addLayer(markers);

  // ========== MAP LEGEND (opciono) ==========
  const legend = L.control({ position: 'bottomleft' });
  
  legend.onAdd = function(map) {
    const div = L.DomUtil.create('div', 'map-legend');
    div.innerHTML = `
      <h6><i class="fas fa-info-circle me-1"></i>Legenda</h6>
      <div class="legend-item">
        <div class="legend-marker"></div>
        <span>Auto Servis Lokacija</span>
      </div>
      <div class="legend-item" style="font-size: 12px; color: #888; margin-top: 0.5rem;">
        <i class="fas fa-mouse-pointer me-1"></i>
        <span>Klikni za detalje</span>
      </div>
    `;
    return div;
  };
  
  legend.addTo(map);

  // ========== RESPONSIVE ZOOM ==========
  function adjustMapZoom() {
    if (window.innerWidth < 768) {
      map.setZoom(6);
    } else {
      map.setZoom(7);
    }
  }

  window.addEventListener('resize', adjustMapZoom);
  adjustMapZoom();

  // ========== MAP EVENTS ==========
  
  // Log kada se mapa učita
  map.on('load', () => {
    console.log('✅ Mapa je uspešno učitana');
  });

  // Animacija markera kada se zoom promeni
  map.on('zoomend', () => {
    const currentZoom = map.getZoom();
    if (currentZoom >= 10) {
      // Prikaži sve markere pojedinačno
      markers.refreshClusters();
    }
  });

  // ========== FIT BOUNDS TO MARKERS ==========
  // Prilagodi zoom da obuhvati sve markere
  if (locations.length > 0) {
    const group = new L.featureGroup(
      locations.map(loc => L.marker([loc.lat, loc.lng]))
    );
    map.fitBounds(group.getBounds(), {
      padding: [50, 50],
      maxZoom: 8
    });
  }

  // ========== SEARCH FUNCTIONALITY (opciono) ==========
  window.searchLocation = function(city) {
    const location = locations.find(loc => 
      loc.city.toLowerCase().includes(city.toLowerCase())
    );
    
    if (location) {
      map.setView([location.lat, location.lng], 13);
      
      // Pronađi i otvori popup za tu lokaciju
      markers.eachLayer(layer => {
        if (layer.getLatLng().lat === location.lat && 
            layer.getLatLng().lng === location.lng) {
          layer.openPopup();
        }
      });
    } else {
      console.warn('Lokacija nije pronađena:', city);
    }
  };

  // ========== EXPORT LOCATIONS DATA ==========
  // Za debugging ili kasnije korišćenje
  window.mapLocations = locations;
  
  console.log(`✅ Učitano ${locations.length} lokacija na mapu`);
  console.log('💡 Tip: Koristi searchLocation("Beograd") za pretragu');

});