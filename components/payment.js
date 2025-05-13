// components/payment.js
$(function () {
    // Cache DOM nodes once the modal exists
    let pollInterval = null;
    let currentLinkId = null;

    const $modal = $('#paymentModal');
    const $body = $('#paymentBody');
    const $qr = $('#maqr');
    const $desc = $('#paymentDescription');
    const $amt = $('#paymentAmount');
    const $acn = $('#accountNumber');
    const $ord = $('#orderCode');
    const $btn = $('#checkoutBtn');
    const $label = $('#btnLabel');
    const $err = $('#paymentError');

    // Main entry: fetches a new payment link and shows the modal
    async function upgrade() {
        resetState();
        $body.addClass('loading');
        try {
            const res = await fetch('/checkout.php');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            // 1) Populate QR and texts
            $qr
                .attr('src', `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(data.qrCode)}`)
                .attr('alt', `QR thanh toán cho đơn ${data.orderCode}`);
            $modal.find('.modal-title')
                .text(`Thanh toán đơn ${data.orderCode}`);
            $desc.text(data.description);
            $amt.text(new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: data.currency
            }).format(data.amount));
            $ord.text(data.orderCode);
            $acn.text(`${data.accountNumber}`);
            // 2) Enable “Pay” button
            $btn.prop('disabled', false).removeClass('disabled');
            $label.text(`Open payos page`);
            $btn.off('click').on('click', () => window.open(data.checkoutUrl));

            // 3) Show modal
            $body.removeClass('loading');
            $modal.modal('show');
            // 4) Start polling every 3s
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(async () => {
                try {
                    const res = await fetch(`/api/check-status/index.php?id=${data.orderCode}`);
                    const json = await res.json();
                    if (json.status === 'PAID' || json.status === 'CANCELLED') {
                        // Update UI one last time
                        $err.text(`Trạng thái: ${json.status}`).show();
                        clearInterval(pollInterval);
                    }
                } catch (e) {
                    console.error('Polling error', e);
                    clearInterval(pollInterval);
                }
            }, 3000);
        } catch (err) {
            console.error('Payment init failed', err);
            $body.removeClass('loading');
            $err.text('Không thể khởi tạo thanh toán. Vui lòng thử lại.').show();
        }
    }

    // Polling helper for return.php pages
    async function pollReturnStatus(paymentLinkId, targetEl, interval = 3000, maxAttempts = 20) {
        let attempts = 0;
        targetEl.text('Kiểm tra trạng thái...');
        while (attempts < maxAttempts) {
            try {
                const res = await fetch(`/api/check-status?id=${encodeURIComponent(paymentLinkId)}`);
                const json = await res.json();
                if (json.status === 'PAID' || json.status === 'CANCELLED') {
                    targetEl.text(`Trạng thái: ${json.status}`);
                    return json.status;
                }
                targetEl.text(`Chờ xử lý… (${json.status})`);
            } catch (e) {
                console.error('Polling error', e);
                targetEl.text('Lỗi kiểm tra trạng thái.');
                return null;
            }
            await new Promise(r => setTimeout(r, interval));
            attempts++;
        }
        targetEl.text('Hết thời gian chờ. Vui lòng làm mới.');
        return null;
    }

    // Clear UI bits before each run
    function resetState() {
        $err.hide().text('');
        $btn.prop('disabled', true).addClass('disabled');
        $label.text('Loading…');
        $qr.attr('src', '').attr('alt', 'Loading QR…');
        $desc.text('Đang thiết lập...');
        $amt.text('–');
        $ord.text('–');
    }

    // Expose functions globally
    window.upgrade = upgrade;
    window.pollReturnStatus = pollReturnStatus;
    window.addEventListener('beforeunload', () => {
        if (pollInterval) {
            clearInterval(pollInterval);
            console.log('Page is unloading—polling aborted.');
        }
    });

    // When user closes the modal manually…
    $modal.on('hidden.bs.modal', () => {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
            console.log('User closed modal—polling cancelled.');
        }
    });

});
