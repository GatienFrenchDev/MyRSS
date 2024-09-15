
/**
 * Deletes a notification by its ID and reloads the page.
 *
 * @param {number} notificationId - The ID of the notification to delete.
 * @returns {Promise<void>} A promise that resolves when the notification is deleted and the page is reloaded.
 */
async function deleteNotification(notificationId) {
    await API.deleteNotification(notificationId);
    location.reload();
}