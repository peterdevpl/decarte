const hasInvoice = document.getElementById('shipping_details_hasInvoice');
const taxId = document.getElementById('shipping_details_taxId');

if (hasInvoice !== null && taxId !== null) {
    hasInvoice.addEventListener('click', function () {
        if (!this.checked) {
            taxId.parentElement.style.display = 'none';
            taxId.value = '';
        } else {
            taxId.parentElement.style.display = 'block';
        }
    });

    if (!hasInvoice.checked) {
        taxId.parentElement.style.display = 'none';
        taxId.value = '';
    }
}
