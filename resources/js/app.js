import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const target = document.querySelector(this.getAttribute('href'));

        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // AJAX add-to-cart buttons
    document.querySelectorAll('form.ajax-add-to-cart').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const url = form.action;
            const token = form.querySelector('input[name="_token"]').value;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            }).then(res => res.json())
              .then(json => {
                  const badge = document.getElementById('cart-badge');
                  if (badge && json.count !== undefined) {
                      badge.innerText = json.count;
                  }
                  // small visual feedback
                  form.classList.add('opacity-60');
                  setTimeout(() => form.classList.remove('opacity-60'), 300);
              }).catch(err => {
                  console.error('Add to cart failed', err);
                  window.location = url; // fallback
              });
        });
    });

    // Cart selection total update
    function updateCartSelectedTotal() {
        const rows = document.querySelectorAll('[data-item-id]');
        let total = 0;
        rows.forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"][name^="selected"]');
            if (!checkbox) return;
            if (checkbox.checked) {
                const price = parseFloat(row.getAttribute('data-price') || 0);
                const qty = parseInt(row.getAttribute('data-quantity') || 0, 10);
                total += price * qty;
            }
        });

        const totalEl = document.getElementById('summary-total');
        const subtotalEl = document.getElementById('summary-subtotal');
        if (totalEl) {
            totalEl.innerText = '$' + total.toFixed(2);
        }
        if (subtotalEl) {
            subtotalEl.innerText = '$' + total.toFixed(2);
        }
    }

    document.addEventListener('change', function (e) {
        if (e.target && e.target.matches('input[type="checkbox"][name^="selected"]')) {
            updateCartSelectedTotal();
        }
    });

    // init on page load
    updateCartSelectedTotal();
});
