// JavaScript to handle the modal and delete functionality
document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-button');
    const modal = document.getElementById('deleteModal');
    const confirmDelete = document.getElementById('confirmDelete');
    const cancelDelete = document.getElementById('cancelDelete');
    let reportIdToDelete = null;

    // Open modal when clicking "Delete"
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            reportIdToDelete = button.getAttribute('data-reportid');
            modal.style.display = 'flex'; // Show modal
        });
    });

    // Close modal on "Cancel"
    cancelDelete.addEventListener('click', () => {
        modal.style.display = 'none'; // Hide modal
        reportIdToDelete = null; // Reset the report ID
    });

    // Confirm delete
    confirmDelete.addEventListener('click', () => {
        if (reportIdToDelete) {
            // Send an AJAX request to delete the report
            fetch('delete-report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reportid: reportIdToDelete }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide the modal and remove the report card
                        modal.style.display = 'none';
                        const card = document.querySelector(
                            `.delete-button[data-reportid='${reportIdToDelete}']`
                        ).parentElement;
                        card.remove();
                        alert('Report deleted successfully!');
                    } else {
                        alert('Error deleting the report.');
                    }
                })
                .catch(err => {
                    alert('An error occurred while deleting the report.');
                    console.error(err);
                });
        }
    });
});
