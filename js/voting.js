// Market Sentiment Voting System with Firebase

// *** IMPORTANT: REPLACE WITH YOUR OWN FIREBASE CONFIG ***
// You'll get this from Firebase console > Project settings > General > Your apps
const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_PROJECT_ID.appspot.com",
    messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
    appId: "YOUR_APP_ID"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();
const votesCollection = db.collection("votes");

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

// Fetch the current votes from Firebase
function fetchVotes() {
    // Get all vote documents
    votesCollection.get()
        .then(querySnapshot => {
            // Reset counts
            votes.long = 0;
            votes.short = 0;
            
            // Count each vote by type
            querySnapshot.forEach(doc => {
                const voteData = doc.data();
                if (voteData.type === 'long') {
                    votes.long++;
                } else if (voteData.type === 'short') {
                    votes.short++;
                }
            });
            
            // Update the UI
            updateVoteDisplay();
            
            // Store in localStorage as backup
            localStorage.setItem('marketVotes', JSON.stringify(votes));
        })
        .catch(error => {
            console.error('Error fetching votes:', error);
            
            // Fall back to localStorage if available
            if (localStorage.getItem('marketVotes')) {
                try {
                    const localVotes = JSON.parse(localStorage.getItem('marketVotes'));
                    votes = localVotes;
                    updateVoteDisplay();
                } catch (e) {
                    console.error('Error parsing local votes:', e);
                }
            }
        });
}

// Submit a vote to Firebase
function submitVote(voteType) {
    // Get client IP (optional - if you want to limit one vote per IP)
    // This method just gives an approximation and isn't 100% reliable
    fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            const clientIP = data.ip;
            
            // Add the vote to Firebase
            votesCollection.add({
                type: voteType,
                timestamp: firebase.firestore.FieldValue.serverTimestamp(),
                ip: clientIP
            })
            .then(() => {
                console.log('Vote successfully submitted');
                
                // Update local counts for immediate feedback
                votes[voteType]++;
                updateVoteDisplay();
                
                // Then fetch all votes to ensure accuracy
                fetchVotes();
            })
            .catch(error => {
                console.error('Error submitting vote:', error);
                
                // Fallback to local counting if Firebase fails
                votes[voteType]++;
                updateVoteDisplay();
                localStorage.setItem('marketVotes', JSON.stringify(votes));
            });
        })
        .catch(error => {
            // If IP lookup fails, just submit the vote without IP
            votesCollection.add({
                type: voteType,
                timestamp: firebase.firestore.FieldValue.serverTimestamp()
            })
            .then(() => {
                console.log('Vote successfully submitted (without IP)');
                votes[voteType]++;
                updateVoteDisplay();
                fetchVotes();
            })
            .catch(error => {
                console.error('Error submitting vote:', error);
                votes[voteType]++;
                updateVoteDisplay();
                localStorage.setItem('marketVotes', JSON.stringify(votes));
            });
        });
}

// Update the display with current votes
function updateVoteDisplay() {
    const total = votes.long + votes.short;
    
    // Update total vote count
    totalVotes.textContent = total;
    
    // Calculate percentages
    let longPercent = 50;
    let shortPercent = 50;
    
    if (total > 0) {
        longPercent = Math.round((votes.long / total) * 100);
        shortPercent = Math.round((votes.short / total) * 100);
    }
    
    // Update percentage displays
    longPercentage.textContent = longPercent + '%';
    shortPercentage.textContent = shortPercent + '%';
    
    // Update the progress bar with gradient showing the split
    voteProgressBar.style.backgroundImage = `linear-gradient(135deg, 
        #327fff ${longPercent}%, 
        transparent ${longPercent}%, 
        transparent ${longPercent + 1}%, 
        #ff6900 ${longPercent + 1}%, 
        #ff6900)`;
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

// Setup real-time listener for vote changes
function setupRealtimeUpdates() {
    votesCollection.onSnapshot(snapshot => {
        // Reset counts
        votes.long = 0;
        votes.short = 0;
        
        // Count each vote by type
        snapshot.forEach(doc => {
            const voteData = doc.data();
            if (voteData.type === 'long') {
                votes.long++;
            } else if (voteData.type === 'short') {
                votes.short++;
            }
        });
        
        // Update the UI
        updateVoteDisplay();
    }, error => {
        console.error("Realtime updates error:", error);
    });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    // Fetch initial votes
    fetchVotes();
    
    // Setup realtime updates
    setupRealtimeUpdates();
}); 