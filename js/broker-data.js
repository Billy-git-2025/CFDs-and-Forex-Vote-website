// Broker data array
const brokerData = [
    {
        name: "Plus500",
        score: 85,
        popularity: 95,
        fees: "Low",
        minDeposit: "$100",
        regulator: "FCA",
        mt4: true,
        mt5: false,
        paypal: true
    },
    {
        name: "IG",
        score: 90,
        popularity: 98,
        fees: "Medium",
        minDeposit: "$250",
        regulator: "FCA",
        mt4: true,
        mt5: true,
        paypal: true
    },
    {
        name: "MC Markets",
        score: 88,
        popularity: 92,
        fees: "Low",
        minDeposit: "$200",
        regulator: "ASIC",
        mt4: true,
        mt5: true,
        paypal: true
    },
    {
        name: "XM",
        score: 82,
        popularity: 88,
        fees: "Low",
        minDeposit: "$5",
        regulator: "CySEC",
        mt4: true,
        mt5: true,
        paypal: true
    },
    {
        name: "Pepperstone",
        score: 87,
        popularity: 90,
        fees: "Low",
        minDeposit: "$200",
        regulator: "ASIC",
        mt4: true,
        mt5: true,
        paypal: true
    }
];

// Sorting functions
function sortByScore(a, b) {
    return b.score - a.score;
}

function sortByPopularity(a, b) {
    return b.popularity - a.popularity;
}

function sortByFees(a, b) {
    const feeOrder = { "Low": 1, "Medium": 2, "High": 3 };
    return feeOrder[a.fees] - feeOrder[b.fees];
}

function sortByMinDeposit(a, b) {
    return parseInt(a.minDeposit.replace(/[^0-9]/g, '')) - parseInt(b.minDeposit.replace(/[^0-9]/g, ''));
}

// Filter functions
function filterByRegulator(brokers, regulator) {
    if (!regulator) return brokers;
    return brokers.filter(broker => broker.regulator === regulator);
}

function filterByMinScore(brokers, minScore) {
    return brokers.filter(broker => broker.score >= minScore);
}

// Render function
function renderBrokers(brokers) {
    const tbody = document.getElementById('brokerTableBody');
    tbody.innerHTML = '';

    brokers.forEach(broker => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${broker.name}</td>
            <td>${broker.score}</td>
            <td>${broker.popularity}%</td>
            <td>${broker.fees}</td>
            <td>${broker.minDeposit}</td>
            <td>${broker.regulator}</td>
            <td class="feature-icon">${broker.mt4 ? '✓' : '✗'}</td>
            <td class="feature-icon">${broker.mt5 ? '✓' : '✗'}</td>
            <td class="feature-icon">${broker.paypal ? '✓' : '✗'}</td>
        `;
        tbody.appendChild(row);
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    // Initial render
    renderBrokers(brokerData);

    // Sort by change
    document.getElementById('sortBy').addEventListener('change', (e) => {
        let sortedBrokers = [...brokerData];
        switch (e.target.value) {
            case 'score':
                sortedBrokers.sort(sortByScore);
                break;
            case 'popularity':
                sortedBrokers.sort(sortByPopularity);
                break;
            case 'fees':
                sortedBrokers.sort(sortByFees);
                break;
            case 'minDeposit':
                sortedBrokers.sort(sortByMinDeposit);
                break;
        }
        renderBrokers(sortedBrokers);
    });

    // Regulator filter
    document.getElementById('regulator').addEventListener('change', (e) => {
        const filteredBrokers = filterByRegulator(brokerData, e.target.value);
        renderBrokers(filteredBrokers);
    });

    // Minimum score filter
    document.getElementById('minScore').addEventListener('input', (e) => {
        const minScore = parseInt(e.target.value) || 0;
        const filteredBrokers = filterByMinScore(brokerData, minScore);
        renderBrokers(filteredBrokers);
    });
}); 