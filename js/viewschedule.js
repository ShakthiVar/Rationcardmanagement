$(document).ready(function() {
    $("#showFormBtn").click(function() {
        $("#addScheduleForm").toggle();
    });

    // Submit schedule form
    $("#addScheduleForm").submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting

        // Get schedule text and date from the form
        var scheduleText = $("#scheduleText").val();
        var scheduleDate = $("#scheduleDate").val();

        // Save schedule to database
        saveScheduleToDatabase(scheduleText, scheduleDate);
    });

    // Fetch and display schedule cards when the page loads
    fetchScheduleCards();
});

// Function to save schedule to database
function saveScheduleToDatabase(text, date) {
    $.ajax({
        type: 'POST',
        url: '../php/viewschedule.php',
        data: { text: text, date: date },
        success: function(response) {
            console.log('Schedule saved to database:', response);
            // Reload schedule cards after adding a new schedule
            fetchScheduleCards();
        },
        error: function(xhr, status, error) {
            console.error('Error saving schedule to database:', error);
        }
    });
}

// Function to fetch and display schedule cards
function fetchScheduleCards() {
    $.ajax({
        type: 'GET',
        url: '../php/fetch_schedule.php',
        success: function(response) {
            console.log('Fetched schedule cards:', response);
            // Display fetched schedule cards in the container outside the login container
            $("#scheduleCardsContainer").html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching schedule cards:', error);
        }
    });
}
// Event listener for delete button
$(document).on('click', '.delete-schedule-btn', function() {
    var scheduleId = $(this).data('schedule-id');
    deleteScheduleFromDatabase(scheduleId);
});

// Function to delete schedule from database
function deleteScheduleFromDatabase(scheduleId) {
    $.ajax({
        type: 'POST',
        url: '../php/delete_schedule.php',
        data: { id: scheduleId },
        success: function(response) {
            console.log('Schedule deleted from database:', response);
            fetchScheduleCards();
        },
        error: function(xhr, status, error) {
            console.error('Error deleting schedule from database:', error);
        }
    });
}
