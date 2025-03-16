// Import the Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
import { getFirestore, collection, addDoc, query, where, getDocs, updateDoc, increment, doc, onSnapshot } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-analytics.js";

// Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCAeBMb-HfvLhQt5yeEWfioZ29wLWKgcOE",
  authDomain: "voting-ec72a.firebaseapp.com",
  projectId: "voting-ec72a",
  storageBucket: "voting-ec72a.firebasestorage.app",
  messagingSenderId: "92915704760",
  appId: "1:92915704760:web:c67cfddcd65f17e3ab254d",
  measurementId: "G-V9M85WK6TT"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const analytics = getAnalytics(app);

// Collection reference
const votesCollection = collection(db, "market_votes");

// DOM elements
const buyVoteBtn = document.getElementById('vote-buy');
const cashVoteBtn = document.getElementById('vote-cash');
const voteProgressBar = document.getElementById('vote-progress-bar');
const buyPercentage = document.getElementById('buy-percentage');
const cashPercentage = document.getElementById('cash-percentage');
const totalVotes = document.getElementById('total-votes');

// Votes state
let votes = {
  buy: 0,
  cash: 0
};

// Generate a unique user ID if not exists
function getUserId() {
  let userId = localStorage.getItem('userId');
  if (!userId) {
    userId = 'user_' + Math.random().toString(36).substring(2, 15);
    localStorage.setItem('userId', userId);
  }
  return userId;
}

// Check if user has already voted
async function hasUserVoted() {
  const userId = getUserId();
  const q = query(votesCollection, where("userId", "==", userId));
  const querySnapshot = await getDocs(q);
  return !querySnapshot.empty;
}

// Update vote display
function updateVoteDisplay() {
  const total = votes.buy + votes.cash;
  
  if (total === 0) {
    buyPercentage.textContent = '50%';
    cashPercentage.textContent = '50%';
    voteProgressBar.style.backgroundImage = 'linear-gradient(135deg, #327fff 50%, transparent 50%, transparent 51%, #ff6900 51%, #ff6900)';
    totalVotes.textContent = '0';
    return;
  }
  
  const buyPercent = Math.round((votes.buy / total) * 100);
  const cashPercent = 100 - buyPercent;
  
  buyPercentage.textContent = buyPercent + '%';
  cashPercentage.textContent = cashPercent + '%';
  
  voteProgressBar.style.backgroundImage = `linear-gradient(135deg, #327fff ${buyPercent}%, transparent ${buyPercent}%, transparent ${buyPercent + 1}%, #ff6900 ${buyPercent + 1}%, #ff6900)`;
  
  totalVotes.textContent = total;
}

// Submit vote
async function submitVote(voteType) {
  try {
    const hasVoted = await hasUserVoted();
    if (hasVoted) {
      alert('You have already voted!');
      return;
    }
    
    // Add the vote
    await addDoc(votesCollection, {
      type: voteType,
      userId: getUserId(),
      timestamp: new Date()
    });
    
    // Update local votes
    votes[voteType]++;
    updateVoteDisplay();
    
    // Mark button as voted
    if (voteType === 'buy') {
      buyVoteBtn.classList.add('voted');
    } else {
      cashVoteBtn.classList.add('voted');
    }
    
  } catch (error) {
    console.error("Error submitting vote:", error);
    alert("Error submitting vote. Please try again.");
  }
}

// Fetch all votes
async function fetchAllVotes() {
  try {
    const querySnapshot = await getDocs(votesCollection);
    votes.buy = 0;
    votes.cash = 0;
    
    querySnapshot.forEach((doc) => {
      const vote = doc.data();
      if (vote.type === 'buy') {
        votes.buy++;
      } else if (vote.type === 'cash') {
        votes.cash++;
      }
    });
    
    updateVoteDisplay();
    
    // Check if user has voted
    const hasVoted = await hasUserVoted();
    if (hasVoted) {
      buyVoteBtn.classList.add('disabled');
      cashVoteBtn.classList.add('disabled');
    }
    
  } catch (error) {
    console.error("Error fetching votes:", error);
  }
}

// Listen for vote changes
function listenForVotes() {
  onSnapshot(votesCollection, (snapshot) => {
    votes.buy = 0;
    votes.cash = 0;
    
    snapshot.forEach((doc) => {
      const vote = doc.data();
      if (vote.type === 'buy') {
        votes.buy++;
      } else if (vote.type === 'cash') {
        votes.cash++;
      }
    });
    
    updateVoteDisplay();
  });
}

// Add event listeners
if (buyVoteBtn && cashVoteBtn) {
  buyVoteBtn.addEventListener('click', () => submitVote('buy'));
  cashVoteBtn.addEventListener('click', () => submitVote('cash'));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
  fetchAllVotes();
  listenForVotes();
});

export { votes, updateVoteDisplay, fetchAllVotes }; 