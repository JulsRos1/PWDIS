<?php
include('includes/config.php');
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Navigate Accessibility  in Los Baños, Laguna</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link href="css/modern-business.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/mapcontent.css" />
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO23kIOUSOKRGYzYoVMbnEMmbriP6IvR8&libraries=places" defer async></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php include("includes/header.php"); ?>
  <div id="map-container">
  <div id="map"></div>
  <div class="top-button-bar">
    <button class="category-button" onclick="updatePlaces('restaurant')">
      <i class="fas fa-utensils"></i>
      Restaurants
    </button>
    <button class="category-button" onclick="updatePlaces('school')">
      <i class="fas fa-school"></i>
      Schools
    </button>
    <button class="category-button" onclick="updatePlaces('hospital')">
      <i class="fas fa-hospital"></i>
      Healthcare
    </button>
    <button class="category-button" onclick="updatePlaces('grocery_store')">
      <i class="fas fa-shopping-cart"></i>
      Grocery Store
    </button>
    <button class="category-button" onclick="updatePlaces('community_center')">
      <i class="fas fa-users"></i>
      Community Center
    </button>
  </div>
  <div class="legend">
    <h4>Legend</h4>
    <ul>
      <li><span style="background-color: red"></span> Not Accessible (0 Options)</li>
      <li><span style="background-color: blue"></span> Accessible (1-2 Options)</li>
      <li><span style="background-color: green"></span> Highly Accessible (3-4 Options)</li>
    </ul>
  </div>
  <div id="place-details-panel" class="place-details-panel">
    <button id="close-details" class="close-details">&times;</button>
    <div id="place-details-content"></div>
  </div>
</div>
</body>


<!-- Write Reviews Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="submit_review.php" method="POST">
          <div class="rating-box">
            <header>How was your experience?</header>
            <div class="stars">
              <i class="fa-solid fa-star" data-value="1"></i>
              <i class="fa-solid fa-star" data-value="2"></i>
              <i class="fa-solid fa-star" data-value="3"></i>
              <i class="fa-solid fa-star" data-value="4"></i>
              <i class="fa-solid fa-star" data-value="5"></i>
            </div>
            <p>Leave a Review:</p>
            <input type="hidden" id="rating_input" name="rating" value="">
            <textarea id="review" name="review" rows="4" cols="50"></textarea>
            <br>
            <input type="hidden" name="display_name" id="display_name_modal">
            <input type="hidden" name="place_id" id="place_id_modal">
            <input type="hidden" name="photo_url" id="photo_url_modal">
            <input type="hidden" name="formatted_address" id="formatted_address_modal">
            <input type="hidden" name="first_name" value="<?php echo $_SESSION['name']; ?>">
            <input type="hidden" name="last_name" value="<?php echo $_SESSION['lname']; ?>">
            <input type="hidden" name="review_date" id="review_date_modal" value="<?php echo date('Y-m-d H:i:s'); ?>">
            <input type="submit" value="Submit" name="submit">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Accessibility Options Modal -->
<div class="modal fade" id="accessibilityModal" tabindex="-1" role="dialog" aria-labelledby="accessibilityModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="accessibilityModalLabel">Update Accessibility Options</h5>
        <button type="button" class="btn btn-primary close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="accessibilityForm" action="submit_accessibility.php" method="POST" onsubmit="updateAccessibilityLevel()">
          <p>Make an Update on Accessibility Options</p>
          <input type="checkbox" id="parking" name="accessibilityOptions[]" value="wheelchairAccessibleParking">
          <label for="parking">Has PWD Accessible Parking</label><br>
          <input type="checkbox" id="entrance" name="accessibilityOptions[]" value="wheelchairAccessibleEntrance">
          <label for="entrance">Has PWD Accessible Entrance</label><br>
          <input type="checkbox" id="restroom" name="accessibilityOptions[]" value="wheelchairAccessibleRestroom">
          <label for="restroom">Has PWD Accessible Restroom</label><br>
          <input type="checkbox" id="seating" name="accessibilityOptions[]" value="wheelchairAccessibleSeating">
          <label for="seating">Has PWD Accessible Seating</label><br>

          <input type="hidden" name="accessibility_level" id="accessibility_level">
          <input type="hidden" name="display_name" id="display_name_modal1">
          <input type="hidden" name="place_id" id="place_id_accessibility_modal">
          <input type="submit" value="Submit" name="submit">
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Show Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="reviewsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewsModalLabel">Reviews</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="reviewsContent">
        <!-- Dito magshowshow yung reviews content -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap JS -->

<script>
  let map;
  let userLocation;
  let markers = [];
  document.addEventListener("DOMContentLoaded", function() {
    // Call initMap() when the page is fully loaded
    initMap();
    const reviewForm = document.querySelector("#reviewModal form");
    reviewForm.addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent the default form submission
      const formData = new FormData(reviewForm);

      // Use fetch to submit the form data via AJAX
      fetch("submit_review.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          // Handle the response
          if (data.status === "success") {
            Swal.fire({
              title: "Success!",
              text: data.message,
              icon: "success",
              confirmButtonText: "OK",
            }).then(() => {
              // Optionally, reload the page or close the modal
              $("#reviewModal").modal("hide");
              reviewForm.reset(); // Clear the form after success
            });
          } else {
            Swal.fire({
              title: "Error!",
              text: data.message,
              icon: "error",
              confirmButtonText: "OK",
            });
          }
        })
        .catch(error => {
          console.error("Error submitting review:", error);
          Swal.fire({
            title: "Error!", 
            text: "There was an error submitting your review. Please try again.",
            icon: "error",
            confirmButtonText: "OK",
          });
        });
    });
  });

  function initMap() {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };

        map = new google.maps.Map(document.getElementById("map"), {
          center: userLocation,
          zoom: 17,
          mapTypeControl: false, // This disables the default "Map" and "Satellite" buttons
          streetViewControl: false, // Optionally disable the Street View button
          fullscreenControl: false // Optionally disable the fullscreen control
        });

        // Display user's current location
        new google.maps.Marker({
          position: userLocation,
          map: map,
          title: "Your Location",
          icon: {
            url: "images/usericonmap.png",
          },
        });

        // Add click event listener to the map
        map.addListener('click', function(event) {
          event.stop(); // Stop any default actions
          handleMapClick(event.latLng);
        });
      },
      (error) => {
        console.error("Geolocation error:", error);
      }
    );
  }
  

  function updatePlaces(type) {
  // Clear existing markers (if any)
  clearMarkers();
  const request = {
    includedTypes: [type],
    locationRestriction: {
      circle: {
        center: {
          latitude: userLocation.lat,
          longitude: userLocation.lng,
        },
        radius: 3000,
      },
    },
  };

  fetch(
    "https://places.googleapis.com/v1/places:searchNearby?key=AIzaSyBO23kIOUSOKRGYzYoVMbnEMmbriP6IvR8",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Goog-FieldMask": "places.id,places.displayName,places.formattedAddress,places.accessibilityOptions,places.location,places.photos",
      },
      body: JSON.stringify(request),
    }
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Parsed data: ", data); // Log the entire response once

      if (data.places && Array.isArray(data.places)) {
        data.places.forEach((place) => {
          if (
            place.location &&
            place.location.latitude &&
            place.location.longitude
          ) {
            fetch(`fetch_accessibility_options.php?place_id=${place.id}`)
              .then((response) => response.json())
              .then((userAccessibility) => {
                const accessibilityOptions = {
                  wheelchairAccessibleParking: userAccessibility?.wheelchairAccessibleParking !== null
                    ? userAccessibility.wheelchairAccessibleParking
                    : place.accessibilityOptions?.wheelchairAccessibleParking,
                  wheelchairAccessibleEntrance: userAccessibility?.wheelchairAccessibleEntrance !== null
                    ? userAccessibility.wheelchairAccessibleEntrance
                    : place.accessibilityOptions?.wheelchairAccessibleEntrance,
                  wheelchairAccessibleRestroom: userAccessibility?.wheelchairAccessibleRestroom !== null
                    ? userAccessibility.wheelchairAccessibleRestroom
                    : place.accessibilityOptions?.wheelchairAccessibleRestroom,
                  wheelchairAccessibleSeating: userAccessibility?.wheelchairAccessibleSeating !== null
                    ? userAccessibility.wheelchairAccessibleSeating
                    : place.accessibilityOptions?.wheelchairAccessibleSeating,
                };

                addMarker(place, accessibilityOptions);
              })
              .catch((error) => {
                console.error(
                  "Fetch error for accessibility options:",
                  error
                );
              });
          }
        });
      } else {
        console.error("No places found in the response");
      }
    })
    .catch((error) => {
      console.error("Fetch error for places:", error);
    });
}

  function handleMapClick(latLng) {
    console.log("Unknown Clicked Location:", latLng.lat(), latLng.lng());
    // Clear existing markers
    clearMarkers();

    // Create a request to find the nearest place
    const request = {
      locationRestriction: {
        circle: {
          center: {
            latitude: latLng.lat(),
            longitude: latLng.lng(),
          },
          radius: 15, // Increase the radius to 50 meters
        },
      },
      maxResultCount: 1
    };

    // Fetch place details
    fetch(
      "https://places.googleapis.com/v1/places:searchNearby?key=AIzaSyBO23kIOUSOKRGYzYoVMbnEMmbriP6IvR8",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Goog-FieldMask": "places.id,places.displayName,places.formattedAddress,places.accessibilityOptions,places.location,places.photos"
        },
        body: JSON.stringify(request),
      }
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.places && data.places.length > 0) {
          const place = data.places[0];
          fetchPlaceDetails(place);
        } else {
          console.log("No place found at this location");
          addMarker({
            location: { latitude: latLng.lat(), longitude: latLng.lng() },
            displayName: { text: "Unknown Location" },
            formattedAddress: "Unknown Address",
            id: "clicked_location",
          }, {});
        }
      })
      .catch((error) => {
        console.error("Fetch error for places:", error);
      });
  }

  function fetchPlaceDetails(place) {
    console.log("Fetching details for place:", place);
    fetch(`fetch_accessibility_options.php?place_id=${place.id}`)
      .then((response) => response.json())
      .then((userAccessibility) => {
        const accessibilityOptions = {
          wheelchairAccessibleParking: userAccessibility?.wheelchairAccessibleParking ?? place.accessibilityOptions?.wheelchairAccessibleParking ?? false,
          wheelchairAccessibleEntrance: userAccessibility?.wheelchairAccessibleEntrance ?? place.accessibilityOptions?.wheelchairAccessibleEntrance ?? false,
          wheelchairAccessibleRestroom: userAccessibility?.wheelchairAccessibleRestroom ?? place.accessibilityOptions?.wheelchairAccessibleRestroom ?? false,
          wheelchairAccessibleSeating: userAccessibility?.wheelchairAccessibleSeating ?? place.accessibilityOptions?.wheelchairAccessibleSeating ?? false,
        };

        addMarker(place, accessibilityOptions);
        
        // Fetch reviews and display place details
        fetch(`fetch_reviews.php?place_id=${place.id}`)
          .then((response) => response.json())
          .then((reviewsData) => {
            const content = createPlaceDetailsContent(
              place,
              reviewsData,
              accessibilityOptions,
              place.photos && place.photos.length > 0 ? place.photos[0] : null
            );
            document.getElementById('place-details-content').innerHTML = content;
            openPlaceDetailsPanel();
          })
          .catch((error) => {
            console.error("Fetch error for reviews:", error);
          });
      })
      .catch((error) => {
        console.error("Fetch error for accessibility options:", error);
        addMarker(place, {});
      });
  }

  function addMarker(place, accessibilityOptions) {
    const accessibilityCount =
      Object.values(accessibilityOptions).filter(Boolean).length;

    let markerIcon;
    if (accessibilityCount === 0) {
      markerIcon = "images/red-marker.png";
    } else if (accessibilityCount <= 2) {
      markerIcon = "images/blue-marker.png";
    } else {
      markerIcon = "images/green-marker.png";
    }

    const marker = new google.maps.Marker({
      position: {
        lat: place.location.latitude,
        lng: place.location.longitude,
      },
      map: map,
      title: place.displayName.text,
      icon: {
        url: markerIcon,
        scaledSize: new google.maps.Size(40, 40),
      },
    });

    markers.push(marker);

    marker.addListener("click", function() {
      fetch(`fetch_reviews.php?place_id=${place.id}`)
        .then((response) => response.json())
        .then((reviewsData) => {
          const content = createPlaceDetailsContent(
            place,
            reviewsData,
            accessibilityOptions,
            place.photos && place.photos.length > 0 ? place.photos[0] : null
          );
          document.getElementById('place-details-content').innerHTML = content;
          openPlaceDetailsPanel();
        })
        .catch((error) => {
          console.error("Fetch error for reviews:", error);
        });
    });
  }

  function clearMarkers() {
    for (let marker of markers) {
      marker.setMap(null);
    }
    markers = [];
  }

  function createPlaceDetailsContent(place, reviewsData, accessibilityOptions, photo) {
    const averageRating = reviewsData.averageRating !== undefined ? reviewsData.averageRating.toFixed(1) : "No ratings yet";
    const reviewCount = reviewsData.reviewCount || "No reviews";
    const starsHtml = Array.from({ length: 5 }, (v, i) => `<i class="fa fa-star ${i < averageRating ? "active" : ""}"></i>`).join("");

    // Helper function to calculate time ago
    function timeAgo(reviewDate) {
        const date = new Date(reviewDate);  // Make sure reviewDate is a valid Date string
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) return interval === 1 ? "1 year ago" : `${interval} years ago`;

        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) return interval === 1 ? "1 month ago" : `${interval} months ago`;

        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval === 1 ? "1 day ago" : `${interval} days ago`;

        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval === 1 ? "1 hour ago" : `${interval} hours ago`;

        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval === 1 ? "1 minute ago" : `${interval} minutes ago`;

        return seconds < 5 ? "just now" : `${Math.floor(seconds)} seconds ago`;
    }
    

    // Generate reviews HTML
    const reviewsHtml = reviewsData.reviews.map(review => {
        const starsHtml = Array.from({ length: 5 }, (v, i) => `<i class="fa fa-star ${i < review.rating ? "active" : ""}"></i>`).join("");
        const reviewTimeAgo = timeAgo(review.review_date);  // Use the review_date field here

        return `
            <div class="review">
              <p><strong>${review.full_name}</strong> - <strong>Rating:</strong> ${review.rating}</p>
              <div class="stars">${starsHtml}</div>
              <p>${review.review}</p>
              <p><small>${reviewTimeAgo}</small></p>  <!-- Display the time ago -->
            </div>
            <hr>
        `;
    }).join("");

    const photoUrl = photo ? `https://places.googleapis.com/v1/${photo.name}/media?key=AIzaSyBO23kIOUSOKRGYzYoVMbnEMmbriP6IvR8&maxHeightPx=400&maxWidthPx=600` : '';

    // Create the photo HTML if a photo is available
    const photoHtml = photoUrl ? `
      <div style="position: relative; width: 100%; max-height: 230px;">
        <img src="${photoUrl}"
            alt="${place.displayName.text}"
            style="width: 100%; height: 230px; object-fit: cover;">   
      </div>` : '';


    return `
        <div>
            ${photoHtml}
            <h4>${place.displayName.text}</h4>
            <p>${place.formattedAddress}</p>
            <ul>
              ${accessibilityOptions.wheelchairAccessibleParking
                ? "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Parking: <i class='fa-solid fa-check' style='color: green;'></i></li>"
                : "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Parking - Not Accessible</li>"}
              ${accessibilityOptions.wheelchairAccessibleEntrance
                ? "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Entrance: <i class='fa-solid fa-check' style='color: green;'></i></li>"
                : "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Entrance - Not Accessible</li>"}
              ${accessibilityOptions.wheelchairAccessibleRestroom
                ? "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Restroom: <i class='fa-solid fa-check' style='color: green;'></i></li>"
                : "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Restroom - Not Accessible</li>"}
              ${accessibilityOptions.wheelchairAccessibleSeating
                ? "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Seating: <i class='fa-solid fa-check' style='color: green;'></i></li>"
                : "<li class='no-bullet'><i class='fa-solid fa-wheelchair' style='color: #007bff;'></i> Has PWD Accessible Seating - Not Accessible</li>"}
            </ul>
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary btn-sm" onclick="openReviewModal('${place.id}', '${place.displayName.text.replace(/'/g, "\\'")}', '${place.formattedAddress.replace(/'/g, "\\'")}', '${photoUrl.replace(/'/g, "\\'")}')">Write a Review</button>
                <button class="btn btn-primary btn-sm" onclick="openAccessibilityModal('${place.id}', '${place.displayName.text.replace(/'/g, "\\'")}')">Update Accessibility</button>
            </div>
            
            <hr>
            <div class="review-summary">
              <div class="average-rating">
                <span class="rating-number">${averageRating}</span>
                <div class="stars">${starsHtml}</div>
                <p class="total-reviews">${reviewCount} reviews</p> 
              </div>
              <hr>
            </div>

            <div class="recent-reviews">
              <div class="reviews-list">
                <h4>Recent Reviews:</h4>
                ${reviewsHtml}
              </div>
            </div>
        </div>
    `;
}


// Adjust other functions as needed


function openReviewModal(placeId, displayName, formattedAddress, photoUrl) {
  console.log("Opening review modal for:", placeId, displayName, formattedAddress);
  document.getElementById('place_id_modal').value = placeId;
  document.getElementById('photo_url_modal').value = photoUrl || ''; // Set to empty string if undefined
  document.getElementById('display_name_modal').value = displayName;
  document.getElementById('formatted_address_modal').value = formattedAddress;
  $('#reviewModal').modal('show');
}
  // Eto yung logic sa stars in the ratings
  document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.stars i');
    let selectedRating = 0;
    stars.forEach((star, index1) => {
      star.addEventListener('click', () => {
        selectedRating = index1 + 1;
        stars.forEach((star, index2) => {
          index1 >= index2 ? star.classList.add('active') : star.classList.remove('active');
        });
        document.getElementById('rating_input').value = selectedRating;
      });
    });
  });

  function openAccessibilityModal(placeId, displayName) {
  console.log("Opening accessibility modal for:", placeId, displayName);
  
  // Set place ID and display name
  document.getElementById("place_id_accessibility_modal").value = placeId;
  document.getElementById("display_name_modal1").value = displayName;

  // Fetch accessibility options and update the checkboxes
  fetch(`fetch_accessibility_options.php?place_id=${placeId}`)
    .then(response => response.json())
    .then(data => {
      // Update checkboxes based on fetched data
      for (const [key, value] of Object.entries(data)) {
        const checkbox = document.querySelector(`#accessibilityModal input[name="accessibilityOptions[]"][value="${key}"]`);
        if (checkbox) {
          checkbox.checked = value;
        }
      }
      // Show the modal
      $("#accessibilityModal").modal("show");
    })
    .catch(error => {
      console.error("Error fetching accessibility options:", error);
      // Show the modal even if fetch fails
      $("#accessibilityModal").modal("show");
    });
}

  // Add event listener for accessibility form submission
  document.querySelector("#accessibilityModal form").addEventListener("submit", function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch("submit_accessibility.php", {
          method: "POST",
          body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire({
              title: "Success!",
              text: data.message,
              icon: "success",
              confirmButtonText: "OK",
            }).then(() => {
              $("#accessibilityModal").modal("hide");
              updatePlaceInfo(formData.get("place_id"));
            });
          } else {
            Swal.fire({
              title: "Error!",
              text: data.message,
              icon: "error",
              confirmButtonText: "OK",
            });
          }
        })
        .catch((error) => {
          console.error("Error updating accessibility options:", error);
          Swal.fire({
            title: "Error!",
            text: "There was an error updating accessibility options. Please try again.",
            icon: "error",
            confirmButtonText: "OK",
          });
        });
    });

    function updateAccessibilityLevel() {
      const checkboxes = document.querySelectorAll('#accessibilityModal input[type="checkbox"]');
      const accessibilityCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;

      let accessibilityLevel = "Not Accessible";
      if (accessibilityCount > 2) accessibilityLevel = "Highly Accessible";
      else if (accessibilityCount > 0) accessibilityLevel = "Accessible";

      document.getElementById("accessibility_level").value = accessibilityLevel;
}

document.querySelectorAll('#accessibilityModal input[type="checkbox"]')
  .forEach(checkbox => checkbox.addEventListener("change", updateAccessibilityLevel));

  function showReviews(placeId) {
  fetch(`fetch_reviews.php?place_id=${placeId}`)
    .then(response => response.json())
    .then(reviewsData => {
      let reviewsHtml = "No reviews yet.";
      if (reviewsData.reviews && reviewsData.reviews.length > 0) {
        reviewsHtml = reviewsData.reviews.map(review => {
          const starsHtml = Array.from({length: 5}, (_, i) => 
            `<i class="fa fa-star ${i < review.rating ? "active" : ""}"></i>`
          ).join("");
          return `
            <div class="review">
              <p><strong>${review.full_name}</strong> - <strong>Rating:</strong> ${review.rating}</p>
              <div class="stars">${starsHtml}</div>
              <p>${review.review}</p>
            </div>
            <hr>
          `;
        }).join("");
      }
      document.getElementById("reviewsContent").innerHTML = reviewsHtml;
      $("#reviewsModal").modal("show");
    })
    .catch(error => {
      console.error("Error fetching reviews:", error);
      document.getElementById("reviewsContent").innerHTML = "Error loading reviews.";
      $("#reviewsModal").modal("show");
    });
}

function openPlaceDetailsPanel() {
  document.getElementById('place-details-panel').classList.add('open');
  document.getElementById('map').classList.add('panel-open');
}

function closePlaceDetailsPanel() {
  document.getElementById('place-details-panel').classList.remove('open');
  document.getElementById('map').classList.remove('panel-open');
}

document.getElementById('close-details').addEventListener('click', closePlaceDetailsPanel);
</script>
</body>

</html>