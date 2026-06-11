/**
 * Bihar Vihaan Enterprise - Razorpay payment simulation
 */

window.initiatePayment = function(refType, refId, amount, itemName) {
    // 1. Create Modal elements dynamically if they don't exist
    let overlay = document.getElementById('payment-modal-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'payment-modal-overlay';
        overlay.className = 'modal-overlay';
        document.body.appendChild(overlay);
    }

    overlay.innerHTML = `
        <div class="premium-modal" style="position: relative;">
            <button type="button" id="close-payment-modal" style="position: absolute; top: 15px; right: 15px; background: transparent; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;">✕</button>
            <div class="razorpay-logo">
                <span style="color:#3399cc;">💳</span> Razorpay Secure
            </div>
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Paying To</div>
                <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-main);">Bihar Vihaan Enterprise Tourism Portal</div>
                <div style="margin-top: 0.5rem; font-size: 1.4rem; font-weight: 800; color: var(--primary);">₹${parseFloat(amount).toFixed(2)}</div>
                <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">For: ${itemName}</div>
            </div>

            <div id="payment-form-section">
                <div class="razorpay-field">
                    <label>Choose Payment Method</label>
                    <select id="payment-method">
                        <option value="upi">UPI / GooglePay / PhonePe</option>
                        <option value="card">Credit / Debit Card</option>
                        <option value="netbanking">Net Banking</option>
                    </select>
                </div>

                <div class="razorpay-field" id="upi-details-field">
                    <label>Enter UPI ID</label>
                    <input type="text" id="upi-id" placeholder="e.g. username@okaxis" value="tourist@upi">
                </div>

                <div class="razorpay-field" id="card-details-field" style="display:none;">
                    <label>Card Number</label>
                    <input type="text" placeholder="4111 1111 1111 1111" maxlength="19">
                    <div style="display:flex; gap: 10px; margin-top: 0.5rem;">
                        <input type="text" placeholder="MM/YY" style="width: 50%;">
                        <input type="password" placeholder="CVV" style="width: 50%;" maxlength="3">
                    </div>
                </div>

                <button type="button" id="pay-simulate-btn" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    Simulate Capture Payment
                </button>
            </div>

            <div id="payment-loading-section" style="display:none; text-align: center; padding: 2rem 0;">
                <div style="width: 50px; height: 50px; border: 3px solid rgba(20, 184, 166, 0.1); border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1.5rem;"></div>
                <div id="payment-loading-text" style="font-weight: 600; font-size: 1rem;">Connecting to Secure Gateway...</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Please do not refresh this page.</div>
            </div>
        </div>
    `;

    // Add keyframe animation for loader
    if (!document.getElementById('spinner-keyframes')) {
        const style = document.createElement('style');
        style.id = 'spinner-keyframes';
        style.textContent = `@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }`;
        document.head.appendChild(style);
    }

    // Toggle fields based on payment method
    const methodSelector = document.getElementById('payment-method');
    const upiField = document.getElementById('upi-details-field');
    const cardField = document.getElementById('card-details-field');

    methodSelector.addEventListener('change', (e) => {
        if (e.target.value === 'upi') {
            upiField.style.display = 'block';
            cardField.style.display = 'none';
        } else if (e.target.value === 'card') {
            upiField.style.display = 'none';
            cardField.style.display = 'block';
        } else {
            upiField.style.display = 'none';
            cardField.style.display = 'none';
        }
    });

    // Close actions
    const closeModal = () => {
        overlay.classList.remove('active');
    };
    document.getElementById('close-payment-modal').addEventListener('click', closeModal);

    // Run Modal trigger
    setTimeout(() => {
        overlay.classList.add('active');
    }, 50);

    // Pay Simulator action
    document.getElementById('pay-simulate-btn').addEventListener('click', () => {
        const formSec = document.getElementById('payment-form-section');
        const loadSec = document.getElementById('payment-loading-section');
        const loadTxt = document.getElementById('payment-loading-text');

        formSec.style.display = 'none';
        loadSec.style.display = 'block';

        // Stage 1: Connecting
        setTimeout(() => {
            loadTxt.textContent = "Verifying UPI/Card credentials...";
            
            // Stage 2: Processing
            setTimeout(() => {
                loadTxt.textContent = "Capturing Payment from Bank...";
                
                // Stage 3: Capturing & POSTing back to DB
                setTimeout(() => {
                    const mockOrder = 'order_' + Math.random().toString(36).substr(2, 9);
                    const mockTxn = 'pay_' + Math.random().toString(36).substr(2, 9);

                    // Form parameters to notify backend
                    const formData = new FormData();
                    formData.append('order_id', mockOrder);
                    formData.append('transaction_id', mockTxn);
                    formData.append('amount', amount);
                    formData.append('reference_type', refType);
                    formData.append('reference_id', refId);

                    fetch(`${window.baseUrl}/bookings/pay`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            loadTxt.innerHTML = `<span style="color:var(--status-success); font-size:2rem;">✓</span><br>Payment Captured Successfully!`;
                            setTimeout(() => {
                                closeModal();
                                window.location.href = `${window.baseUrl}/dashboard`;
                            }, 1500);
                        } else {
                            loadSec.style.display = 'none';
                            formSec.style.display = 'block';
                            alert("Payment verification failed: " + res.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        loadSec.style.display = 'none';
                        formSec.style.display = 'block';
                        alert("Network error processing payment. Please verify database connection.");
                    });

                }, 1000);
            }, 1000);
        }, 1000);
    });
};
