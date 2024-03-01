$(document).ready(function() {
    var bookedAreas = {}; // Object to store booked areas
    var PHHSelected = false;
    var PHHAAYAreasBooked = false;
    var PHHAreasBooked = false; 

    function fetchCardholdersList(cardType, mainArea, additionalArea) {
        var selectedArea = mainArea;
        if ($("#further_areas").prop("checked") && additionalArea) {
            selectedArea += ", " + additionalArea;
        }
        $.ajax({
            type: "POST",
            url: "../php/fetch-cardholders.php",
            data: { cardType: cardType, area: selectedArea, addarea: additionalArea },
            dataType: 'html',
            success: function(response) {
                $("#cardholdersList").html(response);
                attachEventListeners(); 
            },
            error: function(xhr, status, error) {
                console.error("Error fetching cardholders: " + error);
                $("#cardholdersList").html("An error occurred while fetching cardholders. Please try again later.");
            }
        });
    }

    // Event listener for card type selection
    $("#card_type").change(function() {
        var cardType = $(this).val();
        var mainArea = $("#area").val();
        var additionalArea = $("#additional_area").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var isBooked = checkBookingDates(cardType, fromDate, toDate);
        PHHSelected = (cardType === 'PHH' && !isBooked);
        toggleFieldsEnabled();
        fetchCardholdersList(cardType, mainArea, additionalArea);
        updateMessageContent();
    });
   
    function insertIntoDatabase(cardType, area, fromDate, toDate) {
        $.ajax({
            type: "POST",
            url: "../php/savedb.php",
            data: {
                cardType: cardType,
                area: area,
                from_date: fromDate,
                to_date: toDate,
            },
            success: function(response) {
                console.log(response);
                fetchBookedAreas(); // Refresh booked areas after insertion
            },
            error: function(xhr, status, error) {
                console.error("Error inserting data into the database: " + error);
            }
        });
    }

    // Event listener for sending message button
    $("#send_message_btn").click(function() {
        var cardType = $("#card_type").val();
        var mainArea = $("#area").val();
        var additionalArea = $("#additional_area").val();
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        
        if ($("#further_areas").prop("checked") && additionalArea) {
            insertIntoDatabase(cardType, mainArea, fromDate, toDate);
            insertIntoDatabase(cardType, additionalArea, fromDate, toDate);
        } else {
            insertIntoDatabase(cardType, mainArea, fromDate, toDate);
        }
    });
    function attachEventListeners() {
        $("#select-all").change(function() {
            var isChecked = $(this).prop("checked");
            $(".select-checkbox").prop("checked", isChecked);
        });

        $(".select-checkbox").change(function() {
            var totalCheckboxes = $(".select-checkbox").length;
            var checkedCheckboxes = $(".select-checkbox:checked").length;
            $("#select-all").prop("checked", checkedCheckboxes === totalCheckboxes);
        });
    }

    // Event listeners for changes in area and additional area
    $("#area, #additional_area").change(function() {
        var cardType = $("#card_type").val();
        var mainArea = $("#area").val();
        var additionalArea = $("#additional_area").val();
        fetchCardholdersList(cardType, mainArea, additionalArea);
        updateMessageContent();
    });

    // Event listener for checkbox for further areas
    $("#further_areas").change(function() {
        var isChecked = $(this).prop("checked");
        $("#additional_area_selection").toggle(isChecked);
        var cardType = $("#card_type").val();
        var mainArea = $("#area").val();
        var additionalArea = $("#additional_area").val();
        fetchCardholdersList(cardType, mainArea, additionalArea);
        updateMessageContent();
    });
     // Function to check if dates are already booked
     function checkBookingDates(cardType, fromDate, toDate) {
        var isBooked = false;
        $.ajax({
            type: "POST",
            url: "../php/savedb.php",
            data: { cardType: cardType, fromDate: fromDate, toDate: toDate },
            async: false,
            success: function(response) {
                isBooked = response === "true";
            },
            error: function(xhr, status, error) {
                console.error("Error checking booking dates: " + error);
            }
        });
        return isBooked;
    }
    function checkPHHAreasBooked() {
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
    
        $.ajax({
            type: "POST",
            url: "../php/check-phh-areas.php",
            data: { from_date: fromDate, to_date: toDate },
            dataType: 'json',
            success: function(response) {
                PHHAreasBooked = response;
                toggleFieldsEnabled();
            },
            error: function(xhr, status, error) {
                console.error("Error checking PHH areas booking status: " + error);
            }
        });
    }
    function checkPHHAAYAreasBooked() {
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
    
        $.ajax({
            type: "POST",
            url: "../php/check-phh-aay-areas.php",
            data: { from_date: fromDate, to_date: toDate },
            dataType: 'json',
            success: function(response) {
                PHHAAYAreasBooked = response;
                toggleFieldsEnabled();
            },
            error: function(xhr, status, error) {
                console.error("Error checking PHH-AAY areas booking status: " + error);
            }
        });
    }
    
    
    function toggleFieldsEnabled() {
        $("#card_type option[value='PHH-AAY']").prop("disabled", !PHHAreasBooked);
        $("#card_type option[value='NPHH']").prop("disabled", !PHHAAYAreasBooked);
        if (!PHHSelected) {
            $("#card_type").val("PHH");
        }
    }

    // Function to update message content
    function updateMessageContent() {
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var message = "You can get your ration items from " + fromDate + " to " + toDate + ". If you are not okay with this date, please fill the form: https://tnpds.com";
        $("#message").val(message);
    }

    // Event listener for changes in from date and to date
    $("#from_date, #to_date").change(updateMessageContent);

    // Initial call to check booking status
    checkPHHAreasBooked();
    checkPHHAAYAreasBooked();
    
    // Fetch booked areas from the server
    function fetchBookedAreas() {
        $.ajax({
            type: "GET",
            url: "../php/get-booked-areas.php",
            dataType: 'json',
            success: function(response) {
                bookedAreas = response;
                updateAreaOptions(); // Update area options after fetching booked areas
            },
            error: function(xhr, status, error) {
                console.error("Error fetching booked areas: " + error);
            }
        });
    }

    // Update area options based on booked areas
    function updateAreaOptions() {
        // Loop through all select elements with class "area-select"
        $(".area-select").each(function() {
            var select = $(this);
            var selectedValue = select.val(); // Get the currently selected value
    
            // Remove all options except the default option
            select.children("option:not(:first-child)").remove();
    
            // Loop through all available areas and add them as options
            var availableAreas = ["Meenakshipuram", "Kottar", "Chettikulam", "Chidambaram Nagar"];
            for (var i = 0; i < availableAreas.length; i++) {
                var area = availableAreas[i];
                // Check if the area is not already booked
                if (!bookedAreas.hasOwnProperty(area)) {
                    // Check if the area is not already selected
                    if (area !== selectedValue) {
                        select.append($("<option></option>")
                            .attr("value", area)
                            .text(area));
                    }
                }
            }
        });
    }
    

    // Fetch booked areas when the document is ready
    fetchBookedAreas();

    // Event listener for changes in date range
    $("#from_date, #to_date").change(function() {
        fetchBookedAreas(); // Fetch booked areas whenever the date range changes
    });

});
