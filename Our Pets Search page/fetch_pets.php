<?php
include 'database.php';  // Ensure this path is correct

$sql = "SELECT PetID, Name, Species, Age, Sex, Weight, Color, SpayedNeutered, IntakeDate, AdoptionStatus, image_path FROM Pets";  // Query to select data from the Pets table
$result = $conn->query($sql);

$pets = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $pets[] = $row;
  }
  echo json_encode($pets);
} else {
  echo json_encode([]);
}
$conn->close();
?>
