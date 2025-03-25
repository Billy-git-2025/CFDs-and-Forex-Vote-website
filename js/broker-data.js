// Broker data array
const brokerData = [
    {
        name: "VT Markets",
        score: "4.1/5",
        popularity: "3.1K",
        usStockFee: "N/A",
        eurusdSpread: "0.2",
        sp500Spread: "0.4",
        withdrawalFee: "$0",
        depositFee: "$0",
        inactivityFee: "No",
        regulators: "ASIC in Australia",
        foundationDate: "2016",
        investorProtection: "No",
        minDeposit: "$100",
        bankTransfer: "Yes",
        creditDebit: "Yes",
        paypal: "No",
        wise: "No",
        revolut: "Yes",
        otherEwallets: "Yes",
        mobilePlatformScore: "4.1/5",
        webPlatformScore: "2.8/5",
        mt4: "Yes",
        mt5: "Yes",
        customerServiceScore: "3.1/5",
        accountOpeningScore: "5.0/5"
    },
    // Add all other brokers here from the HTML data
];

// Sorting functions
const sortFunctions = {
    score: (a, b) => parseFloat(b.score) - parseFloat(a.score),
    popularity: (a, b) => parseInt(b.popularity) - parseInt(a.popularity),
    fees: (a, b) => {
        const aFee = a.usStockFee === "N/A" ? Infinity : parseFloat(a.usStockFee);
        const bFee = b.usStockFee === "N/A" ? Infinity : parseFloat(b.usStockFee);
        return aFee - bFee;
    },
    minDeposit: (a, b) => {
        const aDeposit = parseInt(a.minDeposit.replace("$", ""));
        const bDeposit = parseInt(b.minDeposit.replace("$", ""));
        return aDeposit - bDeposit;
    }
};

// Filter functions
function filterByRegulator(brokers, regulator) {
    if (regulator === "all") return brokers;
    return brokers.filter(broker => 
        broker.regulators.toLowerCase().includes(regulator.toLowerCase())
    );
}

function filterByMinScore(brokers, minScore) {
    if (!minScore) return brokers;
    return brokers.filter(broker => 
        parseFloat(broker.score) >= minScore
    );
}

function filterByFeature(brokers, feature) {
    if (feature === "all") return brokers;
    const featureMap = {
        mt4: "mt4",
        mt5: "mt5",
        paypal: "paypal"
    };
    return brokers.filter(broker => 
        broker[featureMap[feature]] === "Yes"
    );
}

// Event listeners
document.getElementById("sortBy").addEventListener("change", (e) => {
    const sortedData = [...brokerData].sort(sortFunctions[e.target.value]);
    renderBrokerData(sortedData);
});

document.getElementById("filterRegulator").addEventListener("change", (e) => {
    let filteredData = filterByRegulator(brokerData, e.target.value);
    const minScore = document.getElementById("minScore").value;
    if (minScore) {
        filteredData = filterByMinScore(filteredData, parseFloat(minScore));
    }
    const feature = document.getElementById("filterFeatures").value;
    filteredData = filterByFeature(filteredData, feature);
    renderBrokerData(filteredData);
});

document.getElementById("minScore").addEventListener("input", (e) => {
    let filteredData = filterByMinScore(brokerData, parseFloat(e.target.value));
    const regulator = document.getElementById("filterRegulator").value;
    filteredData = filterByRegulator(filteredData, regulator);
    const feature = document.getElementById("filterFeatures").value;
    filteredData = filterByFeature(filteredData, feature);
    renderBrokerData(filteredData);
});

document.getElementById("filterFeatures").addEventListener("change", (e) => {
    let filteredData = filterByFeature(brokerData, e.target.value);
    const regulator = document.getElementById("filterRegulator").value;
    filteredData = filterByRegulator(filteredData, regulator);
    const minScore = document.getElementById("minScore").value;
    if (minScore) {
        filteredData = filterByMinScore(filteredData, parseFloat(minScore));
    }
    renderBrokerData(filteredData);
}); 