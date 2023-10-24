var queryHistory = [];
var displayMode = "text";  // Default display mode
var textResultsHtml = "";  // Store text results
var imgResultsHtml = "";  // Store image results

$(document).ready(function () {
    $("#query-button").click(function () {
        sendQuery();
    });

    $("#toggle-button").click(function () {
        toggleDisplayMode();
    });
});

function handleResponse(xml) {
    textResultsHtml = "";
    imgResultsHtml = "";
    var hasResults = false;  // Flag to check if any results were returned

    $(xml).find('pod').each(function () {
        hasResults = true;  // Results were found
        var title = $(this).attr('title');
        var resultText = $(this).find('plaintext').text();
        var resultImgSrc = $(this).find('img').attr('src');

        // Block to parse and log the resultText
        if (title === "Carbohydrates") {  
            console.log(`\nSection: ${title}`);
            
            const rows = resultText.split('\n');
            const originalHeaders = rows[0].split('|').map(h => h.trim());
            
            // Add custom headers for Label and Subject
            const headers = ['Label', 'Subject'].concat(originalHeaders.slice(2));
            
            console.log(`Headers: ${headers.join(' | ')}`);
            
            let currentLabel = "";
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
                
                console.log(`Row: ${JSON.stringify(rowObj)}`);
            }
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
}



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

function updateQueryHistory() {
    var historyHtml = queryHistory.map(function (query) {
        return '<li>' + query + '</li>';
    }).join('');
    $("#query-history").html(historyHtml);
}

function toggleDisplayMode() {
    displayMode = (displayMode === "text") ? "image" : "text";
    updateDisplay();
}

function updateDisplay() {
    if (displayMode === "text") {
        $("#result-container").html(textResultsHtml);
    } else {
        $("#result-container").html(imgResultsHtml);
    }
}