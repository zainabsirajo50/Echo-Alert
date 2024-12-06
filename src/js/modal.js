document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-button');
    const modal = document.getElementById('deleteModal');
    const confirmDelete = document.getElementById('confirmDelete');
    const cancelDelete = document.getElementById('cancelDelete');
    let reportIdToDelete = null;

    // Open modal when delete button is clicked
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            reportIdToDelete = button.getAttribute('data-reportid'); // Store the report ID
            modal.style.display = 'flex'; // Show the modal
        });
    });

    // Confirm deletion
    confirmDelete.addEventListener('click', () => {
        if (reportIdToDelete) {
            fetch('delete-report.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `reportId=${reportIdToDelete}` // Send the report ID to PHP
            })
            .then(response => response.text())
            .then(data => {
                alert('Report deleted successfully!');
                modal.style.display = 'none'; // Hide the modal
                window.location.reload(); // Reload the page to show deletion
            })
            .catch(err => {
                alert('Failed to delete report. Please try again.');
                console.error(err);
            });
        }
    });

    // Cancel deletion
    cancelDelete.addEventListener('click', () => {
        modal.style.display = 'none'; // Hide the modal
        reportIdToDelete = null; // Clear the stored report ID
    });
});
