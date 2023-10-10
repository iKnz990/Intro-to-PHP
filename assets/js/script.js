/*To sort the viewBooking for calendar w/o a button*/
(function viewBookingsSort() {
    document.addEventListener("DOMContentLoaded", function() {
        const sortDropdown = document.getElementById("sort_order");
    
        sortDropdown.addEventListener("change", function() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'view_bookings.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status == 200) {
                    // Update your table with this.responseText
                    const parser = new DOMParser();
                    const htmlDoc = parser.parseFromString(this.responseText, 'text/html');
                    const newTable = htmlDoc.querySelector('.content table');
                    const oldTable = document.querySelector('.content table');
                    oldTable.parentNode.replaceChild(newTable, oldTable);
                }
            }
            xhr.send('sort_order=' + this.value);
        });
    });
})();

