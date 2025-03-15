// Market Sentiment Voting System

// Helper function to get today's date as a string (YYYY-MM-DD)
function getTodayDateString() {
    const today = new Date();
    return today.toISOString().split('T')[0]; // Format: YYYY-MM-DD
}

// Initialize the votes from localStorage or use defaults
// New structure: { currentVotes: {long: 0, short: 0}, history: { "2023-08-15": {long: 5, short: 3}, ... } }
let voteData = {
    currentVotes: {
        long: 0,
        short: 0
    },
    history: {}
};

// Check if we have stored votes
if (localStorage.getItem('marketVotes')) {
    try {
        voteData = JSON.parse(localStorage.getItem('marketVotes'));
        
        // Check if we need to reset for a new day
        const today = getTodayDateString();
        if (!voteData.history[today]) {
            // Save yesterday's votes to history if they exist
            const votesToSave = { ...voteData.currentVotes };
            if (votesToSave.long > 0 || votesToSave.short > 0) {
                // Find yesterday's date
                const yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                const yesterdayString = yesterday.toISOString().split('T')[0];
                
                // Save to history
                voteData.history[yesterdayString] = votesToSave;
            }
            
            // Reset current votes for today
            voteData.currentVotes = { long: 0, short: 0 };
            localStorage.setItem('marketVotes', JSON.stringify(voteData));
        }
    } catch (e) {
        console.error('Error loading votes from storage', e);
        localStorage.removeItem('marketVotes');
    }
}

// DOM elements
const longVoteBtn = document.getElementById('vote-long');
const shortVoteBtn = document.getElementById('vote-short');
const longBar = document.getElementById('long-bar');
const shortBar = document.getElementById('short-bar');
const longVotes = document.getElementById('long-votes');
const shortVotes = document.getElementById('short-votes');
const totalVotes = document.getElementById('total-votes');

// Update the display with current votes
function updateVoteDisplay() {
    const currentVotes = voteData.currentVotes;
    const total = currentVotes.long + currentVotes.short;
    
    // Update counters
    longVotes.textContent = currentVotes.long;
    shortVotes.textContent = currentVotes.short;
    totalVotes.textContent = total;
    
    // Calculate percentages
    let longPercent = 50;
    let shortPercent = 50;
    
    if (total > 0) {
        longPercent = Math.round((currentVotes.long / total) * 100);
        shortPercent = Math.round((currentVotes.short / total) * 100);
    }
    
    // Update the bars
    longBar.style.width = longPercent + '%';
    shortBar.style.width = shortPercent + '%';
    
    // Save to localStorage
    localStorage.setItem('marketVotes', JSON.stringify(voteData));
    
    // Update history display
    renderVoteHistory();
}

// Add click event listeners to the vote buttons
longVoteBtn.addEventListener('click', function() {
    voteData.currentVotes.long++;
    updateVoteDisplay();
    
    // Briefly highlight the button to give feedback
    this.classList.add('voted');
    setTimeout(() => {
        this.classList.remove('voted');
    }, 300);
});

shortVoteBtn.addEventListener('click', function() {
    voteData.currentVotes.short++;
    updateVoteDisplay();
    
    // Briefly highlight the button to give feedback
    this.classList.add('voted');
    setTimeout(() => {
        this.classList.remove('voted');
    }, 300);
});

// Create and render the vote history
function renderVoteHistory() {
    // Check if the history container exists, if not, create it
    let historyContainer = document.getElementById('vote-history-container');
    
    if (!historyContainer) {
        // Create the history container
        historyContainer = document.createElement('div');
        historyContainer.id = 'vote-history-container';
        historyContainer.className = 'vote-history-container';
        
        // Add title
        const historyTitle = document.createElement('h3');
        historyTitle.textContent = 'Previous Vote Results';
        historyContainer.appendChild(historyTitle);
        
        // Insert after the voting results
        const votingSection = document.querySelector('.voting-section');
        const votingResults = document.querySelector('.voting-results');
        votingSection.insertBefore(historyContainer, votingResults.nextSibling);
    }
    
    // Clear existing history display
    while (historyContainer.children.length > 1) {
        historyContainer.removeChild(historyContainer.lastChild);
    }
    
    // Convert history object to array and sort by date (most recent first)
    const historyEntries = Object.entries(voteData.history)
        .map(([date, votes]) => ({ date, votes }))
        .sort((a, b) => new Date(b.date) - new Date(a.date));
    
    if (historyEntries.length === 0) {
        const noHistory = document.createElement('p');
        noHistory.textContent = 'No previous voting data available yet.';
        noHistory.className = 'no-history';
        historyContainer.appendChild(noHistory);
        return;
    }
    
    // Create a table for the history
    const table = document.createElement('table');
    table.className = 'history-table';
    
    // Create table header
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    
    const dateHeader = document.createElement('th');
    dateHeader.textContent = 'Date';
    headerRow.appendChild(dateHeader);
    
    const longHeader = document.createElement('th');
    longHeader.textContent = 'Long';
    headerRow.appendChild(longHeader);
    
    const shortHeader = document.createElement('th');
    shortHeader.textContent = 'Short';
    headerRow.appendChild(shortHeader);
    
    const resultHeader = document.createElement('th');
    resultHeader.textContent = 'Result';
    headerRow.appendChild(resultHeader);
    
    thead.appendChild(headerRow);
    table.appendChild(thead);
    
    // Create table body
    const tbody = document.createElement('tbody');
    
    historyEntries.forEach(entry => {
        const row = document.createElement('tr');
        
        // Format date
        const dateParts = entry.date.split('-');
        const formattedDate = `${dateParts[1]}/${dateParts[2]}/${dateParts[0]}`;
        
        const dateCell = document.createElement('td');
        dateCell.textContent = formattedDate;
        row.appendChild(dateCell);
        
        const longCell = document.createElement('td');
        longCell.textContent = entry.votes.long;
        row.appendChild(longCell);
        
        const shortCell = document.createElement('td');
        shortCell.textContent = entry.votes.short;
        row.appendChild(shortCell);
        
        const resultCell = document.createElement('td');
        const total = entry.votes.long + entry.votes.short;
        
        if (total > 0) {
            const longPercent = Math.round((entry.votes.long / total) * 100);
            const winner = entry.votes.long > entry.votes.short ? 'LONG' : 
                          entry.votes.long < entry.votes.short ? 'SHORT' : 'TIE';
            
            const resultBar = document.createElement('div');
            resultBar.className = 'result-mini-bar';
            
            const longPart = document.createElement('div');
            longPart.className = 'long-part';
            longPart.style.width = `${longPercent}%`;
            
            const shortPart = document.createElement('div');
            shortPart.className = 'short-part';
            shortPart.style.width = `${100 - longPercent}%`;
            
            resultBar.appendChild(longPart);
            resultBar.appendChild(shortPart);
            
            const winnerSpan = document.createElement('span');
            winnerSpan.className = `winner ${winner.toLowerCase()}`;
            winnerSpan.textContent = winner;
            
            resultCell.appendChild(resultBar);
            resultCell.appendChild(winnerSpan);
        } else {
            resultCell.textContent = 'No votes';
        }
        
        row.appendChild(resultCell);
        tbody.appendChild(row);
    });
    
    table.appendChild(tbody);
    historyContainer.appendChild(table);
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateVoteDisplay();
    
    // Add CSS for the voted animation and history table
    const style = document.createElement('style');
    style.textContent = `
        .vote-btn.voted {
            transform: scale(1.1);
            opacity: 0.8;
        }
        
        .vote-history-container {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .vote-history-container h3 {
            margin-bottom: 15px;
            color: #0088cc;
        }
        
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .history-table th,
        .history-table td {
            padding: 8px 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .history-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        
        .history-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .result-mini-bar {
            height: 10px;
            width: 100%;
            display: flex;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }
        
        .long-part {
            height: 100%;
            background-color: #4CAF50;
        }
        
        .short-part {
            height: 100%;
            background-color: #F44336;
        }
        
        .winner {
            font-weight: bold;
            font-size: 12px;
            padding: 2px 5px;
            border-radius: 3px;
        }
        
        .winner.long {
            background-color: rgba(76, 175, 80, 0.2);
            color: #2E7D32;
        }
        
        .winner.short {
            background-color: rgba(244, 67, 54, 0.2);
            color: #C62828;
        }
        
        .winner.tie {
            background-color: rgba(158, 158, 158, 0.2);
            color: #616161;
        }
        
        .no-history {
            color: #777;
            font-style: italic;
        }
    `;
    document.head.appendChild(style);
}); 