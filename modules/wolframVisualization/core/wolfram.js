var queryHistory = [];
var displayMode = "text";  // Default display mode
var textResultsHtml = "";  // Store text results
var imgResultsHtml = "";  // Store image results
var availableSections = [];  // Store available sections
var formattedData = {};  // Store formatted data

// Listeners for the buttons
$(document).ready(function () {
    $("#query-button").click(function () {
        sendQuery();
    });

    $("#toggle-button").click(function () {
        toggleDisplayMode();
    });
      // Add click event for the new button
    $("#show-infographic").click(function () {
        renderInfographic();
    });
});

// Function to handle the response from the Wolfram API
function handleResponse(xml) {
    textResultsHtml = "";
    imgResultsHtml = "";
    var hasResults = false;  // Flag to check if any results were returned

    $(xml).find('pod').each(function () {
        hasResults = true;  // Results were found
        var title = $(this).attr('title');
        var resultText = $(this).find('plaintext').text();
        var resultImgSrc = $(this).find('img').attr('src');

        // Block to update the dropdown options
        title = $(this).attr('title');
        if (!availableSections.includes(title)) {
        availableSections.push(title);
        }
        
        textResultsHtml += "<h3>" + title + "</h3>";
        imgResultsHtml += "<h3>" + title + "</h3>";

        if (resultText) {
            textResultsHtml += "<p>" + resultText + "</p>";
        } else {
            textResultsHtml += "";
        }

        if (resultImgSrc) {
            imgResultsHtml += "<img src='" + resultImgSrc + "' alt='" + title + " image' />";
        }
    });

    if (!hasResults) {
        textResultsHtml = "<p>No results found.</p>";
        imgResultsHtml = "<p>No results found.</p>";
    }

    updateDisplay();
    updateSectionDropdown();
}

// Function to parse and format data
function parseAndFormatData(selectedSection, sectionText) {
    // Check if the selected section matches the title
    if (selectedSection) {  
        console.log(`\nSection: ${selectedSection}`);
        
        const rows = sectionText.split('\n');
        const originalHeaders = rows[0].split('|').map(h => h.trim());
        
        // Add custom headers for Label and Subject
        const headers = ['Label', 'Subject'].concat(originalHeaders.slice(2));
        
        console.log(`Headers: ${headers.join(' | ')}`);
        
        let currentLabel = "";
        let formattedRows = [];
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].split('|').map(cell => cell.trim());
            
            let rowObj = {};
            
            // Check for a label at the beginning of the row
            if (cells[0] !== "" && isNaN(cells[1])) {
                currentLabel = cells.shift();  // Remove the label cell
            }
            
            // Apply the current label
            rowObj["Label"] = currentLabel;
            
            // Capture the subject (e.g., "tea", "milk")
            if (cells[0] !== "") {
                rowObj["Subject"] = cells.shift();
            } else {
                rowObj["Subject"] = cells[1];
                cells.splice(1, 1);  // Remove the subject cell
            }
            
            for (let j = 2; j < headers.length; j++) {
                rowObj[headers[j]] = cells[j - 2] || "";
            }
            
            // Correct for shifted cells
            if (rowObj["mean value"] === "") {
                rowObj["mean value"] = rowObj["% daily value"];
                rowObj["% daily value"] = rowObj["range"];
                rowObj["range"] = cells[cells.length - 1] || "";  // Capture the last cell as the range
            }
            
            formattedRows.push(rowObj);
        }
        
        // Store the formatted rows in the global formattedData object
        formattedData[selectedSection] = formattedRows;
    }
}

// Function to send the query to the Wolfram API
function sendQuery() {
    var input = $("#query-input").val();
    if (input) {
        queryHistory.push(input);
        updateQueryHistory();
    }

    var url = "wolfram_proxy.php?input=" + encodeURIComponent(input);

    $.ajax({
        url: url,
        type: "GET",
        dataType: "xml",
        success: handleResponse,
        error: function () {
            alert("Error: Could not connect to Wolfram API");
        }
    });

    $("#query-input").val('');
}
// Function to update the query history
function updateQueryHistory() {
    var historyHtml = queryHistory.map(function (query) {
        return '<li>' + query + '</li>';
    }).join('');
    $("#query-history").html(historyHtml);
}
// Function to toggle the display mode
function toggleDisplayMode() {
    displayMode = (displayMode === "text") ? "image" : "text";
    updateDisplay();
}
//Function to update the display with Wolfram results text or images
function updateDisplay() {
    if (displayMode === "text") {
        $("#result-container").html(textResultsHtml);
    } else {
        $("#result-container").html(imgResultsHtml);
    }
}

// Function to update the dropdown options
function updateSectionDropdown() {
    const select = document.getElementById('section-select');
    select.innerHTML = availableSections.map(section => `<option value="${section}">${section}</option>`).join('');
  }
  
  // Function to render the infographic
function renderInfographic() {
    const selectedSection = document.getElementById('section-select').value;
    
    // Convert textResultsHtml to a DOM object
    const parser = new DOMParser();
    const doc = parser.parseFromString(textResultsHtml, 'text/html');
    
    // Find the <h3> element that matches the selected section
    const sectionHeader = Array.from(doc.querySelectorAll('h3')).find(h3 => h3.textContent === selectedSection);
    
    // Initialize sectionText as an empty string
    let sectionText = "";
    
    if (sectionHeader) {
        // Get the next sibling (should be the <p> element containing the text)
        const sectionParagraph = sectionHeader.nextElementSibling;
        
        if (sectionParagraph) {
            sectionText = sectionParagraph.textContent;
        }
    }
    
    // Call the function to populate formattedData based on the selected section
    parseAndFormatData(selectedSection, sectionText);
    
    console.log(`Selected Section: ${selectedSection}`);
    console.log(`Formatted Data: ${JSON.stringify(formattedData[selectedSection], null, 2)}`);
  
    // TODO: Use D3 to render the infographic based on the selected section
}






