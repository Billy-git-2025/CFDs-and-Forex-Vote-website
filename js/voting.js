// Market Sentiment Voting System

// Initialize the votes object
let votes = {
    long: 0,
    short: 0
};

// DOM elements
const longVoteBtn = document.getElementById('vote-long');
const shortVoteBtn = document.getElementById('vote-short');
const longBar = document.getElementById('long-bar');
const shortBar = document.getElementById('short-bar');
const longVotes = document.getElementById('long-votes');
const shortVotes = document.getElementById('short-votes');
const totalVotes = document.getElementById('total-votes');

// Fetch the current votes from the server
function fetchVotes() {
    fetch('vote.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                votes = data.counts;
                updateVoteDisplay();
            } else {
                console.error('Error fetching votes:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching votes:', error);
            
            // Use local data if already loaded, or fallback to zeros
            if (localStorage.getItem('marketVotes')) {
                try {
                    const localVotes = JSON.parse(localStorage.getItem('marketVotes'));
                    votes = localVotes;
                } catch (e) {
                    console.error('Error parsing local votes:', e);
                }
            }
            
            updateVoteDisplay();
        });
}

// Submit a vote to the server
function submitVote(voteType) {
    // Create form data
    const formData = new FormData();
    formData.append('vote_type', voteType);
    
    // Submit the vote
    fetch('vote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update local votes with server data
            votes = data.counts;
            updateVoteDisplay();
            
            // Store in localStorage as backup
            localStorage.setItem('marketVotes', JSON.stringify(votes));
        } else {
            console.error('Error submitting vote:', data.message);
        }
    })
    .catch(error => {
        console.error('Error submitting vote:', error);
        
        // Fallback to local counting if server fails
        if (voteType === 'long') {
            votes.long++;
        } else if (voteType === 'short') {
            votes.short++;
        }
        
        updateVoteDisplay();
        localStorage.setItem('marketVotes', JSON.stringify(votes));
    });
}

// Update the display with current votes
function updateVoteDisplay() {
    const total = votes.long + votes.short;
    
    // Update counters
    longVotes.textContent = votes.long;
    shortVotes.textContent = votes.short;
    totalVotes.textContent = total;
    
    // Calculate percentages
    let longPercent = 50;
    let shortPercent = 50;
    
    if (total > 0) {
        longPercent = Math.round((votes.long / total) * 100);
        shortPercent = Math.round((votes.short / total) * 100);
    }
    
    // Update the bars
    longBar.style.width = longPercent + '%';
    shortBar.style.width = shortPercent + '%';
}

// Add click event listeners to the vote buttons
longVoteBtn.addEventListener('click', function() {
    submitVote('long');
    
    // Briefly highlight the button to give feedback
    this.classList.add('voted');
    setTimeout(() => {
        this.classList.remove('voted');
    }, 300);
});

shortVoteBtn.addEventListener('click', function() {
    submitVote('short');
    
    // Briefly highlight the button to give feedback
    this.classList.add('voted');
    setTimeout(() => {
        this.classList.remove('voted');
    }, 300);
});

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    // Fetch initial votes
    fetchVotes();
    
    // Add CSS for the voted animation
    const style = document.createElement('style');
    style.textContent = `
        .vote-btn.voted {
            transform: scale(1.1);
            opacity: 0.8;
        }
    `;
    document.head.appendChild(style);
});

// Refresh votes periodically (every 30 seconds)
setInterval(fetchVotes, 30000); 