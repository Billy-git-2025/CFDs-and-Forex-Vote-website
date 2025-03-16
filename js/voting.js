// Market Sentiment Voting System with Firebase

// Note: Firebase configuration is now loaded from config.js
// Make sure to include config.js before this file in your HTML

import { db, collection, addDoc, query, where, getDocs, doc, updateDoc, increment, serverTimestamp } from './firebase-init.js';
import VotePeriodManager from './vote-manager.js';

// Initialize collections
const votesCollection = collection(db, "market_votes");

// Initialize vote manager
const voteManager = new VotePeriodManager();

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
const timeRemainingDisplay = document.getElementById('time-remaining');
const createVoteBtn = document.getElementById('create-vote');

let currentPeriodId = null;
let timeRemainingInterval = null;

// Check if user has voted in current period
async function hasVotedInPeriod(periodId) {
    const userId = localStorage.getItem('userId') || generateUserId();
    const q = query(
        votesCollection,
        where('periodId', '==', periodId),
        where('userId', '==', userId)
    );
    const snapshot = await getDocs(q);
    return !snapshot.empty;
}

// Generate a unique user ID
function generateUserId() {
    const userId = 'user_' + Math.random().toString(36).substr(2, 9);
    localStorage.setItem('userId', userId);
    return userId;
}

// Update time remaining display
function updateTimeRemaining() {
    const seconds = voteManager.getTimeRemaining();
    if (seconds <= 0) {
        clearInterval(timeRemainingInterval);
        disableVoting();
        checkAndCreateNewPeriod();
        return;
    }
    
    timeRemainingDisplay.textContent = voteManager.formatTimeRemaining(seconds);
}

// Disable voting buttons
function disableVoting() {
    if (longVoteBtn) longVoteBtn.disabled = true;
    if (shortVoteBtn) shortVoteBtn.disabled = true;
    timeRemainingDisplay.textContent = 'Voting period ended';
}

// Enable voting buttons
function enableVoting() {
    if (longVoteBtn) longVoteBtn.disabled = false;
    if (shortVoteBtn) shortVoteBtn.disabled = false;
}

// Check and create new voting period if needed
async function checkAndCreateNewPeriod() {
    await voteManager.closeExpiredPeriods();
    const currentPeriod = await voteManager.getCurrentPeriod();
    
    if (!currentPeriod) {
        if (createVoteBtn) {
            createVoteBtn.style.display = 'block';
        }
    }
}

// Create a new voting period
async function createNewVotingPeriod() {
    try {
        const duration = 7 * 24 * 60 * 60; // 7 days in seconds
        const periodId = await voteManager.createVotingPeriod(duration);
        await initializeVoting();
        if (createVoteBtn) {
            createVoteBtn.style.display = 'none';
        }
    } catch (error) {
        console.error('Error creating new voting period:', error);
        alert('Error creating new voting period. Please try again.');
    }
}

// Submit a vote to Firebase
async function submitVote(voteType) {
    if (!currentPeriodId) {
        alert('No active voting period.');
        return;
    }

    const hasVoted = await hasVotedInPeriod(currentPeriodId);
    if (hasVoted) {
        alert('You have already voted in this period!');
        return;
    }

    const userId = localStorage.getItem('userId') || generateUserId();

    try {
        await addDoc(votesCollection, {
            type: voteType,
            timestamp: serverTimestamp(),
            periodId: currentPeriodId,
            userId: userId
        });

        console.log('Vote successfully submitted');
        votes[voteType]++;
        updateVoteDisplay();
        
        // Update vote counts in period document
        const periodRef = doc(db, "voting_periods", currentPeriodId);
        await updateDoc(periodRef, {
            [`${voteType}Votes`]: increment(1),
            totalVotes: increment(1)
        });
    } catch (error) {
        console.error('Error submitting vote:', error);
        alert('Error submitting vote. Please try again.');
    }
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

// Initialize voting system
async function initializeVoting() {
    try {
        await voteManager.closeExpiredPeriods();
        const currentPeriod = await voteManager.getCurrentPeriod();
        
        if (currentPeriod) {
            currentPeriodId = currentPeriod.id;
            votes.long = currentPeriod.longVotes;
            votes.short = currentPeriod.shortVotes;
            updateVoteDisplay();
            
            // Start time remaining updates
            clearInterval(timeRemainingInterval);
            timeRemainingInterval = setInterval(updateTimeRemaining, 1000);
            updateTimeRemaining();
            
            enableVoting();
            if (createVoteBtn) {
                createVoteBtn.style.display = 'none';
            }
            
            // Check if user has already voted
            const hasVoted = await hasVotedInPeriod(currentPeriodId);
            if (hasVoted) {
                if (longVoteBtn) longVoteBtn.classList.add('voted');
                if (shortVoteBtn) shortVoteBtn.classList.add('voted');
            }
        } else {
            // Automatically create a new voting period if none exists
            console.log("No active voting period found, creating a new one...");
            await createNewVotingPeriod();
        }
    } catch (error) {
        console.error('Error initializing voting:', error);
        // Still show the create button in case of errors
        if (createVoteBtn) {
            createVoteBtn.style.display = 'block';
        }
    }
}

// Add click event listeners
if (longVoteBtn && shortVoteBtn) {
    longVoteBtn.addEventListener('click', function() {
        submitVote('long');
        this.classList.add('voted');
    });

    shortVoteBtn.addEventListener('click', function() {
        submitVote('short');
        this.classList.add('voted');
    });
}

if (createVoteBtn) {
    createVoteBtn.addEventListener('click', createNewVotingPeriod);
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.vote_mount')) {
        initializeVoting();
    }
}); 