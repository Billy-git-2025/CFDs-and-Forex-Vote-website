// Market Sentiment Voting System with Firebase

// Note: Firebase configuration is now loaded from config.js
// Make sure to include config.js before this file in your HTML

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();
const votesCollection = db.collection("market_votes");

// Initialize the votes object
let votes = {
    long: 0,
    short: 0
};

// DOM elements
const longVoteBtn = document.getElementById('vote-long');
const shortVoteBtn = document.getElementById('vote-short');
const voteProgressBar = document.getElementById('vote-progress-bar');
const longPercentage = document.getElementById('long-percentage');
const shortPercentage = document.getElementById('short-percentage');
const totalVotes = document.getElementById('total-votes');

// Check if user has voted today
function hasVotedToday() {
    const lastVote = localStorage.getItem('lastVoteDate');
    if (!lastVote) return false;
    
    const today = new Date().toDateString();
    return lastVote === today;
}

// Set last vote date
function setLastVoteDate() {
    localStorage.setItem('lastVoteDate', new Date().toDateString());
}

// Fetch the current votes from Firebase
function fetchVotes() {
    votesCollection.get()
        .then(querySnapshot => {
            votes.long = 0;
            votes.short = 0;
            
            querySnapshot.forEach(doc => {
                const voteData = doc.data();
                if (voteData.type === 'long') {
                    votes.long++;
                } else if (voteData.type === 'short') {
                    votes.short++;
                }
            });
            
            updateVoteDisplay();
            localStorage.setItem('marketVotes', JSON.stringify(votes));
        })
        .catch(error => {
            console.error('Error fetching votes:', error);
            
            const localVotes = localStorage.getItem('marketVotes');
            if (localVotes) {
                try {
                    votes = JSON.parse(localVotes);
                    updateVoteDisplay();
                } catch (e) {
                    console.error('Error parsing local votes:', e);
                }
            }
        });
}

// Submit a vote to Firebase
function submitVote(voteType) {
    if (hasVotedToday()) {
        alert('You have already voted today. Please come back tomorrow!');
        return;
    }

    votesCollection.add({
        type: voteType,
        timestamp: firebase.firestore.FieldValue.serverTimestamp(),
        date: new Date().toDateString()
    })
    .then(() => {
        console.log('Vote successfully submitted');
        votes[voteType]++;
        updateVoteDisplay();
        setLastVoteDate();
        fetchVotes(); // Refresh all votes
    })
    .catch(error => {
        console.error('Error submitting vote:', error);
        alert('Error submitting vote. Please try again.');
    });
}

// Update the display with current votes
function updateVoteDisplay() {
    const total = votes.long + votes.short;
    totalVotes.textContent = total;
    
    let longPercent = 50;
    let shortPercent = 50;
    
    if (total > 0) {
        longPercent = Math.round((votes.long / total) * 100);
        shortPercent = Math.round((votes.short / total) * 100);
    }
    
    longPercentage.textContent = longPercent + '%';
    shortPercentage.textContent = shortPercent + '%';
    
    voteProgressBar.style.backgroundImage = `linear-gradient(to right, 
        #327fff ${longPercent}%, 
        #ff6900 ${longPercent}%)`;
}

// Add click event listeners to the vote buttons
if (longVoteBtn && shortVoteBtn) {
    longVoteBtn.addEventListener('click', function() {
        submitVote('long');
        this.classList.add('voted');
        setTimeout(() => this.classList.remove('voted'), 300);
    });

    shortVoteBtn.addEventListener('click', function() {
        submitVote('short');
        this.classList.add('voted');
        setTimeout(() => this.classList.remove('voted'), 300);
    });
}

// Setup real-time listener for vote changes
function setupRealtimeUpdates() {
    votesCollection.onSnapshot(snapshot => {
        votes.long = 0;
        votes.short = 0;
        
        snapshot.forEach(doc => {
            const voteData = doc.data();
            if (voteData.type === 'long') {
                votes.long++;
            } else if (voteData.type === 'short') {
                votes.short++;
            }
        });
        
        updateVoteDisplay();
    }, error => {
        console.error("Realtime updates error:", error);
    });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.vote_mount')) {
        fetchVotes();
        setupRealtimeUpdates();
        
        // Check if buttons should be disabled
        if (hasVotedToday()) {
            if (longVoteBtn) longVoteBtn.classList.add('voted');
            if (shortVoteBtn) shortVoteBtn.classList.add('voted');
        }
    }
}); 