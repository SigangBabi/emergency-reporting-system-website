const itemChange = document.getElementById("body-container");
const metricsNav = document.getElementById("metrics-nav-container");

document.addEventListener("click", (e) => {
  if (e.target.closest("#open-metrics")){
    e.preventDefault();
    itemChange.innerHTML = `
    <div class="metrics-header">
      <h1>Metrics</h1>
    </div>
    <div class="metrics-container">
      <a href="#" id="flood">
        <img src="assets/flood.png">
        <div class="metric">
          <h3>Flood</h3>
          <p>No. of Flood reports</p>
          <p class="count">1</p>
        </div>
      </a>
      <a href="#" id="crime">
        <img src="assets/crime.png">
        <div class="metric">
          <h3>Crime</h3>
          <p>No. of Criminal Reports</p>
          <p class="count">1</p>
        </div>
      </a>
      <a href="#" id="fire">
        <img src="assets/fire.png">
        <div class="metric">
          <h3>Fire</h3>
          <p>No. of Fire Reports</p>
          <p class="count">1</p>
        </div>
      </a>
      <a href="#" id="medical">
        <img src="assets/med.png">
        <div class="metric">
          <h3>Medic</h3>
          <p>No. of Medical Reports</p>
          <p class="count">1</p>
        </div>
      </a>
      <a href="#" id="other">
        <img src="assets/hazard.png">
        <div class="metric">
          <h3>Other</h3>
          <p>No. of Other Concerns</p>
          <p class="count">1</p>
        </div>
      </a>
    </div>
    <a class="no-users" href="usersList.html">
      <h1>Current No. of Users:</h1>
      <h1>1</h1>
    </a>
        `;
  }
  
})