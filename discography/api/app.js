// View Release Handler
document.querySelectorAll('.view-release').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const releaseId = this.dataset.releaseId;
        showReleaseDetails(releaseId);
    });
});

async function showReleaseDetails(releaseId) {
    const modal = $('#viewReleaseModal');
    const loading = $('#loadingSpinner');
    const content = $('#releaseContent');
    const errorDiv = $('#releaseError');

    // Reset modal state
    loading.show();
    content.hide();
    errorDiv.hide().text('');

    try {
        const response = await fetch(`api/get_release.php?id=${releaseId}`);
        if (!response.ok) throw new Error('Failed to fetch release data');

        const json = await response.json();
        if (json.status !== 'success') throw new Error('API returned an error status');
        const releaseData = json.data;
        // Populate data
        $('#releaseTitle').text(releaseData.name || '(Untitled Release)');
        $('#releaseUPC').text(releaseData.upc || '--');
        const allArtists = [].concat(...releaseData.artists).filter((v, i, a) => a.indexOf(v) === i);
        $('#releaseArtists').text(allArtists.join(', ') || '--');
        $('#releaseStatus').text(formatStatus(releaseData.status));
        $('#releaseDate').text(releaseData.release_date || '--');
        $('#releaseArt').attr('src', releaseData.artwork || '/assets/images/alb.png');
        $('#originalReleaseDate').text(releaseData.original_release_date || '--');

        // Populate tracks
        const trackList = $('#trackList');
        trackList.empty();
        releaseData.tracks.forEach(track => {
            trackList.append(`
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>FMG_T${track.id} - </strong> ${track.name}
          <br><small class="text-muted">${track.artists.join(', ')}</small>
        </div>
        <span class="badge badge-secondary">${track.duration}</span>
      </li>
    `);
        });

        loading.hide();
        content.show();
        modal.modal('show');
    } catch (error) {
        console.error('Error loading release:', error);
        loading.hide();
        errorDiv.text('Error loading release details: ' + error.message).show();
        modal.modal('show');
    }
}

function formatStatus(statusCode) {
    const statusMap = {
        0: 'DRAFT',
        1: 'DELIVERED',
        2: 'ERROR',
        3: 'CHECKING'
    };
    return statusMap[statusCode] || 'UNKNOWN';
}