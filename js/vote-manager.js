import { db, collection, addDoc, query, where, getDocs, doc, updateDoc, serverTimestamp } from './firebase-init.js';

// Vote Period Manager
class VotePeriodManager {
    constructor() {
        this.votingPeriodsCollection = collection(db, "voting_periods");
        this.currentPeriod = null;
    }

    // Create a new voting period
    async createVotingPeriod(duration) {
        const startTime = new Date();
        const endTime = new Date(startTime.getTime() + duration * 1000); // duration in seconds

        try {
            const docRef = await addDoc(this.votingPeriodsCollection, {
                startTime: startTime,
                endTime: endTime,
                status: 'active',
                totalVotes: 0,
                longVotes: 0,
                shortVotes: 0
            });

            return docRef.id;
        } catch (error) {
            console.error("Error creating voting period:", error);
            throw error;
        }
    }

    // Get the current active voting period
    async getCurrentPeriod() {
        try {
            const now = new Date();
            const q = query(
                this.votingPeriodsCollection,
                where('status', '==', 'active'),
                where('endTime', '>', now)
            );
            
            const snapshot = await getDocs(q);
            
            if (!snapshot.empty) {
                const doc = snapshot.docs[0];
                this.currentPeriod = {
                    id: doc.id,
                    ...doc.data()
                };
                return this.currentPeriod;
            }

            return null;
        } catch (error) {
            console.error("Error getting current period:", error);
            throw error;
        }
    }

    // Close expired voting periods
    async closeExpiredPeriods() {
        try {
            const now = new Date();
            const q = query(
                this.votingPeriodsCollection,
                where('status', '==', 'active'),
                where('endTime', '<=', now)
            );
            
            const snapshot = await getDocs(q);

            const updates = snapshot.docs.map(doc => 
                updateDoc(doc.ref, { status: 'completed' })
            );

            await Promise.all(updates);
        } catch (error) {
            console.error("Error closing expired periods:", error);
            throw error;
        }
    }

    // Get time remaining in current period
    getTimeRemaining() {
        if (!this.currentPeriod) return 0;
        
        const now = new Date();
        const endTime = this.currentPeriod.endTime.toDate();
        const timeRemaining = endTime - now;
        
        return Math.max(0, Math.floor(timeRemaining / 1000)); // Return seconds remaining
    }

    // Format time remaining for display
    formatTimeRemaining(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;

        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
}

export default VotePeriodManager; 