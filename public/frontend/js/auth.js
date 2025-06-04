// public/js/auth.js

// Enhanced UI animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add focus animations to form inputs
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
});

// Main login form handler
document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('error');
    const btn = document.querySelector('.btn-login');
    const spinner = document.querySelector('.loading-spinner');
    const card = document.querySelector('.login-card');
    
    // Show loading state
    btn.disabled = true;
    if (spinner) {
        spinner.style.display = 'inline-block';
    }
    errorDiv.style.display = 'none';
    
    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        // Reset loading state
        btn.disabled = false;
        if (spinner) {
            spinner.style.display = 'none';
        }

        if (!res.ok) {
            const err = await res.json();
            console.error('Login error:', err);
            
            // Show error with animation
            showError(err.message || 'Login gagal', card, errorDiv);
            return;
        }

        // Simpan token dan user
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));

        // Success animation before redirect
        btn.innerHTML = '<i class="fas fa-check"></i> Success!';
        btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 1000);

    } catch (err) {
        console.error('Connection error:', err);
        
        // Reset loading state
        btn.disabled = false;
        if (spinner) {
            spinner.style.display = 'none';
        }
        
        // Show connection error with animation
        showError('Terjadi kesalahan koneksi', card, errorDiv);
    }
});

// Function to show error with animations
function showError(message, card, errorDiv) {
    // Add shake animation to card
    if (card) {
        card.classList.add('shake');
        setTimeout(() => {
            card.classList.remove('shake');
        }, 500);
    }
    
    // Show error message
    if (errorDiv.querySelector('.error-text')) {
        errorDiv.querySelector('.error-text').textContent = message;
    } else {
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
    }
    errorDiv.style.display = 'block';
    
    // Auto hide error after 5 seconds
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}