/*To sort the viewBooking for calendar w/o a button*/
(function viewBookingsSort() {
    document.addEventListener("DOMContentLoaded", function() {
        const sortForm = document.getElementById("sortForm");
        const sortDropdown = document.getElementById("sort_order");

        sortDropdown.addEventListener("change", function() {
            sortForm.submit();
        });
    });
})();

