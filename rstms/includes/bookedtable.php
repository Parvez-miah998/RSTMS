<?php
include('includes/dbconnection.php');

$success = '';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bookedtable_form'])) {
    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

        $logedInUser = $_SESSION['user'];

        $sql = "SELECT u_email, u_id FROM users WHERE u_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $logedInUser);
        $stmt->execute();
        $stmt->bind_result($userEmail, $userId);
        $result = $stmt->fetch();
        $stmt->close();

        if ($result) {
            $name = $conn->real_escape_string($_POST['name'] ?? '');
            $email = $conn->real_escape_string($_POST['email'] ?? '');
            $contact = $conn->real_escape_string($_POST['contact'] ?? '');
            $category = $conn->real_escape_string($_POST['category'] ?? '');
            $message = $conn->real_escape_string($_POST['message'] ?? '');

            $bookedDateTime = isset($_POST['bookedDate']) && isset($_POST['bookedTime']) ? $_POST['bookedDate'] . ' ' . $_POST['bookedTime'] : '0000-00-00 00:00:00';

            $selectedSeats = '';

            if (isset($_POST['availableSeats'])) {
                if ($_POST['availableSeats'] === 'more+ seats' && isset($_POST['moreSeatsInput'])) {
                    $selectedSeats = $conn->real_escape_string($_POST['moreSeatsInput']);
                } else {
                    $selectedSeats = $conn->real_escape_string($_POST['availableSeats']);
                }
            }

            // Provide a default value for $userId if not found
            $userId = $userId ?? 0;

            $sql = $conn->prepare("INSERT INTO tbl_bookedtable (u_id, u_name, u_contact, u_email, t_category, t_seat, t_desc, t_bookeddate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("isssssss", $userId, $name, $contact, $email, $category, $selectedSeats, $message, $bookedDateTime);

            if ($sql->execute()) {
                $sql->close();
                $success = 'Your table booking has been successfully submitted!';
                unset($_POST);
            } else {
                echo "Error: " . $sql->error;
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "CSRF token validation failed!";
    }
}
?>


<div class="contact-container container">
    <div class="contact-img">
        <img class="tbl-image" src="assets/image/image6.jpg" alt="" style="height: 600px!important; object-fit: cover;" />
    </div>

    <div class="form-container">
        <h2>Book a Table</h2>
        <?php if (!empty($success)) : ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="hidden" name="bookedtable_form" value="1">
            <input type="text" name="name" placeholder="Your Name" required />
            <input type="contact" name="contact" placeholder="Contact No"  required />
            <input type="email" name="email" placeholder="Email" required />

            <label for="bookedDate">Booked Date:</label>
            <input type="date" name="bookedDate" id="bookedDate" min="" />
            <label for="bookedTime">Time:</label>
            <input type="time" name="bookedTime" id="bookedTime" />
            <label for="tableSize">Select Table:</label>
            <select name="category" id="tableSize" onchange="displayAvailableSeats()">
                <option value="" selected>Select</option>
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>
            <br>
            <div id="availableSeatsSection" style="display: none;">
                <label for="availableSeats">Available Seats:</label>
                <div id="seatsContainer">
                    <select name="availableSeats" id="availableSeats" onchange="handleMoreSeats()">
                        <!-- Seats will be dynamically populated based on table size selection -->
                    </select>
                    <input type="number" name="moreSeatsInput" id="moreSeatsInput" placeholder="Enter Number of Seats more than 7." style="display: none;" />
                </div>
            </div>

            <textarea cols="30" rows="3" name="message" placeholder="Type Your Message"></textarea>
            <div class="form-control">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" class="btn btn-primary" name="login" value="login">Submit</button>
            </div>
        </form>
    </div>
    <!--Control for Date and time-->
   <script type="text/javascript">
        function getCurrentDate() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();

            return yyyy + '-' + mm + '-' + dd;
        }

        // Set the minimum date for the 'bookedDate' input to the current date
        document.getElementById("bookedDate").setAttribute("min", getCurrentDate());

        function getCurrentTime() {
            var now = new Date();
            var hh = String(now.getHours()).padStart(2, '0');
            var mm = String(now.getMinutes()).padStart(2, '0');

            return hh + ':' + mm;
        }

        // Set the minimum time for the 'bookedTime' input to the current time
        document.getElementById("bookedTime").setAttribute("min", getCurrentTime());

        // Ensure 'bookedTime' is updated whenever 'bookedDate' changes
        document.getElementById("bookedDate").addEventListener("input", function() {
            var today = getCurrentDate();
            var selectedDate = this.value;

            // If selected date is today, set the minimum time to the current time
            if (selectedDate === today) {
                document.getElementById("bookedTime").setAttribute("min", getCurrentTime());
            } else {
                // Otherwise, there's no minimum time restriction
                document.getElementById("bookedTime").removeAttribute("min");
            }
        });
    </script>

    <!--Control for Table and Chair-->
    <script type="text/javascript">
        function displayAvailableSeats() {
            var tableSize = document.getElementById("tableSize").value;
            var availableSeatsSection = document.getElementById("availableSeatsSection");
            var seatsContainer = document.getElementById("seatsContainer");
            var availableSeats = document.getElementById("availableSeats");
            var moreSeatsInput = document.getElementById("moreSeatsInput");

            // Clear previous seat options
            availableSeats.innerHTML = "";

            if (tableSize === "") {
                availableSeatsSection.style.display = "none";
            } else {
                availableSeatsSection.style.display = "block";

                // Define seat options based on table size
                var seatOptions = [];
                if (tableSize === "small") {
                    seatOptions = ["2 seats", "3 seats"];
                } else if (tableSize === "medium") {
                    seatOptions = ["4 seats", "5 seats"];
                } else if (tableSize === "large") {
                    seatOptions = ["6 seats", "7 seats", "more+ seats"];
                }

                // Populate the availableSeats dropdown with seat options
                seatOptions.forEach(function (option) {
                    var seatOption = document.createElement("option");
                    seatOption.value = option;
                    seatOption.textContent = option;
                    availableSeats.appendChild(seatOption);
                });

                moreSeatsInput.style.display = "none";
            }
        }

        function handleMoreSeats() {
            var availableSeats = document.getElementById("availableSeats");
            var moreSeatsInput = document.getElementById("moreSeatsInput");

            // If the user selects "more+ seats," show the input field
            if (availableSeats.value === "more+ seats") {
                moreSeatsInput.style.display = "block";
            } else {
                // If another option is selected, hide the input field
                moreSeatsInput.style.display = "none";
            }
        }
    </script>
</div>
