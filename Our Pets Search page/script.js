function fetchPets() {
    fetch('fetch_pets.php')
        .then(response => response.json())
        .then(data => {
            const petsContainer = document.getElementById('pets');
            petsContainer.innerHTML = ''; // Clear existing entries

            data.forEach(pet => {
                const petDiv = document.createElement('div');
                petDiv.className = 'pet';
                petDiv.innerHTML = `
                    <img src="${pet.image_path}">
                    <div class="pet-id-name">
                        <p>${pet.PetID}</p>
                        <p>${pet.Name}</p>
                    </div>
                    <div class="hover">
                        <i class='bx bx-search'></i>
                    </div>
                `;
                // Set the onclick event to the showPopUp function passing the current pet object
                petDiv.onclick = () => showPopUp(pet);
                petsContainer.appendChild(petDiv);
            });
        })
        .catch(error => console.error('Error fetching pets:', error));
}

document.addEventListener('DOMContentLoaded', fetchPets); // Fetch pets when the DOM is fully loaded

function showPopUp(pet) {
    const popup = document.getElementById('popup');
    popup.innerHTML = `
        <div class="popup-container">
            <span class="close" onclick="closePopUp()">&times;</span>
            <div class="pet-info">
                <div class="left-section">
                    <h3>Pet ID: ${pet.PetID}</h3>
                    <img src="${pet.image_path}">
                </div>
                <div class="right-section">
                    <table>
                        <tr><th>Name:</th><td>${pet.Name}</td></tr>
                        <tr><th>Species:</th><td>${pet.Species}</td></tr>
                        <tr><th>Age:</th><td>${pet.Age} year(s)</td></tr>
                        <tr><th>Sex:</th><td>${pet.Sex}</td></tr>
                        <tr><th>Weight:</th><td>${pet.Weight} lb</td></tr>
                        <tr><th>Color:</th><td>${pet.Color}</td></tr>
                        <tr><th>Spayed/Neutered:</th><td>${pet.SpayedNeutered === "1" ? 'Yes' : 'No'}</td></tr>
                        <tr><th>Intake Date:</th><td>${pet.IntakeDate}</td></tr>
                        <tr><th>Adoption Status:</th><td>${pet.AdoptionStatus}</td></tr>
                    </table>
                </div>
            </div>
            <div class="adoption-process">
                <a href="#process">
                    <p>Find out more about our adoption process</p>
                </a>
                <a href="#reclaim">
                    <p>If you believe this may be your missing pet, find out about our pet reclaim process.</p>
                </a>
            </div>
            <div class="contact-infos">
                <p>To find out more information, please email <a href="#email">paradisepetrescue@gmail.com</a> or call
                    970-123-4567 and reference this pet's ID number</p>
            </div>
        </div>
    `;
    popup.style.display = 'block'; // Show the popup
}

function closePopUp() {
    const popup = document.getElementById('popup');
    popup.style.display = 'none'; // Hide the popup
}
