var queryHistory = [];
var displayMode = "text";  // Default display mode
var textResultsHtml = "";  // Store text results
var imgResultsHtml = "";  // Store image results
var availableSections = [];  // Store available sections
var formattedData = {};  // Store formatted data


// initialHide() only once
$(document).ready(function () {
    initialHide();
});

// Listeners for the buttons
$(document).ready(function () {
    // Existing listeners
    $("#query-button").click(function () {
        sendQuery();
    });

    $("#toggle-button").click(function () {

        toggleDisplayMode();
        event.stopPropagation();
    });

    $("#show-infographic").click(function () {
        renderInfographic();
    });

    // Toggle arrow click handler
    $("#toggle-arrow").click(function () {
        toggleVisibility("wolfram-results");
    });


    // Initially attach the "on" listener for the toggle-arrow
    attachOnListener();
});

function initialHide() {
    // Initially hide the D3 Infographic and Wolfram Results sections
    toggleVisibility("d3InfoResults", false);
    toggleVisibility("wolfram-results", false);
    toggleVisibility("wolframRawResults", false);

}
// Function to attach the "on" listener
function attachOnListener() {
    $("#toggle-arrow").off("click"); // Remove any existing click listeners
    $("#toggle-arrow").on("click", function () {
        console.log("Toggle ON");
        const isCurrentlyVisible = $("#wolfram-results").is(":visible");
        toggleVisibility("wolfram-results", !isCurrentlyVisible);

        // Change the arrow to point upwards
        $("#toggle-arrow").html("&#9650;");

        // Switch to the "off" listener
        attachOffListener();
    });
}

// Function to attach the "off" listener
function attachOffListener() {
    $("#toggle-arrow").off("click"); // Remove any existing click listeners
    $("#toggle-arrow").on("click", function () {
        console.log("Toggle OFF");
        const isCurrentlyVisible = $("#wolfram-results").is(":visible");
        toggleVisibility("wolfram-results", !isCurrentlyVisible);

        // Change the arrow to point downwards
        $("#toggle-arrow").html("&#9660;");

        // Switch to the "on" listener
        attachOnListener();
    });
}
// Function to toggle the display mode
function toggleDisplayMode() {
    displayMode = (displayMode === "text") ? "image" : "text";
    updateDisplay();

}
// Function to toggle the visibility of a given element by ID
function toggleVisibility(elementId) {
    const element = document.getElementById(elementId);
    if (element) {  // Check if the element exists
        if (element.classList.contains("hidden")) {
            console.log(`Toggling visibility for ${elementId} to true`);
            element.classList.remove("hidden");
            element.classList.add("visible");
        } else {
            console.log(`Toggling visibility for ${elementId} to false`);
            element.classList.remove("visible");
            element.classList.add("hidden");
        }
    }
}

// Function to update the display with Wolfram results text or images
function updateDisplay() {
    if (displayMode === "text") {
        $("#result-container").html(textResultsHtml);
    } else {
        $("#result-container").html(imgResultsHtml);
    }

    // Show the D3 Infographic section if there are results
    toggleD3InfographicVisibility(textResultsHtml !== "<p>No results found.</p>");
}
// Function to toggle the visibility of the D3 Infographic section
function toggleD3InfographicVisibility(show) {
    const d3InfoResults = document.getElementById("d3InfoResults");
    if (d3InfoResults) {  // Check if the element exists
        if (show) {
            d3InfoResults.classList.remove("hidden");
        } else {
            d3InfoResults.classList.add("hidden");
        }
    }
}

function fillDropDown(title) {

    // Filter out sections that have not been parsed before adding to availableSections
    //TODO: Parse these sections correctly
    if (!["Input interpretation", "Individual nutrition facts", "Physical properties", "Image"].includes(title)) {
        if (!availableSections.includes(title)) {
            availableSections.push(title);
        }
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

// Function to update the dropdown options
function updateSectionDropdown() {
    const select = document.getElementById('section-select');
    select.innerHTML = availableSections.map(section => `<option value="${section}">${section}</option>`).join('');
}

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

        fillDropDown(title);



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
    toggleVisibility("wolframRawResults", true);
    toggleVisibility("wolfram-results", true);
    renderInfographic();

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




// Function to render the infographic
function renderInfographic() {
    let selectedSection = document.getElementById('section-select').value;

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

    // Clear any existing SVG
    d3.select("#chartArea").select("svg").remove();

    // Get the selected section from the dropdown
    selectedSection = document.getElementById('section-select').value;

    // Get the data for the selected section
    const dataForSection = formattedData[selectedSection];

    // Aggregate the data by subject
    const aggregatedData = {};
    dataForSection.forEach((d) => {
        if (!aggregatedData[d.Subject]) {
            aggregatedData[d.Subject] = {};
        }
        aggregatedData[d.Subject][d.Label] = parseFloat(d['mean value'].split(' ')[0]);
    });

    // Transform aggregatedData into an array of objects
    const dataForD3 = Object.keys(aggregatedData).map((subject) => {
        return {
            subject,
            ...aggregatedData[subject],
        };
    });

    // Set up SVG dimensions
    const width = 800;
    const height = 400;
    const margin = { top: 20, right: 20, bottom: 30, left: 40 };

    // Create SVG element
    const svg = d3.select("#chartArea").append("svg")
        .attr("width", width)
        .attr("height", height);

    // Create scales
    const x0 = d3.scaleBand()
        .domain(dataForD3.map(d => d.subject))
        .rangeRound([margin.left, width - margin.right])
        .paddingInner(0.1);

    const x1 = d3.scaleBand()
        .domain(Object.keys(aggregatedData[Object.keys(aggregatedData)[0]]))
        .rangeRound([0, x0.bandwidth()])
        .padding(0.05);

    const y = d3.scaleLinear()
        .domain([0, d3.max(dataForD3, d => d3.max(Object.values(d).slice(1)))])
        .rangeRound([height - margin.bottom, margin.top]);

    // Create axes
    const xAxis = svg.append("g")
        .attr("transform", `translate(0,${height - margin.bottom})`)
        .call(d3.axisBottom(x0));

    const yAxis = svg.append("g")
        .attr("transform", `translate(${margin.left},0)`)
        .call(d3.axisLeft(y));


    // Define a color scale
    const colorScale = d3.scaleOrdinal(d3.schemeCategory10);

    // Create bars
    svg.append("g")
        .selectAll("g")
        .data(dataForD3)
        .enter().append("g")
        .attr("transform", d => `translate(${x0(d.subject)},0)`)
        .selectAll("rect")
        .data(d => Object.keys(d).slice(1).map(key => ({ key, value: d[key] })))
        .enter().append("rect")
        .attr("x", d => x1(d.key))
        .attr("y", d => y(d.value))
        .attr("width", x1.bandwidth())
        .attr("height", d => y(0) - y(d.value))
        .attr("fill", d => colorScale(d.key));

    // Add Legend
    const legend = svg.append("g")
        .attr("font-family", "sans-serif")
        .attr("font-size", 10)
        .attr("text-anchor", "end")
        .selectAll("g")
        .data(Object.keys(aggregatedData[Object.keys(aggregatedData)[0]]).slice().reverse())
        .enter().append("g")
        .attr("transform", (d, i) => `translate(0,${i * 20})`);

    legend.append("rect")
        .attr("x", width - 19)
        .attr("width", 19)
        .attr("height", 19)
        .attr("fill", colorScale);

    legend.append("text")
        .attr("x", width - 24)
        .attr("y", 9.5)
        .attr("dy", "0.32em")
        .text(d => d);

    // Add labels, titles, and other elements as needed
}

