/**
 * Offline Appointment Booking System for MESMTF
 * Allows appointment booking when offline
 */

class OfflineAppointments {
    constructor() {
        this.initialized = false;
    }

    /**
     * Initialize offline appointments system
     */
    async init() {
        if (this.initialized) return;

        try {
            this.initialized = true;
            console.log('Offline appointments system initialized');

        } catch (error) {
            console.error('Failed to initialize offline appointments:', error);
        }
    }

    /**
     * Book offline appointment
     */
    async bookOfflineAppointment(appointmentData) {
        if (!this.initialized) {
            await this.init();
        }

        const offlineAppointment = {
            ...appointmentData,
            offline: true,
            status: 'pending',
            created_at: new Date().toISOString(),
            synced: false
        };

        try {
            await offlineStorage.storeOfflineAppointment(offlineAppointment);
            console.log('Offline appointment booked successfully');
            return {
                success: true,
                appointment: offlineAppointment,
                message: 'Appointment booked offline. Will be confirmed when online.'
            };
        } catch (error) {
            console.error('Failed to book offline appointment:', error);
            return {
                success: false,
                error: 'Failed to book appointment offline'
            };
        }
    }

    /**
     * Get offline appointments
     */
    async getOfflineAppointments() {
        try {
            return await offlineStorage.getOfflineAppointments();
        } catch (error) {
            console.error('Failed to get offline appointments:', error);
            return [];
        }
    }

    /**
     * Update offline appointment
     */
    async updateOfflineAppointment(appointmentId, updateData) {
        try {
            const appointments = await this.getOfflineAppointments();
            const appointment = appointments.find(apt => apt.id === appointmentId);
            
            if (!appointment) {
                throw new Error('Appointment not found');
            }

            const updatedAppointment = {
                ...appointment,
                ...updateData,
                updated_at: new Date().toISOString()
            };

            await offlineStorage.storeData('appointments', updatedAppointment);
            console.log('Offline appointment updated successfully');
            return {
                success: true,
                appointment: updatedAppointment
            };
        } catch (error) {
            console.error('Failed to update offline appointment:', error);
            return {
                success: false,
                error: 'Failed to update appointment'
            };
        }
    }

    /**
     * Cancel offline appointment
     */
    async cancelOfflineAppointment(appointmentId) {
        try {
            const result = await this.updateOfflineAppointment(appointmentId, {
                status: 'cancelled',
                cancelled_at: new Date().toISOString()
            });

            if (result.success) {
                console.log('Offline appointment cancelled successfully');
            }

            return result;
        } catch (error) {
            console.error('Failed to cancel offline appointment:', error);
            return {
                success: false,
                error: 'Failed to cancel appointment'
            };
        }
    }

    /**
     * Get appointment availability (offline estimation)
     */
    async getOfflineAvailability(date, duration = 30) {
        // This is a simplified offline availability check
        // In a real implementation, this would use cached availability data
        
        const requestedDate = new Date(date);
        const dayOfWeek = requestedDate.getDay();
        
        // Basic availability rules (9 AM to 5 PM, Monday to Friday)
        const isWeekday = dayOfWeek >= 1 && dayOfWeek <= 5;
        const isBusinessHours = true; // Simplified for offline
        
        if (!isWeekday) {
            return {
                available: false,
                message: 'Appointments only available on weekdays',
                suggested_times: []
            };
        }

        // Generate suggested time slots
        const suggestedTimes = this.generateTimeSlots(date, duration);
        
        return {
            available: true,
            message: 'Appointments available (offline estimation)',
            suggested_times: suggestedTimes,
            note: 'Actual availability will be confirmed when online'
        };
    }

    /**
     * Generate time slots for offline availability
     */
    generateTimeSlots(date, duration) {
        const slots = [];
        const startHour = 9; // 9 AM
        const endHour = 17; // 5 PM
        
        for (let hour = startHour; hour < endHour; hour++) {
            for (let minute = 0; minute < 60; minute += duration) {
                const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                slots.push({
                    time: timeString,
                    available: true,
                    offline: true
                });
            }
        }
        
        return slots.slice(0, 10); // Return first 10 slots
    }

    /**
     * Get offline appointment statistics
     */
    async getOfflineAppointmentStats() {
        try {
            const appointments = await this.getOfflineAppointments();
            
            const stats = {
                total: appointments.length,
                pending: appointments.filter(apt => apt.status === 'pending').length,
                confirmed: appointments.filter(apt => apt.status === 'confirmed').length,
                cancelled: appointments.filter(apt => apt.status === 'cancelled').length,
                synced: appointments.filter(apt => apt.synced).length,
                unsynced: appointments.filter(apt => !apt.synced).length
            };
            
            return stats;
        } catch (error) {
            console.error('Failed to get offline appointment stats:', error);
            return {
                total: 0,
                pending: 0,
                confirmed: 0,
                cancelled: 0,
                synced: 0,
                unsynced: 0
            };
        }
    }

    /**
     * Validate offline appointment data
     */
    validateAppointmentData(data) {
        const errors = [];
        
        if (!data.patient_name) {
            errors.push('Patient name is required');
        }
        
        if (!data.appointment_date) {
            errors.push('Appointment date is required');
        }
        
        if (!data.appointment_time) {
            errors.push('Appointment time is required');
        }
        
        if (!data.reason) {
            errors.push('Appointment reason is required');
        }
        
        // Validate date
        const appointmentDate = new Date(data.appointment_date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (appointmentDate < today) {
            errors.push('Appointment date cannot be in the past');
        }
        
        // Validate time format
        const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        if (data.appointment_time && !timeRegex.test(data.appointment_time)) {
            errors.push('Invalid time format (use HH:MM)');
        }
        
        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    /**
     * Get offline appointment form data
     */
    getOfflineAppointmentFormData() {
        return {
            patient_name: '',
            patient_phone: '',
            patient_email: '',
            appointment_date: '',
            appointment_time: '',
            reason: '',
            urgency: 'normal',
            notes: '',
            offline: true
        };
    }

    /**
     * Export offline appointments
     */
    async exportOfflineAppointments() {
        try {
            const appointments = await this.getOfflineAppointments();
            const exportData = {
                export_date: new Date().toISOString(),
                total_appointments: appointments.length,
                appointments: appointments
            };
            
            const blob = new Blob([JSON.stringify(exportData, null, 2)], {
                type: 'application/json'
            });
            
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `offline-appointments-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            return {
                success: true,
                message: 'Offline appointments exported successfully'
            };
        } catch (error) {
            console.error('Failed to export offline appointments:', error);
            return {
                success: false,
                error: 'Failed to export appointments'
            };
        }
    }
}

// Initialize offline appointments
const offlineAppointments = new OfflineAppointments();

// Export for use in other scripts
window.OfflineAppointments = OfflineAppointments;
window.offlineAppointments = offlineAppointments;
