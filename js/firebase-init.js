// Import the functions you need from the SDKs you need
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js';
import { getFirestore, collection, addDoc, query, where, getDocs, doc, updateDoc, increment, serverTimestamp } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js';
import { getAnalytics } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-analytics.js';

// Your web app's Firebase configuration
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

export { db, collection, addDoc, query, where, getDocs, doc, updateDoc, increment, serverTimestamp }; 