const itemChange = document.getElementById("item-container");

document.getElementById("open-profile").onclick = (e) => {
    e.preventDefault();
    itemChange.innerHTML = `
    <div class="profile-container">
          <div class="profile-main">
            <div class="profile-img">
              <img src="assets/profile-icon.png">
            </div>
            <div class="profile-info">
              <div class="informations">
                <p>Name: </p>
                <h1>Jule Andre D. Evaristo</h1>
                <p>Address: </p>
                <h1>MARIA SANTOS, 123 LOPEZ STREET, BARANGAY KAPITAN KILYONG, QUEZON CITY, 1101 METRO MANILA, PHILIPPINES</h1>
                <p>Mobile Number: </p>
                <h1>09999999999</h1>
                <p>Email: </p>
                <h1>testemail@email.com</h1>
              </div>
            </div>
          </div>
          <div class="update-info">
            <h1>To Update your information <a href="#">Click here!</a></h1>
          </div>
        </div>
        `;
};

document.getElementById("open-report").onclick = (e) => {
    e.preventDefault();
    itemChange.innerHTML = `
    <div class="report-container">
          <h1>🚨 REPORT AN EMERGENCY 🚨</h1>
          <p>Select the type of Emergency</p>
          <hr>
          <div class="top-option">
            <a href="#" class="fire">FIRE</a>
            <a href="#" class="flood">FLOOD RESCUE</a>
          </div>
          <div class="bottom-option">
            <a href="#" class="medical">MEDICAL</a>
            <a href="#" class="crime">CRIME</a>
            <a href="#" class="other">OTHER</a>
          </div>
        </div>
        `;
};

document.getElementById("open-settings").onclick = (e) => {
    e.preventDefault();
    itemChange.innerHTML = `
    <div class="settings-container">
          <form action="#" method="post">
            <div class="change-profile">
              <img src="assets/profile-icon.png">
                <input type="file" accept="image/*">
            </div>
            <div class="change-information">
              <div class="information-field">
                <label for="name">Name:</label>
                <input name="name" type="text">
                <label for="address">Address:</label>
                <input name="address" type="text">
                <label for="number">Mobile Number:</label>
                <input name="number" type="tel" pattern="[0-9]{11}">
                <label for="email">Email:</label>
                <input name="email" type="email">
                <input type="submit">
              </div>
              <div class="logout">
                <div>
                  <img src="assets/dashboard.png">
                  <a href="#">Back to Dashboard</a>
                </div>
                <div>
                  <img src="assets/creds.png">
                  <a href="#">Change Login Credentials</a>
                </div>
                <div>
                  <img src="assets/logout.png">
                  <a href="#">Logout</a>
                </div>
              </div>
            </div>
          </form>
        </div>
        `;
};