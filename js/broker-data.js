// Broker data array
const brokerData = [
    {
        name: "IG",
        score: "4.5/5",
        popularity: "28K",
        usStockFee: "$0.0",
        eurusdSpread: "0.6",
        sp500Spread: "0.4",
        withdrawalFee: "$0",
        depositFee: "$0",
        inactivityFee: "No",
        regulators: "FCA in the UK, BaFin in Germany, ASIC in Australia",
        foundationDate: "1974",
        investorProtection: "Yes",
        minDeposit: "$0",
        bankTransfer: "Yes",
        creditDebit: "Yes",
        paypal: "Yes",
        wise: "Yes",
        revolut: "No",
        otherEWallets: "No",
        mobileScore: "5.0/5",
        webScore: "5.0/5",
        mt4: "Yes",
        mt5: "Yes",
        customerService: "4.4/5",
        accountOpening: "4.4/5",
        overallScore: "4.70/5"
    },
    {
        name: "moomoo",
        score: "4.0/5",
        popularity: "1.9K",
        usStockFee: "$0.0",
        eurusdSpread: "N/A",
        sp500Spread: "N/A",
        withdrawalFee: "$0",
        depositFee: "$0",
        inactivityFee: "No",
        regulators: "SEC and FINRA in the US, MAS in Singapore, ASIC in Australia, Japan",
        foundationDate: "2018",
        investorProtection: "Yes",
        minDeposit: "$0",
        bankTransfer: "Yes",
        creditDebit: "No",
        paypal: "No",
        wise: "No",
        revolut: "No",
        otherEWallets: "No",
        mobileScore: "5.0/5",
        webScore: "5.0/5",
        mt4: "No",
        mt5: "No",
        customerService: "3.4/5",
        accountOpening: "4.4/5",
        overallScore: "4.45/5"
    },
    {
        name: "MC Markets",
        score: "4.8/5",
        popularity: "30K",
        usStockFee: "$0.0",
        eurusdSpread: "N/A",
        sp500Spread: "0.0",
        withdrawalFee: "$0",
        depositFee: "$0",
        inactivityFee: "No",
        regulators: "FSA and CySEC",
        foundationDate: "2024",
        investorProtection: "Yes",
        minDeposit: "$0",
        bankTransfer: "Yes",
        creditDebit: "No",
        paypal: "No",
        wise: "No",
        revolut: "No",
        otherEWallets: "No",
        mobileScore: "5.0/5",
        webScore: "5.0/5",
        mt4: "No",
        mt5: "No",
        customerService: "3.4/5",
        accountOpening: "3.4/5",
        overallScore: "4.20/5"
    },
    {
        name: "Vantage",
        score: "4.3/5",
        popularity: "30K",
        usStockFee: "N/A",
        eurusdSpread: "0.6",
        sp500Spread: "0.4",
        withdrawalFee: "$0",
        depositFee: "$0",
        inactivityFee: "No",
        regulators: "ASIC in Australia",
        foundationDate: "2009",
        investorProtection: "No",
        minDeposit: "$0",
        bankTransfer: "Yes",
        creditDebit: "Yes",
        paypal: "Yes",
        wise: "Yes",
        revolut: "Yes",
        otherEWallets: "Yes",
        mobileScore: "4.4/5",
        webScore: "4.5/5",
        mt4: "Yes",
        mt5: "Yes",
        customerService: "4.4/5",
        accountOpening: "4.4/5",
        overallScore: "4.43/5"
    },
    {
        name: "FP Markets",
        score: "4.3/5",
        popularity: "30K",
        usStockFee: "N/A",
        eurusdSpread: "0.6",
        sp500Spread: "0.4",
        withdrawalFee: "$0",
        depositFee: "$0",
        inactivityFee: "No",
        regulators: "ASIC in Australia",
        foundationDate: "2005",
        investorProtection: "No",
        minDeposit: "$0",
        bankTransfer: "Yes",
        creditDebit: "Yes",
        paypal: "Yes",
        wise: "Yes",
        revolut: "Yes",
        otherEWallets: "Yes",
        mobileScore: "4.4/5",
        webScore: "4.5/5",
        mt4: "Yes",
        mt5: "Yes",
        customerService: "4.4/5",
        accountOpening: "4.4/5",
        overallScore: "4.43/5"
    }
    // Add more brokers as needed
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
            <td>${broker.popularity}</td>
            <td>${broker.usStockFee}</td>
            <td>${broker.eurusdSpread}</td>
            <td>${broker.sp500Spread}</td>
            <td>${broker.withdrawalFee}</td>
            <td>${broker.depositFee}</td>
            <td>${broker.inactivityFee}</td>
            <td>${broker.regulators}</td>
            <td>${broker.foundationDate}</td>
            <td>${broker.investorProtection}</td>
            <td>${broker.minDeposit}</td>
            <td>${broker.bankTransfer}</td>
            <td>${broker.creditDebit}</td>
            <td>${broker.paypal}</td>
            <td>${broker.wise}</td>
            <td>${broker.revolut}</td>
            <td>${broker.otherEWallets}</td>
            <td>${broker.mobileScore}</td>
            <td>${broker.webScore}</td>
            <td>${broker.mt4}</td>
            <td>${broker.mt5}</td>
            <td>${broker.customerService}</td>
            <td>${broker.accountOpening}</td>
            <td>${broker.overallScore}</td>
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