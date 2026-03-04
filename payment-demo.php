<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$registration_id = isset($_GET['registration_id']) ? (int)$_GET['registration_id'] : 0;
$user_id = $_SESSION['user_id'];
$payment_success = false;
$error = '';

// Fetch registration details
$sql = "SELECT r.*, e.event_name, e.registration_fee, e.event_date, e.event_time, e.venue, e.category 
        FROM registrations r 
        JOIN events e ON r.event_id = e.event_id 
        WHERE r.registration_id = $registration_id AND r.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$registration = $result->fetch_assoc();

// Handle payment simulation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulate payment processing
    $payment_method = $_POST['payment_method'] ?? 'credit_card';
    $card_number = $_POST['card_number'] ?? '';
    $card_name = $_POST['card_name'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    
    // Basic validation (simulated)
    if (strlen($card_number) >= 16 && strlen($cvv) >= 3) {
        // Update payment status
        $update_sql = "UPDATE registrations SET payment_status = 'completed' WHERE registration_id = $registration_id";
        if ($conn->query($update_sql) === TRUE) {
            $payment_success = true;
        } else {
            $error = "Payment processing error. Please try again.";
        }
    } else {
        $error = "Invalid card details. Please check and try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Demo - Campus Events</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .payment-header h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        .payment-header p {
            color: #666;
        }

        .demo-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 0.9rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .event-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .event-summary h3 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #555;
        }

        .summary-row.total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .amount {
            font-weight: 600;
            color: #667eea;
        }

        .payment-methods {
            margin-bottom: 30px;
        }

        .payment-methods h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .method-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .method-option:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }

        .method-option.selected {
            border-color: #667eea;
            background: #e8f0fe;
        }

        .method-option input[type="radio"] {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            accent-color: #667eea;
        }

        .method-icon {
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .method-details {
            flex: 1;
        }

        .method-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .method-desc {
            font-size: 0.9rem;
            color: #666;
        }

        .card-details {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn-pay {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-pay:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .demo-notice {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #fff3cd;
            border-radius: 10px;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .demo-card-info {
            background: #e7f3ff;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
            font-size: 0.9rem;
            color: #004085;
            border-left: 4px solid #2196F3;
        }

        .success-message {
            text-align: center;
            padding: 40px 20px;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2.5rem;
            animation: bounce 1s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .success-message h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .success-message p {
            color: #666;
            margin-bottom: 30px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <?php if($payment_success): ?>
            <div class="success-message">
                <div class="success-icon">✓</div>
                <h2>Payment Successful!</h2>
                <p>Your registration for <strong><?php echo htmlspecialchars($registration['event_name']); ?></strong> is confirmed.</p>
                <p>A confirmation email has been sent to your registered email address.</p>
                
                <div class="action-buttons">
                    <a href="dashboard.php" class="btn btn-primary">View Dashboard</a>
                    <a href="events.php" class="btn btn-outline">Browse More Events</a>
                </div>
            </div>
        <?php else: ?>
            <div class="payment-header">
                <span class="demo-badge">🔷 DEMO PAYMENT GATEWAY</span>
                <h1>Complete Payment</h1>
                <p>This is a demo interface - No real transactions</p>
            </div>

            <?php if($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="event-summary">
                <h3>Event Summary</h3>
                <div class="summary-row">
                    <span>Event:</span>
                    <span class="amount"><?php echo htmlspecialchars($registration['event_name']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Category:</span>
                    <span><?php echo $registration['category']; ?></span>
                </div>
                <div class="summary-row">
                    <span>Date:</span>
                    <span><?php echo date('F j, Y', strtotime($registration['event_date'])); ?></span>
                </div>
                <div class="summary-row">
                    <span>Time:</span>
                    <span><?php echo date('g:i A', strtotime($registration['event_time'])); ?></span>
                </div>
                <div class="summary-row">
                    <span>Venue:</span>
                    <span><?php echo $registration['venue']; ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount:</span>
                    <span class="amount">$<?php echo $registration['registration_fee']; ?></span>
                </div>
            </div>

            <div class="demo-card-info">
                <strong>📝 Demo Card Details:</strong> Use any 16-digit card number (4242 4242 4242 4242), future expiry date, any 3-digit CVV
            </div>

            <form method="POST" action="" id="paymentForm">
                <div class="payment-methods">
                    <h3>Select Payment Method</h3>
                    
                    <label class="method-option selected">
                        <input type="radio" name="payment_method" value="credit_card" checked>
                        <div class="method-icon">💳</div>
                        <div class="method-details">
                            <div class="method-name">Credit / Debit Card</div>
                            <div class="method-desc">Pay securely with your card</div>
                        </div>
                    </label>

                    <div class="card-details" id="cardDetails">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" name="card_number" placeholder="4242 4242 4242 4242" value="4242424242424242" maxlength="16" required>
                        </div>
                        <div class="form-group">
                            <label>Cardholder Name</label>
                            <input type="text" name="card_name" placeholder="John Doe" value="<?php echo $_SESSION['full_name']; ?>" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="text" name="expiry" placeholder="MM/YY" value="12/25" required>
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="text" name="cvv" placeholder="123" value="123" maxlength="3" required>
                            </div>
                        </div>
                    </div>

                    <label class="method-option">
                        <input type="radio" name="payment_method" value="paypal">
                        <div class="method-icon">📱</div>
                        <div class="method-details">
                            <div class="method-name">PayPal</div>
                            <div class="method-desc">Pay with your PayPal account</div>
                        </div>
                    </label>

                    <label class="method-option">
                        <input type="radio" name="payment_method" value="gpay">
                        <div class="method-icon">📲</div>
                        <div class="method-details">
                            <div class="method-name">Google Pay</div>
                            <div class="method-desc">Fast and secure payment</div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-pay" id="payButton">Pay $<?php echo $registration['registration_fee']; ?></button>
            </form>

            <div class="demo-notice">
                <strong>⚠️ Demo Mode</strong><br>
                This is a demonstration payment interface. Click "Pay" to simulate a successful payment.<br>
                <small>No actual money will be charged.</small>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Handle payment method selection
        const methodOptions = document.querySelectorAll('.method-option');
        const cardDetails = document.getElementById('cardDetails');

        methodOptions.forEach(option => {
            option.addEventListener('click', function() {
                methodOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;

                // Show/hide card details based on selection
                if (radio.value === 'credit_card') {
                    cardDetails.style.display = 'block';
                } else {
                    cardDetails.style.display = 'none';
                }
            });
        });

        // Handle form submission with loading state
        document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
            const payButton = document.getElementById('payButton');
            const originalText = payButton.textContent;
            
            // Simple validation for card payments
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (selectedMethod === 'credit_card') {
                const cardNumber = document.querySelector('input[name="card_number"]').value.replace(/\s/g, '');
                const cvv = document.querySelector('input[name="cvv"]').value;
                const expiry = document.querySelector('input[name="expiry"]').value;
                
                if (cardNumber.length < 16) {
                    e.preventDefault();
                    alert('Please enter a valid 16-digit card number');
                    return;
                }
                
                if (cvv.length < 3) {
                    e.preventDefault();
                    alert('Please enter a valid CVV');
                    return;
                }
                
                if (!expiry.includes('/')) {
                    e.preventDefault();
                    alert('Please enter a valid expiry date (MM/YY)');
                    return;
                }
            }
            
            // Show loading state
            payButton.textContent = 'Processing...';
            payButton.disabled = true;
            
            // Form will submit after a short delay
            setTimeout(() => {
                // Form submission continues
            }, 500);
        });

        // Auto-format card number
        document.querySelector('input[name="card_number"]')?.addEventListener('input', function(e) {
            let value = this.value.replace(/\s/g, '').replace(/\D/g, '');
            if (value.length > 16) value = value.slice(0, 16);
            
            // Add space every 4 digits
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += value[i];
            }
            this.value = formatted;
        });

        // Auto-format expiry
        document.querySelector('input[name="expiry"]')?.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                this.value = value.slice(0, 2) + '/' + value.slice(2, 4);
            } else {
                this.value = value;
            }
        });

        // Restrict CVV to numbers only
        document.querySelector('input[name="cvv"]')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 3);
        });
    </script>
</body>
</html>