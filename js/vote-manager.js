// Vote Period Manager
class VotePeriodManager {
    constructor() {
        this.db = firebase.firestore();
        this.votingPeriodsCollection = this.db.collection("voting_periods");
        this.currentPeriod = null;
    }

    // Create a new voting period
    async createVotingPeriod(duration) {
        const startTime = new Date();
        const endTime = new Date(startTime.getTime() + duration * 1000); // duration in seconds

        try {
            const docRef = await this.votingPeriodsCollection.add({
                startTime: firebase.firestore.Timestamp.fromDate(startTime),
                endTime: firebase.firestore.Timestamp.fromDate(endTime),
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
            const now = firebase.firestore.Timestamp.now();
            const snapshot = await this.votingPeriodsCollection
                .where('status', '==', 'active')
                .where('endTime', '>', now)
                .orderBy('endTime', 'asc')
                .limit(1)
                .get();

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
            const now = firebase.firestore.Timestamp.now();
            const snapshot = await this.votingPeriodsCollection
                .where('status', '==', 'active')
                .where('endTime', '<=', now)
                .get();

            const batch = this.db.batch();
            snapshot.docs.forEach(doc => {
                batch.update(doc.ref, { status: 'completed' });
            });

            await batch.commit();
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